<?php
/**
 * Builds report data from checks.
 *
 * @package WPCareReport
 */

namespace WPCareReport;

use WPCareReport\Checks\AdminUsernameCheck;
use WPCareReport\Checks\AdministratorUsersCheck;
use WPCareReport\Checks\BackupPluginCheck;
use WPCareReport\Checks\CachingPluginCheck;
use WPCareReport\Checks\CheckInterface;
use WPCareReport\Checks\CoreUpdatesCheck;
use WPCareReport\Checks\DebugModeCheck;
use WPCareReport\Checks\EnvironmentSettingCheck;
use WPCareReport\Checks\FileEditCheck;
use WPCareReport\Checks\InactivePluginsCheck;
use WPCareReport\Checks\InactiveThemesCheck;
use WPCareReport\Checks\LegacyEnvironmentCheck;
use WPCareReport\Checks\PermalinkStructureCheck;
use WPCareReport\Checks\PluginUpdatesCheck;
use WPCareReport\Checks\SearchVisibilityCheck;
use WPCareReport\Checks\SeoPluginCheck;
use WPCareReport\Checks\SslCheck;
use WPCareReport\Checks\ThemeUpdatesCheck;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ReportGenerator {
	/**
	 * @var array<string,string>
	 */
	private array $category_labels = array();

	/**
	 * @var array<string,mixed>|null
	 */
	private ?array $report_cache = null;

	public function __construct() {
		$this->category_labels = array(
			CheckInterface::CATEGORY_UPDATES     => __( 'Updates', 'wp-care-report' ),
			CheckInterface::CATEGORY_SECURITY    => __( 'Security basics', 'wp-care-report' ),
			CheckInterface::CATEGORY_SEO         => __( 'SEO basics', 'wp-care-report' ),
			CheckInterface::CATEGORY_MAINTENANCE => __( 'Maintenance readiness', 'wp-care-report' ),
			CheckInterface::CATEGORY_ENVIRONMENT => __( 'Environment', 'wp-care-report' ),
		);
	}

	/**
	 * @return array<string,mixed>
	 */
	public function generate(): array {
		if ( null !== $this->report_cache ) {
			return $this->report_cache;
		}

		if ( ! function_exists( 'wp_update_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/update.php';
		}

		wp_update_plugins();
		wp_update_themes();

		$checks = array_map(
			static fn ( CheckInterface $check ): array => $check->run(),
			$this->get_checks()
		);

		$calculator = new ScoreCalculator();
		$care_plan  = new CarePlan();
		$score      = $calculator->calculate( $checks );

		$this->report_cache = array(
			'site_name'       => get_bloginfo( 'name' ),
			'site_url'        => home_url( '/' ),
			'generated_at'    => current_time( 'mysql' ),
			'score'           => $score,
			'status_label'    => $calculator->get_status_label( $score ),
			'checks'          => $checks,
			'grouped_checks'  => $this->group_checks( $checks ),
			'category_labels' => $this->category_labels,
			'care_plan'       => $care_plan->build( $checks ),
			'care_plan_labels' => $care_plan->labels(),
		);

		return $this->report_cache;
	}

	/**
	 * @return array<int,CheckInterface>
	 */
	public function get_checks(): array {
		return array(
			new CoreUpdatesCheck(),
			new PluginUpdatesCheck(),
			new ThemeUpdatesCheck(),
			new InactivePluginsCheck(),
			new InactiveThemesCheck(),
			new DebugModeCheck(),
			new FileEditCheck(),
			new SslCheck(),
			new AdminUsernameCheck(),
			new AdministratorUsersCheck(),
			new SearchVisibilityCheck(),
			new PermalinkStructureCheck(),
			new SeoPluginCheck(),
			new BackupPluginCheck(),
			new CachingPluginCheck(),
			new EnvironmentSettingCheck(),
			new LegacyEnvironmentCheck(),
		);
	}

	/**
	 * @param array<int,array<string,mixed>> $checks Check results.
	 *
	 * @return array<string,array<int,array<string,mixed>>>
	 */
	private function group_checks( array $checks ): array {
		$grouped = array_fill_keys( array_keys( $this->category_labels ), array() );

		foreach ( $checks as $check ) {
			$category = isset( $check['category'] ) ? (string) $check['category'] : CheckInterface::CATEGORY_MAINTENANCE;

			if ( ! isset( $grouped[ $category ] ) ) {
				$grouped[ $category ] = array();
			}

			$grouped[ $category ][] = $check;
		}

		return $grouped;
	}
}
