<?php
/**
 * Main plugin coordinator.
 *
 * @package WPCareReport
 */

namespace WPCareReport;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin {
	public const OPTION_ENVIRONMENT = 'wp_care_report_environment';

	public function register(): void {
		if ( is_admin() ) {
			$report_generator = new ReportGenerator();
			$markdown_exporter = new MarkdownExporter();

			( new AdminPage( $report_generator, $markdown_exporter ) )->register();
			( new SiteHealthIntegration( $report_generator ) )->register();
			( new EnvironmentBanner() )->register();
		}
	}
}
