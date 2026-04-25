<?php
/**
 * File editor check.
 *
 * @package WPCareReport
 */

namespace WPCareReport\Checks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class FileEditCheck extends AbstractCheck {
	public function run(): array {
		if ( ! defined( 'DISALLOW_FILE_EDIT' ) || ! DISALLOW_FILE_EDIT ) {
			return $this->result(
				'file_edit',
				self::CATEGORY_SECURITY,
				self::STATUS_RECOMMENDED,
				__( 'Theme and plugin file editor is enabled', 'wp-care-report' ),
				__( 'WordPress file editing is not explicitly disabled.', 'wp-care-report' ),
				__( 'Define DISALLOW_FILE_EDIT as true in wp-config.php on production sites.', 'wp-care-report' ),
				8
			);
		}

		return $this->result(
			'file_edit',
			self::CATEGORY_SECURITY,
			self::STATUS_GOOD,
			__( 'Theme and plugin file editor is disabled', 'wp-care-report' ),
			__( 'WordPress file editing is explicitly disabled.', 'wp-care-report' ),
			__( 'Keep file editing disabled and deploy code changes through version control or hosting tools.', 'wp-care-report' ),
			8
		);
	}
}
