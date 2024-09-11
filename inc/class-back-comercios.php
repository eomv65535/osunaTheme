<?php
class BackComercios {
    public function __construct() {
        add_action('admin_post_guardar_comercio', [$this, 'handle_guardar_comercio']);
        add_action('admin_post_guardar_categoria', [$this, 'handle_guardar_categoria']);
        add_action('admin_post_guardar_comercio2', [$this, 'handle_guardar_comercio2']);
        add_action('admin_post_eliminar_comercio', [$this, 'handle_eliminar_comercio']);
        add_action('admin_post_eliminar_categoria', [$this, 'handle_eliminar_categoria']);
    }

    public static function subir_img_comercios($file) {
        $upload_dir = wp_upload_dir();
        $custom_upload_path = trailingslashit($upload_dir['basedir']) . 'comercios/img/';
    
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
    
    public static function subir_galeria_comercios($files) {
        $upload_dir = wp_upload_dir();
        $custom_upload_path = trailingslashit($upload_dir['basedir']) . 'comercios/img/';
    
        if (!file_exists($custom_upload_path)) {
            wp_mkdir_p($custom_upload_path);
        }
        
        $imagenes_subidas = [];
        $num_files = count($files['name']);
    
        for ($i = 0; $i < $num_files; $i++) {
            if ($files['error'][$i] !== 0) {
                break;
            }
    
            $file = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i],
            ];
    
            $file_name = sanitize_file_name($file['name']);
            $file_path = $custom_upload_path . $file_name;
    
            // Validar tipo de archivo
            $file_type = wp_check_filetype($file_name);
            if (!in_array($file_type['type'], ['image/jpeg', 'image/png', 'image/gif'])) {
                break;
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
    
                    $imagenes_subidas[] =$attach_id;
                } else {
                    // Manejar error de inserción de attachment
                    error_log('Error al insertar el attachment: ' . $attach_id->get_error_message());
                }
            } else {
                // Manejar error de subida
                error_log('Error al subir el archivo: ' . $uploaded_file['error']);
            }
        }
        return $imagenes_subidas;
    }

    public static function crud_ayuntamiento_comercios()
    {
        if (
            empty($_GET['eliminarc']) &&
            empty($_GET['editarc']) &&
            empty($_GET['qrc']) &&
            empty($_GET['agregarc'])
        ) {
            self::ayuntamiento_comercios_listado();
        } elseif (!empty($_GET['editarc']) || !empty($_GET['agregarc'])) {
            self::get_ayuntamiento_comercios_form();
        } elseif (!empty($_GET['eliminarc'])) {
            self::get_ayuntamiento_comercios_eliminar();
        }elseif (!empty($_GET['qrc'])) {
            self::get_ayuntamiento_comercios_qr();
        }
    }

    public static function crud_ayuntamiento_categorias()
    {
        if (
            empty($_GET['eliminarcat']) &&
            empty($_GET['editarcat']) &&            
            empty($_GET['agregarcat'])
        ) {
            self::ayuntamiento_categorias_listado();
        } elseif (!empty($_GET['editarcat']) || !empty($_GET['agregarcat'])) {
            self::get_ayuntamiento_categorias_form();
        } elseif (!empty($_GET['eliminarcat'])) {
            self::get_ayuntamiento_categorias_eliminar();
    }
    }

    public static function ayuntamiento_comercios_listado()
    {
        echo '<div class="bg-azulito rounded p-4 w-100 mb-3 d-flex align-items-center">
                <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-shop"></i> Comercios</h2>
            </div>
        <div class="table-responsive m-b-40">';
        $parametros = [
            'post_type' => 'comercios',
            'posts_per_page' => -1,
        ];
        $comercios = new WP_Query($parametros);
        echo '
            <table id="comercios" class="table table-striped table-bordered display nowrap" style="width:100%">
                <thead class="bg-azulito">                    
                    <tr>
                        <th>Nombre</th>
                        <th>Categorías</th>
                        <th>Opciones</th>
                    </tr>    
                </thead>
                <tbody>';
        if ($comercios->have_posts()) {
            while ($comercios->have_posts()) {
                $comercios->the_post();
                $id = get_the_ID();
                $url = get_permalink($id);
                $titulo = get_the_title($id);
                $categoria = get_the_terms($id, 'categoria_comercio')[0]->name;
                echo '
                            <tr>
                                <td>' .
                    $titulo .
                    '</td>
                                <td>' .
                    $categoria .
                    '</td>
                                <td align="center">
                                    <a class="text-dark icon" target="_blank" href="' .$url .'" title="Ver comercio"><i class="bi bi-eye"></i></a>                                    
                                    <a class="text-success icon" href="usuarios/?id_comer=' .$id .'" title="Usuarios autorizados"><i class="bi bi-person"></i></a>
                                    <a class="text-warning icon" href="?editarc=' .$id .'" title="Editar comercio"><i class="bi bi-pencil"></i></a>
                                    <a class="text-danger icon" href="?eliminarc=' .$id .'" title="Eliminar comercio"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>';
            }
        }
        echo '</tbody>
            </table>
            </div>
            <div class="text-center"><a href="?agregarc=1" class="btn btn-primary mb-3">Añadir Nuevo</a></div>';
    }
    
    public static function get_translated_term($term_id,  $language) {
        $taxonomy = 'categoria_comercio';
        $translated_term_id = apply_filters( 'wpml_object_id', $term_id, $taxonomy, FALSE, $language );                  
       $translated_term_object = get_term_by('term_id', $translated_term_id, $taxonomy);       
       return $translated_term_object->name;
	
    }
   
    public static function ayuntamiento_categorias_listado()
    {
        echo '<div class="bg-azulito rounded p-4 w-100 mb-3 d-flex align-items-center">
                <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-list-ul"></i> Categorías</h2>
            </div>
        <div class="table-responsive m-b-40">';
        
        $categorias = get_terms([
            'taxonomy' => 'categoria_comercio',
        ], ['hide_empty' => false]);
        echo '
            <table id="categorias" class="table table-striped table-bordered display nowrap" style="width:100%">
                <thead class="bg-azulito">                    
                    <tr>
                        <th>Nombre Español</th>                        
                        <th>Nombre Inglés</th>                        
                        <th>Nombre Francés</th>                        
                        <th>Nombre Alemán</th> 
                        <th>Icono</th>                       
                        <th>Opciones</th>
                    </tr>    
                </thead>
                <tbody>';                
        if (!is_wp_error($categorias) && !empty($categorias)) {
            $ids = array_map(function($categoria) {
				return $categoria->term_id;
			}, $categorias);

			// Crear un array para almacenar las traducciones por idioma
			$traducciones = [];

			foreach (['en', 'fr', 'de'] as $idioma) {
				do_action('wpml_switch_language', $idioma);

				foreach ($ids as $id) {
					$traduccion = self::get_translated_term($id, $idioma);
					$traducciones[$id][$idioma] = $traduccion;
				}
			}

			// Ahora que tenemos las traducciones, volvemos al idioma original y mostramos los datos
			do_action('wpml_switch_language', 'es');

			foreach ($categorias as $categoria) {
				$id = $categoria->term_id;
				$nombre_espanol = $categoria->name;
				$nombre_ingles = $traducciones[$id]['en'];
				$nombre_frances = $traducciones[$id]['fr'];
				$nombre_aleman = $traducciones[$id]['de'];
				
                $icon_url = get_field('icon', $categoria);
                
                echo '
                            <tr>
                                <td>'.$nombre_espanol.'</td>
                                <td>'.$nombre_ingles.'</td>
                                <td>'.$nombre_frances.'</td>
                                <td>'.$nombre_aleman.'</td>      
                                <td style="background-color:#ccc;text-align:center"><img src="' .esc_url($icon_url['url']) .'" width="40"></td>                          
                                <td align="center">                                    
                                    <a class="text-warning icon" href="?editarcat=' .$id .'" title="Editar categoria"><i class="bi bi-pencil"></i></a>
                                    <a class="text-danger icon" href="?eliminarcat=' .$id .'" title="Eliminar categoria"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>';
				
        
            }
        }
        echo '</tbody>
            </table>
            </div>
            <div class="text-center"><a href="?agregarcat=1" class="btn btn-primary mb-3">Añadir Nuevo</a></div>';
    }

    public static function get_ayuntamiento_comercios_form() {
        $is_edit = !empty($_GET['editarc']);
        $id = $is_edit ? $_GET['editarc'] : '';
        $titulo_Editar = $is_edit ? 'Editar' : 'Nuevo';
        $comercio = $is_edit ? get_post($id) : null;

        $nombre = $is_edit ? $comercio->post_title : '';
        $direccion = $is_edit ? get_post_meta($id, 'direccion', true) : '';
        $latitud = $is_edit ? get_post_meta($id, 'latitud', true) : '';
        $longitud = $is_edit ? get_post_meta($id, 'longitud', true) : '';
        $telefono = $is_edit ? get_post_meta($id, 'telefono', true) : '';
        $email = $is_edit ? get_post_meta($id, 'email', true) : '';
        $horario = $is_edit ? get_post_meta($id, 'horario', true) : '';
        $web = $is_edit ? get_post_meta($id, 'web', true) : '';
        $facebook = $is_edit ? get_post_meta($id, 'facebook', true) : '';
        $instagram = $is_edit ? get_post_meta($id, 'instagram', true) : '';
        $thumbnail_id = get_post_thumbnail_id($id,'full');
        $imagen_url = get_the_post_thumbnail_url($id,'full');
        $logo_id = $is_edit ? get_post_meta($id, 'logo', true) : '';
        $galeria = $is_edit ? get_post_meta($id, 'galeria', true) : [];
      
        $categorias_comer = get_terms([
            'taxonomy' => 'categoria_comercio',
        ], ['hide_empty' => false]);
        $categoria_del_comercio = $is_edit ? get_the_terms($id, 'categoria_comercio'): '';

        echo '
        <div class="bg-azulito rounded p-4 w-100 mb-4 mt-3 d-flex align-items-center">
            <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-shop"></i> '.$titulo_Editar.' Comercio</h2>
        </div>
        <form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" class="needs-validation" novalidate  enctype="multipart/form-data">
                <input type="hidden" name="action" value="guardar_comercio">
                <input type="hidden" name="comercio_id" value="' . esc_attr($id) . '">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="' . esc_attr($nombre) . '" required>
                    <div class="invalid-feedback">Por favor, ingrese el nombre.</div>
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Categoría</label>
                    <select class="form-select" id="categoria_comercio" name="categoria_comercio" required>';
                    
                    for ($i = 0; $i <count($categorias_comer); $i++) {
                        $catego = $categorias_comer[$i];        
                        $seleccione= $categoria_del_comercio && $catego->slug == $categoria_del_comercio[0]->slug ? 'selected' : '';                
                        echo '<option value="' . esc_attr($catego->slug) . '" '.$seleccione .'>' . esc_html($catego->name) . '</option>';
                    }               

         echo '</select>
                </div>
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" value="' . esc_attr($direccion) . '" required>
                    <div class="invalid-feedback">Por favor, ingrese la dirección.</div>
                </div>
                <div class="mb-3">
                    <label for="latitud" class="form-label">Latitud</label>
                    <input type="text" class="form-control" id="latitud" name="latitud" value="' . esc_attr($latitud) . '">
                </div>
                <div class="mb-3">
                    <label for="longitud" class="form-label">Longitud</label>
                    <input type="text" class="form-control" id="longitud" name="longitud" value="' . esc_attr($longitud) . '">
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" value="' . esc_attr($telefono) . '">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="' . esc_attr($email) . '">
                </div>
                <div class="mb-3">
                    <label for="horario" class="form-label">Horario</label>
                    <input type="text" class="form-control" id="horario" name="horario" value="' . esc_attr($horario) . '">
                </div>
                <div class="mb-3">
                    <label for="web" class="form-label">Web</label>
                    <input type="url" class="form-control" id="web" name="web" value="' . esc_attr($web) . '">
                </div>
                <div class="mb-3">
                    <label for="facebook" class="form-label">Facebook</label>
                    <input type="url" class="form-control" id="facebook" name="facebook" value="' . esc_attr($facebook) . '">
                </div>
                <div class="mb-3">
                    <label for="instagram" class="form-label">Instagram</label>
                    <input type="url" class="form-control" id="instagram" name="instagram" value="' . esc_attr($instagram) . '">
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
            <input type="file" class="form-control" id="imagen_comer" name="imagen_comer">
        </div>';
        
        if ($logo_id) {
            $logo_url = wp_get_attachment_url($logo_id);
            echo '<div class="mb-3">
                    <label class="form-label">Logo Actual</label>
                    <div class="mb-3">
                        <img src="' . esc_url($logo_url) . '" class="img-thumbnail" style="max-width: 200px;">
                        <div>
                            <button type="button" class="btn btn-danger btn-sm mt-2" id="eliminar-logo" data-id="' . esc_attr($logo_id) . '">Eliminar Logo</button>
                        </div>
                    </div>
                  </div>';
        }
      
            echo '<div class="mb-3">
                    <label for="logo" class="form-label">Subir Logo</label>
                    <input type="file" class="form-control" id="logo" name="logo">
                </div>';
       
        
        
         if (!empty($galeria)) {
                echo '<div id="galeriaCarousel" class="mb-3">
                        <label class="form-label w-100">Galeria</label>
                            <div class="d-flex flex-wrap justified-content-space-between">';
                foreach ($galeria as $index => $imagen_id) {
                    $imagen_url = wp_get_attachment_url($imagen_id);
                    echo '      <div class="mb-3">
                                    <img src="' . esc_url($imagen_url) . '" class="img-thumbnail" style="max-width: 200px;">
                                     <div>
                                        <button type="button" class="btn btn-danger btn-sm mt-2 eliminar-imagen" data-id="' . esc_attr($imagen_id) . '">Eliminar</button>
                                    </div>
                                </div>
                        ';
                }
                echo '</div></div>';
            }              
        
        echo '<div class="mb-3">
                <label for="galeria" class="form-label">Subir Imágenes a la Galería</label>
                <input type="file" class="form-control" id="galeria" name="galeria[]" multiple>
              </div>
               <div class="text-center"><button type="submit" class="btn btn-primary">Guardar</button> <a href="/ayuntamiento/stores" class="btn btn-secondary">Volver</a></div>
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

    public static function get_ayuntamiento_categorias_form() {
        $is_edit = !empty($_GET['editarcat']);
        $id = $is_edit ? $_GET['editarcat'] : '';
        $titulo_Editar = $is_edit ? 'Editar' : 'Nueva';
        $categoria = $is_edit ? get_term_by('term_id', $id, 'categoria_comercio'): null;
        $nombre = $is_edit ? $categoria->name : '';        
        $nombre_aleman = $is_edit ? self::get_translated_term($id, 'de'): '';
        $nombre_frances = $is_edit ? self::get_translated_term($id, 'fr'): '';
        $nombre_ingles =$is_edit ?  self::get_translated_term($id, 'en'): '';
        $icono_catego = $is_edit ? get_field('icon', 'categoria_comercio_'.$id) : '';
        echo '
        <div class="bg-azulito rounded p-4 w-100 mb-4 mt-3 d-flex align-items-center">
            <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-list-ul"></i> '.$titulo_Editar.' Categoria</h2>
        </div>
        <form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" class="needs-validation" novalidate  enctype="multipart/form-data">
                <input type="hidden" name="action" value="guardar_categoria">
                <input type="hidden" name="categoria_id" value="' . esc_attr($id) . '">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre (Esp)</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="' . esc_attr($nombre) . '" required>
                    <div class="invalid-feedback">Por favor, ingrese el nombre de la categoria - español.</div>
                </div>
                <div class="mb-3">
                    <label for="nombre_ingles" class="form-label">Nombre (Eng)</label>
                    <input type="text" class="form-control" id="nombre_ingles" name="nombre_ingles" value="' . esc_attr($nombre_ingles) . '">
                </div>
                <div class="mb-3">
                    <label for="nombre_frances" class="form-label">Nombre (Fra)</label>
                    <input type="text" class="form-control" id="nombre_frances" name="nombre_frances" value="' . esc_attr($nombre_frances) . '">
                </div>
                <div class="mb-3">
                    <label for="nombre_aleman" class="form-label">Nombre (Deu)</label>
                    <input type="text" class="form-control" id="nombre_aleman" name="nombre_aleman" value="' . esc_attr($nombre_aleman) . '">
                </div>';                
                if ($icono_catego) {
                    
                    $icono_url = $icono_catego['url'];
                    echo '<div class="mb-3">
                            <label class="form-label">Icono Actual</label>
                            <div class="mb-3">
                                <img src="' . esc_url($icono_url) . '" class="img-thumbnail" style="background-color: #ccc;">
                                <div>
                                    <button type="button" class="btn btn-danger btn-sm mt-2" id="eliminar-icono" data-id="' . esc_attr($icono_catego['ID']) . '">Eliminar icono</button>
                                </div>
                            </div>
                          </div>';
                }
              
                    echo '<div class="mb-3">
                            <label for="icono_catego" class="form-label">Subir Icono</label>
                            <input type="file" class="form-control" id="icono_catego" name="icono_catego">
                        </div>';
                echo '<div class="text-center"><button type="submit" class="btn btn-primary">Guardar</button> <a href="/ayuntamiento/store_category" class="btn btn-secondary">Volver</a></div>
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

    public static function get_ayuntamiento_comercios_eliminar()
    {
        $id = $_GET['eliminarc'];
        $nombre = get_the_title($id);

        echo '<div class="bg-azulito rounded p-4 w-100 mb-4 mt-3 d-flex align-items-center">
            <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-shop"></i> Eliminar Comercio</h2>
        </div>
        <form method="post" action="' .
            esc_url(admin_url('admin-post.php')) .
            '">';
        echo '<input type="hidden" name="action" value="eliminar_comercio">';
        echo '<input type="hidden" name="comercio_id" value="' .
            esc_attr($id) .
            '">';
        echo '<p>¿Está seguro que desea eliminar el comercio: ' .
            esc_html($nombre) .
            '?</p>';
        echo '<input type="submit" class="btn btn-danger" value="Eliminar">  <a href="/ayuntamiento/stores" class="btn btn-secondary">Cancelar</a>';
        echo '</form>';
    }
    
    public static function get_ayuntamiento_categorias_eliminar()
    {
        $id = $_GET['eliminarcat'];
        $translated_term_object = get_term_by('term_id', $id, 'categoria_comercio');
        $nombre = $translated_term_object->name;

        echo '<div class="bg-azulito rounded p-4 w-100 mb-4 mt-3 d-flex align-items-center">
            <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-list-ul"></i> Eliminar Categoría</h2>
        </div>
        <form method="post" action="' .
            esc_url(admin_url('admin-post.php')) .
            '">';
        echo '<input type="hidden" name="action" value="eliminar_categoria">';
        echo '<input type="hidden" name="categoria_id" value="' .
            esc_attr($id) .
            '">';
        echo '<p>¿Está seguro que desea eliminar la categoría: ' .
            esc_html($nombre) .
            '?</p>';
        echo '<input type="submit" class="btn btn-danger" value="Eliminar">  <a href="/ayuntamiento/store_category" class="btn btn-secondary">Cancelar</a>';
        echo '</form>';
    }

    public static function get_ayuntamiento_comercios_qr()
    {
        $id = $_GET['qrc'];
        $nombre = get_the_title($id);
                
        
        echo '<div class="bg-azulito rounded p-4 w-100 mb-4 mt-3 d-flex align-items-center">
            <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-qr-code"></i> QR del Comercio "'.$nombre.'"</h2>
        </div>';
            echo do_shortcode( '[kaya_qrcode title="Nueva participación" title_align="aligncenter" content="https://revitaliza.osuna.es/nueva-participacion?id_comer='.$id.'" ecclevel="L" border="4" color="#000000" bgcolor="#FFFFFF" align="aligncenter" download_button="1" download_text="Descargar QR" download_align="aligncenter"]' );
        echo '<br><p align="center"><a href="/ayuntamiento/stores" class="btn btn-secondary">Volver</a></p>';
        
    }

    public static function get_comercios_qr($id)
    {
        
        $nombre = get_the_title($id);
                
        
        echo '<div class="bg-azulito rounded p-4 w-100 mb-4 mt-3 d-flex align-items-center">
            <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-qr-code"></i> QR de "'.$nombre.'"</h2>
        </div>';
            echo do_shortcode( '[kaya_qrcode title="Nueva participación" title_align="aligncenter" content="https://revitaliza.osuna.es/nueva-participacion?id_comer='.$id.'" ecclevel="L" border="4" color="#000000" bgcolor="#FFFFFF" align="aligncenter" download_button="1" download_text="Descargar QR" download_align="aligncenter"]' );        
        
    }

    public static function gestiona_traducciones($id,$is_edit) {
        
        if (function_exists('icl_object_id')) {
            $object_id = icl_object_id($id, 'comercios', true);
            $traducciones = apply_filters('wpml_get_element_translations', null, $object_id);
            
           
            
            if (empty($traducciones)) {
            
                $languages = apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');
                $terms = wp_get_object_terms($id, 'categoria_comercio', array('fields' => 'ids'));                
                
                foreach ($languages as $lang => $details) {
                    if ($details['language_code'] != ICL_LANGUAGE_CODE) {  
                        if ($is_edit) {
                            $new_post_id = apply_filters('wpml_object_id', $id, 'comercios', false, $details['language_code']);
                            if ($new_post_id) {
                                $args['ID'] = $new_post_id;
                                wp_update_post($args);
                            }    
                           
                        } else {
                            $new_post_id = wp_insert_post(array(
                                'post_type' => 'comercios',
                                'post_title' => get_the_title($id),                           
                                'post_status' => 'publish'
                            ));
                            
                            $trid = apply_filters('wpml_element_trid', NULL, $id, 'post_comercios');
                            do_action('wpml_set_element_language_details', [
                                'element_id'    => $new_post_id,
                                'element_type'  => 'post_comercios',
                                'trid'          => $trid,
                                'language_code' => $details['language_code'],
                                'source_language_code' => ICL_LANGUAGE_CODE
                            ]);
                           
                        }                      
                       
                        
                        $meta = get_post_meta($id);
                        foreach ($meta as $key => $values) {
                            foreach ($values as $value) {
                                update_post_meta($new_post_id, $key, maybe_unserialize($value));
                            }
                        }                                                                        
                        $terms_id_traducido = apply_filters( 'wpml_object_id', $terms[0], 'categoria_comercio', true, $details['language_code']);                                               
                        wp_set_object_terms($new_post_id, $terms_id_traducido, 'categoria_comercio');
                    }
                }
            } else {
              
                // Actualizar traducciones existentes
                foreach ($traducciones as $traduccion) {
                    if ($traduccion->element_id != $id) {
                        foreach ($fields as $key => $value) {
                            update_post_meta($traduccion->element_id, $key, $value);
                        }
                        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
                            update_post_meta($traduccion->element_id, 'logo', $logo_id);
                        }
                        if (isset($_FILES['galeria'])) {
                            update_post_meta($traduccion->element_id, 'galeria', $galeria);
                        }
                        if (isset($_FILES['imagen_comer']) && $_FILES['imagen_comer']['error'] === 0) {
                            update_post_meta($traduccion->element_id, '_thumbnail_id', $imagen_comer_id);
                        }
                    }
                }
            }
        } 
    }
    
    public static function handle_guardar_comercio() {
        
        if (!isset($_POST['comercio_id'])) {
            return;
        }

        $id = $_POST['comercio_id'];
        $is_edit = !empty($id);

        $args = [
            'post_title'   => sanitize_text_field($_POST['nombre']),
            'post_type'    => 'comercios',
            'post_status'  => 'publish',
        ];

        if ($is_edit) {
            $args['ID'] = $id;
            wp_update_post($args);
            wp_set_object_terms( $id, $_POST["categoria_comercio"], 'categoria_comercio' );
        } else {
            $id = wp_insert_post($args);
            wp_set_object_terms( $id, $_POST["categoria_comercio"], 'categoria_comercio' );
        }

        update_post_meta($id, 'direccion', sanitize_text_field($_POST['direccion']));
        update_post_meta($id, 'latitud', sanitize_text_field($_POST['latitud']));
        update_post_meta($id, 'longitud', sanitize_text_field($_POST['longitud']));
        update_post_meta($id, 'telefono', sanitize_text_field($_POST['telefono']));
        update_post_meta($id, 'email', sanitize_email($_POST['email']));
        update_post_meta($id, 'horario', sanitize_text_field($_POST['horario']));
        update_post_meta($id, 'web', esc_url_raw($_POST['web']));
        update_post_meta($id, 'facebook', esc_url_raw($_POST['facebook']));
        update_post_meta($id, 'instagram', esc_url_raw($_POST['instagram']));
        

        if (isset($_POST['eliminar_thumbnail'])) {
            delete_post_thumbnail($id);           
        }

        if (isset($_POST['eliminar_logo'])) {
            delete_field('logo', $id);     
            wp_delete_attachment( $_POST['eliminar_logo'], true );  
        }

        if (isset($_POST['eliminar_imagen'])) {
            $imagenes_a_eliminar = array_map('intval', $_POST['eliminar_imagen']);
            $galeria = get_post_meta( $id, 'galeria', true );
            $galeria_actualizada = array_diff($galeria, $imagenes_a_eliminar);
            update_field('galeria', $galeria_actualizada, $id);
            foreach ($imagenes_a_eliminar as $imagen_id) {                
                wp_delete_attachment( $imagen_id, true );              
            }
        }

        if (isset($_FILES['imagen_comer']) && $_FILES['imagen_comer']['error'] === 0) {
            $imagen_comer = $_FILES['imagen_comer'];
            $imagen_comer_id= self::subir_img_comercios($imagen_comer);
            if ($imagen_comer_id)               
                {
                    update_post_meta($id, '_thumbnail_id', $imagen_comer_id);
                }      
        }

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
            $logo_file = $_FILES['logo'];
            $logo_id= self::subir_img_comercios($logo_file);
            if ($logo_id)               
                update_field('logo', $logo_id, $id);            
        }

        if (isset($_FILES['galeria'])) {
            $imagenes_subidas = $_FILES['galeria'];            
            $galeria_ids = self::subir_galeria_comercios($imagenes_subidas);            
            $galeria = get_post_meta( $id, 'galeria', true );            
            if ( !empty($galeria) ) :
                $galeria = array_merge($galeria, $galeria_ids);            
            else :
                $galeria = $galeria_ids;
            endif;    
            update_field('galeria', $galeria, $id);            
            
        }
        
        self::gestiona_traducciones($id,$is_edit);        
        wp_redirect(site_url('/ayuntamiento/stores?id_comer='.$id));
        exit;
    }

    public static function handle_guardar_categoria() {
        
        if (!isset($_POST['categoria_id'])) {
            return;
        }

        $id = $_POST['categoria_id'];
        $is_edit = !empty($id);

        $nombre = sanitize_text_field($_POST['nombre']);        
        $nombre_aleman = sanitize_text_field($_POST['nombre_aleman']);
        $nombre_frances = sanitize_text_field($_POST['nombre_frances']);
        $nombre_ingles = sanitize_text_field($_POST['nombre_ingles']);
        do_action('wpml_switch_language', 'es');
        if ($is_edit) {
                      
             wp_update_term($id, 'categoria_comercio', [
                'name' => $nombre,                
             ]);
             $id_term = $id;
             
        } else {
           $id = wp_insert_term($nombre, 'categoria_comercio', [
                'name' => $nombre,
            ]);
            $id_term= $id['term_id'];
            do_action('wpml_set_element_language_details', [
                'element_id'    => $id_term,
                'element_type'  => 'tax_categoria_comercio',
                'trid'          => false,
                'language_code' => 'es',
                'source_language_code' => 'es'
            ]);
        }                     
        $trid = apply_filters( 'wpml_element_trid', NULL, $id_term, 'tax_categoria_comercio' );
       
        $id_aleman = $is_edit ? self::get_translated_term_id($id_term,'de'): '';
        $id_frances = $is_edit ?self::get_translated_term_id($id_term,'fr'):'';
        $id_ingles = $is_edit ?self::get_translated_term_id($id_term,'en'):'';

        $insert_res_aleman = self::guardar_translated_term($trid,$id_aleman,$nombre_aleman,'de',$is_edit);
        $insert_res_frances = self::guardar_translated_term($trid,$id_frances,$nombre_frances,'fr',$is_edit);
        $insert_res_ingles = self::guardar_translated_term($trid,$id_ingles,$nombre_ingles,'en',$is_edit);
        
        if (isset($_POST['eliminar_icono'])) {
            delete_field('icon', "categoria_comercio_".$id_term);     
            delete_field('icon', "categoria_comercio_".$insert_res_aleman);     
            delete_field('icon', "categoria_comercio_".$insert_res_frances);     
            delete_field('icon', "categoria_comercio_".$insert_res_ingles);     
            wp_delete_attachment( $_POST['eliminar_logo'], true );  
        }
		
		 if (isset($_FILES['icono_catego']) && $_FILES['icono_catego']['error'] === 0) {
            $icono_file = $_FILES['icono_catego'];
            $icono_id= self::subir_img_comercios($icono_file);
            if ($icono_id)               
             {
                update_field('icon', $icono_id, "categoria_comercio_".$id_term);            
                update_field('icon', $icono_id, "categoria_comercio_".$insert_res_aleman);            
                update_field('icon', $icono_id, "categoria_comercio_".$insert_res_frances);            
                update_field('icon', $icono_id, "categoria_comercio_".$insert_res_ingles);            
             }
                
        }
    
        wp_redirect(site_url('/ayuntamiento/store_category'));
        exit;
    }

    public static function get_translated_term_id($term_id,  $language) {
        $taxonomy = 'categoria_comercio';
        $translated_term_id = apply_filters( 'wpml_object_id', $term_id, $taxonomy, FALSE, $language );               
        
        return $translated_term_id;
    }

    public static function guardar_translated_term($trid,$term_id,$nombre,$language,$is_edit) {
        $taxonomy = 'categoria_comercio';
        $insert_res = $term_id;
       
        do_action('wpml_switch_language', $language);
        if ($is_edit) {
            $args['ID'] = $term_id;
            
             wp_update_term($term_id, $taxonomy, [
                'name' => $nombre,                
                                
             ]);
            
        } else {
            
           $id = wp_insert_term($nombre,  $taxonomy , [
                'name' => $nombre,
                'parent' => 0,
            ]);

            $insert_res = $id['term_id'];
            do_action('wpml_set_element_language_details', array(
                'element_id'    => $insert_res,
                'element_type'  => 'tax_categoria_comercio',
                'trid'          => $trid,
                'language_code' => $language,
                'source_language_code' => 'es',
            ));
        }    
        
        
        
        return $insert_res;
    }

    public static function handle_guardar_comercio2() {
        
        if (!isset($_POST['comercio_id'])) {
            return;
        }

        $id = $_POST['comercio_id'];
        $is_edit = !empty($id);

        $args = [
            'post_title'   => sanitize_text_field($_POST['nombre']),
            'post_type'    => 'comercios',
            'post_status'  => 'publish',
        ];

        if ($is_edit) {
            $args['ID'] = $id;
            wp_update_post($args);
            wp_set_object_terms( $id, $_POST["categoria_comercio"], 'categoria_comercio' );
        } else {
            $id = wp_insert_post($args);
            wp_set_object_terms( $id, $_POST["categoria_comercio"], 'categoria_comercio' );
        }

        update_post_meta($id, 'direccion', sanitize_text_field($_POST['direccion']));
        update_post_meta($id, 'latitud', sanitize_text_field($_POST['latitud']));
        update_post_meta($id, 'longitud', sanitize_text_field($_POST['longitud']));
        update_post_meta($id, 'telefono', sanitize_text_field($_POST['telefono']));
        update_post_meta($id, 'email', sanitize_email($_POST['email']));
        update_post_meta($id, 'horario', sanitize_text_field($_POST['horario']));
        update_post_meta($id, 'web', esc_url_raw($_POST['web']));
        update_post_meta($id, 'facebook', esc_url_raw($_POST['facebook']));
        update_post_meta($id, 'instagram', esc_url_raw($_POST['instagram']));
        

        if (isset($_POST['eliminar_thumbnail'])) {
            delete_post_thumbnail($id);           
        }

        if (isset($_POST['eliminar_logo'])) {
            delete_field('logo', $id);     
            wp_delete_attachment( $_POST['eliminar_logo'], true );  
        }

        if (isset($_POST['eliminar_imagen'])) {
            $imagenes_a_eliminar = array_map('intval', $_POST['eliminar_imagen']);
            $galeria = get_post_meta( $id, 'galeria', true );
            $galeria_actualizada = array_diff($galeria, $imagenes_a_eliminar);
            update_field('galeria', $galeria_actualizada, $id);
            foreach ($imagenes_a_eliminar as $imagen_id) {                
                wp_delete_attachment( $imagen_id, true );              
            }
        }

        if (isset($_FILES['imagen_comer']) && $_FILES['imagen_comer']['error'] === 0) {
            $imagen_comer = $_FILES['imagen_comer'];
            $imagen_comer_id= self::subir_img_comercios($imagen_comer);
            if ($imagen_comer_id)               
                {
                    update_post_meta($id, '_thumbnail_id', $imagen_comer_id);
                }      
        }

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
            $logo_file = $_FILES['logo'];
            $logo_id= self::subir_img_comercios($logo_file);
            if ($logo_id)               
                update_field('logo', $logo_id, $id);            
        }

        if (isset($_FILES['galeria'])) {
            $imagenes_subidas = $_FILES['galeria'];            
            $galeria_ids = self::subir_galeria_comercios($imagenes_subidas);            
            $galeria = get_post_meta( $id, 'galeria', true );            
            if ( !empty($galeria) ) :
                $galeria = array_merge($galeria, $galeria_ids);            
            else :
                $galeria = $galeria_ids;
            endif;    
            update_field('galeria', $galeria, $id);            
            
        }
        
        self::gestiona_traducciones($id,$is_edit);        
        wp_redirect(site_url('/stores/datos'));
        exit;
    }

    public static function handle_eliminar_comercio() {
        if (!isset($_POST['comercio_id'])) {
            return;
        }

        $post_id = $_POST['comercio_id'];

        
        $type = apply_filters( 'wpml_element_type', get_post_type( $post_id ) );
        $trid = apply_filters( 'wpml_element_trid', false, $post_id, $type );
        
        $traducciones = apply_filters( 'wpml_get_element_translations', array(), $trid, $type );
        
        if (!empty($traducciones)) {
            
            foreach ($traducciones as $lang => $traduccion) {
                
                if ($traduccion->element_id != $post_id) {
                    wp_delete_post($traduccion->element_id, true); // Eliminar la traducción
                }
            }
        }
    
        wp_delete_post($post_id, true);

         wp_redirect(site_url('/ayuntamiento/stores'));
        exit;
    }

    public static function handle_eliminar_categoria() {
        if (!isset($_POST['categoria_id'])) {
            return;
        }

        $categoria_id = $_POST['categoria_id'];

        
        
        $trid = apply_filters( 'wpml_element_trid', false, $categoria_id, 'tax_categoria_comercio' );
        
        $traducciones = apply_filters( 'wpml_get_element_translations', array(), $trid, 'tax_categoria_comercio' );
        
        if (!empty($traducciones)) {
            
            foreach ($traducciones as $lang => $traduccion) {
                
                if ($traduccion->element_id != $categoria_id) {
                    wp_delete_term($traduccion->element_id,'categoria_comercio' ); // Eliminar la traducción
                }
            }
        }
    
        wp_delete_term($categoria_id, 'categoria_comercio');

         wp_redirect(site_url('/ayuntamiento/store_category'));
        exit;
    }

    public static function get_editar_comercios_form($id) {
        $is_edit = true;
        $titulo_Editar = $is_edit ? 'Editar' : 'Nuevo';
        $comercio = $is_edit ? get_post($id) : null;

        $nombre = $is_edit ? $comercio->post_title : '';
        $direccion = $is_edit ? get_post_meta($id, 'direccion', true) : '';
        $latitud = $is_edit ? get_post_meta($id, 'latitud', true) : '';
        $longitud = $is_edit ? get_post_meta($id, 'longitud', true) : '';
        $telefono = $is_edit ? get_post_meta($id, 'telefono', true) : '';
        $email = $is_edit ? get_post_meta($id, 'email', true) : '';
        $horario = $is_edit ? get_post_meta($id, 'horario', true) : '';
        $web = $is_edit ? get_post_meta($id, 'web', true) : '';
        $facebook = $is_edit ? get_post_meta($id, 'facebook', true) : '';
        $instagram = $is_edit ? get_post_meta($id, 'instagram', true) : '';
        $thumbnail_id = get_post_thumbnail_id($id,'full');
        $imagen_url = get_the_post_thumbnail_url($id,'full');
        $logo_id = $is_edit ? get_post_meta($id, 'logo', true) : '';
        $galeria = $is_edit ? get_post_meta($id, 'galeria', true) : [];
      
        $categorias_comer = get_terms([
            'taxonomy' => 'categoria_comercio',
        ]);
        echo '
        <div class="bg-azulito rounded p-4 w-100 mb-4 mt-3 d-flex align-items-center">
            <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-shop"></i> Mis datos</h2>
        </div>
        <form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" class="needs-validation" novalidate  enctype="multipart/form-data">
                <input type="hidden" name="action" value="guardar_comercio2">
                <input type="hidden" name="comercio_id" value="' . esc_attr($id) . '">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="' . esc_attr($nombre) . '" required>
                    <div class="invalid-feedback">Por favor, ingrese el nombre.</div>
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Categoría</label>
                    <select class="form-select" id="categoria_comercio" name="categoria_comercio" required>';
                    for ($i = 0; $i <count($categorias_comer); $i++) {
                        $catego = $categorias_comer[$i];
                        echo '<option value="' . esc_attr($catego->slug) . '">' . esc_html($catego->name) . '</option>';
                    }               

         echo '</select>
                </div>
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" value="' . esc_attr($direccion) . '" required>
                    <div class="invalid-feedback">Por favor, ingrese la dirección.</div>
                </div>
                <div class="mb-3">
                    <label for="latitud" class="form-label">Latitud</label>
                    <input type="text" class="form-control" id="latitud" name="latitud" value="' . esc_attr($latitud) . '">
                </div>
                <div class="mb-3">
                    <label for="longitud" class="form-label">Longitud</label>
                    <input type="text" class="form-control" id="longitud" name="longitud" value="' . esc_attr($longitud) . '">
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" value="' . esc_attr($telefono) . '">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="' . esc_attr($email) . '">
                </div>
                <div class="mb-3">
                    <label for="horario" class="form-label">Horario</label>
                    <input type="text" class="form-control" id="horario" name="horario" value="' . esc_attr($horario) . '">
                </div>
                <div class="mb-3">
                    <label for="web" class="form-label">Web</label>
                    <input type="url" class="form-control" id="web" name="web" value="' . esc_attr($web) . '">
                </div>
                <div class="mb-3">
                    <label for="facebook" class="form-label">Facebook</label>
                    <input type="url" class="form-control" id="facebook" name="facebook" value="' . esc_attr($facebook) . '">
                </div>
                <div class="mb-3">
                    <label for="instagram" class="form-label">Instagram</label>
                    <input type="url" class="form-control" id="instagram" name="instagram" value="' . esc_attr($instagram) . '">
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
            <input type="file" class="form-control" id="imagen_comer" name="imagen_comer">
        </div>';
        
        if ($logo_id) {
            $logo_url = wp_get_attachment_url($logo_id);
            echo '<div class="mb-3">
                    <label class="form-label">Logo Actual</label>
                    <div class="mb-3">
                        <img src="' . esc_url($logo_url) . '" class="img-thumbnail" style="max-width: 200px;">
                        <div>
                            <button type="button" class="btn btn-danger btn-sm mt-2" id="eliminar-logo" data-id="' . esc_attr($logo_id) . '">Eliminar Logo</button>
                        </div>
                    </div>
                  </div>';
        }
      
            echo '<div class="mb-3">
                    <label for="logo" class="form-label">Subir Logo</label>
                    <input type="file" class="form-control" id="logo" name="logo">
                </div>';
       
        
        
         if (!empty($galeria)) {
                echo '<div id="galeriaCarousel" class="mb-3">
                        <label class="form-label w-100">Galeria</label>
                            <div class="d-flex flex-wrap justified-content-space-between">';
                foreach ($galeria as $index => $imagen_id) {
                    $imagen_url = wp_get_attachment_url($imagen_id);
                    echo '      <div class="mb-3">
                                    <img src="' . esc_url($imagen_url) . '" class="img-thumbnail" style="max-width: 200px;">
                                     <div>
                                        <button type="button" class="btn btn-danger btn-sm mt-2 eliminar-imagen" data-id="' . esc_attr($imagen_id) . '">Eliminar</button>
                                    </div>
                                </div>
                        ';
                }
                echo '</div></div>';
            }              
        
        echo '<div class="mb-3">
                <label for="galeria" class="form-label">Subir Imágenes a la Galería</label>
                <input type="file" class="form-control" id="galeria" name="galeria[]" multiple>
              </div>
               <div class="text-center"><button type="submit" class="btn btn-primary">Guardar</button> </div>
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
}