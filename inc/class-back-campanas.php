<?php
class BackCampanas {
    public function __construct() {
        add_action('admin_post_guardar_campana', [$this, 'handle_guardar_campana']);
        add_action('admin_post_guardar_sorteo', [$this, 'handle_guardar_sorteo']);
        add_action('admin_post_eliminar_campana', [$this, 'handle_eliminar_campana']);
    }

    public static function crud_ayuntamiento_campanas()
    {
        if (
            empty($_GET['eliminarca']) &&
            empty($_GET['editarca']) &&            
            empty($_GET['agregarca']) &&            
            empty($_GET['verca']) &&            
            empty($_GET['resultadosorteo']) &&
            empty($_GET['realizandosorteo']) &&
            empty($_GET['hsorteo'])
        ) {
            self::ayuntamiento_campanas_listado();
        } elseif (!empty($_GET['verca'])) {
            self::ayuntamiento_listado_participaciones();
        } elseif (!empty($_GET['editarca']) || !empty($_GET['agregarca'])) {
            self::get_ayuntamiento_campanas_form();
        } elseif (!empty($_GET['eliminarca'])) {
            self::get_ayuntamiento_campanas_eliminar();        
        } elseif (!empty($_GET['resultadosorteo'])) {
            self::get_ayuntamiento_resultados_sorteo();        
        } elseif (!empty($_GET['realizandosorteo'])) {
            self::get_ayuntamiento_realizando_sorteo();        
        } elseif (!empty($_GET['hsorteo'])) {
            self::get_ayuntamiento_sorteo();
        }

    }
    public static function crud_comercio_campanas()
    {
        $id_usuario = get_current_user_id();
        $id_comercio = strval(get_user_meta($id_usuario,'usuario_comercio', true)[0]); 
        if (
            empty($_GET['verca']) &&                        
            empty($_GET['qrc'])
        ) {
            self::comercio_campanas_listado($id_comercio);
        } elseif (!empty($_GET['verca'])) {
            self::comercio_listado_participaciones($id_comercio);             
        } elseif (!empty($_GET['qrc'])) {
            self::get_comercio_qrc($id_comercio);
        }

    }

    public static function ayuntamiento_campanas_listado() {
        echo '<div class="bg-azulito rounded p-4 w-100 mb-3 d-flex align-items-center">
                <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-ticket"></i> Campañas</h2>
            </div>
        <div class="table-responsive m-b-40">';
        $parametros = [
            'post_type' => 'campana',
            'posts_per_page' => -1,
        ];
        $campanas = new WP_Query($parametros);
        echo '
            <table id="campanas" class="table table-striped table-bordered display nowrap" style="width:100%">
                <thead class="bg-azulito">                    
                    <tr>
                        <th>Nombre</th>
                        <th>Fecha de inicio</th>
                        <th>Fecha de fin</th>
                        <th>Num. participaciones</th>
                        <th>Estatus</th>
                        <th>Opciones</th>
                    </tr>    
                </thead>
                <tbody>';
        if ($campanas->have_posts()) {
            while ($campanas->have_posts()) {
                $campanas->the_post();
                $id = get_the_ID();
                $url = get_permalink($id);
                $titulo = get_the_title($id);
                $fecha_inicio = date('d-m-Y',strtotime(get_post_meta($id, 'fecha_inicio', true)));
                $fecha_fin = date('d-m-Y',strtotime(get_post_meta($id, 'fecha_fin', true)));
                $estatus = get_post_meta($id, 'estatus', true);
                $participacion_parametros = [
                    'post_type' => 'participacion',
                    'posts_per_page' => -1,
                    'meta_query' => [
                        [
                            'key' => 'campana', // Nombre del campo ACF
                            'value' => $id,
                            'compare' => '=',
                        ],                        
                    ],
                ];
                $participaciones = new WP_Query($participacion_parametros);
                $num_participaciones = $participaciones->found_posts;   
                echo '
                            <tr>
                                <td>' .$titulo .'</td>
                                <td>' .$fecha_inicio .'</td>
                                <td>' .$fecha_fin .'</td>
                                <td>' .$num_participaciones .'</td>
                                <td>' .$estatus .'</td>
                                <td align="center">
                                    <a class="text-dark icon" href="?verca=' .$id .'" title="Ver participaciones"><i class="bi bi-ticket"></i></a>                                                                        
                                    <a class="text-success icon" href="?resultadosorteo=' .$id .'" title="Resultados sorteo"><i class="bi bi-coin"></i></a>                                                                        
                                    <a class="text-warning icon" href="?editarca=' .$id .'" title="Editar campana"><i class="bi bi-pencil"></i></a>
                                    <a class="text-danger icon" href="?eliminarca=' .$id .'" title="Eliminar campana"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>';
            }
        }
        echo '</tbody>
            </table>
            </div>
            <div class="text-center"><a href="?agregarca=1" class="btn btn-primary mb-3">Añadir Nueva</a></div>';
    }

