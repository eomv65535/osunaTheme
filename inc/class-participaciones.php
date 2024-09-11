<?php
class Participaciones   {
    public function __construct() {
        add_action('admin_post_guardar_participacion', [$this, 'handle_guardar_participacion']);        
    }

    public static function subir_img_participacion($file) {
        $upload_dir = wp_upload_dir();
        $custom_upload_path = trailingslashit($upload_dir['basedir']) . 'participaciones/img/';
    
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

    public function handle_guardar_participacion() {
        $id_comer = isset($_POST['id_comer']) ? intval($_POST['id_comer']) : 0;
        $fecha_ticket = isset($_POST['fecha_ticket']) ? sanitize_text_field($_POST['fecha_ticket']) : '';
        $codigo_ticket = isset($_POST['codigo_ticket']) ? sanitize_text_field($_POST['codigo_ticket']) : '';
        $monto_ticket = isset($_POST['monto_ticket']) ? floatval($_POST['monto_ticket']) : 0.0;
        $imagen_ticket = isset($_FILES['imagen_ticket']) ? $_FILES['imagen_ticket'] : null;
        $campana = isset($_POST['campana']) ? intval($_POST['campana']) : 0;
        $participante = get_current_user_id();

        // Verificar que todos los campos necesarios están presentes
        if (!$id_comer || !$fecha_ticket || !$codigo_ticket || !$monto_ticket || !$imagen_ticket || !$campana) {
            return;
        }
        $id_imagen = self::subir_img_participacion($imagen_ticket);
       
    
        // Crear nueva participación
        $participacion_data = array(
            'post_title' => 'Participación ' . current_time('Y-m-d H:i:s'),
            'post_type' => 'participacion',
            'post_status' => 'publish',
            'meta_input' => array(
                'id_comer' => $id_comer,
                'participante' => get_current_user_id(),
                'fecha_ticket' => $fecha_ticket,
                'codigo_ticket' => $codigo_ticket,
                'monto_ticket' => $monto_ticket,
                'imagen_ticket' => $id_imagen,
                'campana' => $campana,
            ),
        );
    
        wp_insert_post($participacion_data);
    
        // Redirigir a una página de éxito o a la misma página con un mensaje
        wp_redirect(home_url('/gracias'));
        exit;
    }

    public static function mis_participaciones(){
        
        $args = array(
            'post_type' => 'participacion',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => 'participante',
                    'value' => get_current_user_id(),
                    'compare' => '='
                )
            )
        );
        
        
        return new WP_Query($args);
    }
    public static function participaciones_por_comercio()
    {
        $id_usuario = get_current_user_id();
        $id_comercio = strval(get_user_meta($id_usuario,'usuario_comercio', true)[0]);

        
        echo '<div class="bg-azulito rounded p-4 w-100 mb-3 d-flex align-items-center">
                <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-ticket"></i> Participaciones</h2>
            </div>
        <div class="table-responsive m-b-40">';
        $parametros = [
            'post_type' => 'participacion',
            'posts_per_page' => -1,
            'meta_key' => 'id_comer',
            'meta_query' => [
                'key' => 'id_comer',
                'value' => strval($id_comercio),
                'compare' => '=',
            ],
        ];

        $participaciones = new WP_Query($parametros);
        
        echo '
            <table id="participaciones" class="table table-striped table-bordered display nowrap" style="width:100%">
                <thead class="bg-azulito">                    
                    <tr>
                        <th>Participante</th>
                        <th>Fecha de ticket</th>
                        <th>Código de ticket</th>                  
                        <th>Precio de ticket</th>
                        <th>Imagen de ticket</th>                     
                    </tr>    
                </thead>
                <tbody>';
       
        if ($participaciones->have_posts()) {
            while ($participaciones->have_posts()) {
                $participaciones->the_post();
                $id = get_the_ID();
                $participante= get_userdata (get_post_meta($id, 'participante', true));
                $fecha_ticket = get_post_meta($id, 'fecha_ticket', true);
                $codigo_ticket = get_post_meta($id, 'codigo_ticket', true);
                $monto_ticket = get_post_meta($id, 'monto_ticket', true);
                $imagen_ticket = get_post_meta($id, 'imagen_ticket', true);
                $id_comercio = get_post_meta($id, 'id_comer', true);                
                $imagen_ticket = !empty($imagen_ticket) ? $imagen_ticket : 'https://osuna.cbtpruebas.es/wp-content/uploads/2024/06/no-image.jpg';                             
                echo '
                            <tr>
                                <td>' .$participante->first_name .' ' .$participante->last_name.'</td>
                                <td>' .$fecha_ticket .'</td>
                                <td>' .$codigo_ticket .'</td>
                                <td>' .$monto_ticket .'</td>
                                <td class="text-center"><a style="cursor:pointer" onclick="abre_modal_ticket(\'' .$imagen_ticket .'\')"><img src="' .$imagen_ticket .'" width="100px"></a></td>                                                                                                                         
                            </tr>';
            }            
        }
        echo '</tbody>
            </table>
            </div><br>
             
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
}