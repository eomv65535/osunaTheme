<?php get_header(); ?>
<div class="bg-azulito border-inferiores cabecera">
	<?php OsunaTheme::get_custom_header(); ?>
    <p class="subtitulo"><?php echo __('Shops', 'osunatheme');?></p>
    <?php if(isset($_GET['s']) && !empty($_GET['s'])){?>
        <p class="badge text-dark rounded-4 comersele"><?php echo __('Search', 'osunatheme');?>:<em>&nbsp;"<?php echo $_GET['s'];?>"</em> <button type="button" class="btn-close btn-close" onclick="borrar_busqueda('s')"></button></p>
    <?php }?>
    
    <?php 
    $muestra_cats="";
    $valor_s=(isset($_GET['s']) && !empty($_GET['s']))? $_GET['s'] :"";
    if(isset($_GET['catcomer']) && !empty($_GET['catcomer'])){
        $muestra_cats="d-none";
        $nombre_catego ="";
        $slug = $_GET['catcomer'];
        $term = get_term_by('slug', $slug, 'categoria_comercio');
        if ($term && !is_wp_error($term))
            $nombre_catego = $term->name;    
        ?>
        <p class="badge text-dark rounded-4 comersele"><?php echo __('Category', 'osunatheme');?>:<em>&nbsp;"<?php echo $nombre_catego;?>"</em> <button type="button" class="btn-close btn-close" onclick="borrar_busqueda('catcomer')"></button></p>
    <?php }?>
    
    <h3 class="mt-3 pb-2 text-white titulohead"><?php echo __('Near you', 'osunatheme');?></h3>
    <form id="forma_busq_comer" method="get" action="<?php echo esc_url(home_url('/comercios')); ?>">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
            </div>            
            <input type="text" class="form-control" id="s" name="s" value="<?php echo $valor_s;?>" placeholder="<?php echo __('Search', 'osunatheme');?>..." aria-label="Search" aria-describedby="basic-addon1">            
            <?php if (isset($_GET['catcomer']) && !empty($_GET['catcomer'])): ?>
                <input type="hidden" id="catcomer" name="catcomer" value="<?php echo esc_attr($_GET['catcomer']); ?>">
            <?php endif; ?>
        </div>
    </form>
    <div class="<?php echo $muestra_cats;?>" id="contenedor-categorias">
        <?php Comercios::get_catego_comercios($valor_s); ?>        
    </div>
    <div class="flechita <?php echo $muestra_cats;?>">
            <img src="https://osuna.cbtpruebas.es/wp-content/uploads/2024/06/flecha-hacia-abajo3.png">
    </div>      
</div>
<div class="cabecera">
      <?php if (have_posts()) {
          Comercios::get_comercios();
      } else {
          echo '<br><h4 class="text-center">' .
              __('There are no shops available', 'osunatheme') .
              '</h4>';
      } ?>    
      <div class="pagination-container">
              <?php the_posts_pagination([
                  'mid_size' => 2,
                  'prev_text' => __('«', 'osunatheme'),
                  'next_text' => __('»', 'osunatheme'),
                  'screen_reader_text' => __('Paginación', 'osunatheme'),
                  'before_page_number' =>
                      '<span class="screen-reader-text">' .
                      __('Página', 'osunatheme') .
                      ' </span>',
              ]); ?>
      </div>
</div> 
<?php OsunaTheme::get_custom_footer(); 
get_footer(); ?>