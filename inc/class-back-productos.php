<?php
class BackProductos {
    public function __construct() {
        add_action('admin_post_guardar_producto', [$this, 'handle_guardar_producto']);
        add_action('admin_post_eliminar_producto', [$this, 'handle_eliminar_producto']);
    }

    public static function subir_img_productos($file) {
        $upload_dir = wp_upload_dir();
        $custom_upload_path = trailingslashit($upload_dir['basedir']) . 'productos/img/';
    
        if (!file_exists($custom_upload_path)) {
            wp_mkdir_p($custom_upload_path);
        }
    
        $imagen_subidas = '';
    
        $file_name = sanitize_file_name($file['name']);
        $file_path = $custom_upload_path . $file_name;
    
        // Validar tipo de archivo
        $file_type = wp_check_filetype($file_name);
        if (!in_array($file_type['type'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
            return 'Tipo de archivo no permitido'; // O maneja el error como prefieras
        }
    
        // Usar wp_handle_upload para mayor seguridad
        $upload_overrides = ['test_form' => false, 'unique_filename_callback' => function ($dir, $name, $ext) {
            return uniqid() . $ext; // Usar nombres únicos para evitar colisiones
        }];
        $uploaded_file = wp_handle_upload($file, $upload_overrides);
    
        if ($uploaded_file && !isset($uploaded_file['error'])) {
            $attachment = array(
                'post_mime_type' => $uploaded_file['type'],
                'post_title' => $file_name,
                'post_content' => '',
                'post_status' => 'inherit'
            );
    
            $attach_id = wp_insert_attachment($attachment, $uploaded_file['file']);
    
            if (!is_wp_error($attach_id)) {
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attach_data = wp_generate_attachment_metadata($attach_id, $uploaded_file['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);
    
                $imagen_subidas = $attach_id;
            } else {
                // Manejar error de inserción de attachment
                error_log('Error al insertar el attachment: ' . $attach_id->get_error_message());
            }
        } else {
            // Manejar error de subida
            error_log('Error al subir el archivo: ' . $uploaded_file['error']);
        }
    
        return $imagen_subidas;
    }
        

    public static function crud_comercio_productos()
    {
        if (
            empty($_GET['eliminarp']) &&
            empty($_GET['editarp']) &&            
            empty($_GET['agragap'])
        ) {
            self::comercio_productos_listado();
        } elseif (!empty($_GET['editarp']) || !empty($_GET['agragap'])) {
            self::get_comercio_productos_form();
        } elseif (!empty($_GET['eliminarp'])) {
            self::get_comercio_productos_eliminar();
        }
    }

    public static function comercio_productos_listado()
    {
        echo '<div class="bg-azulito rounded p-4 w-100 mb-3 d-flex align-items-center">
                <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-basket"></i> Productos</h2>
            </div>
        <div class="table-responsive m-b-40">';
        $id_usuario = get_current_user_id();
        $id_comercio = strval(get_user_meta($id_usuario,'usuario_comercio', true)[0]);        
        $parametros = [
            'post_type' => 'producto',
            'posts_per_page' => -1,
            'meta_key' => 'comercio',
            'meta_query' => [
                'key' => 'comercio',
                'value' => strval($id_comercio),
                'compare' => '=',
            ],
        ];
        $productos = new WP_Query($parametros);
        echo '
            <table id="productos" class="table table-striped table-bordered display nowrap" style="width:100%">
                <thead class="bg-azulito">                    
                    <tr>
                        <th>Nombre</th>
                        <th>Precio (€)</th>
                        <th>Precio con descuento (€)</th>
                        <th>% descuento</th>
                        <th>Imagen</th>
                        <th>Opciones</th>
                    </tr>    
                </thead>
                <tbody>';
        if ($productos->have_posts()) {
            while ($productos->have_posts()) {
                $productos->the_post();
                $id_prod = get_the_ID();
                $url = get_permalink($id_prod);
                $titulo = get_the_title($id_prod);
                $precio = get_post_meta($id_prod, 'precio', true);
                $descuento = get_post_meta($id_prod, 'descuento', true);
                $porc_descuento = '0';
                if ($precio && $descuento && $precio > $descuento) {
                    $porc_descuento = round((($precio - $descuento) / $precio) * 100, 0);
                }
                $thumbnail_url = get_the_post_thumbnail_url($id_prod, 'medium');
                if (!$thumbnail_url) {
                    $thumbnail_url = 'https://via.placeholder.com/300x200'; // URL de la imagen placeholder
                }
                echo '
                            <tr>
                                <td>' .$titulo .'</td>
                                <td>' .$precio .'</td>
                                <td>' .$descuento .'</td>
                                <td>' .$porc_descuento .'%</td>
                                <td class="text-center"><a style="cursor:pointer" onclick="abre_modal_ticket(\'' .$thumbnail_url .'\')"><img src="' .$thumbnail_url .'" width="100px"></a></td>
                               
                                <td align="center">                                    
                                    <a class="text-warning icon" href="?editarp=' .$id_prod .'" title="Editar producto"><i class="bi bi-pencil"></i></a>
                                    <a class="text-danger icon" href="?eliminarp=' .$id_prod .'" title="Eliminar producto"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>';
            }
        }
        echo '</tbody>
            </table>
            </div>
            <div class="text-center"><a href="?agragap=1" class="btn btn-primary mb-3">Añadir Nuevo</a></div><br>
             
             <div class="modal" tabindex="-1" id="modal_ticket">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Imagen de ticket</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p><img src="" width="100%"></p>
                        <p><a href="#" class="btn btn-primary" download>Descargar</a></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>                        
                    </div>
                    </div>
                </div>
                </div>';
    }

    public static function get_comercio_productos_form() {
        $is_edit = !empty($_GET['editarp']);
        $id = $is_edit ? $_GET['editarp'] : '';
        $titulo_Editar = $is_edit ? 'Editar' : 'Nuevo';
        $producto = $is_edit ? get_post($id) : null;

        $nombre = $is_edit ? $producto->post_title : '';
        $precio = $is_edit ? get_post_meta($id, 'precio', true) : '';
        $descuento = $is_edit ? get_post_meta($id, 'descuento', true) : '0';
        
        $thumbnail_id = get_post_thumbnail_id($id,'full');
        $imagen_url = get_the_post_thumbnail_url($id,'full');
        
      
        $categorias_comer = get_terms([
            'taxonomy' => 'categoria_producto',
        ]);
        echo '
        <div class="bg-azulito rounded p-4 w-100 mb-4 mt-3 d-flex align-items-center">
            <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-basket"></i> '.$titulo_Editar.' producto</h2>
        </div>
        <form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" class="needs-validation" novalidate  enctype="multipart/form-data">
                <input type="hidden" name="action" value="guardar_producto">
                <input type="hidden" name="producto_id" value="' . esc_attr($id) . '">
                
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="' . esc_attr($nombre) . '" required>
                    <div class="invalid-feedback">Por favor, ingrese el nombre.</div>
                </div>                
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio (€)</label>
                    <input type="text" class="form-control" id="precio" name="precio" value="' . esc_attr($precio) . '" required>
                    <div class="invalid-feedback">Por favor, ingrese el precio.</div>
                </div>
                <div class="mb-3">
                    <label for="descuento" class="form-label">Precio con descuento (€)</label>
                    <br><em>Poner "0" si no hay descuento</em>
                    <input type="text" class="form-control" id="descuento" name="descuento" value="' . esc_attr($descuento) . '">
                </div>';
             
        
        if ($imagen_url) : 
            
            echo '<div class="mb-3">
                    <label class="form-label">Imagen</label>
                    <div class="mb-3">
                        <img src="' . esc_url($imagen_url) . '" alt="Imagen" class="img-thumbnail" style="max-width: 200px;">
                        <div>
                            <button type="button" class="btn btn-danger btn-sm mt-2" id="eliminar-thumbnail" data-id="' . esc_attr($thumbnail_id) . '">Eliminar Imagen</button>
                        </div>
                    </div>
                  </div>';
                                   
        endif;
        
            
        echo '<div class="mb-3">
            <label for="ima" class="form-label">Subir Imagen</label>
            <input type="file" class="form-control" id="imagen_prod" name="imagen_prod">
        </div>';
        
        
        echo '
               <div class="text-center"><button type="submit" class="btn btn-primary">Guardar</button> <a href="/store/productos" class="btn btn-secondary">Volver</a></div>
                </form>';

        echo '<script>
                (function () {
                  "use strict";
                  var forms = document.querySelectorAll(".needs-validation");
                  Array.prototype.slice.call(forms).forEach(function (form) {
                    form.addEventListener("submit", function (event) {
                      if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                      }
                      form.classList.add("was-validated");
                    }, false);
                  });
                })();

              </script>';
    }

    public static function get_comercio_productos_eliminar()
    {
        $id = $_GET['eliminarp'];
        $nombre = get_the_title($id);

        echo '<div class="bg-azulito rounded p-4 w-100 mb-4 mt-3 d-flex align-items-center">
            <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-shop"></i> Eliminar producto</h2>
        </div>
        <form method="post" action="' .
            esc_url(admin_url('admin-post.php')) .
            '">';
        echo '<input type="hidden" name="action" value="eliminar_producto">';
        echo '<input type="hidden" name="producto_id" value="' .
            esc_attr($id) .
            '">';
        echo '<p>¿Está seguro que desea eliminar el producto: ' .
            esc_html($nombre) .
            '?</p>';
        echo '<input type="submit" class="btn btn-danger" value="Eliminar">  <a href="/store/productos" class="btn btn-secondary">Cancelar</a>';
        echo '</form>';
    }

    
    public static function handle_guardar_producto() {
        
        if (!isset($_POST['producto_id'])) {
            return;
        }

        $id = $_POST['producto_id'];
        $is_edit = !empty($id);

        $args = [
            'post_title'   => sanitize_text_field($_POST['nombre']),
            'post_type'    => 'producto',
            'post_status'  => 'publish',
        ];

        if ($is_edit) {
            $args['ID'] = $id;
            wp_update_post($args);            
        } else {
            $id = wp_insert_post($args);            
        }
        $id_usuario = get_current_user_id();
        $id_comercio = strval(get_user_meta($id_usuario,'usuario_comercio', true)[0]);
        update_post_meta($id, 'comercio', $id_comercio);
        
        update_post_meta($id, 'precio', sanitize_text_field($_POST['precio']));
        update_post_meta($id, 'descuento', sanitize_text_field($_POST['descuento']));
        

        if (isset($_POST['eliminar_thumbnail'])) {
            delete_post_thumbnail($id);           
        }


        if (isset($_FILES['imagen_prod']) && $_FILES['imagen_prod']['error'] === 0) {
            $imagen_prod = $_FILES['imagen_prod'];
            $imagen_prod_id= self::subir_img_productos($imagen_prod);
            if ($imagen_prod_id)               
                {
                    update_post_meta($id, '_thumbnail_id', $imagen_prod_id);
                }      
        }
        wp_redirect(site_url('/store/productos'));
        exit;
    }

    public static function handle_eliminar_producto() {
        if (!isset($_POST['producto_id'])) {
            return;
        }

        $post_id = $_POST['producto_id'];        
        $type = apply_filters( 'wpml_element_type', get_post_type( $post_id ) );           
        wp_delete_post($post_id, true);

         wp_redirect(site_url('/store/productos'));
        exit;
    }
}