    public static function comercio_campanas_listado($id_comercio) {
         

        echo '<div class="bg-azulito rounded p-4 w-100 mb-3 d-flex align-items-center">
                <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-ticket"></i> Campañas</h2>
            </div>
        <div class="table-responsive m-b-40">';
        $parametros = [
            'post_type' => 'campana',
            'posts_per_page' => -1,
            'meta_query' => [    
                        'relation' => 'AND',                   
                        [
                            'key' => 'comercios', 
                            'value' => serialize($id_comercio),
                            'compare' => 'like',
                        ]
                    ],
        ];
        
        $campanas = new WP_Query($parametros);
        
        echo '
            <table id="campanas" class="table table-striped table-bordered display nowrap" style="width:100%">
                <thead class="bg-azulito">                    
                    <tr>
                        <th>Nombre</th>
                        <th>Fecha de inicio</th>
                        <th>Fecha de fin</th>
                        <th>Num. participaciones</th>
                        <th>Estatus</th>
                        <th>Opciones</th>
                    </tr>    
                </thead>
                <tbody>';
        if ($campanas->have_posts()) {
            while ($campanas->have_posts()) {
                $campanas->the_post();
                $id = get_the_ID();
                $url = get_permalink($id);
                $titulo = get_the_title($id);
                $fecha_inicio = date('d-m-Y',strtotime(get_post_meta($id, 'fecha_inicio', true)));
                $fecha_fin = date('d-m-Y',strtotime(get_post_meta($id, 'fecha_fin', true)));
                $estatus = get_post_meta($id, 'estatus', true);
                $participacion_parametros = [
                    'post_type' => 'participacion',
                    'posts_per_page' => -1,
                    'meta_query' => [
                        [
                            'key' => 'campana', // Nombre del campo ACF
                            'value' => $id,
                            'compare' => '=',
                        ],
                        [
                            'key' => 'id_comer', // Nombre del campo ACF
                            'value' => strval($id_comercio),
                            'compare' => 'like',
                        ],
                    ],
                ];
                $participaciones = new WP_Query($participacion_parametros);
                
                $num_participaciones = $participaciones->found_posts;   
                echo '
                            <tr>
                                <td>' .$titulo .'</td>
                                <td>' .$fecha_inicio .'</td>
                                <td>' .$fecha_fin .'</td>
                                <td>' .$num_participaciones .'</td>
                                <td>' .$estatus .'</td>
                                <td align="center">
                                    <a class="text-dark icon" href="?verca=' .$id .'" title="Ver participaciones"><i class="bi bi-ticket"></i></a>                                                                        
                                    <a class="text-success icon" href="?qrc=' .$id .'" title="Ver QR"><i class="bi bi-qr-code"></i></a>                                                                                                            
                                </td>
                            </tr>';
            }
        }
        echo '</tbody>
            </table>
            </div>';
    }

