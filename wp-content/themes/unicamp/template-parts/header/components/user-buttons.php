<?php
/**
 * User buttons on header
 *
 * @package Unicamp
 * @since   1.3.1
 * @version 2.7.0
 */

defined( 'ABSPATH' ) || exit;

$header_skin      = Unicamp_Global::instance()->get_header_skin();
$button_2_classes = 'button-thin';

if ( 'light' === $header_skin ) {
	$button_2_classes .= ' button-secondary-white';
} else {
	$button_2_classes .= ' button-light-primary';
}
?>
<div class="header-user-buttons">
	<div class="inner">
		<?php
		if ( ! is_user_logged_in() ) {
			Unicamp_Templates::render_button( [
				'link'        => [
					'url' => unicamp_login_url(),
				],
				'text'        => esc_html__( 'Log In', 'unicamp' ),
				'size'        => 'sm',
				'style'       => 'bottom-line-alt button-thin',
			] );

			if ( get_option( 'users_can_register', false ) ) :
				Unicamp_Templates::render_button( [
					'link'        => [
						'url' => unicamp_registration_url(),
					],
					'text'        => esc_html__( 'Sign Up', 'unicamp' ),
					'extra_class' => $button_2_classes,
					'size'        => 'sm',
				] );
			endif;
		} else {
			$profile_url  = unicamp_user_profile_url();
			$profile_text = apply_filters( 'unicamp_user_profile_text', esc_html__( 'Profile', 'unicamp' ) );

			if ( '' !== $profile_url && '' !== $profile_text ) {
				Unicamp_Templates::render_button( [
					'link'  => [
						'url' => $profile_url,
					],
					'text'  => $profile_text,
					'size'  => 'sm',
					'style' => 'bottom-line-alt',
				] );
			}

			Unicamp_Templates::render_button( [
				'link'        => [
					'url' => esc_url( wp_logout_url( home_url() ) ),
				],
				'text'        => esc_html__( 'Log out', 'unicamp' ),
				'extra_class' => $button_2_classes,
				'size'        => 'sm',
			] );
		}
		?>
	</div>
</div>
