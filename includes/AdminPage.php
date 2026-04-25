<?php
/**
 * Admin report page.
 *
 * @package WPCareReport
 */

namespace WPCareReport;

use WPCareReport\Checks\CheckInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AdminPage {
	private ReportGenerator $report_generator;
	private MarkdownExporter $markdown_exporter;

	/**
	 * @var array<string,string>
	 */
	private array $environments = array(
		'production'  => 'Production',
		'staging'     => 'Staging',
		'development' => 'Development',
		'legacy'      => 'Legacy',
	);

	public function __construct( ReportGenerator $report_generator, MarkdownExporter $markdown_exporter ) {
		$this->report_generator  = $report_generator;
		$this->markdown_exporter = $markdown_exporter;
	}

	public function register(): void {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_init', array( $this, 'maybe_save_environment' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'admin_post_wp_care_report_download_markdown', array( $this, 'download_markdown' ) );
	}

	public function register_menu(): void {
		add_management_page(
			__( 'WP Care Report', 'wp-care-report' ),
			__( 'WP Care Report', 'wp-care-report' ),
			'manage_options',
			'wp-care-report',
			array( $this, 'render' )
		);
	}

	public function enqueue_assets( string $hook_suffix ): void {
		if ( 'tools_page_wp-care-report' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style(
			'wp-care-report-admin',
			WP_CARE_REPORT_URL . 'assets/admin.css',
			array(),
			WP_CARE_REPORT_VERSION
		);

		wp_enqueue_script(
			'wp-care-report-admin',
			WP_CARE_REPORT_URL . 'assets/admin.js',
			array(),
			WP_CARE_REPORT_VERSION,
			true
		);
	}

	public function maybe_save_environment(): void {
		if ( ! isset( $_POST['wp_care_report_action'] ) || 'save_environment' !== sanitize_text_field( wp_unslash( $_POST['wp_care_report_action'] ) ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to save this setting.', 'wp-care-report' ) );
		}

		check_admin_referer( 'wp_care_report_save_environment' );

		$environment = isset( $_POST['wp_care_report_environment'] )
			? sanitize_text_field( wp_unslash( $_POST['wp_care_report_environment'] ) )
			: 'production';

		if ( ! array_key_exists( $environment, $this->environments ) ) {
			$environment = 'production';
		}

		update_option( Plugin::OPTION_ENVIRONMENT, $environment );

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'                 => 'wp-care-report',
					'wp-care-report-saved' => '1',
				),
				admin_url( 'tools.php' )
			)
		);
		exit;
	}

	public function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to view this report.', 'wp-care-report' ) );
		}

		$report      = $this->report_generator->generate();
		$markdown    = $this->markdown_exporter->export( $report );
		$environment = (string) get_option( Plugin::OPTION_ENVIRONMENT, 'production' );

		?>
		<div class="wrap wp-care-report">
			<h1><?php esc_html_e( 'WP Care Report', 'wp-care-report' ); ?></h1>

			<?php if ( isset( $_GET['wp-care-report-saved'] ) ) : ?>
				<div class="notice notice-success is-dismissible">
					<p><?php esc_html_e( 'Environment setting saved.', 'wp-care-report' ); ?></p>
				</div>
			<?php endif; ?>

			<div class="wp-care-report__summary">
				<div>
					<p class="wp-care-report__eyebrow"><?php esc_html_e( 'Site', 'wp-care-report' ); ?></p>
					<h2><?php echo esc_html( (string) $report['site_name'] ); ?></h2>
					<p><a href="<?php echo esc_url( (string) $report['site_url'] ); ?>"><?php echo esc_html( (string) $report['site_url'] ); ?></a></p>
					<p><?php echo esc_html( sprintf( __( 'Generated: %s', 'wp-care-report' ), (string) $report['generated_at'] ) ); ?></p>
				</div>
				<div class="wp-care-report__score">
					<span class="wp-care-report__score-number"><?php echo esc_html( (string) $report['score'] ); ?></span>
					<span class="wp-care-report__score-total">/100</span>
					<strong><?php echo esc_html( (string) $report['status_label'] ); ?></strong>
				</div>
			</div>

			<div class="wp-care-report__panel">
				<h2><?php esc_html_e( 'Environment', 'wp-care-report' ); ?></h2>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin.php?page=wp-care-report' ) ); ?>">
					<?php wp_nonce_field( 'wp_care_report_save_environment' ); ?>
					<input type="hidden" name="wp_care_report_action" value="save_environment">
					<label for="wp-care-report-environment"><?php esc_html_e( 'Site environment', 'wp-care-report' ); ?></label>
					<select id="wp-care-report-environment" name="wp_care_report_environment">
						<?php foreach ( $this->environments as $value => $label ) : ?>
							<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $environment, $value ); ?>>
								<?php echo esc_html( $label ); ?>
							</option>
						<?php endforeach; ?>
					</select>
					<?php submit_button( __( 'Save Environment', 'wp-care-report' ), 'secondary', 'submit', false ); ?>
				</form>
			</div>

			<div class="wp-care-report__actions">
				<button type="button" class="button button-primary" data-wp-care-report-copy>
					<?php esc_html_e( 'Copy Markdown Report', 'wp-care-report' ); ?>
				</button>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="wp_care_report_download_markdown">
					<?php wp_nonce_field( 'wp_care_report_download_markdown' ); ?>
					<?php submit_button( __( 'Download Markdown Report', 'wp-care-report' ), 'secondary', 'submit', false ); ?>
				</form>
				<span class="wp-care-report__copy-status" aria-live="polite" data-wp-care-report-copy-status></span>
			</div>

			<textarea class="wp-care-report__markdown" data-wp-care-report-markdown readonly><?php echo esc_textarea( $markdown ); ?></textarea>

			<section class="wp-care-report__section">
				<h2><?php esc_html_e( 'Care priorities', 'wp-care-report' ); ?></h2>
				<div class="wp-care-report__care-plan">
					<?php foreach ( $report['care_plan'] as $group => $items ) : ?>
						<div class="wp-care-report__care-plan-group">
							<h3><?php echo esc_html( (string) $report['care_plan_labels'][ $group ] ); ?></h3>
							<?php if ( empty( $items ) ) : ?>
								<p><?php esc_html_e( 'No items in this group.', 'wp-care-report' ); ?></p>
							<?php else : ?>
								<ul>
									<?php foreach ( $items as $item ) : ?>
										<li>
											<strong><?php echo esc_html( (string) $item['title'] ); ?></strong><br>
											<?php echo esc_html( (string) $item['recommendation'] ); ?>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</section>

			<?php foreach ( $report['grouped_checks'] as $category => $checks ) : ?>
				<section class="wp-care-report__section">
					<h2><?php echo esc_html( (string) $report['category_labels'][ $category ] ); ?></h2>
					<div class="wp-care-report__checks">
						<?php foreach ( $checks as $check ) : ?>
							<article class="wp-care-report__check">
								<span class="<?php echo esc_attr( 'wp-care-report__badge wp-care-report__badge--' . $check['status'] ); ?>">
									<?php echo esc_html( $this->status_label( (string) $check['status'] ) ); ?>
								</span>
								<h3><?php echo esc_html( (string) $check['title'] ); ?></h3>
								<p><?php echo esc_html( (string) $check['message'] ); ?></p>
								<p><strong><?php esc_html_e( 'Recommendation:', 'wp-care-report' ); ?></strong> <?php echo esc_html( (string) $check['recommendation'] ); ?></p>
							</article>
						<?php endforeach; ?>
					</div>
				</section>
			<?php endforeach; ?>
		</div>
		<?php
	}

	public function download_markdown(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to download this report.', 'wp-care-report' ) );
		}

		check_admin_referer( 'wp_care_report_download_markdown' );

		$report   = $this->report_generator->generate();
		$markdown = $this->markdown_exporter->export( $report );
		$filename = sanitize_file_name( 'wp-care-report-' . gmdate( 'Y-m-d-His' ) . '.md' );

		nocache_headers();
		header( 'Content-Type: text/markdown; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
		header( 'Content-Length: ' . strlen( $markdown ) );

		echo $markdown; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Markdown file download content.
		exit;
	}

	private function status_label( string $status ): string {
		return match ( $status ) {
			CheckInterface::STATUS_CRITICAL => __( 'Critical', 'wp-care-report' ),
			CheckInterface::STATUS_RECOMMENDED => __( 'Recommended', 'wp-care-report' ),
			default => __( 'Good', 'wp-care-report' ),
		};
	}
}
