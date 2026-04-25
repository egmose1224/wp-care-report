<?php
/**
 * Theme update check.
 *
 * @package WPCareReport
 */

namespace WPCareReport\Checks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ThemeUpdatesCheck extends AbstractCheck {
	public function run(): array {
		$updates = get_site_transient( 'update_themes' );
		$count   = isset( $updates->response ) && is_array( $updates->response ) ? count( $updates->response ) : 0;

		if ( $count > 0 ) {
			return $this->result(
				'theme_updates',
				self::CATEGORY_UPDATES,
				self::STATUS_RECOMMENDED,
				sprintf(
					/* translators: %d: number of theme updates. */
					_n( '%d theme update available', '%d theme updates available', $count, 'wp-care-report' ),
					$count
				),
				__( 'One or more installed themes have available updates.', 'wp-care-report' ),
				__( 'Update active and retained themes after checking compatibility.', 'wp-care-report' ),
				8
			);
		}

		return $this->result(
			'theme_updates',
			self::CATEGORY_UPDATES,
			self::STATUS_GOOD,
			__( 'Themes are up to date', 'wp-care-report' ),
			__( 'No theme updates are currently available.', 'wp-care-report' ),
			__( 'Keep the active theme and any retained fallback theme updated.', 'wp-care-report' ),
			8
		);
	}
}
