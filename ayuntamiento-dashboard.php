<section class="row p-4">
    <div class="col-md-12"> 
        <div class="container contenedor-indi gap-3">
            <h2>Sumario</h2>
            <div class="summary">
                <div class="summary-item">
                    <p>Total Visitas</p>
                    <p class="number"><?php Dashboard::total_visitas_generales();?></p>
                </div>
                <div class="summary-item">
                    <p>Total favoritos</p>
                    <p class="number"><?php Dashboard::total_favoritos_generales();?></p>
                </div>
                <div class="summary-item">
                    <p>Participaciones</p>
                    <p class="number"><?php Dashboard::total_participaciones_generales();?></p>
                </div>            
            </div>
        </div>
        <div class="container d-block d-md-flex justify-content-between gap-3 mt-4 pt-4">
             
            <div class="contenedor-indi w-100">
                <h4 style="width: 315px;">Comercios mejor valorados</h4>
                <?php Dashboard::top10ComerciomeGusta();?>                                
            </div>   
             <div class="contenedor-indi w-50">
                <h4 style="width: 280px;">Participación campañas</h4>                
                    <?php Dashboard::top10Campanasparticipa();?>                                
            </div>  
        </div>       
        <div class="container d-block d-md-flex justify-content-between gap-3 mt-4 pt-4">
        <div class="contenedor-indi w-100">
                <h4 style="width: 290px;">Comercios más visitados</h4>                
                <?php Dashboard::top10ComercioVisitas();?>                                
            </div>  
            
        </div>       
    </div>
</section>