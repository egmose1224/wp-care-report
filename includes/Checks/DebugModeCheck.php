<?php
/**
 * Debug mode check.
 *
 * @package WPCareReport
 */

namespace WPCareReport\Checks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DebugModeCheck extends AbstractCheck {
	public function run(): array {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			return $this->result(
				'debug_mode',
				self::CATEGORY_SECURITY,
				self::STATUS_CRITICAL,
				__( 'WP_DEBUG is enabled', 'wp-care-report' ),
				__( 'Debug mode is currently enabled on this site.', 'wp-care-report' ),
				__( 'Disable WP_DEBUG on public production websites.', 'wp-care-report' ),
				10
			);
		}

		return $this->result(
			'debug_mode',
			self::CATEGORY_SECURITY,
			self::STATUS_GOOD,
			__( 'WP_DEBUG is disabled', 'wp-care-report' ),
			__( 'Debug mode is not enabled.', 'wp-care-report' ),
			__( 'Enable debugging only temporarily while troubleshooting.', 'wp-care-report' ),
			10
		);
	}
}
