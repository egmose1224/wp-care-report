<?php
/**
 * Plugin uninstall cleanup.
 *
 * @package WPCareReport
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'wp_care_report_environment' );
