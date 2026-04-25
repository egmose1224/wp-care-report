<?php
/**
 * SSL check.
 *
 * @package WPCareReport
 */

namespace WPCareReport\Checks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SslCheck extends AbstractCheck {
	public function run(): array {
		$home_url = home_url();
		$site_url = site_url();
		$uses_ssl = str_starts_with( $home_url, 'https://' ) && str_starts_with( $site_url, 'https://' );

		if ( ! $uses_ssl ) {
			return $this->result(
				'ssl',
				self::CATEGORY_SECURITY,
				self::STATUS_CRITICAL,
				__( 'SSL is not active for site URLs', 'wp-care-report' ),
				__( 'The WordPress home or site URL does not use HTTPS.', 'wp-care-report' ),
				__( 'Use HTTPS for public websites and update WordPress URLs after installing a valid certificate.', 'wp-care-report' ),
				12
			);
		}

		return $this->result(
			'ssl',
			self::CATEGORY_SECURITY,
			self::STATUS_GOOD,
			__( 'SSL is active for site URLs', 'wp-care-report' ),
			__( 'The WordPress home and site URLs use HTTPS.', 'wp-care-report' ),
			__( 'Keep certificates renewed and check mixed content after URL changes.', 'wp-care-report' ),
			12
		);
	}
}
