<?php
/**
 * WordPress Site Health integration.
 *
 * @package WPCareReport
 */

namespace WPCareReport;

use WPCareReport\Checks\CheckInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SiteHealthIntegration {
	private ReportGenerator $report_generator;

	public function __construct( ReportGenerator $report_generator ) {
		$this->report_generator = $report_generator;
	}

	public function register(): void {
		add_filter( 'site_status_tests', array( $this, 'register_tests' ) );
	}

	/**
	 * @param array<string,mixed> $tests Site Health tests.
	 *
	 * @return array<string,mixed>
	 */
	public function register_tests( array $tests ): array {
		$tests['direct']['wp_care_report_debug_mode'] = array(
			'label' => __( 'WP Care Report: Debug mode', 'wp-care-report' ),
			'test'  => array( $this, 'test_debug_mode' ),
		);

		$tests['direct']['wp_care_report_file_edit'] = array(
			'label' => __( 'WP Care Report: File editor', 'wp-care-report' ),
			'test'  => array( $this, 'test_file_edit' ),
		);

		$tests['direct']['wp_care_report_search_visibility'] = array(
			'label' => __( 'WP Care Report: Search visibility', 'wp-care-report' ),
			'test'  => array( $this, 'test_search_visibility' ),
		);

		$tests['direct']['wp_care_report_plugin_updates'] = array(
			'label' => __( 'WP Care Report: Plugin updates', 'wp-care-report' ),
			'test'  => array( $this, 'test_plugin_updates' ),
		);

		return $tests;
	}

	/**
	 * @return array<string,mixed>
	 */
	public function test_debug_mode(): array {
		return $this->build_test_result( 'debug_mode' );
	}

	/**
	 * @return array<string,mixed>
	 */
	public function test_file_edit(): array {
		return $this->build_test_result( 'file_edit' );
	}

	/**
	 * @return array<string,mixed>
	 */
	public function test_search_visibility(): array {
		return $this->build_test_result( 'search_visibility' );
	}

	/**
	 * @return array<string,mixed>
	 */
	public function test_plugin_updates(): array {
		return $this->build_test_result( 'plugin_updates' );
	}

	/**
	 * @return array<string,mixed>
	 */
	private function build_test_result( string $check_id ): array {
		$report = $this->report_generator->generate();
		$check  = null;

		foreach ( $report['checks'] as $result ) {
			if ( isset( $result['id'] ) && $check_id === $result['id'] ) {
				$check = $result;
				break;
			}
		}

		if ( ! $check ) {
			return array(
				'label'       => __( 'WP Care Report check was not found', 'wp-care-report' ),
				'status'      => 'recommended',
				'badge'       => array(
					'label' => __( 'WP Care Report', 'wp-care-report' ),
					'color' => 'blue',
				),
				'description' => '<p>' . esc_html__( 'The requested WP Care Report check could not be found.', 'wp-care-report' ) . '</p>',
				'actions'     => '',
				'test'        => 'wp_care_report_' . $check_id,
			);
		}

		$status = CheckInterface::STATUS_GOOD === $check['status'] ? 'good' : 'recommended';

		return array(
			'label'       => (string) $check['title'],
			'status'      => $status,
			'badge'       => array(
				'label' => __( 'WP Care Report', 'wp-care-report' ),
				'color' => CheckInterface::STATUS_GOOD === $check['status'] ? 'green' : 'orange',
			),
			'description' => '<p>' . esc_html( (string) $check['message'] ) . '</p>',
			'actions'     => '<p>' . esc_html( (string) $check['recommendation'] ) . '</p>',
			'test'        => 'wp_care_report_' . $check_id,
		);
	}
}
