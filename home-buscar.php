<?php
/*
Template Name: Home buscar
*/
get_header(); 
OsunaTheme::sino_usuario();
?>
<div class="bg-azulito border-inferiores cabecera">
	<?php OsunaTheme::get_custom_header(); ?>
    <p class="subtitulo"><?php echo __('Search', 'osunatheme');?></p>
    <h3 class="mt-3 pb-2 text-white titulohead"><?php echo __('What are you looking for?', 'osunatheme');?></h3>   
    <form method="get" action="<?php echo esc_url(home_url('/comercios')); ?>"> 
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
            </div>
            <input type="text" class="form-control" name="s" placeholder="<?php echo __('Search', 'osunatheme');?>..." aria-label="Username" aria-describedby="basic-addon1">
        </div>
        </form>    
    <div class="" id="contenedor-categorias">
        <?php Comercios::get_catego_comercios(""); ?>        
    </div>
    <div class="flechita">
            <img src="https://osuna.cbtpruebas.es/wp-content/uploads/2024/06/flecha-hacia-abajo3.png">
    </div>
</div>
<div>
    <h2 class="subt_comercio"><?php echo __('Shops near you', 'osunatheme');?></h2>        
    <?php echo do_shortcode('[dynamic_map]'); ?>
</div>
<?php OsunaTheme::get_custom_footer(); ?>  
<?php get_footer(); ?>
