<?php
class Dashboard{

    public function __construct()
    {

    }

    public static function grafica_lineas($IDGrafico, $nombreGrafico, $datosGrafico)
    {        
        echo '<canvas id="'.$IDGrafico.'"></canvas>';
        ?>        
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            var datosGrafico = <?php echo json_encode($datosGrafico); ?>;
        
            var config = {
                type: 'line',
                data: {
                    datasets: [{
                        label: "Nro. <?php echo $nombreGrafico;?>",
                        data: datosGrafico,
                        fill: false,
                        borderColor: '#2196f3',
                        backgroundColor: '#2196f3',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    title: {
                        display: false,                        
                    },
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day',
                                tooltipFormat: 'DD/MM/YYYY',
                                displayFormats: {
                                    day: 'DD/MM'
                                }
                            },
                            title: {
                                display: false,
                            }
                        },
                        y: {
                            
                        }
                    },
                },
            };        
            var ctx = document.getElementById("<?php echo $IDGrafico;?>").getContext("2d");
            window.myLine = new Chart(ctx, config);
        });
        </script>
        <?php
    }

    public static function grafica_barras($IDGrafico, $nombreGrafico, $datosGrafico)
    {        
        echo '<canvas id="'.$IDGrafico.'"></canvas>';
        ?>        
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            var datosGrafico = <?php echo json_encode($datosGrafico); ?>;
        
            new Chart(
                document.getElementById("<?php echo $IDGrafico;?>"),
                {
                type: 'bar',
                options: {
                    animation: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                stepSize: 1, // Define el intervalo entre los ticks
                                callback: function(value) {
                                    return Number.isInteger(value) ? value : null;
                                }
                            }
                        }
                    }
                },
                data: {
                    labels: datosGrafico.map(row => row.etiqueta),
                    datasets: [
                        {
                            label: '<?php echo $nombreGrafico;?>',
                            data: datosGrafico.map(row => row.cantidad),
                            backgroundColor: datosGrafico.map(row => getRandomRgb())
                        }
                    ]
                }
                }
            );         
            
        });
        </script>
        <?php
    }

    public static function grafica_torta($IDGrafico, $nombreGrafico, $datosGrafico)
    {        
        echo '<canvas id="'.$IDGrafico.'"></canvas>';
        ?>        
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            var datosGrafico = <?php echo json_encode($datosGrafico); ?>;
        
            new Chart(
                document.getElementById("<?php echo $IDGrafico;?>"),
                {
                type: 'pie',
                options: {
                    animation: true,
                    plugins: {
                        legend: {
                            display: 'top'
                        },
                        tooltip: {
                            enabled: true
                        }
                    },
                    
                },
                data: {
                    labels: datosGrafico.map(row => row.etiqueta),
                    datasets: [
                        {
                            label: '<?php echo $nombreGrafico;?>',
                            data: datosGrafico.map(row => row.cantidad),
                            backgroundColor: datosGrafico.map(row => getRandomRgb())
                        }
                    ]
                }
                }
            );         
            
        });
        </script>
        <?php
    }

    public static function top10ComercioVisitas() {
        
        global $wpdb;
        
        // Definir el rango de fechas
        //$fecha_desde = '2024-01-01';
       // $fecha_hasta = '2024-12-31';
        
        // Consultar los 10 comercios con más visitas en el rango de fechas especificado
        /*$query = $wpdb->prepare("
            SELECT p.post_title as etiqueta, ifnull(COUNT(*),0) as cantidad
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id            
            WHERE p.post_type = 'comercios'
            AND p.post_status = 'publish'
            AND pm.meta_key LIKE 'Visitas_%_fecha_visita'
            AND pm.meta_value BETWEEN %s AND %s
            GROUP BY p.ID
            ORDER BY etiqueta ASC
            LIMIT 20", $fecha_desde, $fecha_hasta);*/

            $query = "
            SELECT p.post_title as etiqueta, ifnull(COUNT(*),0) as cantidad
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id            
            WHERE p.post_type = 'comercios'
            AND p.post_status = 'publish'
            AND pm.meta_key LIKE 'Visitas_%_fecha_visita'            
            GROUP BY p.ID
            ORDER BY etiqueta ASC
            LIMIT 20";
        
        $top_comercios = $wpdb->get_results($query);
        
        // Verificar si hay resultados
        if ($top_comercios) :
           self::grafica_barras('top10ComercioVisitas', 'Total de visitas', $top_comercios);
        else :
            // No se encontraron resultados
            echo '<p>No hay datos.</p>';
        endif;
        
        
    }    

    public static function top10ComerciomeGusta() {
        
        global $wpdb;
        
        $query = "
            SELECT user_id, meta_value
            FROM {$wpdb->usermeta}
            WHERE meta_key = 'favoritos'
        ";

        $results = $wpdb->get_results($query);

        $comercio_counts = array();

        // Deserializar los datos y contar los favoritos
        foreach ($results as $result) {
            $favoritos = maybe_unserialize($result->meta_value);
            if (is_array($favoritos)) {
                foreach ($favoritos as $comercio_id) {
                    if (isset($comercio_counts[$comercio_id])) {
                        $comercio_counts[$comercio_id]++;
                    } else {
                        $comercio_counts[$comercio_id] = 1;
                    }
                }
            }
        }
        
        arsort($comercio_counts);

        // Obtener los top 10 comercios
        $top_comercios = array_slice($comercio_counts, 0, 10, true);
        // Consultar los 10 comercios con más visitas en el rango de fechas especificado
        if (!empty($top_comercios)) {
            $topi_comercios= array();
            foreach ($top_comercios as $comercio_id => $total_favoritos) {
                // Obtener el título del comercio
                $titulo= get_the_title( $comercio_id );
                if(!empty($titulo))
                    $topi_comercios[] = [
                        'etiqueta' => $titulo,
                        'cantidad' => $total_favoritos
                    ];                                
            }
            self::grafica_barras('top10ComerciomeGusta', 'Comercios mejor valorados', $topi_comercios);
           
        } else {
            // No se encontraron resultados
            echo '<p>No se encontraron comercios en favoritos.</p>';
        }                       
        
    }    

    public static function top10Campanasparticipa() {
        
        global $wpdb;
        
        $query = "
                SELECT c.post_title AS etiqueta, IFNULL(COUNT(p.ID), 0) AS cantidad
                FROM  {$wpdb->posts} p
                INNER JOIN  {$wpdb->postmeta} pm ON p.ID = pm.post_id
                INNER JOIN  {$wpdb->posts} c ON pm.meta_value = c.ID AND c.post_type = 'campana'
                WHERE  p.post_type = 'participacion'
                AND p.post_status = 'publish'
                AND pm.meta_key = 'campana'
                GROUP BY pm.meta_value
                ORDER BY etiqueta ASC LIMIT 10";
        
        $top_campanas  = $wpdb->get_results($query);
        
        // Verificar si hay resultados
        if ($top_campanas) :
           self::grafica_torta('top10Campanasparticipa', 'Total de participaciones', $top_campanas );
        else :
            // No se encontraron resultados
            echo '<p>No hay datos.</p>';
        endif;
        
        
    }

    public static function ComercioVisitas($id_comercio) {
        
        global $wpdb;
        

            $query = "
            SELECT DATE_FORMAT(pm.meta_value, '%d/%m/%Y') as etiqueta, ifnull(COUNT(*),0) as cantidad
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id            
            WHERE p.post_type = 'comercios'
            AND p.ID = '".$id_comercio."'
            AND p.post_status = 'publish'
            AND pm.meta_key LIKE 'Visitas_%_fecha_visita'            
            GROUP BY pm.meta_value
            ORDER BY etiqueta ASC
            LIMIT 20";
        
        $top_comercios = $wpdb->get_results($query);
        
        // Verificar si hay resultados
        if ($top_comercios) :
           self::grafica_barras('ComercioVisitas', 'Visitas', $top_comercios);
        else :
            // No se encontraron resultados
            echo '<p>No hay datos.</p>';
        endif;
        
        
    }
    public static function Campanasparticipa($id_comercio) {
        
        global $wpdb;                
        $query = "
                SELECT c.post_title AS etiqueta, IFNULL(COUNT(p.ID), 0) AS cantidad
                FROM  {$wpdb->posts} p
                INNER JOIN  {$wpdb->postmeta} pm ON p.ID = pm.post_id
                INNER JOIN  {$wpdb->posts} c ON pm.meta_value = c.ID AND c.post_type = 'campana'
                INNER JOIN  {$wpdb->postmeta} pmc ON pm.post_id = pmc.post_id AND pmc.meta_key = 'id_comer' AND pmc.meta_value = '{$id_comercio}'
                WHERE  p.post_type = 'participacion'
                AND p.post_status = 'publish'
                AND pm.meta_key = 'campana'
                GROUP BY pm.meta_value
                ORDER BY etiqueta ASC LIMIT 10";
        
        $top_campanas  = $wpdb->get_results($query);
        
        // Verificar si hay resultados
        if ($top_campanas) :
           self::grafica_torta('Campanasparticipa', 'Total de participaciones', $top_campanas );
        else :
            // No se encontraron resultados
            echo '<p>No hay datos.</p>';
        endif;
        
        
    }
    
    public static function total_visitas_generales(){
        global $wpdb;
        
        // Definir el rango de fechas
        //$fecha_desde = '2024-01-01';
        //$fecha_hasta = '2024-12-31';

           /* $total_query = $wpdb->prepare("
            SELECT IFNULL(SUM(visitas.cantidad), 0) as total_cantidad
            FROM (
                SELECT COUNT(*) as cantidad
                FROM {$wpdb->posts} p
                INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                WHERE p.post_type = 'comercios'
                AND p.post_status = 'publish'
                AND pm.meta_key LIKE 'Visitas_%_fecha_visita'
                AND pm.meta_value BETWEEN %s AND %s
                GROUP BY p.ID
            ) as visitas", 
            $fecha_desde, 
            $fecha_hasta    );*/

            $total_query = "
            SELECT IFNULL(SUM(visitas.cantidad), 0) as total_cantidad
            FROM (
                SELECT COUNT(*) as cantidad
                FROM {$wpdb->posts} p
                INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                WHERE p.post_type = 'comercios'
                AND p.post_status = 'publish'
                AND pm.meta_key LIKE 'Visitas_%_fecha_visita'               
                GROUP BY p.ID
            ) as visitas";

        echo $wpdb->get_var($total_query);

    }

    public static function total_visitas_porcomercio($id_comercio){
        global $wpdb;        

            $total_query = "
            SELECT IFNULL(SUM(visitas.cantidad), 0) as total_cantidad
            FROM (
                SELECT COUNT(*) as cantidad
                 FROM {$wpdb->posts} p
                INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id            
                WHERE p.post_type = 'comercios'
                AND p.ID = '".$id_comercio."'
                AND p.post_status = 'publish'
                AND pm.meta_key LIKE 'Visitas_%_fecha_visita'
            ) as visitas";

        echo $wpdb->get_var($total_query);

    }

    public static function total_favoritos_generales() {
        
        global $wpdb;
        
        $query = "
            SELECT user_id, meta_value
            FROM {$wpdb->usermeta}
            WHERE meta_key = 'favoritos'
        ";

        $results = $wpdb->get_results($query);

        $comercio_counts = array();

        // Deserializar los datos y contar los favoritos
        foreach ($results as $result) {
            $favoritos = maybe_unserialize($result->meta_value);
            if (is_array($favoritos)) {
                foreach ($favoritos as $comercio_id) {
                    if (isset($comercio_counts[$comercio_id])) {
                        $comercio_counts[$comercio_id]++;
                    } else {
                        $comercio_counts[$comercio_id] = 1;
                    }
                }
            }
        }
        
        arsort($comercio_counts);

        // Obtener los top 10 comercios
        $top_comercios = array_slice($comercio_counts, 0, 10, true);
        // Consultar los 10 comercios con más visitas en el rango de fechas especificado
        $acum =0;
        if (!empty($top_comercios)) {
            $topi_comercios= array();
            foreach ($top_comercios as $comercio_id => $total_favoritos) {
                // Obtener el título del comercio
                $titulo= get_the_title( $comercio_id );
                if(!empty($titulo))
                    $acum += $total_favoritos;                       
            }            
           
        }                       
        echo $acum;
    }

    public static function total_favoritos_porcomercio($id_comercio) {
        
        global $wpdb;
        
        $query = "
            SELECT user_id, meta_value
            FROM {$wpdb->usermeta}
            WHERE meta_key = 'favoritos'
        ";

        $results = $wpdb->get_results($query);

        $comercio_counts[$id_comercio] = 0;

        // Deserializar los datos y contar los favoritos
        foreach ($results as $result) {
            $favoritos = maybe_unserialize($result->meta_value);
            if (is_array($favoritos)) {
                foreach ($favoritos as $comercio_id) {
                    if (isset($comercio_counts[$comercio_id])) {
                        $comercio_counts[$comercio_id]++;
                    } else {
                        $comercio_counts[$comercio_id] = 1;
                    }
                }
            }
        }
        
        echo $comercio_counts[$id_comercio];
    }
    public static function total_participaciones_generales(){
        global $wpdb;
        
        //

            $total_query = "
            SELECT IFNULL(SUM(participaciones.cantidad), 0) as total_cantidad
            FROM (
                SELECT IFNULL(COUNT(p.ID), 0) AS cantidad
                FROM  {$wpdb->posts} p
                INNER JOIN  {$wpdb->postmeta} pm ON p.ID = pm.post_id              
                WHERE  p.post_type = 'participacion'
                AND p.post_status = 'publish'
                AND pm.meta_key = 'campana'
                GROUP BY pm.meta_value
            ) as participaciones";

        echo $wpdb->get_var($total_query);

    }

    public static function total_participaciones_porcomercio($id_comercio){
        global $wpdb;
        

            $total_query = "           
                SELECT IFNULL(COUNT(*), 0) AS cantidad
                FROM  {$wpdb->posts} p
                INNER JOIN  {$wpdb->postmeta} AS pm ON p.ID = pm.post_id                
                INNER JOIN  {$wpdb->postmeta} AS mt1 ON p.ID = mt1.post_id                  
                WHERE  p.post_type = 'participacion'
                AND p.post_status = 'publish'
                AND pm.meta_key = 'campana'
                AND mt1.meta_key = 'id_comer' 
                AND mt1.meta_value = '{$id_comercio}'
                GROUP BY mt1.meta_value
            ";

        echo $wpdb->get_var($total_query);

    }

}