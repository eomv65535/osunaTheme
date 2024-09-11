<?php
/*
Template Name: Nuevo Bono
*/

get_header();

// Asegúrate de que el usuario esté autenticado
if (!is_user_logged_in()) {
    // Establecer una variable de sesión para recordar la página "escanear"
    setcookie('redirect_to_bono', true, time() + 3600, '/');  
    setcookie('id_comercio', $_GET["id_comer"], time() + 3600, '/');  
    setcookie('id_campana', $_GET["campa"], time() + 3600, '/');  
    // Redirigir a la página de inicio de sesión
    wp_redirect(home_url('/login'));
    exit;
}

$id_comercio = isset($_GET['id_comer']) ? intval($_GET['id_comer']) : 0;
$id_campana = isset($_GET['campa']) ? intval($_GET['campa']) : 0;

$comercio = Comercios::validar_un_comercio($id_comercio);

if (!$comercio) {
    
   wp_redirect(home_url('/buscar'));
   // exit;
}
$campana = Campanas::confirma_campana_activa($id_campana);

if (!$campana) {
   
    
    wp_redirect(home_url('/buscar'));
    //exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handle_guardar_participacion();
}
?>
<div class="bg-azulito container-utm">
    <div class="cerrar_login" style="position:absolute"><a href="<?php echo home_url('/buscar'); ?>" class="btn-close btn-close-white" aria-label="Close"></a></div>
    <div class="custom-login-form w-100">
	<div class=" w-100 text-center"><img src="https://osuna.cbtpruebas.es/wp-content/uploads/2024/06/Osuna-Logo-Vector.png" style="width: 120px;"></div>
	<br>
        <p class="title"><?php echo __('New participation', 'osunatheme');?></p>
        <h2>Revitaliza Osuna</h2>
        <br>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php'));?>" enctype="multipart/form-data">
            <input type="hidden" name="action" value="guardar_participacion">
            <input type="hidden" name="id_comer" value="<?php echo esc_attr($id_comercio); ?>">
            <input type="hidden" name="campana" value="<?php echo esc_attr($id_campana); ?>">
            <div class="form-group">
                <label for="fecha_ticket"><?php echo __('Ticket Date', 'osunatheme'); ?></label>
                <input type="date" name="fecha_ticket" id="fecha_ticket" required>
            </div>
            <div class="form-group">
                <label for="codigo_ticket"><?php echo __('Ticket Code', 'osunatheme'); ?></label>
                <input type="text" name="codigo_ticket" id="codigo_ticket" required>
            </div>
            <div class="form-group">
                <label for="monto_ticket"><?php echo __('Ticket Amount', 'osunatheme'); ?></label>
                <input type="number" name="monto_ticket" id="monto_ticket" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="imagen_ticket"><?php echo __('Ticket Image', 'osunatheme'); ?></label>
                <input type="file" name="imagen_ticket" id="imagen_ticket" accept="image/*;capture=camera" required>
            </div>
            <button id="btn-submit-participacion" type="submit"><?php echo __('Submit', 'osunatheme'); ?></button>
        </form>
    </div>
</div>
<?php get_footer();?>
