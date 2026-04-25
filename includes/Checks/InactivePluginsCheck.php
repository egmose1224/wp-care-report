<?php
/**
 * Inactive plugin check.
 *
 * @package WPCareReport
 */

namespace WPCareReport\Checks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class InactivePluginsCheck extends AbstractCheck {
	public function run(): array {
		$plugins = $this->get_installed_plugins();
		$count   = 0;

		foreach ( array_keys( $plugins ) as $plugin_file ) {
			if ( ! is_plugin_active( $plugin_file ) ) {
				++$count;
			}
		}

		if ( $count > 0 ) {
			return $this->result(
				'inactive_plugins',
				self::CATEGORY_UPDATES,
				self::STATUS_RECOMMENDED,
				sprintf(
					/* translators: %d: number of inactive plugins. */
					_n( '%d inactive plugin found', '%d inactive plugins found', $count, 'wp-care-report' ),
					$count
				),
				__( 'Inactive plugins can still increase maintenance work and may be forgotten during updates.', 'wp-care-report' ),
				__( 'Remove unused plugins after confirming they are not needed.', 'wp-care-report' ),
				5
			);
		}

		return $this->result(
			'inactive_plugins',
			self::CATEGORY_UPDATES,
			self::STATUS_GOOD,
			__( 'No inactive plugins found', 'wp-care-report' ),
			__( 'All installed plugins are currently active.', 'wp-care-report' ),
			__( 'Keep the plugin list focused and remove tools that are no longer needed.', 'wp-care-report' ),
			5
		);
	}
}
