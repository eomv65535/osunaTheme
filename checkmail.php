<?php
/*
Template Name: Confirmar_correo
Template Post Type: page
*/
get_header(); 
?>
<div class="bg-azulito container-utm align-items-center">
	<div class="cerrar_login"><a href="<?php echo site_url('/buscar'); ?>"class="btn-close btn-close-white" aria-label="Close"></a></div>
	<div class="custom-login-form w-100">
		<img src="https://osuna.cbtpruebas.es/wp-content/uploads/2024/06/login.png" class="icon_round"> 
		<p class="title">Inicia sesión</p>
		<h2>Revitaliza Osuna</h2>
		<br>
		<?php __( 'Thank you very much for registering. Before you can log in, we need you to activate your account by clicking the activation link in the email we just sent you.', 'osunatheme' ); ?>
	</div>
</div>
<?php get_footer();  ?>
