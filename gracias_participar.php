<?php
/*
Template Name: Gracias participar
Template Post Type: page
*/
get_header(); 
?>
<div class="bg-azulito container-utm align-items-center">
	<div class="cerrar_login"><a href="<?php echo site_url('/buscar'); ?>"class="btn-close btn-close-white" aria-label="Close"></a></div>
	<div class="custom-login-form w-100">
        <div class=" w-100 text-center"><img src="https://osuna.cbtpruebas.es/wp-content/uploads/2024/06/Osuna-Logo-Vector.png" style="width: 120px;"></div>
        <br>
        <p class="title"><?php echo __('New participation', 'osunatheme');?></p>
        <h2>Revitaliza Osuna</h2>
		<br>
		<p><?php echo __( 'Thanks for participating.', 'osunatheme' ); ?></p>
        <br>
        <p><a class="btn btn-primary" href="<?php echo site_url('/misparticipaciones'); ?>"> <?php echo __( 'My participations', 'osunatheme' );?> </a> <a class="btn btn-secondary" href="<?php echo site_url('/buscar'); ?>"> <?php echo __( 'Return', 'osunatheme' );?></a></p>
	</div>
</div>
<?php get_footer();  ?>
