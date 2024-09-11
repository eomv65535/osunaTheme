<section class="row p-4">
    <div class="col-md-12">            
        <article class="p-5 rounded shadow-sm border-left mb-4">
            <?php Participaciones::participaciones_por_comercio(); ?>           
        </article>
    </div>
</section>

    <script>
        jQuery(document).ready(function($) {
            $('#participaciones').DataTable({
                responsive: true,                
                "order": [[ 0, "asc" ]],                
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                }
                });
           
             
        });
    </script>

