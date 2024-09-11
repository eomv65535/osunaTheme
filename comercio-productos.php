<section class="row p-4">
    <div class="col-md-12">            
        <article class="p-5 rounded shadow-sm border-left mb-4">
            <?php BackProductos::crud_comercio_productos(); ?>           
        </article>
    </div>
</section>
<?php if (empty($_GET['eliminarp']) && empty($_GET['editarp'])) { ?>
    <script>
        jQuery(document).ready(function($) {
            $('#productos').DataTable({
                responsive: true,                
                "order": [[ 0, "asc" ]],                
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                }
                });
        });
    </script>
<?php } ?>