    public static function ayuntamiento_listado_participaciones() {
        $id_campana=$_GET['verca'];
        $campana = get_post($id_campana);
        $nombre_campana = $campana->post_title;
        echo '<div class="bg-azulito rounded p-4 w-100 mb-3 d-flex align-items-center">
                <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-ticket"></i> Participaciones en la campaña "<em>' .$nombre_campana.'</em>"</h2>
            </div>
        <div class="table-responsive m-b-40">';
        $parametros = [
            'post_type' => 'participacion',
            'posts_per_page' => -1,
            'meta_key' => 'campana',
            'meta_query' => [
                'key' => 'campana',
                'value' => strval($id_campana),
                'compare' => '=',
            ],
        ];

        $participaciones = new WP_Query($parametros);
        
        echo '
            <table id="campanas" class="table table-striped table-bordered display nowrap" style="width:100%">
                <thead class="bg-azulito">                    
                    <tr>
                        <th>Participante</th>
                        <th>Fecha de ticket</th>
                        <th>Código de ticket</th>                  
                        <th>Precio de ticket</th>
                        <th>Imagen de ticket</th>
                        <th>Comercio</th>
                       
                    </tr>    
                </thead>
                <tbody>';
        //$elbotoncito = '<a href="?hsorteo='.$id_campana.'" class="btn btn-primary">Hacer sorteo</a>';
		$elbotoncito = '<a href="?hsorteo='.$id_campana.'" class="btn btn-primary boton-sorteo">Hacer sorteo</a>';

        if ($participaciones->have_posts()) {
            while ($participaciones->have_posts()) {
                $participaciones->the_post();
                $id = get_the_ID();
                $participante= get_userdata (get_post_meta($id, 'participante', true));
                $fecha_ticket = get_post_meta($id, 'fecha_ticket', true);
                $codigo_ticket = get_post_meta($id, 'codigo_ticket', true);
                $monto_ticket = get_post_meta($id, 'monto_ticket', true);
                $id_imagen_ticket = get_post_meta($id, 'imagen_ticket', true);
                $id_comercio = get_post_meta($id, 'id_comer', true);
                $nombre_comercio = get_the_title($id_comercio);
                $imagen_url = wp_get_attachment_image_url($id_imagen_ticket,'');
                $imagen_ticket = !empty($imagen_url) ? $imagen_url : 'https://osuna.cbtpruebas.es/wp-content/uploads/2024/06/no-image.jpg';                             
                echo '
                            <tr>
                                <td>' .$participante->first_name .' ' .$participante->last_name.'</td>
                                <td>' .$fecha_ticket .'</td>
                                <td>' .$codigo_ticket .'</td>
                                <td>' .$monto_ticket .'</td>
                                <td class="text-center"><a style="cursor:pointer" onclick="abre_modal_ticket(\'' .$imagen_ticket .'\')"><img src="' .$imagen_ticket .'" width="100px"></a></td>
                                <td>' .$nombre_comercio .'</td>                                                                                            
                            </tr>';
            }            
        }
        else
            $elbotoncito="";
        if(!empty($elbotoncito))
        {
            $parametros_sorteos = [
                'post_type' => 'sorteo',
                'posts_per_page' => -1,
                'meta_key' => 'campana',
                'meta_query' => [
                    'key' => 'campana',
                    'value' => strval($id_campana),
                    'compare' => '=',
                ],
            ];
    
            $sorteos = new WP_Query($parametros_sorteos);
            $num_sorteos = $sorteos->found_posts;
            if($num_sorteos>0)
                $elbotoncito = '<a href="?resultadosorteo='.$id_campana.'" class="btn btn-primary">Ver resultados sorteo</a>';
            else
            {               
                $fecha_fin = date('d-m-Y', strtotime(get_post_meta($id_campana, 'fecha_fin', true)));
                $fecha_hoy = date('d-m-Y');
                $timestamp_fin = strtotime($fecha_fin);
                $timestamp_hoy = strtotime($fecha_hoy);

                if ($timestamp_hoy > $timestamp_fin) 
                    $elbotoncito = '<a href="?hsorteo='.$id_campana.'" class="btn btn-primary">Hacer sorteo</a>';
                else
                    $elbotoncito="";
            } 
        }
        echo '</tbody>
            </table>
            </div><br>
             <div class="text-center">'.$elbotoncito.' <a href="/ayuntamiento/campanas" class="btn btn-secondary">Volver</a></div>
             <div class="modal" tabindex="-1" id="modal_ticket">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Imagen de ticket</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p><img src="" width="100%"></p>
                        <p><a href="#" class="btn btn-primary" download>Descargar</a></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>                        
                    </div>
                    </div>
                </div>
                </div>';
    }

