<?php
/**
 * Custom Post Types class
 *
 * @package OsunaTheme
 */

class CPT {

    public function register_comercio_cpt() {
        $labels = [
            'name'               => _x('Comercios', 'post type general name', 'osunatheme'),
            'singular_name'      => _x('Comercio', 'post type singular name', 'osunatheme'),
            'menu_name'          => _x('Comercios', 'admin menu', 'osunatheme'),
            'name_admin_bar'     => _x('Comercio', 'add new on admin bar', 'osunatheme'),
            'add_new'            => _x('Agregar Nuevo', 'comercio', 'osunatheme'),
            'add_new_item'       => __('Agregar Nuevo Comercio', 'osunatheme'),
            'new_item'           => __('Nuevo Comercio', 'osunatheme'),
            'edit_item'          => __('Editar Comercio', 'osunatheme'),
            'view_item'          => __('Ver Comercio', 'osunatheme'),
            'all_items'          => __('Todos los Comercios', 'osunatheme'),
            'search_items'       => __('Buscar Comercios', 'osunatheme'),
            'parent_item_colon'  => __('Comercios Padre:', 'osunatheme'),
            'not_found'          => __('No se encontraron comercios.', 'osunatheme'),
            'not_found_in_trash' => __('No se encontraron comercios en la papelera.', 'osunatheme')
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => ['slug' => 'comercios'],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => ['title', 'editor', 'author', 'thumbnail'],
            'menu_icon'          => 'dashicons-store',
        ];

        register_post_type('comercios', $args);

        // Registrar la taxonomía de categorías para el CPT "Comercio"
        $this->register_comercio_taxonomy();
    }

    public function register_comercio_taxonomy() {
        $labels = [
            'name'              => _x('Categorías de Comercio', 'taxonomy general name', 'osunatheme'),
            'singular_name'     => _x('Categoría de Comercio', 'taxonomy singular name', 'osunatheme'),
            'search_items'      => __('Buscar Categorías', 'osunatheme'),
            'all_items'         => __('Todas las Categorías', 'osunatheme'),
            'parent_item'       => __('Categoría Padre', 'osunatheme'),
            'parent_item_colon' => __('Categoría Padre:', 'osunatheme'),
            'edit_item'         => __('Editar Categoría', 'osunatheme'),
            'update_item'       => __('Actualizar Categoría', 'osunatheme'),
            'add_new_item'      => __('Añadir Nueva Categoría', 'osunatheme'),
            'new_item_name'     => __('Nuevo Nombre de Categoría', 'osunatheme'),
            'menu_name'         => __('Categorías de Comercio', 'osunatheme')
        ];

        $args = [
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => ['slug' => 'categoria-comercio']            
        ];

        register_taxonomy('categoria_comercio', ['comercios'], $args);        
    }   

    public function register_productos_cpt() {
        $labels = [
            'name'               => _x('Productos', 'post type general name', 'osunatheme'),
            'singular_name'      => _x('Producto', 'post type singular name', 'osunatheme'),
            'menu_name'          => _x('Productos', 'admin menu', 'osunatheme'),
            'name_admin_bar'     => _x('Producto', 'add new on admin bar', 'osunatheme'),
            'add_new'            => _x('Añadir Nuevo', 'producto', 'osunatheme'),
            'add_new_item'       => __('Añadir Nuevo Producto', 'osunatheme'),
            'new_item'           => __('Nuevo Producto', 'osunatheme'),
            'edit_item'          => __('Editar Producto', 'osunatheme'),
            'view_item'          => __('Ver Producto', 'osunatheme'),
            'all_items'          => __('Todos los Productos', 'osunatheme'),
            'search_items'       => __('Buscar Productos', 'osunatheme'),
            'parent_item_colon'  => __('Producto Padre:', 'osunatheme'),
            'not_found'          => __('No se encontraron productos.', 'osunatheme'),
            'not_found_in_trash' => __('No se encontraron productos en la papelera.', 'osunatheme')
        ];
    
        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => ['slug' => 'producto'],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => ['title', 'editor', 'author', 'thumbnail'],
            'menu_icon'          => 'dashicons-products', // Cambia esto a un icono adecuado
        ];
    
