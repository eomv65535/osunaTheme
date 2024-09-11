<?php
/*
Template Name: Mis Favoritos
*/

get_header();

// Asegúrate de que el usuario esté autenticado
if (!is_user_logged_in()) {
    
    wp_redirect(home_url('/login'));
    exit;
}
OsunaTheme::sino_usuario();
?>
<div class="bg-azulito border-inferiores cabecera">
	<?php OsunaTheme::get_custom_header(); ?>
    <p class="subtitulo"><?php echo __('Shops', 'osunatheme');?></p>
    <?php if(isset($_GET['busq']) && !empty($_GET['busq']) && isset($_GET['busq']) && !empty($_GET['busq'])){?>
        <p class="badge text-dark rounded-4 comersele"><?php echo __('Search', 'osunatheme');?>:<em>&nbsp;"<?php echo $_GET['busq'];?>"</em> <button type="button" class="btn-close btn-close" onclick="borrar_busqueda('busq')"></button></p>
    <?php }?>
    
    <?php 
    $muestra_cats="";
    $valor_s=(isset($_GET['busq']) && !empty($_GET['busq']))? $_GET['busq'] :"";   
    ?>       
    
    
    <h3 class="mt-3 pb-2 text-white titulohead"><?php echo __('My favourites', 'osunatheme');?></h3>
    <form id="forma_busq_favoritos" method="get" action="<?php echo esc_url(home_url('/misfavoritos')); ?>">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
            </div>            
            <input type="text" class="form-control" id="busq" name="busq" value="<?php echo $valor_s;?>" placeholder="<?php echo __('Search', 'osunatheme');?>..." aria-label="Search" aria-describedby="basic-addon1">                       
        </div>
    </form>  
</div>
<div class="cabecera">
      <?php 
      $user_id = get_current_user_id();
      Comercios::get_comercios_favoritos($user_id);
      ?>      
</div> 
<?php OsunaTheme::get_custom_footer(); ?>  
<?php get_footer(); ?>
