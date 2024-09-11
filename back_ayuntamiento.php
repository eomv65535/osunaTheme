<?php
/*
Template Name: Backend Ayuntamiento
*/

get_header();

// Asegúrate de que el usuario esté autenticado
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login'));
    exit();
}
?>
<div class="container-fluid">
  <div class="row">
    
    <div class="col-md-3 col-lg-2 px-0 h-100 bg-white shadow-sm sidebar" id="sidebar">   
        <?php OsunaTheme::menu_ayuntamiento(); ?>
    </div>    
    <div class="w-100 vh-100 position-fixed overlay d-none" id="sidebar-overlay"></div>    
    <div class="col-md-9 col-lg-10 ml-md-auto px-0">
      
      <nav class="w-100 d-flex justify-content-between px-4 py-2 mb-4 shadow-sm bg-azulito">      
        <span>
            <button class="btn py-0 d-lg-none" id="open-sidebar">
            <span class="bi bi-list text-white h3"></span>
            </button>
        </span>
        <div class="dropdown ml-auto">
          <button class="btn py-0 d-flex align-items-center" type="button" id="logout-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="bi bi-person text-white h4"></span>
            <span class="bi bi-chevron-down ml-1 mb-2 text-white small"></span>
          </button>
          <div class="dropdown-menu dropdown-menu-right border-0 shadow-sm" aria-labelledby="logout-dropdown">
            <a class="dropdown-item" href="<?php echo home_url("/account"); ?>">Perfil</a>
            <a class="dropdown-item" href="<?php echo wp_logout_url(home_url("/logout")); ?>">Salir</a>
          </div>
        </div>
      </nav>      
      <main class="">
        <?php OsunaTheme::redirect_ayuntamiento(); ?>      
      </main>
    </div>
  </div>
</div>
<?php get_footer();
?>
