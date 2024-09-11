<?php
class Productos
{

    
    public function __construct()
    {
        add_action('wp_ajax_get_productos_cerca', [
            $this,
            'get_productos_cerca',
        ]);
        add_action('wp_ajax_nopriv_get_productos_cerca', [
            $this,
            'get_productos_cerca',
        ]);
       
    }

    public static function muestra_paginador($query)
    {
        $big = 999999999; // Un número grande para reemplazar
        $pagination_args = [
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?pagina=%#%',
            'current' => max(1, get_query_var('pagina')),
            'total' => $query->max_num_pages,
            'prev_text' => __('«'),
            'next_text' => __('»'),
        ];

        echo paginate_links($pagination_args);
    }

    public static function get_productos_recientes()
    {
        $pagina = get_query_var('pagina') ? get_query_var('pagina') : 1;

        // Consultar productos recientes
        $args = [
            'post_type' => 'producto',
            'posts_per_page' => 10,
            'paged' => $pagina,
            'orderby' => 'date',
            'order' => 'DESC',
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()):
            while ($query->have_posts()):

                $query->the_post();
                $id_prod = get_the_ID();
                $precio = get_post_meta($id_prod, 'precio', true);
                $descuento = get_post_meta($id_prod, 'descuento', true);
                $porc_descuento = '';
                if ($precio && $descuento && $precio > $descuento) {
                    $porc_descuento = round((($precio - $descuento) / $precio) * 100, 0);
                }

                $thumbnail_url = get_the_post_thumbnail_url($id_prod, 'medium');
                if (!$thumbnail_url) {
                    $thumbnail_url = 'https://via.placeholder.com/300x200'; // URL de la imagen placeholder
                }

                $titulo = get_the_title();
                $comercio_id = get_post_meta($id_prod, 'comercio', true);
                $comercio_titulo = $comercio_id ? get_the_title($comercio_id) : __('No shop', 'textdomain');
                ?>
        <div class="card product-card">
            <div class="position-relative">
                <img src="<?php echo esc_url($thumbnail_url); ?>" class="card-img-top" alt="<?php echo esc_attr($titulo); ?>">
                <?php if ($descuento): ?>
                    <span class="discount-badge"> -<?php echo esc_html($porc_descuento); ?>%</span>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?php echo esc_html($titulo); ?></h5>
                <p class="card-text"><em><?php echo esc_html($comercio_titulo); ?></em></p>
                <p class="text-end">                    
                    <?php if ($descuento): ?>
                        <span class="price-original"><?php echo esc_html($precio); ?>€</span>
                        <span class="price-discounted"><?php echo esc_html($descuento); ?>€</span>
                    <?php else: ?>
                        <span class="price-discounted"><?php echo esc_html($precio); ?>€</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <?php
            endwhile;

            // Mostrar paginación
            self::muestra_paginador($query);

            // Restablecer datos de publicación
            wp_reset_postdata();
        else:
            echo '<p>' . __('No products') . '</p>';
        endif;
    }

    public static function get_productos_por_comercio($id_comer)
    {
        
        $pagina = get_query_var('pagina') ? get_query_var('pagina') : 1;
        $args = [
            'post_type' => 'producto',
            'posts_per_page' => 10,
            'paged' => $pagina,
            'meta_key' => 'comercio',
            'meta_query' => [
                'key' => 'comercio',
                'value' => strval($id_comer),
                'compare' => 'LIKE',
            ],
        ];
        $query = new WP_Query($args);
        
        if ($query->have_posts()):
            while ($query->have_posts()):

                $query->the_post();
                $id_prod = get_the_ID();
                $precio = get_post_meta($id_prod, 'precio', true);
                $descuento = get_post_meta($id_prod, 'descuento', true);
                $porc_descuento = '';
                if ($precio && $descuento && $precio > $descuento) {
                    $porc_descuento = round((($precio - $descuento) / $precio) * 100, 0);
                }

                $thumbnail_url = get_the_post_thumbnail_url($id_prod, 'medium');
                if (!$thumbnail_url) {
                    $thumbnail_url = 'https://via.placeholder.com/300x200'; // URL de la imagen placeholder
                }

                $titulo = get_the_title();
                $comercio_id = get_post_meta($id_prod, 'comercio', true);
                $comercio_titulo = $comercio_id ? get_the_title($comercio_id) : __('No shop', 'textdomain');
                ?>
        <div class="card product-card">
            <div class="position-relative">
                <img src="<?php echo esc_url($thumbnail_url); ?>" class="card-img-top" alt="<?php echo esc_attr($titulo); ?>">
                <?php if ($descuento): ?>
                    <span class="discount-badge"> -<?php echo esc_html($porc_descuento); ?>%</span>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?php echo esc_html($titulo); ?></h5>
                <p class="card-text"><em><?php echo esc_html($comercio_titulo); ?></em></p>
                <p class="text-end">                    
                    <?php if ($descuento): ?>
                        <span class="price-original"><?php echo esc_html($precio); ?>€</span>
                        <span class="price-discounted"><?php echo esc_html($descuento); ?>€</span>
                    <?php else: ?>
                        <span class="price-discounted"><?php echo esc_html($precio); ?>€</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <?php
            endwhile;
            self::muestra_paginador($query);
            wp_reset_postdata();
        else:
            echo '<p>' . __('No products') . '</p>';
        endif;
    }

