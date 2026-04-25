<?php
/**
 * Calculates the overall care score.
 *
 * @package WPCareReport
 */

namespace WPCareReport;

use WPCareReport\Checks\CheckInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ScoreCalculator {
	/**
	 * @param array<int,array<string,mixed>> $checks Check results.
	 */
	public function calculate( array $checks ): int {
		$score = 100;

		foreach ( $checks as $check ) {
			$weight = isset( $check['weight'] ) ? (int) $check['weight'] : 0;
			$status = isset( $check['status'] ) ? (string) $check['status'] : CheckInterface::STATUS_GOOD;

			if ( CheckInterface::STATUS_CRITICAL === $status ) {
				$score -= $weight;
			} elseif ( CheckInterface::STATUS_RECOMMENDED === $status ) {
				$score -= (int) ceil( $weight / 2 );
			}
		}

		return max( 0, min( 100, $score ) );
	}

	public function get_status_label( int $score ): string {
		if ( $score >= 90 ) {
			return __( 'Excellent', 'wp-care-report' );
		}

		if ( $score >= 75 ) {
			return __( 'Good', 'wp-care-report' );
		}

		if ( $score >= 50 ) {
			return __( 'Needs attention', 'wp-care-report' );
		}

		return __( 'Critical attention needed', 'wp-care-report' );
	}
}
