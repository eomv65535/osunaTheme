<?php
/**
 * Shortcodes Class
 *
 * @package OsunaTheme
 */

class Shortcodes {
    public function __construct() {
        add_shortcode('dynamic_map', [$this, 'dynamic_map_shortcode']);
        add_shortcode('dynamic_map_comercios', [$this, 'dynamic_map_comercios_shortcode']);
    }

    public function dynamic_map_shortcode($atts) {
        wp_enqueue_script('jquery');
        wp_enqueue_script('ajax-mapa', get_template_directory_uri() . '/js/mapa.js', array('jquery'), '2.0', true);
        $theme_options = [
            'latitude' => get_option('osunatheme_latitude', '0'),
            'longitude' => get_option('osunatheme_longitude', '0'),
            'google_maps_api_key' => get_option('osunatheme_google_maps_api_key', ''),
            'mimarcador' => get_option('osunatheme_icon_image'),
            'iconcasita' => get_option('osunatheme_icon_image_casa'),
        ];
        wp_localize_script('ajax-mapa', 'themeOptions', $theme_options);

        $locations = new WP_Query(array(
            'post_type' => 'comercios',
            'posts_per_page' => -1,
        ));

        $markers = array();
        if ($locations->have_posts()) {
            while ($locations->have_posts()) {
                $locations->the_post();
                
                $lat = get_field('latitud')? get_field('latitud'): "";
                $lng = get_field('longitud')? get_field('longitud'): "";
                $title = get_the_title();
                $loguito = get_field('logo')? get_field('logo'): "";                
                $logo_url = isset($loguito['url'])? $loguito['url']: "";
                $content = get_the_content();
                
                if (!empty($lat) && !empty($lng)) {
                    $markers[] = array(
                        'lat' => $lat,
                        'lng' => $lng,
                        'title' => $title,
                        'content' => "<p><img src='".$logo_url."'></p><h4>".$title."</h4>",
                    );
                }
            }
            wp_reset_postdata();
        }

        wp_localize_script('ajax-mapa', 'ajaxmapa', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('mi_nonce'),
            'markers' => $markers
        ));

        ob_start();
        ?>
        <div id="dynamic-map" style="width: 100%; height: 500px;"></div>
        <?php
        return ob_get_clean();
    }

    public function dynamic_map_comercios_shortcode($atts) {
        wp_enqueue_script('jquery');
        wp_enqueue_script('ajax-mapa', get_template_directory_uri() . '/js/mapa.js', array('jquery'), '2.0', true);
        $theme_options = [
            'latitude' => get_option('osunatheme_latitude', '0'),
            'longitude' => get_option('osunatheme_longitude', '0'),
            'google_maps_api_key' => get_option('osunatheme_google_maps_api_key', ''),
            'mimarcador' => get_option('osunatheme_icon_image'),
            'iconcasita' => get_option('osunatheme_icon_image_casa'),
        ];
        wp_localize_script('ajax-mapa', 'themeOptions', $theme_options);

        $locations = new WP_Query(array(
            'post_type' => 'comercios',
            'posts_per_page' => -1,
        ));

        $markers = array();
      
                
                $lat = $atts['lat'];
                $lng = $atts['lng'];
                $title = $atts['title'];                              
                $logo_url = $atts['logo_url'];
                $content = get_the_content();
                
                if (!empty($lat) && !empty($lng)) {
                    $markers[] = array(
                        'lat' => $lat,
                        'lng' => $lng,
                        'title' => $title,
                        'content' => "<p><img src='".$logo_url."'></p><h4>".$title."</h4>",
                    );
                }
            

        wp_localize_script('ajax-mapa', 'ajaxmapa', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('mi_nonce'),
            'markers' => $markers
        ));

        ob_start();
        ?>
        <div id="dynamic-map" style="width: 100%; height: 500px;"></div>
        <?php
        return ob_get_clean();
    }
}
?>