    public static function comercio_listado_participaciones($id_comercio) {
        $id_campana=$_GET['verca'];
        $campana = get_post($id_campana);
        $nombre_campana = $campana->post_title;
        echo '<div class="bg-azulito rounded p-4 w-100 mb-3 d-flex align-items-center">
                <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-ticket"></i> Participaciones en la campaña "<em>' .$nombre_campana.'</em>"</h2>
            </div>
        <div class="table-responsive m-b-40">';
        $parametros = [
            'post_type' => 'participacion',
            'posts_per_page' => -1,
            'meta_key' => 'campana',
            'meta_query' => [
                [
                    'key' => 'campana',
                    'value' => strval($id_campana),
                    'compare' => '=',
                ],
                [
                    'key' => 'id_comer', // Nombre del campo ACF
                    'value' => strval($id_comercio),
                    'compare' => 'like',
                ],
            ],
        ];

        $participaciones = new WP_Query($parametros);
        
        echo '
            <table id="campanas" class="table table-striped table-bordered display nowrap" style="width:100%">
                <thead class="bg-azulito">                    
                    <tr>
                        <th>Participante</th>
                        <th>Fecha de ticket</th>
                        <th>Código de ticket</th>                  
                        <th>Precio de ticket</th>
                        <th>Imagen de ticket</th>
                       
                    </tr>    
                </thead>
                <tbody>';
       
        if ($participaciones->have_posts()) {
            while ($participaciones->have_posts()) {
                $participaciones->the_post();
                $id = get_the_ID();
                $participante= get_userdata (get_post_meta($id, 'participante', true));
                $fecha_ticket = get_post_meta($id, 'fecha_ticket', true);
                $codigo_ticket = get_post_meta($id, 'codigo_ticket', true);
                $monto_ticket = get_post_meta($id, 'monto_ticket', true);
                $id_imagen_ticket = get_post_meta($id, 'imagen_ticket', true);
                $imagen_url = wp_get_attachment_image_url($id_imagen_ticket,'');
                $imagen_ticket = !empty($imagen_url) ? $imagen_url : 'https://osuna.cbtpruebas.es/wp-content/uploads/2024/06/no-image.jpg';                             
                echo '
                            <tr>
                                <td>' .$participante->first_name .' ' .$participante->last_name.'</td>
                                <td>' .$fecha_ticket .'</td>
                                <td>' .$codigo_ticket .'</td>
                                <td>' .$monto_ticket .'</td>
                                <td class="text-center"><a style="cursor:pointer" onclick="abre_modal_ticket(\'' .$imagen_ticket .'\')"><img src="' .$imagen_ticket .'" width="100px"></a></td>
                                                                                                                         
                            </tr>';
            }            
        }
       
       
        echo '</tbody>
            </table>
            </div><br>
             <div class="text-center"> <a href="/store/campanas" class="btn btn-secondary">Volver</a></div>
             <div class="modal" tabindex="-1" id="modal_ticket">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Imagen de ticket</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p><img src="" width="100%"></p>
                        <p><a href="#" class="btn btn-primary" download>Descargar</a></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>                        
                    </div>
                    </div>
                </div>
                </div>';
    }

