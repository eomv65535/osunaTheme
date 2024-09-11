<?php 
class Campanas {
    public function __construct() {
        
    }
    // Método para obtener la campaña activa
    public static function confirma_campana_activa($id_campana) {
        $current_date = current_time('Ymd'); // Formato de fecha ACF

        $args = array(
            'p' => $id_campana,
            'post_type' => 'campana',            
            'post_status' => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'fecha_inicio',
                    'value' => $current_date,
                    'compare' => '<=',
                    'type' => 'DATE'
                ),
                array(
                    'key' => 'fecha_fin',
                    'value' => $current_date,
                    'compare' => '>=',
                    'type' => 'DATE'
                ),
                array(
                    'key' => 'estatus',
                    'value' => 'activo',
                    'compare' => '='
                )
            )
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {           
            wp_reset_postdata();
            return true;
        }

        return false;
    }

}
