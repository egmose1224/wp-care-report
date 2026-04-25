<?php
/**
 * Environment setting check.
 *
 * @package WPCareReport
 */

namespace WPCareReport\Checks;

use WPCareReport\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EnvironmentSettingCheck extends AbstractCheck {
	public function run(): array {
		$environment = (string) get_option( Plugin::OPTION_ENVIRONMENT, 'production' );

		if ( '' === $environment ) {
			return $this->result(
				'environment_setting',
				self::CATEGORY_ENVIRONMENT,
				self::STATUS_RECOMMENDED,
				__( 'Environment is not selected', 'wp-care-report' ),
				__( 'No WP Care Report environment value is currently stored.', 'wp-care-report' ),
				__( 'Select the correct environment so reports and admin banners have useful context.', 'wp-care-report' ),
				5
			);
		}

		return $this->result(
			'environment_setting',
			self::CATEGORY_ENVIRONMENT,
			self::STATUS_GOOD,
			sprintf(
				/* translators: %s: environment label. */
				__( 'Environment is set to %s', 'wp-care-report' ),
				$environment
			),
			__( 'WP Care Report has an environment value for this site.', 'wp-care-report' ),
			__( 'Keep this value current when cloning between production, staging, development, and legacy sites.', 'wp-care-report' ),
			5
		);
	}
}
