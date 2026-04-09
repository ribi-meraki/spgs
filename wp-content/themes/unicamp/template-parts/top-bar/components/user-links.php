<?php
/**
 * User links on top bar
 *
 * @package Unicamp
 * @since   1.3.1
 * @version 2.7.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="top-bar-user-links">
	<div class="link-wrap">
		<?php if ( ! is_user_logged_in() ) { ?>
			<?php
			$btn_login_text = __( 'Log in', 'unicamp' );
			?>
			<a href="<?php echo esc_url( unicamp_login_url() ); ?>"
			   title="<?php echo esc_attr( $btn_login_text ); ?>"
			   class="top-bar-login-link"
			><?php echo esc_html( $btn_login_text ); ?></a>

			<?php if ( get_option( 'users_can_register', false ) ) : ?>
				<?php
				$btn_signup_text = __( 'Register', 'unicamp' );
				$btn_signup_url  = unicamp_registration_url();
				?>
				<a href="<?php echo esc_url( $btn_signup_url ); ?>"
				   title="<?php echo esc_attr( $btn_signup_text ); ?>"
				   class="top-bar-register-link"
				><?php echo esc_html( $btn_signup_text ); ?></a>
			<?php endif; ?>
		<?php } else { ?>
			<?php
			$profile_url  = unicamp_user_profile_url();
			$profile_text = apply_filters( 'unicamp_user_profile_text', esc_html__( 'Profile', 'unicamp' ) );
			?>
			<?php if ( '' !== $profile_url && '' !== $profile_text ): ?>
				<a href="<?php echo esc_url( $profile_url ); ?>"><?php echo esc_html( $profile_text ); ?></a>
			<?php endif; ?>
			<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"><?php esc_html_e( 'Log out', 'unicamp' ); ?></a>
		<?php } ?>
	</div>
</div>
