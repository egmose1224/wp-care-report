<?php
/**
 * Permalink structure check.
 *
 * @package WPCareReport
 */

namespace WPCareReport\Checks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PermalinkStructureCheck extends AbstractCheck {
	public function run(): array {
		$structure = (string) get_option( 'permalink_structure' );

		if ( '' === $structure ) {
			return $this->result(
				'permalink_structure',
				self::CATEGORY_SEO,
				self::STATUS_RECOMMENDED,
				__( 'Plain permalink structure is active', 'wp-care-report' ),
				__( 'The site uses plain query-string permalinks.', 'wp-care-report' ),
				__( 'Use a readable permalink structure such as Post name when appropriate for the site.', 'wp-care-report' ),
				6
			);
		}

		return $this->result(
			'permalink_structure',
			self::CATEGORY_SEO,
			self::STATUS_GOOD,
			__( 'Readable permalinks are active', 'wp-care-report' ),
			__( 'The site uses a custom permalink structure.', 'wp-care-report' ),
			__( 'Keep permalink changes planned carefully because they can affect existing URLs.', 'wp-care-report' ),
			6
		);
	}
}
