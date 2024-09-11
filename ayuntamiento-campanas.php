<section class="row p-4">
    <div class="col-md-12">            
        <article class="p-5 rounded shadow-sm border-left mb-4">
            <?php BackCampanas::crud_ayuntamiento_campanas(); ?>           
        </article>
    </div>
</section>
<?php if (empty($_GET['eliminarca']) && empty($_GET['editarca']) && empty($_GET['hsorteo'])) { ?>
    <script>
        jQuery(document).ready(function($) {
            $('#campanas').DataTable(
                {
                responsive: true,                
                "order": [[ 0, "asc" ]],                
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                }
                });
            $('#suplentes').DataTable(
                {
                responsive: true,                
                "order": [[ 0, "asc" ]],                
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                }
                });
             
        });
    </script>
<?php } ?>
