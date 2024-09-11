<?php
/*
Template Name: Mis participaciones

*/
get_header(); 
if (!is_user_logged_in()) {
    // Redirigir a la página de inicio de sesión si no está autenticado
    wp_redirect(home_url('/login'));
    exit;
}
OsunaTheme::sino_usuario();
$participaciones_query= Participaciones::mis_participaciones();
OsunaTheme::inicia_datatables();
?>
<div class="bg-azulito container-utm align-items-center">
    <div class="cerrar_login" style="position:absolute">
        <a href="<?php echo site_url('/buscar'); ?>" class="btn-close btn-close-white" aria-label="Close"></a>
    </div>
    <div class="w-100 p-4">
        <div class="w-100 text-center">
            <img src="https://osuna.cbtpruebas.es/wp-content/uploads/2024/06/Osuna-Logo-Vector.png" style="width: 120px;">
        </div>
        <br>
        <p class="title"><?php echo __('My participations', 'osunatheme'); ?></p>
        <h2>Revitaliza Osuna</h2>
        <br>
        
        <?php if ($participaciones_query->have_posts()) : ?>
            <div class="table-responsive m-b-40 rounded bg-white text-dark" id="mispartis">
                <table id="misparticipaciones" class="table table-striped table-bordered display nowrap" style="width:100%">
                
                    <thead>
                        <tr>
                            <th><?php echo __('Date', 'osunatheme'); ?></th>
                            <th><?php echo __('Code', 'osunatheme'); ?></th>
                            <th><?php echo __('Amount', 'osunatheme'); ?></th>
                            <th><?php echo __('Commerce', 'osunatheme'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($participaciones_query->have_posts()) : $participaciones_query->the_post(); ?>
                            <tr>
                                <td><?php echo get_post_meta(get_the_ID(), 'fecha_ticket', true); ?></td>
                                <td><?php echo get_post_meta(get_the_ID(), 'codigo_ticket', true); ?></td>
                                <td><?php echo get_post_meta(get_the_ID(), 'monto_ticket', true); ?></td>
                                <td><?php echo get_the_title(get_post_meta(get_the_ID(), 'id_comer', true)); ?></td>
                            </tr>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </tbody>
                </table>
            </div>
            <script>
                jQuery(document).ready(function($) {  
                    $('#misparticipaciones').DataTable(
                        {
                        responsive: true,               
                        "order": [[ 0, "asc" ]],
                        "columnDefs": [
                            {
                            "targets": 2,
                            "orderable": false
                            }
                        ],
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                        }   
                    });
                });
            </script>
        <?php else : ?>
            <p><?php echo __('No participations found.', 'osunatheme'); ?></p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer();  ?>
