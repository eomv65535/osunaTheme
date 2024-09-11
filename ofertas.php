<?php
/*
Template Name: Ofertas
*/

get_header();
OsunaTheme::sino_usuario();
?>
<div class="bg-azulito border-inferiores cabecera">
	<?php OsunaTheme::get_custom_header(); ?>
    <p class="subtitulo"><?php echo __('Products', 'osunatheme');?></p>      
    <h3 class="mt-3 pb-2 text-white titulohead"><?php echo __('Offers near you', 'osunatheme');?></h3>
    <div id="contenedor-productos-cercanos" class="d-flex gap-2"> </div>
</div>
<br>
<h2 class="subt_comercio"><?php echo __('Recent products', 'osunatheme');?></h2>
<div class="cabecera">    
    <div class="d-flex gap-2">    
        <?php Productos::get_productos_recientes(); ?>
    </div> 
</div> 
<?php OsunaTheme::get_custom_footer(); ?>  
<?php get_footer(); ?>
<script>
    trae_comercios_cercanos()
</script>   