    public static function get_comercios_cerca_de_ti($lat, $lon, $radius)
    {        
        $comercios = new WP_Query([
            'post_type' => 'comercios',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => 'latitud',
                    'compare' => 'EXISTS',
                ],
                [
                    'key' => 'longitud',
                    'compare' => 'EXISTS',
                ],
            ],
        ]);
        $comercio_ids = [];
        if ($comercios->have_posts()) {
            while ($comercios->have_posts()) {
                $id_comer = get_the_ID();
                $comercios->the_post();
                $comercio_lat = (float) get_post_meta($id_comer,'latitud',true);
                $comercio_lon = (float) get_post_meta($id_comer,'longitud',true);
                $distancia_mts = round(self::haversine_great_circle_distance($lat,$lon,$comercio_lat,$comercio_lon), 0);
                if ($distancia_mts <= $radius) {
                    $comercio_ids[$id_comer] = $distancia_mts;
                }
            }
        }
        asort($comercio_ids);
        wp_reset_postdata();
        return $comercio_ids;
    }

    public static function haversine_great_circle_distance($latitudeFrom,$longitudeFrom,$latitudeTo,$longitudeTo,$earthRadius = 6371000) {
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle =
            2 *
            asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

    public static function get_productos_cerca()
    {
        $salida = '';
        $current_lat = $_POST['lat'];
        $current_lon = $_POST['lng'];
        $search_radius = get_option('osunatheme_maxmts');
        $comercio_ids = self::get_comercios_cerca_de_ti(
            $current_lat,
            $current_lon,
            $search_radius
        );

        if (count($comercio_ids) > 0) {
            $meta_query = ['relation' => 'OR'];

            foreach ($comercio_ids as $key => $value) {
                $comercio_id = $key;
                $meta_query[] = [
                    'key' => 'comercio',
                    'value' => strval($comercio_id),
                    'compare' => 'LIKE',
                ];
            }

            $args = [
                'post_type' => 'producto',
                'posts_per_page' => 10,
                'meta_key' => 'comercio',
                'meta_query' => $meta_query,
            ];

            $query = new WP_Query($args);
            
            if ($query->have_posts()):
                $salida .= '<div id="productosCarousel" class="carousel slide w-100" data-bs-ride="carousel">
                                <div class="carousel-inner">';
                $ij = 0;
                while ($query->have_posts()):
                    $query->the_post();
                    $id_prod = get_the_ID();

                    $comercio_lat = (float) get_post_meta(get_the_ID(),'latitud',true);
                    $comercio_lon = (float) get_post_meta(get_the_ID(),'longitud',true);

                    //echo "distancia:".$distancia_mts;
                    $precio = get_post_meta($id_prod, 'precio', true);
                    $descuento = get_post_meta($id_prod, 'descuento', true);
                    $porc_descuento = '';
                    if ($precio && $descuento && $precio > $descuento) {
                        $porc_descuento = round((($precio - $descuento) / $precio) * 100,0);
                    }

                    // Obtener la imagen destacada
                    $thumbnail_url = get_the_post_thumbnail_url(
                        get_the_ID(),
                        'medium'
                    );
                    if (!$thumbnail_url) {
                        $thumbnail_url = 'https://via.placeholder.com/300x200'; // URL de la imagen placeholder
                    }

                    $titulo = get_the_title();
                    $comercio_id = get_post_meta($id_prod, 'comercio', true);
                    
                    $comercio_titulo = $comercio_id
                        ? get_the_title($comercio_id)
                        : __('No shop', 'textdomain');

                    $distancia_mts = $comercio_ids[$comercio_id];

                    $salida .=
                        '<div class="carousel-item ' .($ij === 0 ? 'active' : '') .'">
                                    <div class="card product-card">
                                        <div class="position-relative">
                                            <img src="' .esc_url($thumbnail_url) .'" class="card-img-top" alt="' .esc_attr($titulo) .'">';
                    if ($descuento):
                        $salida .='<span class="discount-badge"> -' .esc_html($porc_descuento) .'%</span>';
                    endif;
                    $salida .='</div>
                                <div class="card-body text-dark">
                                            <h5 class="card-title">' .esc_html($titulo) .'</h5>
                                            <p class="card-text"><em>' .esc_html($comercio_titulo . ' - ' . $distancia_mts . ' m') .'</em></p>
                                            <p class="text-end">';
                    if ($descuento):
                        $salida .='<span class="price-original">' .esc_html($precio) .'€</span>
                                    <span class="price-discounted">' .esc_html($descuento) .'€</span>';
                    else:
                        $salida .='<span class="price-discounted">' .esc_html($precio) .'€</span>';
                    endif;

                    $salida .= '</p>
                                        </div>
                                    </div>
                                </div>';
                    $ij++;
                endwhile;
                $salida .= '      </div>
                                 <a class="carousel-control-prev" href="#productosCarousel" role="button" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                 </a>
                                 <a class="carousel-control-next" href="#productosCarousel" role="button" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                 </a>                       
                        </div>';
            else:
                $salida .= '<p>' . __('There are no products nearby') . '</p>';
            endif;
            wp_reset_postdata();
        } else {
            $salida .= '<p>' . __('There are no shops near you') . '</p>';
        }
        echo $salida;
        exit();
    }
}
