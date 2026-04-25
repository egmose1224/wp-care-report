<?php
/**
 * Shared helpers for checks.
 *
 * @package WPCareReport
 */

namespace WPCareReport\Checks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class AbstractCheck implements CheckInterface {
	/**
	 * @return array{id:string,category:string,status:string,title:string,message:string,recommendation:string,weight:int}
	 */
	protected function result( string $id, string $category, string $status, string $title, string $message, string $recommendation, int $weight ): array {
		return array(
			'id'             => $id,
			'category'       => $category,
			'status'         => $status,
			'title'          => $title,
			'message'        => $message,
			'recommendation' => $recommendation,
			'weight'         => $weight,
		);
	}

	/**
	 * @return array<string,array<string,mixed>>
	 */
	protected function get_installed_plugins(): array {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return get_plugins();
	}

	/**
	 * @param array<int,string> $basenames Plugin basenames to look for.
	 */
	protected function has_plugin_basename( array $basenames ): bool {
		$plugins = $this->get_installed_plugins();

		foreach ( $basenames as $basename ) {
			if ( isset( $plugins[ $basename ] ) ) {
				return true;
			}
		}

		return false;
	}
}
