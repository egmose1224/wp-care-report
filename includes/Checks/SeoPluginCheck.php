<?php
/**
 * SEO plugin check.
 *
 * @package WPCareReport
 */

namespace WPCareReport\Checks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SeoPluginCheck extends AbstractCheck {
	public function run(): array {
		$found = $this->has_plugin_basename(
			array(
				'wordpress-seo/wp-seo.php',
				'seo-by-rank-math/rank-math.php',
				'all-in-one-seo-pack/all_in_one_seo_pack.php',
				'seopress/seopress.php',
			)
		);

		if ( ! $found ) {
			return $this->result(
				'seo_plugin',
				self::CATEGORY_SEO,
				self::STATUS_RECOMMENDED,
				__( 'No common SEO plugin found', 'wp-care-report' ),
				__( 'This site does not appear to have one of the common SEO plugins installed.', 'wp-care-report' ),
				__( 'Confirm SEO requirements and install an SEO plugin if the site needs metadata and sitemap management.', 'wp-care-report' ),
				6
			);
		}

		return $this->result(
			'seo_plugin',
			self::CATEGORY_SEO,
			self::STATUS_GOOD,
			__( 'SEO plugin found', 'wp-care-report' ),
			__( 'A common SEO plugin appears to be installed.', 'wp-care-report' ),
			__( 'Review titles, descriptions, sitemap settings, and indexing rules before launch.', 'wp-care-report' ),
			6
		);
	}
}
