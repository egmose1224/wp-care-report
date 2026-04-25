<?php
/**
 * Inactive theme check.
 *
 * @package WPCareReport
 */

namespace WPCareReport\Checks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class InactiveThemesCheck extends AbstractCheck {
	public function run(): array {
		$themes       = wp_get_themes();
		$active       = get_stylesheet();
		$parent       = get_template();
		$inactive     = 0;
		$retained_max = $active === $parent ? 1 : 2;

		foreach ( array_keys( $themes ) as $stylesheet ) {
			if ( $stylesheet !== $active && $stylesheet !== $parent ) {
				++$inactive;
			}
		}

		if ( $inactive > $retained_max ) {
			return $this->result(
				'inactive_themes',
				self::CATEGORY_UPDATES,
				self::STATUS_RECOMMENDED,
				sprintf(
					/* translators: %d: number of inactive themes. */
					_n( '%d extra inactive theme found', '%d extra inactive themes found', $inactive, 'wp-care-report' ),
					$inactive
				),
				__( 'Several inactive themes are installed on this site.', 'wp-care-report' ),
				__( 'Keep the active theme, required parent theme, and one current default fallback theme only.', 'wp-care-report' ),
				4
			);
		}

		return $this->result(
			'inactive_themes',
			self::CATEGORY_UPDATES,
			self::STATUS_GOOD,
			__( 'Theme list looks tidy', 'wp-care-report' ),
			__( 'No concerning number of inactive themes was found.', 'wp-care-report' ),
			__( 'Review inactive themes during maintenance and remove old themes that are not needed.', 'wp-care-report' ),
			4
		);
	}
}
