<?php
/**
 * Legacy environment check.
 *
 * @package WPCareReport
 */

namespace WPCareReport\Checks;

use WPCareReport\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LegacyEnvironmentCheck extends AbstractCheck {
	public function run(): array {
		$environment = (string) get_option( Plugin::OPTION_ENVIRONMENT, 'production' );

		if ( 'legacy' === $environment ) {
			return $this->result(
				'legacy_environment',
				self::CATEGORY_ENVIRONMENT,
				self::STATUS_CRITICAL,
				__( 'Legacy site warning is active', 'wp-care-report' ),
				__( 'This site is marked as a legacy environment.', 'wp-care-report' ),
				__( 'LEGACY SITE — verify before editing content or configuration.', 'wp-care-report' ),
				10
			);
		}

		return $this->result(
			'legacy_environment',
			self::CATEGORY_ENVIRONMENT,
			self::STATUS_GOOD,
			__( 'Legacy warning is not active', 'wp-care-report' ),
			__( 'This site is not marked as a legacy environment.', 'wp-care-report' ),
			__( 'Use the legacy environment only for sites that need extra caution before changes.', 'wp-care-report' ),
			10
		);
	}
}
