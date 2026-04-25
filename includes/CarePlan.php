<?php
/**
 * Builds a practical next-action plan from report findings.
 *
 * @package WPCareReport
 */

namespace WPCareReport;

use WPCareReport\Checks\CheckInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CarePlan {
	/**
	 * @param array<int,array<string,mixed>> $checks Check results.
	 *
	 * @return array<string,array<int,array<string,string>>>
	 */
	public function build( array $checks ): array {
		$plan = array(
			'fix_now'  => array(),
			'schedule' => array(),
			'monitor'  => array(),
		);

		$findings = array_values(
			array_filter(
				$checks,
				static fn ( array $check ): bool => isset( $check['status'] ) && CheckInterface::STATUS_GOOD !== $check['status']
			)
		);

		usort(
			$findings,
			static function ( array $a, array $b ): int {
				$status_order = array(
					CheckInterface::STATUS_CRITICAL    => 0,
					CheckInterface::STATUS_RECOMMENDED => 1,
					CheckInterface::STATUS_GOOD        => 2,
				);

				$a_status = $status_order[ $a['status'] ?? CheckInterface::STATUS_GOOD ] ?? 2;
				$b_status = $status_order[ $b['status'] ?? CheckInterface::STATUS_GOOD ] ?? 2;

				if ( $a_status !== $b_status ) {
					return $a_status <=> $b_status;
				}

				return ( (int) ( $b['weight'] ?? 0 ) ) <=> ( (int) ( $a['weight'] ?? 0 ) );
			}
		);

		foreach ( $findings as $check ) {
			$item = array(
				'title'          => (string) $check['title'],
				'recommendation' => (string) $check['recommendation'],
			);

			if ( CheckInterface::STATUS_CRITICAL === $check['status'] ) {
				$plan['fix_now'][] = $item;
				continue;
			}

			$plan['schedule'][] = $item;
		}

		if ( empty( $plan['fix_now'] ) && empty( $plan['schedule'] ) ) {
			$plan['monitor'][] = array(
				'title'          => __( 'No urgent care actions found', 'wp-care-report' ),
				'recommendation' => __( 'Keep the site on a regular update, backup, security, and performance review schedule.', 'wp-care-report' ),
			);
		} else {
			$plan['monitor'][] = array(
				'title'          => __( 'Re-run this report after maintenance', 'wp-care-report' ),
				'recommendation' => __( 'Use the updated score and remaining findings as the next maintenance baseline.', 'wp-care-report' ),
			);
		}

		return $plan;
	}

	/**
	 * @return array<string,string>
	 */
	public function labels(): array {
		return array(
			'fix_now'  => __( 'Fix now', 'wp-care-report' ),
			'schedule' => __( 'Schedule', 'wp-care-report' ),
			'monitor'  => __( 'Monitor', 'wp-care-report' ),
		);
	}
}
