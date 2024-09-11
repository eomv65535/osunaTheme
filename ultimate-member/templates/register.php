<?php
/**
 * Template for the registration form
 *
 * This template can be overridden by copying it to yourtheme/ultimate-member/templates/register.php
 *
 * Page: "Register"
 *
 * @version 2.7.0
 *
 * @var string $mode
 * @var int    $form_id
 * @var array  $args
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="bg-azulito container-utm">
    <div class="cerrar_login" style="position:absolute"><a href="<?php echo site_url('/buscar'); ?>"class="btn-close btn-close-white" aria-label="Close"></a></div>
    <div class="custom-login-form w-100">
	<div class=" w-100 text-center"><img src="https://osuna.cbtpruebas.es/wp-content/uploads/2024/06/Osuna-Logo-Vector.png" style="width: 120px;"></div>
	<br>
        <p class="title">Regístrate</p>
        <h2>Revitaliza Osuna</h2>
        <br>
        <form method="post" action="">
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
</div>