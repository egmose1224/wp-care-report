<?php
/**
 * Converts reports to Markdown.
 *
 * @package WPCareReport
 */

namespace WPCareReport;

use WPCareReport\Checks\CheckInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MarkdownExporter {
	/**
	 * @param array<string,mixed> $report Report data.
	 */
	public function export( array $report ): string {
		$lines = array(
			'# WP Care Report for ' . $this->plain( (string) $report['site_name'] ),
			'',
			'Generated: ' . $this->plain( (string) $report['generated_at'] ),
			'',
			'Site URL: ' . $this->plain( (string) $report['site_url'] ),
			'',
			'Overall score: ' . (int) $report['score'] . '/100  ',
			'Status: ' . $this->plain( (string) $report['status_label'] ),
			'',
		);

		$critical = $this->filter_by_status( $report['checks'], CheckInterface::STATUS_CRITICAL );
		$recommended = $this->filter_by_status( $report['checks'], CheckInterface::STATUS_RECOMMENDED );

		$lines = array_merge( $lines, $this->summary_section( __( 'Critical issues', 'wp-care-report' ), $critical ) );
		$lines = array_merge( $lines, $this->summary_section( __( 'Recommended improvements', 'wp-care-report' ), $recommended ) );

		$lines[] = '## Care priorities';
		$lines[] = '';

		foreach ( $report['care_plan'] as $group => $items ) {
			$label   = $report['care_plan_labels'][ $group ] ?? $group;
			$lines[] = '### ' . $this->plain( (string) $label );
			$lines[] = '';

			foreach ( $items as $item ) {
				$lines[] = '- ' . $this->plain( (string) $item['title'] ) . '  ';
				$lines[] = '  ' . $this->plain( (string) $item['recommendation'] );
			}

			if ( empty( $items ) ) {
				$lines[] = '- None.';
			}

			$lines[] = '';
		}

		$lines[] = '## Full report';
		$lines[] = '';

		foreach ( $report['grouped_checks'] as $category => $checks ) {
			$label   = $report['category_labels'][ $category ] ?? $category;
			$lines[] = '### ' . $this->plain( (string) $label );
			$lines[] = '';

			foreach ( $checks as $check ) {
				$lines[] = '#### ' . $this->plain( (string) $check['title'] );
				$lines[] = '';
				$lines[] = 'Status: ' . $this->plain( $this->status_label( (string) $check['status'] ) );
				$lines[] = '';
				$lines[] = $this->plain( (string) $check['message'] );
				$lines[] = '';
				$lines[] = 'Recommendation: ' . $this->plain( (string) $check['recommendation'] );
				$lines[] = '';
			}
		}

		return trim( implode( "\n", $lines ) ) . "\n";
	}

	/**
	 * @param array<int,array<string,mixed>> $checks Check results.
	 *
	 * @return array<int,array<string,mixed>>
	 */
	private function filter_by_status( array $checks, string $status ): array {
		return array_values(
			array_filter(
				$checks,
				static fn ( array $check ): bool => isset( $check['status'] ) && $status === $check['status']
			)
		);
	}

	/**
	 * @param array<int,array<string,mixed>> $checks Check results.
	 *
	 * @return array<int,string>
	 */
	private function summary_section( string $title, array $checks ): array {
		$lines = array( '## ' . $this->plain( $title ), '' );

		if ( empty( $checks ) ) {
			$lines[] = '- None found.';
			$lines[] = '';

			return $lines;
		}

		foreach ( $checks as $check ) {
			$lines[] = '- ' . $this->plain( (string) $check['title'] ) . '  ';
			$lines[] = '  Recommendation: ' . $this->plain( (string) $check['recommendation'] );
		}

		$lines[] = '';

		return $lines;
	}

	private function status_label( string $status ): string {
		return match ( $status ) {
			CheckInterface::STATUS_CRITICAL => __( 'Critical', 'wp-care-report' ),
			CheckInterface::STATUS_RECOMMENDED => __( 'Recommended', 'wp-care-report' ),
			default => __( 'Good', 'wp-care-report' ),
		};
	}

	private function plain( string $text ): string {
		$text = wp_strip_all_tags( $text );
		$text = str_replace( array( "\r", "\n" ), ' ', $text );

		return trim( $text );
	}
}
