<?php
/*
Template Name: Valida Login
*/

get_header();

// Asegúrate de que el usuario esté autenticado
if (is_user_logged_in()) {
    // Obtener el objeto de usuario actual
    $current_user = wp_get_current_user();
    
    // Comprobar si la variable de sesión está establecida para redirigir a "escanear"
    if (isset($_COOKIE['redirect_to_bono']) && $_COOKIE['redirect_to_bono'] == true) {
        // Limpiar la variable de sesión
        setcookie('redirect_to_bono', 'false', time() - 3600, '/');        
        $id_comercio = $_COOKIE['id_comercio'];
        $id_campana = $_COOKIE['id_campana'];
        setcookie('id_comercio', 'false', time() - 3600, '/');
        wp_redirect(site_url('/nueva-participacion/?campa=' . $id_campana . '&id_comer=' . $id_comercio));
        exit;
    }

    
    if (in_array('administrator', $current_user->roles)) {
        wp_redirect(admin_url());
        exit;
    } elseif (in_array('editor', $current_user->roles)) {
        wp_redirect(site_url('/buscar'));
        exit;
    } elseif (in_array('author', $current_user->roles)) {
        wp_redirect(site_url('/buscar'));
        exit;
    } elseif (in_array('contributor', $current_user->roles)) {
        wp_redirect(site_url('/buscar'));
        exit;
    } elseif (in_array('subscriber', $current_user->roles)) {
        wp_redirect(site_url('/buscar'));
        exit;
    } elseif (in_array('comercio', $current_user->roles)) {
        wp_redirect(site_url('/store'));
        exit;
    }elseif (in_array('ayuntamiento', $current_user->roles)) {
        wp_redirect(site_url('/ayuntamiento'));
        exit;
    } else {
        // Redirección por defecto para otros roles
        wp_redirect(site_url('/buscar'));
        exit;
    }
} else {
    // Redirigir a la página de inicio de sesión si el usuario no está autenticado
    wp_redirect(wp_login_url());
    exit;
}

get_footer();
?>
