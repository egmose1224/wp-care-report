<?php
/**
 * Plugin Name: WP Care Report
 * Description: Generates a practical maintenance, handover, and technical health report for WordPress sites.
 * Version: 0.1.0
 * Requires at least: 6.2
 * Requires PHP: 8.0
 * Author: WP Care Report Contributors
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 * Text Domain: wp-care-report
 * Domain Path: /languages
 *
 * @package WPCareReport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WP_CARE_REPORT_VERSION', '0.1.0' );
define( 'WP_CARE_REPORT_FILE', __FILE__ );
define( 'WP_CARE_REPORT_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_CARE_REPORT_URL', plugin_dir_url( __FILE__ ) );

$wp_care_report_files = array(
	'includes/Checks/CheckInterface.php',
	'includes/Checks/AbstractCheck.php',
	'includes/Checks/CoreUpdatesCheck.php',
	'includes/Checks/PluginUpdatesCheck.php',
	'includes/Checks/ThemeUpdatesCheck.php',
	'includes/Checks/InactivePluginsCheck.php',
	'includes/Checks/InactiveThemesCheck.php',
	'includes/Checks/DebugModeCheck.php',
	'includes/Checks/FileEditCheck.php',
	'includes/Checks/SslCheck.php',
	'includes/Checks/SearchVisibilityCheck.php',
	'includes/Checks/PermalinkStructureCheck.php',
	'includes/Checks/AdminUsernameCheck.php',
	'includes/Checks/AdministratorUsersCheck.php',
	'includes/Checks/BackupPluginCheck.php',
	'includes/Checks/SeoPluginCheck.php',
	'includes/Checks/CachingPluginCheck.php',
	'includes/Checks/EnvironmentSettingCheck.php',
	'includes/Checks/LegacyEnvironmentCheck.php',
	'includes/ScoreCalculator.php',
	'includes/CarePlan.php',
	'includes/ReportGenerator.php',
	'includes/MarkdownExporter.php',
	'includes/AdminPage.php',
	'includes/SiteHealthIntegration.php',
	'includes/EnvironmentBanner.php',
	'includes/Plugin.php',
);

foreach ( $wp_care_report_files as $wp_care_report_file ) {
	require_once WP_CARE_REPORT_PATH . $wp_care_report_file;
}

add_action(
	'plugins_loaded',
	static function (): void {
		load_plugin_textdomain( 'wp-care-report', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		$plugin = new WPCareReport\Plugin();
		$plugin->register();
	}
);