        register_post_type('producto', $args);
    }

    public function register_participaciones_cpt() {
        $labels = [
            'name'               => _x('Participaciones', 'post type general name', 'osunatheme'),
            'singular_name'      => _x('Participaciones', 'post type singular name', 'osunatheme'),
            'menu_name'          => _x('Participaciones', 'admin menu', 'osunatheme'),
            'name_admin_bar'     => _x('Participación', 'add new on admin bar', 'osunatheme'),
            'add_new'            => _x('Añadir Nuevo', 'participacion', 'osunatheme'),
            'add_new_item'       => __('Añadir Nueva Participación', 'osunatheme'),
            'new_item'           => __('Nueva Participación', 'osunatheme'),
            'edit_item'          => __('Editar Participación', 'osunatheme'),
            'view_item'          => __('Ver Participación', 'osunatheme'),
            'all_items'          => __('Todos las Participaciones', 'osunatheme'),
            'search_items'       => __('Buscar Participaciones', 'osunatheme'),
            'parent_item_colon'  => __('Participación Padre:', 'osunatheme'),
            'not_found'          => __('No se encontraron participaciones.', 'osunatheme'),
            'not_found_in_trash' => __('No se encontraron participaciones en la papelera.', 'osunatheme')
        ];
    
        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => ['slug' => 'participacion'],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => ['title', 'editor', 'author', 'thumbnail'],
            'menu_icon'          => 'dashicons-tickets', // Cambia esto a un icono adecuado
        ];
    
        register_post_type('participacion', $args);
    }

    public function register_campanas_cpt() {
        $labels = [
            'name'               => _x('Campañas', 'post type general name', 'osunatheme'),
            'singular_name'      => _x('Campañas', 'post type singular name', 'osunatheme'),
            'menu_name'          => _x('Campañas', 'admin menu', 'osunatheme'),
            'name_admin_bar'     => _x('Campaña', 'add new on admin bar', 'osunatheme'),
            'add_new'            => _x('Añadir Nuevo', 'campana', 'osunatheme'),
            'add_new_item'       => __('Añadir Nueva Campaña', 'osunatheme'),
            'new_item'           => __('Nueva Campaña', 'osunatheme'),
            'edit_item'          => __('Editar Campaña', 'osunatheme'),
            'view_item'          => __('Ver Campañas', 'osunatheme'),
            'all_items'          => __('Todos las Campañas', 'osunatheme'),
            'search_items'       => __('Buscar Campañas', 'osunatheme'),
            'parent_item_colon'  => __('Campaña Padre:', 'osunatheme'),
            'not_found'          => __('No se encontraron campañas.', 'osunatheme'),
            'not_found_in_trash' => __('No se encontraron campañas en la papelera.', 'osunatheme')
        ];
    
        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => ['slug' => 'campana'],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => ['title', 'editor', 'author', 'thumbnail'],
            'menu_icon'          => 'dashicons-open-folder', // Cambia esto a un icono adecuado
        ];
    
        register_post_type('campana', $args);
    }

    public function register_sorteos_cpt() {
        $labels = [
            'name'               => _x('Sorteos', 'post type general name', 'osunatheme'),
            'singular_name'      => _x('Sorteos', 'post type singular name', 'osunatheme'),
            'menu_name'          => _x('Sorteos', 'admin menu', 'osunatheme'),
            'name_admin_bar'     => _x('Sorteo', 'add new on admin bar', 'osunatheme'),
            'add_new'            => _x('Añadir Nuevo', 'sorteo', 'osunatheme'),
            'add_new_item'       => __('Añadir Nueva Sorteo', 'osunatheme'),
            'new_item'           => __('Nuevo Sorteo', 'osunatheme'),
            'edit_item'          => __('Editar Sorteo', 'osunatheme'),
            'view_item'          => __('Ver Sorteos', 'osunatheme'),
            'all_items'          => __('Todos los Sorteos', 'osunatheme'),
            'search_items'       => __('Buscar Sorteos', 'osunatheme'),
            'parent_item_colon'  => __('Sorteo Padre:', 'osunatheme'),
            'not_found'          => __('No se encontraron sorteos.', 'osunatheme'),
            'not_found_in_trash' => __('No se encontraron sorteos en la papelera.', 'osunatheme')
        ];
    
        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => ['slug' => 'sorteo'],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => ['title', 'editor', 'author', 'thumbnail'],
            'menu_icon'          => 'dashicons-awards', // Cambia esto a un icono adecuado
        ];
    
        register_post_type('sorteo', $args);
    }
        
    
}
