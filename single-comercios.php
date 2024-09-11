<?php
get_header();

if (have_posts()):
    while (have_posts()):

        the_post();
        $idioma = ICL_LANGUAGE_CODE=='es' ? '' : '/' . ICL_LANGUAGE_CODE;
        $id = get_the_ID();
        $nombre = strtoupper(get_the_title());
        $descripcion = get_the_content();
        $latitud = get_post_meta($id, 'latitud', true);
        $longitud = get_post_meta($id, 'longitud', true);
        $direccion = get_post_meta($id, 'direccion', true);
        $telefono = get_post_meta($id, 'telefono', true);
        $email = get_post_meta($id, 'email', true);
        $horario = get_post_meta($id, 'horario', true);
        $web = get_post_meta($id, 'web', true);
        $facebook = get_post_meta($id, 'facebook', true);
        $instagram = get_post_meta($id, 'instagram', true);
        $galeria = get_post_meta($id, 'galeria', true);

        $logo_id = get_post_meta($id, 'logo', true);
        $logo_url = wp_get_attachment_url($logo_id);
        $imagen_url = get_the_post_thumbnail_url($id, 'full');
        $terms = get_the_terms($id, 'categoria_comercio'); 
        $nombre_catego = '';       
        if ($terms && !is_wp_error($terms)) {            
            $term = $terms[0];
            $nombre_catego = $term->name;
            $slug_catego = $term->slug;
        }
        $user_id = get_current_user_id();
            
        $favoritos_usuario = get_user_meta($user_id, 'favoritos', true);
        
        $megusta_class = $favoritos_usuario? (in_array($id, $favoritos_usuario) ? 'megusta' : '') : '';

        $nueva_fecha_visita = date('Y-m-d');        
        $visitas = get_field('Visitas', $id);        
        $visitas[]= array('fecha_visita' => $nueva_fecha_visita);        
        update_field('visitas', $visitas, $id);
?>
<div class="bg-azulito border-inferiores cabecera">
	<?php OsunaTheme::get_custom_header(); ?>
    <div class="">
        <a class="subtitulo" href="<?php echo $idioma.'/comercios/?catcomer='.$slug_catego?>"><em><?php echo esc_html($nombre_catego ); ?></em></a>        
        <div class="mt-3 pb-2 text-white text-center">            
            <span class="titlecomer"><?php echo esc_html($nombre); ?></span>
        </div>   
     </div>   
    
    
</div>
<div class="cabecera">
        <div class="megusta-section megusta-section2">
            <button class="megusta-button <?php echo $megusta_class; ?>" data-post-id="<?php echo $id; ?>"><i class="bi bi-heart-fill icon-footer"></i></button>
        </div>
    <?php 
    $vector_imagenes = [];
    $imagen_url_defecto="https://osuna.cbtpruebas.es/wp-content/uploads/2024/06/comercio-default-2.png";
    if ($imagen_url): 
        $vector_imagenes[] = $imagen_url;
    endif;
    if ($galeria):  
        foreach ($galeria as $imagen_id) {
            $imagen_url = wp_get_attachment_url($imagen_id);
            $vector_imagenes[] = $imagen_url;
        }
    endif;
    if (empty($vector_imagenes)){
        $vector_imagenes[] = $imagen_url_defecto;
    }
    if (!empty($vector_imagenes)){
        echo '<div id="galeriaCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
        <div class="carousel-inner">';
            foreach ($vector_imagenes as  $index => $imagen_url) {            
            echo '<div class="carousel-item ' .($index === 0 ? 'active' : '') .'">
                        <img src="' .esc_url($imagen_url) .'" class="d-block w-100" alt="Imagen de la galería">                           
                    </div>';
            }
            echo '  </div>                       
                </div>';
    }
    ?>
       
    <?php if(!empty($horario)){?>
        <div><b><?php echo __('Schedule', 'osunatheme');?>: </b> <?php echo esc_html($horario); ?></div>
    <?php }?>
    <div><b><?php echo __('Address', 'osunatheme');?>: </b><?php echo esc_html($direccion); ?></div>
    
    <div class="redes">
        <?php if(!empty($telefono)){?>
            <a href="tel:<?php echo esc_html($telefono); ?>" target="_blank"><i class="bi bi-telephone"></i></a>
        <?php }?>     
        <?php if(!empty($email)){?>
            <a href="mailto:<?php echo esc_html($email); ?>" target="_blank"><i class="bi bi-envelope"></i></a>
        <?php }?>    
        <?php if(!empty($web)){?>
            <a href="<?php echo esc_url($web); ?>" target="_blank"><i class="bi bi-globe"></i></a>
        <?php }?>    
        <?php if(!empty($facebook)){?>
            <a href="<?php echo esc_url($facebook); ?>" target="_blank"><i class="bi bi-facebook"></i></a>
        <?php }?>     
        <?php if(!empty($instagram)){?>
            <a href="<?php echo esc_url($instagram); ?>" target="_blank"><i class="bi bi-instagram"></i></a>
        <?php }?>     
    </div>   
    <h3 class="mt-3 pb-2"><?php echo __('Products', 'osunatheme');?></h3>  
    <div class="d-flex gap-2">        
       <?php Productos::get_productos_por_comercio($id); ?>
    </div>       
    <?php echo do_shortcode('[dynamic_map_comercios lat="' . $latitud . '" lng="' . $longitud . '" title="' . $nombre . '" logo_url="' . $logo_url . '"]'); ?>
</div>                         

        <?php
    endwhile;
else:
    echo '<p>' .
        __('No hay detalles del comercio disponibles.', 'osunatheme') .
        '</p>';
endif;

OsunaTheme::get_custom_footer(); 
get_footer(); ?>
