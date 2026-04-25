<?php
/**
 * Plugin update check.
 *
 * @package WPCareReport
 */

namespace WPCareReport\Checks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PluginUpdatesCheck extends AbstractCheck {
	public function run(): array {
		$updates = get_site_transient( 'update_plugins' );
		$count   = isset( $updates->response ) && is_array( $updates->response ) ? count( $updates->response ) : 0;

		if ( $count > 0 ) {
			return $this->result(
				'plugin_updates',
				self::CATEGORY_UPDATES,
				self::STATUS_RECOMMENDED,
				sprintf(
					/* translators: %d: number of plugin updates. */
					_n( '%d plugin update available', '%d plugin updates available', $count, 'wp-care-report' ),
					$count
				),
				__( 'One or more installed plugins have available updates.', 'wp-care-report' ),
				__( 'Review plugin changelogs, test if needed, and apply updates regularly.', 'wp-care-report' ),
				10
			);
		}

		return $this->result(
			'plugin_updates',
			self::CATEGORY_UPDATES,
			self::STATUS_GOOD,
			__( 'Plugins are up to date', 'wp-care-report' ),
			__( 'No plugin updates are currently available.', 'wp-care-report' ),
			__( 'Keep plugins updated as part of the regular maintenance routine.', 'wp-care-report' ),
			10
		);
	}
}
