<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Popup' ) ) {

	class Unicamp_Popup {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_footer', [ $this, 'add_popup_pre_loader' ] );

			add_action( 'wp_ajax_unicamp_lazy_load_template', [ $this, 'ajax_load_template_part' ] );
			add_action( 'wp_ajax_nopriv_unicamp_lazy_load_template', [ $this, 'ajax_load_template_part' ] );
		}

		public function add_popup_pre_loader() {
			?>
			<div id="popup-pre-loader" class="popup-pre-loader">
				<div class="popup-load-inner">
					<div class="popup-loader-wrap">
						<div class="wrap-2">
							<div class="inner">
								<?php unicamp_load_template( 'preloader/style', 'circle' ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		public function ajax_load_template_part() {
			check_ajax_referer( 'unicamp-security', 'security' );

			$template       = ! empty( $_GET['template'] ) ? sanitize_file_name( $_GET['template'] ) : '';
			$template_scope = ! empty( $_GET['template_scope'] ) ? sanitize_file_name( $_GET['template_scope'] ) : '';

			if ( empty( $template ) || empty( $template_scope ) ) {
				wp_send_json_error();
			}

			$template_suffix = '';

			switch ( $template_scope ):
				case 'theme-popup':
					$template_suffix = 'template-parts/popup/';
					break;
				case 'tutor':
					$template_suffix = 'tutor/';
					break;
				default:
					wp_send_json_error();
					break;
			endswitch;

			$template_part = $template_suffix . $template;

			ob_start();
			get_template_part( $template_part );
			$html = ob_get_clean();

			$response = [
				'template' => $html,
			];

			wp_send_json_success( $response );
		}
	}

	Unicamp_Popup::instance()->initialize();
}
