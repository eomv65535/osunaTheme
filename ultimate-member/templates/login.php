<?php
/**
 * Template for the login form
 *
 * This template can be overridden by copying it to yourtheme/ultimate-member/templates/login.php
 *
 * Page: "Login"
 *
 * @version 2.7.0
 *
 * @var string $mode
 * @var int    $form_id
 * @var array  $args
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="bg-azulito container-utm align-content-center align-items-center justify-content-between flex-wrap flex-column pt-5">
	<div class="cerrar_login"><a href="<?php echo site_url('/buscar'); ?>"class="btn-close btn-close-white" aria-label="Close"></a></div>
	<div class="custom-login-form w-100 p-5">
		<div class=" w-100 text-center"><img src="https://osuna.cbtpruebas.es/wp-content/uploads/2024/06/Osuna-Logo-Vector.png" style="width: 120px;"></div>
		<br>
		<p class="title">Inicia sesión</p>
		<h2>Revitaliza Osuna</h2>
		<br>
		<form method="post" action="" autocomplete="off">
			<?php
			/** This action is documented in includes/core/um-actions-profile.php */
			do_action( 'um_before_form', $args );
			/** This action is documented in includes/core/um-actions-profile.php */
			do_action( "um_before_{$mode}_fields", $args );
			/** This action is documented in includes/core/um-actions-profile.php */
			do_action( "um_main_{$mode}_fields", $args );
			/** This action is documented in includes/core/um-actions-profile.php */
			do_action( 'um_after_form_fields', $args );
			/** This action is documented in includes/core/um-actions-profile.php */
			do_action( "um_after_{$mode}_fields", $args );
			/** This action is documented in includes/core/um-actions-profile.php */
			do_action( 'um_after_form', $args );
			?>
		</form>
	</div>
	<?php echo OsunaTheme::get_logos_footer(); ?>
</div>