    public static function get_ayuntamiento_resultados_sorteo() {
        $id_campana=$_GET['resultadosorteo'];
        $campana = get_post($id_campana);
        $nombre_campana = $campana->post_title;
        echo '<div class="bg-azulito rounded p-4 w-100 mb-3 d-flex align-items-center">
                <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-award"></i> Resultados sorteo de la campaña: "<em>' . $nombre_campana . '</em>"</h2>
            </div>
        ';
        $parametros = [
            'post_type' => 'sorteo',
            'posts_per_page' => -1,
            'meta_key' => 'campana',
            'meta_query' => [
                'key' => 'campana',
                'value' => strval($id_campana),
                'compare' => '=',
            ],
        ];

        $sorteos = new WP_Query($parametros);
        $elbotoncito = '';

        if ($sorteos->have_posts()) {
            
            while ($sorteos->have_posts()) {
                $sorteos->the_post();
                $id_srteo = get_the_ID();
                $ganadores= get_post_meta($id_srteo, 'ids_ganadores', true);                                     
                echo '<div class="table-responsive m-b-40">            
                    <table id="campanas" class="table table-striped table-bordered display nowrap" style="width:100%">
                        <thead class="bg-azulito">
                                <th>Ganador</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                foreach ($ganadores as $uno)        
                 {
                    
                    $ganador = get_userdata( $uno);
                    echo '<tr>
                        <td>' .$ganador->first_name .' ' .$ganador->last_name.'</td>
                        <td>' .$ganador->user_email.'</td>
                        <td>' .get_user_meta($uno, 'phone_number', true).'</td>
                    </tr>';
                 }
               
                echo '</tbody></table></div>';
                $suplentes= get_post_meta($id_srteo, 'ids_suplentes', true);                                     
                echo '<br><div class="table-responsive m-b-40">            
                    <table id="suplentes" class="table table-striped table-bordered display nowrap" style="width:100%">
                        <thead class="bg-azulito">
                                <th>Suplente</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                            </tr>
                        </thead>
                        <tbody>';
                foreach ($suplentes as $uno)        
                 {
                    $suplente = get_userdata( $uno);
                    echo '<tr>
                            <td>' .$suplente->first_name .' ' .$suplente->last_name.'</td>
                            <td>' .$suplente->user_email.'</td>
                            <td>' .get_user_meta($uno, 'phone_number', true).'</td>
                        </tr>';
                 }
               
                echo '</tbody></table></div>';
            }  
                     
        }
        else
         {
            $fecha_fin = date('d-m-Y', strtotime(get_post_meta($id_campana, 'fecha_fin', true)));
                $fecha_hoy = date('d-m-Y');
                $timestamp_fin = strtotime($fecha_fin);
                $timestamp_hoy = strtotime($fecha_hoy);

                if ($timestamp_hoy > $timestamp_fin) 
                    $elbotoncito = '<a href="?hsorteo='.$id_campana.'" class="btn btn-primary">Hacer sorteo</a>';
                else
                    $elbotoncito="";
                    $elbotoncito='<h4>No hay resultados</h4><br>'.$elbotoncito;
         }
            
        
        echo '<br>
             <div class="text-center">'.$elbotoncito.' <a href="/ayuntamiento/campanas" class="btn btn-secondary">Volver</a></div>';
    }

    public static function get_ayuntamiento_realizando_sorteo() {        
        $id_campana = $_GET['realizandosorteo'];
        $campana = get_post($id_campana);
        $nombre_campana = $campana->post_title;
        
        $parametros = [
            'post_type' => 'sorteo',
            'posts_per_page' => -1,
            'meta_key' => 'campana',
            'meta_query' => [
                [
                    'key' => 'campana',
                    'value' => strval($id_campana),
                    'compare' => '=',
                ],
            ],
        ];
    
        $sorteos = new WP_Query($parametros);
        $ganadores = [];
        $suplentes = [];
    
        while ($sorteos->have_posts()) {
            $sorteos->the_post();
            $id_srteo = get_the_ID();
            $ganadores = get_post_meta($id_srteo, 'ids_ganadores', true);
            $suplentes = get_post_meta($id_srteo, 'ids_suplentes', true);
        }
        
        wp_reset_postdata(); // Resetea los datos post
        
        echo '
            <h1>Resultados del Sorteo '. $nombre_campana .'</h1>
            <h2>Ganadores</h2>
            <p id="no-ganadores">Todavia no hay ganadores<p>
            <ul id="ganadores-list">
            </ul>
            <h2>Suplentes</h2>
            <p id="no-suplentes">Todavia no hay suplentes<p>
            <ul id="suplentes-list"></ul>
            <div class="w-100 m-auto text-center scale-125">
                <div id="odometer" class="odometer" style="font-size: 100px;">0</div>
            </div>
            <br>
            <div class="text-center">
        ';
    
        $ganadores_data = [];
        foreach ($ganadores as $uno) {
            $ganador = get_userdata($uno);
            $ganadores_data[] = ['ID' => $ganador->ID, 'name' => $ganador->display_name];
        }
    
        $suplentes_data = [];
        foreach ($suplentes as $uno) {
            $suplente = get_userdata($uno);
            $suplentes_data[] = ['ID' => $suplente->ID, 'name' => $suplente->display_name];
        }
    
        echo '
            <button id="next-winner-btn" onclick="showNextWinner()" class="btn btn-primary">Iniciar</button>
            <a id="finalizar-btn" href="?resultadosorteo ' . $id_campana . '" class="btn btn-primary" style="display:none;">Finalizar</a>
            </div>
        ';
    
        echo '
        <script>
            var ganadores = ' . json_encode($ganadores_data) . ';
            var suplentes = ' . json_encode($suplentes_data) . ';
            var currentWinnerIndex = 0;
            var showingGanadores = true;
    
            function showNextWinner() {
                var winner;
                if (showingGanadores) {
                    if (currentWinnerIndex < ganadores.length) {
                        winner = ganadores[currentWinnerIndex++];
                        document.getElementById("odometer").innerHTML = winner.ID;
                        setTimeout(function() {
                            var li = document.createElement("li");
                            li.textContent = winner.name;
                            document.getElementById("ganadores-list").appendChild(li);
                            var noGanadoresP = document.getElementById("no-ganadores");
                            if (noGanadoresP) {
                                noGanadoresP.remove();
                            }
                        }, 2000);
                    }
                    if (currentWinnerIndex >= ganadores.length) {
                        currentWinnerIndex = 0;
                        showingGanadores = false;
                        document.getElementById("next-winner-btn").textContent = "Iniciar Suplentes";
                    } else {
                        document.getElementById("next-winner-btn").textContent = "Siguiente Ganador";
                    }
                } else {
                    if (currentWinnerIndex < suplentes.length) {
                        winner = suplentes[currentWinnerIndex++];
                        document.getElementById("odometer").innerHTML = winner.ID;
                        setTimeout(function() {
                            var li = document.createElement("li");
                            li.textContent = winner.name;
                            document.getElementById("suplentes-list").appendChild(li);
                            var noSuplentesP = document.getElementById("no-suplentes");
                            if (noSuplentesP) {
                                noSuplentesP.remove();
                            }
                        }, 2000);
                    }
                    if (currentWinnerIndex >= suplentes.length) {
                        document.getElementById("next-winner-btn").remove();
                        document.getElementById("finalizar-btn").style.display = "inline";
                    } else {
                        document.getElementById("next-winner-btn").textContent = "Siguiente Suplente";
                    }
                }
            }
        </script>
        ';
    }

