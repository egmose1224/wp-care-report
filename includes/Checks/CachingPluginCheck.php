<?php
/**
 * Caching plugin check.
 *
 * @package WPCareReport
 */

namespace WPCareReport\Checks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CachingPluginCheck extends AbstractCheck {
	public function run(): array {
		$found = $this->has_plugin_basename(
			array(
				'wp-rocket/wp-rocket.php',
				'w3-total-cache/w3-total-cache.php',
				'wp-super-cache/wp-cache.php',
				'litespeed-cache/litespeed-cache.php',
			)
		);

		if ( ! $found ) {
			return $this->result(
				'caching_plugin',
				self::CATEGORY_MAINTENANCE,
				self::STATUS_RECOMMENDED,
				__( 'No common caching plugin found', 'wp-care-report' ),
				__( 'This site does not appear to have one of the common caching plugins installed.', 'wp-care-report' ),
				__( 'Confirm whether hosting provides page caching or install a caching plugin suited to the stack.', 'wp-care-report' ),
				6
			);
		}

		return $this->result(
			'caching_plugin',
			self::CATEGORY_MAINTENANCE,
			self::STATUS_GOOD,
			__( 'Caching plugin found', 'wp-care-report' ),
			__( 'A common caching plugin appears to be installed.', 'wp-care-report' ),
			__( 'Verify cache exclusions, purge behavior, and compatibility with forms or ecommerce.', 'wp-care-report' ),
			6
		);
	}
}
