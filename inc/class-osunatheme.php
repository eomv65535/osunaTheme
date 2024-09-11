<?php
/**
 * Main theme class
 *
 * @package OsunaTheme
 */

class OsunaTheme
{
    const VERSION = '1.7.0';
    
    

    public function __construct()
    {
        $this->crea_acciones();
        $this->crea_filtros();        
        $this->new_roles();
        
        $this->includes();

        // Inicializar Shortcodes
        $this->initialize_shortcodes();
        $this->initialize_dashboard();
        $this->initialize_productos();
        $this->initialize_back_comer();
        $this->initialize_back_campanas();
        $this->initialize_back_productos();
        $this->initialize_usuarios();
        $this->initialize_comercios();
        $this->initialize_campanas();
        $this->initialize_participaciones();        
        $this->crea_filtros();        
        
    }
    public function crea_acciones()
    {
        add_action('after_setup_theme', [$this, 'setup']);
        add_action('wp_enqueue_scripts', [$this, 'imports_cabecera']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('init', [$this, 'register_custom_post_types']);

        // Registrar opciones del tema
        add_action('admin_menu', [$this, 'add_theme_options_page']);
        add_action('admin_init', [$this, 'register_theme_settings']);        
        
        add_action('um_after_account_general', [$this,'showExtraFields'], 100);

        add_action('wp_ajax_mictaajax', [$this, 'mictaajax']);
        add_action('wp_ajax_nopriv_mictaajax', [$this, 'mictaajax']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_odometer_assets']);
    }

    public function crea_filtros(){
        //add_filter('um_account_tab_general_fields', [$this,'addCustomFieldsToAccountTab'], 10, 2 );
    }

    public function setup()
    {
        load_theme_textdomain(
            'osunatheme',
            get_template_directory() . '/languages'
        );
        add_theme_support('post-thumbnails');
        register_nav_menus([
            'primary' => __('Primary Menu', 'osunatheme'),
        ]);
    }

    public function enqueue_scripts()
    {
        wp_enqueue_style(
            'font-awesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
            [],
            '5.15.4'
        );
        wp_enqueue_style(
            'osunatheme-style',
            get_stylesheet_uri(),
            ['um_styles'],
            '1.9.2',
            'all'
        );
        wp_enqueue_script('jquery');
        wp_enqueue_script(
            'varios',
            get_template_directory_uri() . '/js/varios.js',
            ['jquery'],
            '2.1.3',
            true
        );
        wp_enqueue_script(
            'ajax',
            get_template_directory_uri() . '/js/ajax.js',
            ['jquery'],
            '1.2.2',
            true
        );
       
        wp_localize_script('ajax', 'mi_ajax', [
            'ajaxurl' => admin_url('admin-ajax.php')
        ]);

        wp_enqueue_script('moment', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js', array(), '2.29.1', true);

        // Luego, carga Chart.js y su adaptador
        wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', array('moment'), '2.9.3', true);
        wp_enqueue_script('chartjs-adapter-moment', 'https://cdn.jsdelivr.net/npm/chartjs-adapter-moment', array('chartjs'), '2.9.3', true);
    }

    function imports_cabecera()
    {
        ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Nunito:wght@300;400;500;700;800&display=swap" rel="stylesheet">
        <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
        <?php
    }

    public function includes()
    {
        require get_template_directory() . '/inc/class-cpt.php';
        require get_template_directory() . '/inc/class-shortcodes.php';
        require get_template_directory() . '/inc/class-comercios.php';
        require get_template_directory() . '/inc/class-back-comercios.php';
        require get_template_directory() . '/inc/class-back-campanas.php';
        require get_template_directory() . '/inc/class-back-productos.php';
        require get_template_directory() . '/inc/class-productos.php';
        require get_template_directory() . '/inc/class-correo.php';
        require get_template_directory() . '/inc/class-usuarios.php';
        require get_template_directory() . '/inc/class-campanas.php';
        require get_template_directory() . '/inc/class-participaciones.php';
        require get_template_directory() . '/inc/class-dashboard.php';
     
    }

    public function new_roles()
    {
        add_role(
            'ayuntamiento',
            'Ayuntamiento',
            get_role('editor')->capabilities
        );
        add_role('comercio', 'Comercio', get_role('editor')->capabilities);
    }

    public function register_custom_post_types()
    {
        $cpt = new CPT();
        $cpt->register_comercio_cpt();        
        $cpt->register_productos_cpt();
        $cpt->register_participaciones_cpt();
        $cpt->register_campanas_cpt();
        $cpt->register_sorteos_cpt();
    }

    public function initialize_shortcodes()
    {
        new Shortcodes();
    }
    public function initialize_dashboard()
    {
        new Dashboard();
    }

    public function initialize_productos()
    {
        new Productos();
    }
    public function initialize_comercios()
    {
        new Comercios();
    }
    public function initialize_back_comer()
    {
        new BackComercios();
    }

    public function initialize_back_campanas()
    {
        new BackCampanas();
    }

    public function initialize_back_productos()
    {
        new BackProductos();
    }

    public function initialize_usuarios()
    {
        new Usuarios();
    }

    public function initialize_campanas()
    {
        new Campanas();
    }

    public function initialize_participaciones()
    {
        new Participaciones();
    }

    public function initialize_correo()
    {
        new Correo();
    }
    

    public function add_theme_options_page()
    {
        add_menu_page(
            __('Configuración Osuna theme', 'osunatheme'),
            __('Configuración Osuna theme', 'osunatheme'),
            'manage_options',
            'osunatheme-options',
            [$this, 'render_theme_options_page'],
            'dashicons-admin-generic'
        );
    }

    public function render_theme_options_page()
    {
        ?>
        <div class="wrap">
            <h1><?php _e('Configuración Osuna theme', 'osunatheme'); ?></h1>
            <form method="post" action="options.php" enctype="multipart/form-data">
                <?php
                settings_fields('osunatheme_options_group');
                do_settings_sections('osunatheme-options');
                submit_button();?>
            </form>
        </div>
        <?php
    }

    public function register_theme_settings()
    {
        register_setting('osunatheme_options_group', 'osunatheme_latitude');
        register_setting('osunatheme_options_group', 'osunatheme_longitude');
        register_setting('osunatheme_options_group','osunatheme_google_maps_api_key');
        register_setting('osunatheme_options_group','osunatheme_looker_ayuntamiento');
        register_setting('osunatheme_options_group','osunatheme_looker_comercios');
        register_setting('osunatheme_options_group', 'osunatheme_icon_image');
        register_setting('osunatheme_options_group', 'osunatheme_maxmts');
        register_setting(
            'osunatheme_options_group',
            'osunatheme_icon_image_casa'
        );

        add_settings_section(
            'osunatheme_general_section',
            __('Configuraciones Generales', 'osunatheme'),
            null,
            'osunatheme-options'
        );

        add_settings_field(
            'osunatheme_latitude',
            __('Latitud Inicial', 'osunatheme'),
            [$this, 'render_latitude_field'],
            'osunatheme-options',
            'osunatheme_general_section'
        );

        add_settings_field(
            'osunatheme_longitude',
            __('Longitud Inicial', 'osunatheme'),
            [$this, 'render_longitude_field'],
            'osunatheme-options',
            'osunatheme_general_section'
        );

        add_settings_field(
            'osunatheme_google_maps_api_key',
            __('Clave API de Google Maps', 'osunatheme'),
            [$this, 'render_google_maps_api_key_field'],
            'osunatheme-options',
            'osunatheme_general_section'
        );

        add_settings_field(
            'osunatheme_looker_ayuntamiento',
            __('Enlace del Iframe dashboard looker ayuntamiento', 'osunatheme'),
            [$this, 'render_looker_ayuntamiento_field'],
            'osunatheme-options',
            'osunatheme_general_section'
        );

        add_settings_field(
            'osunatheme_looker_comercios',
            __('Enlace del Iframe dashboard looker comercios', 'osunatheme'),
            [$this, 'render_looker_comercios_field'],
            'osunatheme-options',
            'osunatheme_general_section'
        );

        add_settings_field(
            'osunatheme_icon_image',
            __('Imagen Marcador mi posición', 'osunatheme'),
            [$this, 'render_icon_image_field'],
            'osunatheme-options',
            'osunatheme_general_section'
        );

        add_settings_field(
            'osunatheme_icon_casa',
            __('Imagen Marcador por comercio', 'osunatheme'),
            [$this, 'render_icon_casa_field'],
            'osunatheme-options',
            'osunatheme_general_section'
        );

        add_settings_field(
            'osunatheme_maxmts',
            __('Distancia máxima a comercios cerca (mts)', 'osunatheme'),
            [$this, 'render_maxmts_field'],
            'osunatheme-options',
            'osunatheme_general_section'
        );

    }

    public function render_latitude_field()
    {
        $latitude = get_option('osunatheme_latitude', '');
        echo '<input type="text" name="osunatheme_latitude" value="' .
            esc_attr($latitude) .
            '" />';
    }

    public function render_longitude_field()
    {
        $longitude = get_option('osunatheme_longitude', '');
        echo '<input type="text" name="osunatheme_longitude" value="' .
            esc_attr($longitude) .
            '" />';
    }

    public function render_google_maps_api_key_field()
    {
        $api_key = get_option('osunatheme_google_maps_api_key', '');
        echo '<input type="text" name="osunatheme_google_maps_api_key" value="' .
            esc_attr($api_key) .
            '" />';
    }
    public function render_looker_ayuntamiento_field()
    {
        $looker_ayuntamiento = get_option('osunatheme_looker_ayuntamiento', '');
        echo '<input type="text" name="osunatheme_looker_ayuntamiento" value="' .
            esc_attr($looker_ayuntamiento) .
            '" />';
    }
    public function render_looker_comercios_field()
    {
        $looker_comercios = get_option('osunatheme_looker_comercios', '');
        echo '<input type="text" name="osunatheme_looker_comercios" value="' .
            esc_attr($looker_comercios) .
            '" />';
    }

    public function render_icon_image_field()
    {
        $icon_image = get_option('osunatheme_icon_image', '');
        echo '<input type="text" name="osunatheme_icon_image" value="' .
            esc_attr($icon_image) .
            '" />';
    }

    public function render_icon_casa_field()
    {
        $icon_image_casa = get_option('osunatheme_icon_image_casa', '');
        echo '<input type="text" name="osunatheme_icon_image_casa" value="' .
            esc_attr($icon_image_casa) .
            '" />';
    }

    public function render_maxmts_field()
    {
        $maxmts = get_option('osunatheme_maxmts', '');
        echo '<input type="text" name="osunatheme_maxmts" value="' .
            esc_attr($maxmts) .
            '" />';
    }

    
    public static function get_custom_header()
    {
        $mimenu =
            '<a class="dropdown-item" href="' .
            esc_url(um_get_core_page('login')) .
            '">' .
            __('Login', 'osunatheme') .
            '</a>';
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $profile_url = site_url('/account');
            $logout_url = um_get_core_page('logout');

            $mimenu =
                '  <a class="dropdown-item" href="' .
                site_url('/misparticipaciones') .
                '">' .
                __('My participations', 'osunatheme') .
                '</a>
                         <a class="dropdown-item" href="' .
                esc_url($profile_url) .
                '">' .
                __('Profile', 'osunatheme') .
                '</a>
                        <a class="dropdown-item" href="' .
                site_url('/logout') .
                '">' .
                __('Logout', 'osunatheme') .
                '</a>';
        }
        echo '<div class="d-flex justify-content-between align-items-center">
                <div class="navbar navbar-dark">
                        <button class="btn btntopcito" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                </div>		            			
                <div class="dropdown">
                    <button class="btn txt_white dropdown-toggle btntopcito" type="button" id="mimenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="mimenu">' .
            $mimenu .
            '
                    </ul>
                </div>
            </div> 
            <div class="bg-azulito txt_white offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasMenuLabel">' .
            __('Menu', 'osunatheme') .
            '</h5>
                    <button type="button" class="btn-close btn-close-white text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body" id="">';
        wp_nav_menu(['theme_location' => 'primary']);
        echo '</div>';
        echo self::get_logos_footer();
         echo '</div>';
    }

    public static function get_logos_footer(){
        return '
        <div class="logos-abajo">
            <div class="logos-footer">
                <img src="https://osuna.cbtpruebas.es/wp-content/uploads/2024/07/ue.jpg" alt="Logo 1" class="logo">                
                <img src="https://osuna.cbtpruebas.es/wp-content/uploads/2024/07/ptr2.jpg" alt="Logo 3" class="logo">
                <img src="https://osuna.cbtpruebas.es/wp-content/uploads/2024/07/gob-1.jpg" alt="Logo 2" class="logo">
            </div>
        </div>';
    }

    public static function get_custom_footer()
    {
        $idioma = ICL_LANGUAGE_CODE == 'es' ? '' : ICL_LANGUAGE_CODE . '/';
        $active_favoritos = $active_ofertas = $active_buscar ="";
        switch (ICL_LANGUAGE_CODE){
            case 'es':
                $active_favoritos = is_page('misfavoritos') ? 'active' : '';
                $active_ofertas = is_page('ofertas') ? 'active' : '';
                $active_buscar =(is_page('buscar') || is_singular('comercios') || is_post_type_archive('comercios')? 'active': '');
                break;           
            case 'en':
                $active_favoritos = is_page('my-favourites') ? 'active' : '';
                $active_ofertas = is_page('ofertas') ? 'active' : '';
                $active_buscar =(is_page('search') || is_singular('comercios') || is_post_type_archive('comercios')? 'active': '');
                break;           
            case 'fr':
                $active_favoritos = is_page('misfavoritos') ? 'active' : '';
                $active_ofertas = is_page('ofertas') ? 'active' : '';
                $active_buscar =(is_page('chercher') || is_singular('comercios') || is_post_type_archive('comercios')? 'active': '');
                break;           
            case 'de':
                $active_favoritos = is_page('misfavoritos') ? 'active' : '';
                $active_ofertas = is_page('ofertas') ? 'active' : '';
                $active_buscar =(is_page('suche') || is_singular('comercios') || is_post_type_archive('comercios')? 'active': '');
                break;           
        }
        echo '<div class="clear"></div><footer class="bg-white shadow-lg fixed-bottom p-2">
                <div class="container d-flex justify-content-around py-2">
                    <a class="d-flex align-items-center justify-content-center btnfoot" href="' .site_url('/' .$idioma .'misfavoritos') .'"><div class="' .$active_favoritos.'"><i class="bi bi-heart icon-footer"></i></div></a>
                    <a class="d-flex align-items-center justify-content-center btnfoot" href="' .site_url('/' .$idioma .'ofertas') .'"><div class="' .$active_ofertas .'"><i class="bi bi-tag icon-footer"></i></div></a>
                    <a class="d-flex align-items-center justify-content-center btnfoot" href="' .site_url('/' .$idioma .'buscar') .'"><div class="' .$active_buscar .'"><i class="bi bi-search icon-footer"></i></div></a>
            </div>
        </footer>';
    }        

    public static function inicia_datatables()
    {
        wp_enqueue_style(
            'dataTables',
            '//cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css',
            []
        );
        wp_enqueue_style(
            'dataTablesresponsive',
            'https://cdn.datatables.net/responsive/3.0.2/css/responsive.dataTables.css',
            []
        );
        wp_enqueue_style(
            'dataTablesrowReorder',
            'https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.dataTables.css',
            []
        );
        wp_enqueue_script(
            'dataTables',
            '//cdn.datatables.net/2.0.8/js/dataTables.min.js',
            ['jquery']
        );
        wp_enqueue_script(
            'dataTables',
            'https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js',
            ['jquery']
        );
        wp_enqueue_script(
            'dataTables',
            'https://cdn.datatables.net/responsive/3.0.2/js/responsive.dataTables.js',
            ['jquery']
        );
       
    }

    
    
    public static function menu_ayuntamiento()
    {
        self::inicia_datatables();

        $a_dashb =$a_dashb2 = $a_comer = $a_campa = $a_categ = '';
        if (is_page('ayuntamiento')) {
            $a_dashb = 'active';
        } elseif (is_page('ayuntamiento/analytics')) {
            $a_dashb2 = 'active';
        } elseif (is_page('ayuntamiento/stores') || is_page('ayuntamiento/stores/usuarios')) {
            $a_comer = 'active';
        } elseif (is_page('ayuntamiento/campanas')) {
            $a_campa = 'active';
        } elseif (is_page('ayuntamiento/store_category')) {
            $a_categ = 'active';
        }
        echo '<div class="text-center py-4">
                <img src="https://osuna.cbtpruebas.es/wp-content/uploads/2024/06/logo.png" class="img-fluid" width="120">
                <h6>Ayuntamiento de Osuna</h6>
            </div>
            <div class="list-group rounded-0">
                <a href="/ayuntamiento" class="list-group-item list-group-item-action ' .
            $a_dashb .
            ' border-0 d-flex align-items-center">
                    <span class="bi bi-speedometer"></span>
                    <span class="ms-2">Dashboard</span>
                </a>
                <a href="/ayuntamiento/analytics" class="list-group-item list-group-item-action ' .
            $a_dashb2 .
            ' border-0 d-flex align-items-center">
                    <span class="bi bi-graph-down"></span>
                    <span class="ms-2">Estadísticas Usuarios</span>
                </a>
                <a href="/ayuntamiento/store_category" class="list-group-item list-group-item-action ' .
            $a_categ .
            ' border-0 align-items-center">
                    <span class="bi bi-list-ul"></span>
                    <span class="ms-2">Categorías</span>
                </a>
                <a href="/ayuntamiento/stores" class="list-group-item list-group-item-action ' .
            $a_comer .
            ' border-0 align-items-center">
                    <span class="bi bi-shop"></span>
                    <span class="ms-2">Comercios</span>
                </a>
                <a href="/ayuntamiento/campanas" class="list-group-item list-group-item-action ' .
            $a_campa .
            ' border-0 align-items-center">
                    <span class="bi bi-ticket"></span>
                    <span class="ms-2">Campañas</span>
                </a>              
            </div>';
    }

    public static function menu_comercios()
    {
        self::inicia_datatables();
        $id_usuario = get_current_user_id();
        $id_comercio = strval(get_user_meta($id_usuario,'usuario_comercio', true)[0]);
        $logo_id= get_post_meta($id_comercio, 'logo', true);
        
        $nombre = get_the_title($id_comercio);
        $cdashb = $cprod = $cparti = $cqr =$cdatos ='';
        if (is_page('store')) {
            $cdashb = 'active';
        } elseif (is_page('store/productos')) {
            $cprod = 'active';
        } elseif (is_page('store/campanas')) {
            $cparti = 'active';        
        } elseif (is_page('store/datos')) {
            $cdatos = 'active';
        }
        echo '<div class="text-center py-4">';
        if($logo_id){
                $logo_url = wp_get_attachment_url($logo_id);
                echo '<img src="'.$logo_url.'" class="img-fluid" width="120">';                
            }

            echo '<h6>'.$nombre.'</h6>
            </div>
            <div class="list-group rounded-0">
                <a href="/store" class="list-group-item list-group-item-action ' .
            $cdashb .
            ' border-0 d-flex align-items-center">
                    <span class="bi bi-speedometer"></span>
                    <span class="ms-2">Dashboard</span>
                </a>
                <a href="/store/productos" class="list-group-item list-group-item-action ' .
            $cprod .
            ' border-0 align-items-center">
                    <span class="bi bi-basket"></span>
                    <span class="ms-2">Productos</span>
                </a>
                <a href="/store/campanas" class="list-group-item list-group-item-action ' .
            $cparti .
            ' border-0 align-items-center">
                    <span class="bi bi-shop"></span>
                    <span class="ms-2">Campañas</span>
                </a> <a href="/store/datos" class="list-group-item list-group-item-action ' .
            $cdatos .
            ' border-0 align-items-center">
                    <span class="bi bi-pencil-square"></span>
                    <span class="ms-2">Mis datos</span>                   
                </a>              
                          
            </div>';
    }

    public static function redirect_ayuntamiento()
    {
       
        if (is_page('ayuntamiento')) {
            get_template_part('ayuntamiento', 'dashboard');
        } elseif (is_page('ayuntamiento/analytics')) {
            get_template_part('ayuntamiento', 'analytics');
        } elseif (is_page('ayuntamiento/stores')) {
            get_template_part('ayuntamiento', 'comercios');
        } elseif (is_page('ayuntamiento/stores/usuarios')) {
            get_template_part('ayuntamiento', 'comercios-usuarios');
        } elseif (is_page('ayuntamiento/campanas')) {
            get_template_part('ayuntamiento', 'campanas');        
        } elseif (is_page('ayuntamiento/store_category')) {            
            get_template_part('ayuntamiento', 'categorias');
        }
    }

    public static function redirect_comercios()
    {
        if (is_page('store')) {
            get_template_part('comercio', 'dashboard');
        } elseif (is_page('store/productos')) {
            get_template_part('comercio', 'productos');
        } elseif (is_page('store/participaciones')) {
            get_template_part('comercio', 'participaciones');
        } elseif (is_page('store/datos')) {
            get_template_part('comercio', 'datos');        
        } elseif (is_page('store/qr')) {
            get_template_part('comercio', 'qr');        
        } elseif (is_page('store/campanas')) {
            get_template_part('comercio', 'campanas');
        }
    }

    public static function addCustomFieldsToAccountTab( $fieldsToShow, $additionalFields ) {
    return $fieldsToShow;
    }

    public function showExtraFields()
     {
        $custom_fields = [
            "fec_nac" => __('Date of Birth','osunatheme'),
            "direccion" => __('Address','osunatheme'),
            "country" => __('Country','osunatheme'),
            "provincia" => __('Province','osunatheme'),
            "localidad" => __('Location','osunatheme'),
            "zip_code" => __('Zip code','osunatheme'),
            "phone_number" => __('Phone number','osunatheme'),
        ];

            foreach ($custom_fields as $key => $value) {
                
                $field_value = get_user_meta(um_user('ID'), $key, true) ? : '';
                if($key == 'fec_nac'){
                    $field_value = date('Y-m-d', strtotime($field_value));
                }
                $html = '<div id="um_field_0_'.$key.'" class="um-field um-field-'.$key.'" data-key="'.$key.'">                                                              
                            <div class="um-field-label">
                                <label for="'.$key.'">'.$value.'</label>
                                <div class="um-clear"></div>
                            </div>
                            <div class="um-field-area">
                                <input class="um-form-field valid " type="text" name="'.$key.'" id="'.$key.'" value="'.$field_value.'" placeholder="" data-validate="" data-key="'.$key.'">
                             </div>
                        </div>';

                echo $html;

            }

    }

    public function mictaajax() {
        $custom_fields = [
            "fec_nac",
            "direccion",
            "country",
            "provincia",
            "localidad",
            "zip_code",
            "phone_number",
        ];
        $user_id = get_current_user_id();
        foreach ($custom_fields as $key) {
            update_user_meta($user_id, $key, $_POST[$key]);
        }
        wp_send_json_success();
    }
 
    public function enqueue_odometer_assets() {
        // Registrar los archivos
        wp_register_script('odometer-js', get_template_directory_uri() . '/odometer/odometer.js', array(), null, true);
        wp_register_style('odometer-css', get_template_directory_uri() . '/odometer/odometer-theme-car.css');
    
        // Encolar los archivos
        wp_enqueue_script('odometer-js');
        wp_enqueue_style('odometer-css');
    }
   public static function sino_usuario(){    
    $current_user = wp_get_current_user();
    if (in_array('administrator', $current_user->roles)) {
        wp_redirect(admin_url());
        exit;
    } elseif (in_array('comercio', $current_user->roles)) {
        wp_redirect(site_url('/store'));
        exit;
    }elseif (in_array('ayuntamiento', $current_user->roles)) {
        wp_redirect(site_url('/ayuntamiento'));
        exit;
    }
   } 
    
}
