<?php
class Usuarios{
    public function __construct() {
        add_action('admin_post_guardar_usuario', [$this, 'handle_guardar_usuario']);
        add_action('admin_post_eliminar_usuario', [$this, 'handle_eliminar_usuario']);
    }

    public static function handle_guardar_usuario()
    {       
        if (!isset($_POST['usuario']) && !isset($_POST['email']) && !isset($_POST['contra'])) {
            return;
        }

        $id_comer = $_POST['id_comer'];
        $user_login = $_POST['usuario'];
        $user_email = $_POST['email'];
        $user_password = $_POST['contra'];
        
            // Preparar los datos del nuevo usuario
            $userdata = array(
                'user_login' => $user_login,
                'user_email' => $user_email,
                'user_pass'  => $user_password,
                'role'       => 'comercio', // Asegúrate de tener un rol adecuado configurado
            );
        
            // Crear el usuario
            $user_id = wp_insert_user($userdata);
        
            if (is_wp_error($user_id)) {                
                wp_redirect(site_url('/ayuntamiento/stores/usuarios/?id_comer='.esc_attr($id_comer).'&agregarusuc=2'));
                exit;

            } else {
                
                update_user_meta($user_id, 'usuario_comercio',[$id_comer]);
                um_fetch_user( $user_id );
		        UM()->user()->approve();
	
                $login_url = site_url('/login.php');

                wp_mail(
                    $user_email,
                    'Tu nuevo usuario autorizado en Revitaliza Osuna',
                    Correo::cuerpo_correo('<p> Se ha creado una cuenta de usuario para tu comercio. Puedes iniciar sesión <a href="'.$login_url.'" target="_blank">aquí</a> con los siguientes datos: </p>
                    <p><b>Usuario: </b>' . $user_login .'</p>
                    <p><b>Contraseña: </b>'. $user_password.'</p>
                    '),
                    array('Content-Type: text/html; charset=UTF-8')
                );
            }
     
            wp_redirect(site_url('/ayuntamiento/stores/usuarios/?id_comer='.esc_attr($id_comer)));
            exit;
    }

    public static function crud_comercios_usuarios()
    {
        if(empty($_GET['id_comer'])) wp_redirect(site_url('/ayuntamiento/stores'));
        
        if (empty($_GET['eliminarusuc']) && empty($_GET['agregarusuc'])) {
            self::comercios_usuarios_listado($_GET['id_comer']);
        } elseif (!empty($_GET['agregarusuc'])) {
            self::get_comercios_usuarios_form($_GET['id_comer']);
        } elseif (!empty($_GET['eliminarusuc'])) {
            self::get_comercios_usuarios_eliminar($_GET['id_comer'],$_GET['eliminarusuc']);
        }
    }

    public static function comercios_usuarios_listado($id_comer)
    {
        $titulo_comer= get_the_title( $id_comer );
        echo '<div class="bg-azulito rounded p-4 w-100 mb-3 d-flex align-items-center">
                <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-person"></i> Usuarios autorizados para "' . $titulo_comer . '"</h2>
            </div>
        <div class="table-responsive m-b-40">';


        $user_query = new WP_User_Query( array(
            'role' => 'comercio',
            'meta_query' => array(                
                array(
                    'key' => 'usuario_comercio',
                    'value' => $id_comer,
                    'compare' => 'like'
                ),               
            )
        ) );
        
        echo '
            <table id="comercios" class="table table-striped table-bordered display nowrap" style="width:100%">
                <thead class="bg-azulito">                    
                    <tr>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Correo electrónico</th>
                        <th>Fecha de creación</th>
                        
                        <th>Opciones</th>
                    </tr>    
                </thead>
                <tbody>';
           foreach ( $user_query->get_results() as $usuario ) {
            
                    $user_id = $usuario->ID;
                    $nombre = $usuario->display_name;
                    $nombre_usuario = $usuario->user_login;
                    $email = $usuario->user_email;
                    $fec_crea = date('d/m/Y', strtotime($usuario->user_registered));
                    
            
                echo '<tr>
                                <td>' .$nombre .'</td>
                                <td>' .$nombre_usuario .'</td>
                                <td>' .$email .'</td>
                                <td>' .$fec_crea .'</td>
                                                          
                                <td>                                                                      
                                    <a class="text-dark icon" href="?id_comer=' .$id_comer.'&eliminarusuc=' .$user_id .'"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>';
            }
        
        echo '</tbody>
            </table>
            </div>
            <div class="text-center"><a href="?id_comer=' .$id_comer.'&agregarusuc=1" class="btn btn-primary">Añadir Nuevo</a> <a href="/ayuntamiento/stores" class="btn btn-secondary">Volver</a></div>';
    }

    public static function get_comercios_usuarios_form($id_comer) {
        
        $error=$_GET["agregarusuc"]==2?'<div class="alert alert-danger" role="alert"><b>Error:</b> Usuario o Email ya registrados. Verifique los datos e intente nuevamente.</div><br>':'';
        $titulo_comer= get_the_title( $id_comer );
        $password = wp_generate_password(12, true, true);
        echo '
        
        <div class="bg-azulito rounded p-4 w-100 mb-4 mt-3 d-flex align-items-center">
            <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-person"></i> Nuevo Usuario Autorizado</h2>
        </div>
        <form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" class="needs-validation" novalidate  enctype="multipart/form-data">
                <input type="hidden" name="action" value="guardar_usuario">
                <input type="hidden" name="id_comer" value="' . esc_attr($id_comer) . '">
                '.$error.'
                <div class="mb-3">
                    <label for="nombre" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" required>
                    <div class="invalid-feedback">Por favor, ingrese el nombre.</div>
                </div>               
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">Por favor, ingrese el email.</div>
                </div>
                <div class="mb-3">
                    <label for="contra" class="form-label">Contraseña</label>
                    <input type="text" class="form-control" id="contra" name="contra" value="'.$password.'" required>
                    <div class="invalid-feedback">Por favor, ingrese la contraseña.</div>
                </div> 
                <div class="text-center"><button type="submit" class="btn btn-primary">Guardar</button> <a href="/ayuntamiento/stores/usuarios/?id_comer='.esc_attr($id_comer).'" class="btn btn-secondary">Volver</a></div>
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
    public static function get_comercios_usuarios_eliminar($id_comer,$id_usuario)
    {        
        $usuario = get_userdata( $id_usuario );
        $nombre = $usuario->display_name;

        echo '<div class="bg-azulito rounded p-4 w-100 mb-4 mt-3 d-flex align-items-center">
            <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-person"></i> Eliminar Usuario Autorizado</h2>
        </div>
        <form method="post" action="' .
            esc_url(admin_url('admin-post.php')) .
            '">';
        echo '<input type="hidden" name="action" value="eliminar_usuario">';
        echo '<input type="hidden" name="id_usuario" value="' .esc_attr($id_usuario) .'">';
        echo '<input type="hidden" name="id_comer" value="' .esc_attr($id_comer) .'">';
        echo '<p>¿Está seguro que desea eliminar el usuario: ' .
            esc_html($nombre) .
            '?</p>';
        echo '<input type="submit" class="btn btn-danger" value="Eliminar">  <a href="/ayuntamiento/stores/usuarios/?id_comer='.$id_comer.'" class="btn btn-secondary">Cancelar</a>';
        echo '</form>';
    }

    public static function handle_eliminar_usuario() {
        if (!isset($_POST['id_usuario'])) {
            return;
        }

        $id_usuario = $_POST['id_usuario'];
        $id_comer = $_POST['id_comer'];
        
    
        wp_delete_user($id_usuario, true);

         wp_redirect(site_url('/ayuntamiento/stores/usuarios/?id_comer='.$id_comer));
        exit;
    }
}