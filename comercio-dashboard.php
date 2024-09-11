<?php 
    $id_usuario = get_current_user_id();
    $id_comercio = strval(get_user_meta($id_usuario,'usuario_comercio', true)[0]);    
?>

<section class="row p-4">
    <div class="col-md-12"> 
        <div class="container contenedor-indi gap-3">
            <h2>Sumario</h2>
            <div class="summary">
                <div class="summary-item">
                    <p>Total Visitas</p>
                    <p class="number"><?php Dashboard::total_visitas_porcomercio($id_comercio);?></p>
                </div>
                <div class="summary-item">
                    <p>Total favoritos</p>
                    <p class="number"><?php Dashboard::total_favoritos_porcomercio($id_comercio);?></p>
                </div>
                <div class="summary-item">
                    <p>Participaciones</p>
                    <p class="number"><?php Dashboard::total_participaciones_porcomercio($id_comercio);?></p>
                </div>            
            </div>
        </div>
        <div class="container d-block d-md-flex justify-content-between gap-3 mt-4 pt-4">
             
            <div class="contenedor-indi w-100">
                <h4 style="width: 315px;">Visitas</h4>
                <?php Dashboard::ComercioVisitas($id_comercio);?>                                
            </div>   
             <div class="contenedor-indi w-50">
                <h4 style="width: 280px;">Participación campañas</h4>                
                    <?php Dashboard::Campanasparticipa($id_comercio);?>                                
            </div>  
        </div>              
    </div>
</section>