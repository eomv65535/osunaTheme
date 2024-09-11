<?php
class Comercios
{
    public int $maxmts;

    public function __construct()
    {
        // Manejar AJAX para devolver coordenadas
        add_action('wp_ajax_nopriv_coordenadas_comercio', [
            $this,
            'coordenadas_comercio',
        ]);
        add_action('wp_ajax_coordenadas_comercio', [
            $this,
            'coordenadas_comercio',
        ]);

        add_action('pre_get_posts', [
            $this,
            'osunatheme_adjust_comercios_query',
        ]);

        add_action('wp_ajax_handle_megusta', [$this, 'handle_megusta']);
        add_action('wp_ajax_nopriv_handle_megusta', [$this, 'handle_megusta']);
        $this->maxmts = get_option('osunatheme_maxmts');
    }

    public function calcular_distancia($lat1, $lon1, $lat2, $lon2)
    {
        $radio_tierra = 6371000; // Radio de la Tierra en metros

        // Convertir las latitudes y longitudes de grados a radianes
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // Diferencias de las coordenadas
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        // Fórmula de Haversine
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Distancia en metros
        $distancia = $radio_tierra * $c;

        return $distancia;
    }

    public function coordenadas_comercio()
    {
        $lat = isset($_POST['lat']) ? floatval($_POST['lat']) : 0;
        $lng = isset($_POST['lng']) ? floatval($_POST['lng']) : 0;
        $locations = new WP_Query([
            'post_type' => 'comercios',
            'posts_per_page' => -1,
        ]);
        $distancia = 0;
        $comercios = [];
        if ($locations->have_posts()) {
            while ($locations->have_posts()) {
                $locations->the_post();
                $latComercio = get_field('latitud');
                $lngComercio = get_field('longitud');
                $imagen = get_field('imagen');
                $title = get_the_title();
                $content = get_the_content();

                if (!empty($latComercio) && !empty($lngComercio)) {
                    $distancia = $this->calcular_distancia(
                        $lat,
                        $lng,
                        $latComercio,
                        $lngComercio
                    );
                    if ($distancia < 250) {
                        $comercios[] = [
                            'title' => $title,
                            'content' => $content,
                            'imagen' => $imagen,
                            'distancia' => round($distancia, 0),
                        ];
                    }
                }
            }
        }

        if (!empty($comercios)) {
            $response = [
                'success' => true,
                'message' => $comercios,
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Error',
            ];
        }

        wp_send_json_success($response);
    }

    function handle_megusta()
    {
        if (isset($_POST['post_id']) && is_user_logged_in()) {
            $post_id = $_POST['post_id'];
            $user_id = get_current_user_id();

            $favoritos = get_user_meta($user_id, 'favoritos', true);
            $favoritos = $favoritos ? $favoritos : [];

            if (!in_array($post_id, $favoritos)) {
                $favoritos[] = $post_id;
                update_user_meta($user_id, 'favoritos', $favoritos);
            } else {
                $key = array_search($post_id, $favoritos);
                unset($favoritos[$key]);
                update_user_meta($user_id, 'favoritos', $favoritos);
            }

            wp_send_json_success();
        }
        wp_send_json_error();
    }

    public static function get_catego_comercios($viene_s)
    {
        $idioma = ICL_LANGUAGE_CODE == 'es' ? '' : ICL_LANGUAGE_CODE . '/';
        $anade = !empty($viene_s) ? '&s=' . $viene_s : '';
        $args = [
            'taxonomy' => 'categoria_comercio',
        ];

        $terms = get_terms($args, ['hide_empty' => false]);

        $tope = 3;
        if (count($terms) < $tope) {
            $tope = count($terms);
        }
        echo '<div class="categorias-visibles caja-categoria" id="cat_arriba">';
        for ($i = 0; $i < $tope; $i++) {
            $term = $terms[$i];
            $icon_url = get_field('icon', $term);
            echo '<a href="/' .$idioma .'comercios/?catcomer=' .$term->slug .$anade .'" class="col btn-azulmenos cajabtn">                        
                        <img src="' .esc_url($icon_url['url']) .'" alt="' .$term->name .'" width="40">
                        <p>' .$term->name .'</p>                        
                    </a>';
        }
        echo '</div>';
        if (count($terms) > 3) {
            $ini = 3;
            $fin = count($terms);
            echo '<div class="categorias-ocultas caja-categoria" id="cat_oculta">';
            for ($i = $ini; $i < $fin; $i++) {
                $term = $terms[$i];
                $icon_url = get_field('icon', $term);
                echo '<a href="/' .$idioma .'comercios/?catcomer=' .$term->slug .$anade .'" class="col btn-azulmenos cajabtn">                        
                        <img src="' .esc_url($icon_url['url']) .'" alt="' .$term->name .'" width="40">
                        <p>' .$term->name .'</p>                        
                    </a>';
            }
            echo '</div>';
        }
    }

    
    public static function validar_un_comercio($id_comercio) {
        $args = array(
            'post_type' => 'comercios',
            'p' => $id_comercio,
            'post_status' => 'publish',
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            $query->the_post();
            $comercio = new stdClass();
            $comercio->ID = get_the_ID();  
            //print_r($comercio, true);         
            wp_reset_postdata();
            return $comercio;
        }

        return false;
    }

