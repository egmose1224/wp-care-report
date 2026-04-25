<?php
/**
 * Backup plugin check.
 *
 * @package WPCareReport
 */

namespace WPCareReport\Checks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BackupPluginCheck extends AbstractCheck {
	public function run(): array {
		$found = $this->has_plugin_basename(
			array(
				'updraftplus/updraftplus.php',
				'duplicator/duplicator.php',
				'backwpup/backwpup.php',
				'all-in-one-wp-migration/all-in-one-wp-migration.php',
			)
		);

		if ( ! $found ) {
			return $this->result(
				'backup_plugin',
				self::CATEGORY_MAINTENANCE,
				self::STATUS_RECOMMENDED,
				__( 'No common backup plugin found', 'wp-care-report' ),
				__( 'This site does not appear to have one of the common backup plugins installed.', 'wp-care-report' ),
				__( 'Confirm that reliable off-site backups exist through hosting or install a backup plugin.', 'wp-care-report' ),
				10
			);
		}

		return $this->result(
			'backup_plugin',
			self::CATEGORY_MAINTENANCE,
			self::STATUS_GOOD,
			__( 'Backup plugin found', 'wp-care-report' ),
			__( 'A common backup plugin appears to be installed.', 'wp-care-report' ),
			__( 'Verify that backups run successfully and can be restored.', 'wp-care-report' ),
			10
		);
	}
}
