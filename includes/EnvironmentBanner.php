<?php
/**
 * Admin environment banner.
 *
 * @package WPCareReport
 */

namespace WPCareReport;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EnvironmentBanner {
	/**
	 * @var array<string,string>
	 */
	private array $labels = array(
		'staging'     => 'STAGING SITE',
		'development' => 'DEVELOPMENT SITE',
		'legacy'      => 'LEGACY SITE',
	);

	public function register(): void {
		add_action( 'admin_notices', array( $this, 'render' ) );
	}

	public function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$environment = (string) get_option( Plugin::OPTION_ENVIRONMENT, 'production' );

		if ( ! isset( $this->labels[ $environment ] ) ) {
			return;
		}

		$message = 'legacy' === $environment
			? __( 'LEGACY SITE — verify before editing content or configuration.', 'wp-care-report' )
			: sprintf(
				/* translators: %s: environment label. */
				__( '%s — verify you are working on the intended site.', 'wp-care-report' ),
				$this->labels[ $environment ]
			);

		$class = 'legacy' === $environment ? 'notice notice-error' : 'notice notice-warning';

		?>
		<div class="<?php echo esc_attr( $class ); ?> wp-care-report-environment-notice">
			<p><strong><?php echo esc_html( $message ); ?></strong></p>
		</div>
		<?php
	}
}
