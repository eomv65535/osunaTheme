<section class="row p-4">
    <div class="col-md-12">            
        <article class="p-5 rounded shadow-sm border-left mb-4">
            <?php BackComercios::crud_ayuntamiento_comercios(); ?>           
        </article>
    </div>
</section>
<?php if (empty($_GET['eliminarc']) && empty($_GET['editarc'])) { ?>
    <script>
        jQuery(document).ready(function($) {
            $('#comercios').DataTable(
                {
                responsive: true,
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
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
<?php } ?>