    public static function get_comercios()
    {
        global $wp_query;
        $comercios = $wp_query->posts;
        $total = count($comercios);
        $imagen_url_defecto =
            'https://osuna.cbtpruebas.es/wp-content/uploads/2024/06/comercio-default-2.png';
        echo '<div class="lista-comercios caja-categoria">';
        for ($i = 0; $i < $total; $i++) {

            $comercio = $comercios[$i];

            $imagencomercio = get_the_post_thumbnail_url($comercio->ID, 'full');
            if (empty($imagencomercio)) {
                $imagencomercio = $imagen_url_defecto;
            }
            $enlace = get_permalink($comercio->ID);
            $user_id = get_current_user_id();

            $favoritos_usuario = get_user_meta($user_id, 'favoritos', true);

            $megusta_class = $favoritos_usuario ? (in_array($comercio->ID, $favoritos_usuario) ? 'megusta' : '') : '';
            ?>
                 <div class="col cajabtncomer cajabtn-mitad">
                    <div class="megusta-section">
                        <button class="megusta-button <?php echo $megusta_class; ?>" data-post-id="<?php echo $comercio->ID; ?>"><i class="bi bi-heart-fill icon-footer"></i></button>
                    </div>
                    <a href="<?php echo $enlace; ?>">
                        <img src="<?php echo esc_url($imagencomercio); ?>" width="100%">
                            <p><?php echo strtoupper($comercio->post_title); ?></p>    
                    </a>                            
                </div>
            <?php
        }
        echo '</div>';
    }

    public static function get_comercios_favoritos($user_id)
    {
        $favoritos_usuario = get_user_meta($user_id, 'favoritos', true);

        if (!empty($favoritos_usuario) && is_array($favoritos_usuario)) {
            $busqueda="";
            if (isset($_GET['busq']) && !empty($_GET['busq'])) {
                $busqueda=sanitize_text_field($_GET['busq']);
            }
            $args = [
                'post_type' => 'comercios',
                'post__in' => $favoritos_usuario,
                'posts_per_page' => -1, 
                's' => $busqueda
            ];

            $query = new WP_Query($args);

            if ($query->have_posts()) {
                $imagen_url_defecto =
                    'https://osuna.cbtpruebas.es/wp-content/uploads/2024/06/comercio-default-2.png';

                echo '<div class="lista-comercios caja-categoria">';

                while ($query->have_posts()) {

                    $query->the_post();
                    $comercio_id = get_the_ID();
                    $imagencomercio = get_the_post_thumbnail_url($comercio_id,'full');

                    if (empty($imagencomercio)) {
                        $imagencomercio = $imagen_url_defecto;
                    }

                    $enlace = get_permalink($comercio_id);
                    $megusta_class = 'megusta';

                    // Todos los listados son favoritos
                    ?>
                <div class="col cajabtncomer cajabtn-mitad">
                    <div class="megusta-section">
                        <button class="megusta-button2 <?php echo $megusta_class; ?>" data-post-id="<?php echo $comercio_id; ?>"><i class="bi bi-heart-fill icon-footer"></i></button>
                    </div>
                    <a href="<?php echo $enlace; ?>">
                        <img src="<?php echo esc_url($imagencomercio); ?>" width="100%">
                        <p><?php echo strtoupper(get_the_title()); ?></p>
                    </a>
                </div>
                <?php
                }

                echo '</div>';

                // Restaurar la consulta global de post de WP
                wp_reset_postdata();
            } else {
                echo '<p>No tienes comercios favoritos.</p>';
            }
        } else {
            echo '<p>No tienes comercios favoritos.</p>';
        }
    }

    function osunatheme_adjust_comercios_query($query)
    {
        if (
            !is_admin() &&
            $query->is_main_query() &&
            is_post_type_archive('comercios')
        ) {
            if (isset($_GET['catcomer']) && !empty($_GET['catcomer'])) {
                $query->set('tax_query', [
                    [
                        'taxonomy' => 'categoria_comercio',
                        'field' => 'slug',
                        'terms' => sanitize_text_field($_GET['catcomer']),
                    ],
                ]);
            }
            if (isset($_GET['s']) && !empty($_GET['s'])) {
                $query->set('s', sanitize_text_field($_GET['s']));
            }
        }
    }
}
