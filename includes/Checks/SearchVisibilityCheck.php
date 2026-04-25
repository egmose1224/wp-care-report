<?php
/**
 * Search visibility check.
 *
 * @package WPCareReport
 */

namespace WPCareReport\Checks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SearchVisibilityCheck extends AbstractCheck {
	public function run(): array {
		$discouraged = (bool) get_option( 'blog_public' ) === false;

		if ( $discouraged ) {
			return $this->result(
				'search_visibility',
				self::CATEGORY_SEO,
				self::STATUS_CRITICAL,
				__( 'Search engine visibility is disabled', 'wp-care-report' ),
				__( 'WordPress is configured to discourage search engines from indexing this site.', 'wp-care-report' ),
				__( 'Enable search engine visibility before launch if this is a public website.', 'wp-care-report' ),
				12
			);
		}

		return $this->result(
			'search_visibility',
			self::CATEGORY_SEO,
			self::STATUS_GOOD,
			__( 'Search engine visibility is enabled', 'wp-care-report' ),
			__( 'WordPress is not discouraging search engines from indexing this site.', 'wp-care-report' ),
			__( 'Disable visibility only for private, staging, or development sites.', 'wp-care-report' ),
			12
		);
	}
}
