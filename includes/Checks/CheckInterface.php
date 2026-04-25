<?php
/**
 * Contract for report checks.
 *
 * @package WPCareReport
 */

namespace WPCareReport\Checks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface CheckInterface {
	public const STATUS_GOOD        = 'good';
	public const STATUS_RECOMMENDED = 'recommended';
	public const STATUS_CRITICAL    = 'critical';

	public const CATEGORY_UPDATES     = 'updates';
	public const CATEGORY_SECURITY    = 'security';
	public const CATEGORY_SEO         = 'seo';
	public const CATEGORY_MAINTENANCE = 'maintenance';
	public const CATEGORY_ENVIRONMENT = 'environment';

	/**
	 * Run the check.
	 *
	 * @return array{id:string,category:string,status:string,title:string,message:string,recommendation:string,weight:int}
	 */
	public function run(): array;
}
