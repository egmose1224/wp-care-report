<?php
/**
 * WordPress core update check.
 *
 * @package WPCareReport
 */

namespace WPCareReport\Checks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CoreUpdatesCheck extends AbstractCheck {
	public function run(): array {
		if ( ! function_exists( 'get_core_updates' ) ) {
			require_once ABSPATH . 'wp-admin/includes/update.php';
		}

		$updates   = get_core_updates();
		$updates   = is_array( $updates ) ? $updates : array();
		$available = false;

		foreach ( $updates as $update ) {
			if ( isset( $update->response ) && 'upgrade' === $update->response ) {
				$available = true;
				break;
			}
		}

		if ( $available ) {
			return $this->result(
				'core_updates',
				self::CATEGORY_UPDATES,
				self::STATUS_CRITICAL,
				__( 'WordPress core update available', 'wp-care-report' ),
				__( 'A WordPress core update is available for this site.', 'wp-care-report' ),
				__( 'Review and apply the core update after taking a verified backup.', 'wp-care-report' ),
				15
			);
		}

		return $this->result(
			'core_updates',
			self::CATEGORY_UPDATES,
			self::STATUS_GOOD,
			__( 'WordPress core is up to date', 'wp-care-report' ),
			__( 'No WordPress core update is currently available.', 'wp-care-report' ),
			__( 'Keep monitoring core releases and apply security updates promptly.', 'wp-care-report' ),
			15
		);
	}
}