    public static function get_ayuntamiento_sorteo() {
        
        $id_campana = $_GET['hsorteo'];
        $campana = get_post($id_campana);
        $nombre_campana = $campana->post_title;        
        echo '
        <div class="bg-azulito rounded p-4 w-100 mb-4 mt-3 d-flex align-items-center">
            <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-ticket"></i> Sorteo de la campaña: "<em>' . $nombre_campana . '</em>"</h2>
        </div>
        <form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" class="needs-validation" novalidate  enctype="multipart/form-data">
                <input type="hidden" name="action" value="guardar_sorteo">
                <input type="hidden" name="campana_id" value="' . esc_attr($id_campana) . '">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Número de ganadores</label>
                    <input type="number" class="form-control" id="ganadores" name="ganadores"  required>
                    <div class="invalid-feedback">Por favor, ingrese el número de ganadores.</div>
                </div>                
                <div class="mb-3">
                    <label for="nombre" class="form-label">Número de suplentes</label>
                    <input type="number" class="form-control" id="suplentes" name="suplentes"  required>
                    <div class="invalid-feedback">Por favor, ingrese el número de suplentes.</div>
                </div>
                <div class="alert alert-warning" role="alert"><b>Advertencia:</b> Al continuar, se generará un nuevo sorteo. Esta accion no se puede deshacer.</div><br>                               
               <div class="text-center"><button type="submit" class="btn btn-primary">Continuar</button> <a href="/ayuntamiento/campanas" class="btn btn-secondary">Volver</a></div>
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

    public static function get_ayuntamiento_campanas_form() {
        $is_edit = !empty($_GET['editarca']);
        $id = $is_edit ? $_GET['editarca'] : '';
        $titulo_Editar = $is_edit ? 'Editar' : 'Nuevo';
        $campana = $is_edit ? get_post($id) : null;

        $nombre = $is_edit ? $campana->post_title : '';
        $nombre = $is_edit ? get_post_meta($id, 'nombre', true) : '';
        $fecha_inicio = $is_edit ? get_post_meta($id, 'fecha_inicio', true) : '';
        $fecha_fin = $is_edit ? get_post_meta($id, 'fecha_fin', true) : '';
        $estatus = $is_edit ? get_post_meta($id, 'estatus', true) : '';    
        $comercios = $is_edit ? get_post_meta($id, 'comercios', true) : [];    
        
        $categorias_comercios = get_terms([
            'taxonomy' => 'categoria_comercio',
        ], ['hide_empty' => false]);
        echo '
        <div class="bg-azulito rounded p-4 w-100 mb-4 mt-3 d-flex align-items-center">
            <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-ticket"></i> '.$titulo_Editar.' campana</h2>
        </div>
        <form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" class="needs-validation" novalidate  enctype="multipart/form-data">
                <input type="hidden" name="action" value="guardar_campana">
                <input type="hidden" name="campana_id" value="' . esc_attr($id) . '">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="' . esc_attr($nombre) . '" required>
                    <div class="invalid-feedback">Por favor, ingrese el nombre.</div>
                </div>                
                <div class="mb-3">
                    <label for="nombre" class="form-label">Descripción</label>
                    <input type="text" class="form-control" id="nombre" name="direccion" value="' . esc_attr($nombre) . '" required>
                    <div class="invalid-feedback">Por favor, ingrese la descripción.</div>
                </div>
                <div class="mb-3">
                    <label for="latitud" class="form-label">Fecha de inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="' . esc_attr($fecha_inicio) . '">
                </div>
                <div class="mb-3">
                    <label for="longitud" class="form-label">Fecha de fin</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="' . esc_attr($fecha_fin) . '">
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Estatus</label>
                    <select class="form-select" id="estatus" name="estatus" required>
                        <option value="inactiva" '.($estatus=='inactiva'? ' selected="selected"':'').'>Inactiva</option>
                        <option value="activo"'.($estatus=='activo'? ' selected="selected"':'').'>Activo</option>
                        <option value="cerrada"'.($estatus=='cerrada'? ' selected="selected"':'').'>Cerrada</option>
                    </select>
                </div>';
                
                    echo '<div class="mb-3">
                    <label for="comercios" class="form-label">Comercios</label>
                    <select class="form-select" id="comercios_campana" name="comercios_campana[]" multiple required style="height: 300px">';
                    
                    for ($i = 0; $i <count($categorias_comercios); $i++) {
                             $catego = $categorias_comercios[$i];  
                           
                            echo '<optgroup label="'.$catego->name.'" id="'.$catego->slug.'"><label for="'.$catego->slug.'">Clic para seleccionar toda la categoría</label>';
                            $args = [
                                'post_type' => 'comercios',                                                                         
                                'tax_query' => [
                                    [
                                        'taxonomy' => 'categoria_comercio',
                                    'field' => 'slug',
                                    'terms' => strval($catego->slug) ,
                                    'operator' => 'IN'
                                    ]
                                ],
                            ];
                            $querycomercios = new WP_Query($args);
                            
                            if($querycomercios->have_posts()){
                                while($querycomercios->have_posts()){
                                    $querycomercios->the_post();
                                    $id_comercio = get_the_ID();
                                    $nombre = get_the_title();
                                    $seleccione=in_array($id_comercio, $comercios) ? 'selected="selected"' : '';
                                    echo '<option value="'.$id_comercio.'" '.$seleccione.'>'.$nombre.'</option>';
                                }
                            } 
                            echo '</optgroup>';                           
                        }

                    echo '</select>
                </div>';
                
                 
        
        echo '
               <div class="text-center"><button type="submit" class="btn btn-primary">Guardar</button> <a href="/ayuntamiento/campanas" class="btn btn-secondary">Volver</a></div>
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

    public static function get_ayuntamiento_campanas_eliminar() {
        $id = $_GET['eliminarca'];
        $nombre = get_the_title($id);

        echo '<div class="bg-azulito rounded p-4 w-100 mb-4 mt-3 d-flex align-items-center">
            <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-ticket"></i> Eliminar campana</h2>
        </div>
        <form method="post" action="' .
            esc_url(admin_url('admin-post.php')) .
            '">';
        echo '<input type="hidden" name="action" value="eliminar_campana">';
        echo '<input type="hidden" name="campana_id" value="' .
            esc_attr($id) .
            '">';
        echo '<p>¿Está seguro que desea eliminar el campana: ' .
            esc_html($nombre) .
            '?</p>';
        echo '<input type="submit" class="btn btn-danger" value="Eliminar">  <a href="/ayuntamiento/campanas" class="btn btn-secondary">Cancelar</a>';
        echo '</form>';
    }

    public static function get_comercio_qrc($id_comercio)
    {
        $id = $_GET['qrc'];
        $nombre = get_the_title($id);
                
        
        echo '<div class="bg-azulito rounded p-4 w-100 mb-4 mt-3 d-flex align-items-center">
            <h2 class="fs-5 fw-normal mb-0"><i class="bi bi-qr-code"></i> QR de la Campaña "'.$nombre.'"</h2>
        </div>';
            echo do_shortcode( '[kaya_qrcode title="Nueva participación" title_align="aligncenter" content="https://osuna.cbtpruebas.es/nueva-participacion?campa='.$id.'&id_comer='.$id_comercio.'" ecclevel="L" border="4" color="#000000" bgcolor="#FFFFFF" align="aligncenter" download_button="1" download_text="Descargar QR" download_align="aligncenter"]' );
        echo '<br><p align="center"><a href="/store/campanas" class="btn btn-secondary">Volver</a></p>';
        
    }

    public static function handle_guardar_campana() {
        
        if (!isset($_POST['campana_id'])) {
            return;
        }

        $id = $_POST['campana_id'];
        $is_edit = !empty($id);

        $args = [
            'post_title'   => sanitize_text_field($_POST['nombre']),
            'post_type'    => 'campana',
            'post_status'  => 'publish',
        ];

        if ($is_edit) {
            $args['ID'] = $id;
            wp_update_post($args);
           
        } else {
            $id = wp_insert_post($args);
           
        }
        
        
        update_post_meta($id, 'comercios',  $_POST['comercios_campana']);
        update_post_meta($id, 'nombre', sanitize_text_field($_POST['nombre']));
        update_post_meta($id, 'fecha_inicio', sanitize_text_field($_POST['fecha_inicio']));
        update_post_meta($id, 'fecha_fin', sanitize_text_field($_POST['fecha_fin']));
        update_post_meta($id, 'estatus', sanitize_text_field($_POST['estatus']));        
        
        wp_redirect(site_url('/ayuntamiento/campanas?id_comer='.$id));
        exit;
    }

    public static function handle_guardar_sorteo() {
        
        if (!isset($_POST['campana_id'])) {
            return;
        }

        $id_campana = $_POST['campana_id'];
        $campana = get_post($id_campana);
        $nombre_campana = $campana->post_title;

        $args = [
            'post_title'   => 'Sorteo ' . current_time('Y-m-d H:i:s'). ' - Campaña: ' . $nombre_campana,
            'post_type'    => 'sorteo',
            'post_status'  => 'publish',
        ];

        $id_sorteo = wp_insert_post($args);
        
        update_post_meta($id_campana, 'estatus', 'cerrada');

        $num_ganadores = strval($_POST['ganadores']);
        $num_suplentes = strval($_POST['suplentes']);
        $total = $num_ganadores + $num_suplentes;

        update_post_meta($id_sorteo, 'numero_de_ganadores', sanitize_text_field($_POST['ganadores']));
        update_post_meta($id_sorteo, 'numero_de_suplentes', sanitize_text_field($_POST['suplentes']));
        update_post_meta($id_sorteo, 'campana', sanitize_text_field($id_campana));
        update_post_meta($id_sorteo, 'fecha_sorteo', sanitize_text_field(current_time('Y-m-d')));
        
        $parametros = [
            'post_type' => 'participacion',
            'posts_per_page' => $total,
            'meta_key' => 'campana',
            'meta_query' => [
                'key' => 'campana',
                'value' => strval($id_campana),
                'compare' => '=',
            ],
            'orderby' => 'rand',
        ];
        $participaciones = new WP_Query($parametros);
        $ids_todos= array();
        if ($participaciones->have_posts()) {
            while ($participaciones->have_posts()) {
                $participaciones->the_post();
                $id_participacion = get_the_ID();
                $id_todos[]= get_post_meta($id_participacion, 'participante', true);               
            }
            $ganadores = array_slice($id_todos, 0, $num_ganadores);
            $suplentes = array_slice($id_todos, $num_ganadores);   
            update_post_meta($id_sorteo, 'ids_ganadores', $ganadores);           
            update_post_meta($id_sorteo, 'ids_suplentes', $suplentes);           
        }
        
        wp_redirect(site_url('/ayuntamiento/campanas?realizandosorteo='.$id_campana));
        exit;
    }

    public static function handle_eliminar_campana() {
        if (!isset($_POST['campana_id'])) {
            return;
        }

        $post_id = $_POST['campana_id'];        
    
        wp_delete_post($post_id, true);

         wp_redirect(site_url('/ayuntamiento/campanas'));
        exit;
    }
}