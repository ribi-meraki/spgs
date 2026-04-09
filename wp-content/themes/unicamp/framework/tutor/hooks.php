<?php
defined( 'ABSPATH' ) || exit;

//use Tutor\Ecommerce\Settings;
use Tutor\Ecommerce\OptionKeys;

if ( ! class_exists( 'Unicamp_Hooks' ) ) {
	class Unicamp_Hooks {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'get_tutor_course_price', [ $this, 'update_get_tutor_course_price_html' ], 10, 2 );
		}

		/**
		 * Update price html by monetize by Tutor native
		 *
		 * @return void
		 * @see tutor_utils()::get_course_price()
		 */
		public function update_get_tutor_course_price_html( $price, $course_id ) {
			if ( ! tutor_utils()->is_course_purchasable( $course_id ) || ! Unicamp_Tutor::instance()->is_monetize_by_tutor() ) {
				return $price;
			}

			$price_data = tutor_utils()->get_raw_course_price( $course_id );
			$is_on_sale = $price_data->sale_price && $price_data->regular_price != $price_data->sale_price;

			ob_start();
			?>
			<div class="list-item-price tutor-item-price price<?php if ( $is_on_sale ): echo ' price--on-sale'; endif; ?>">
				<?php if ( $is_on_sale ) : ?>
					<ins><span class="amount"><?php $this->get_formatted_price( $price_data->display_price ); ?></span>
					</ins>
					<del><span class="amount"><?php $this->get_formatted_price( $price_data->regular_price ); ?></span>
					</del>
				<?php else : ?>
					<ins><span><?php $this->get_formatted_price( $price_data->display_price ); ?></span></ins>
				<?php endif; ?>
			</div>
			<?php if ( $price_data->show_price_with_tax ) : ?>
				<div class="tutor-course-price-tax tutor-fs-8 tutor-fw-normal tutor-color-black"><?php esc_html_e( 'Incl. tax', 'tutor' ); ?></div>
			<?php endif; ?>
			<?php
			$price = ob_get_clean();

			return $price;
		}

		public function get_formatted_price( $price ) {
			$price             = tutor_get_formatted_price( $price );
			$no_of_decimal     = intval( tutor_utils()->get_option( OptionKeys::NUMBER_OF_DECIMALS, '2' ) );
			$decimal_separator = tutor_utils()->get_option( OptionKeys::DECIMAL_SEPARATOR, '.' );

			if ( $no_of_decimal > 0 && ! empty( $decimal_separator ) ) {

				// Escape the decimal separator for use in the regex.
				$escapedSeparator = preg_quote( $decimal_separator, '/' );

				// Match the price format and wrap the decimal part with a span tag
				$pattern     = '/(\d+)(' . $escapedSeparator . '\d{' . $no_of_decimal . '})(\$?)/';
				$replacement = '$1<span class="decimals-separator">$2</span>$3';
				$price       = preg_replace( $pattern, $replacement, $price );
			}

			echo $price;
		}
	}
}
