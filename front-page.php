<?php get_header(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalación Revitaliza Osuna</title>
   <script src="<?php echo get_template_directory_uri();?>/js/parafront.js?ver=<?php echo date('YmdHis');?>"></script>
</head>
<body>
<div class="bg-azulito container-utm align-content-center align-items-center justify-content-between flex-wrap flex-column pt-5">
        <div class="logs"></div>
        <div class="contenedor_splash">
            <div id="install-container" class="d-none">
                <img src="https://osuna.cbtpruebas.es/wp-content/uploads/2024/06/logo.png" alt="Cargando...">
                <h6>Revitaliza Osuna</h6> 
                <p>Instala nuestra PWA para una mejor experiencia.</p>
                <button class="btn btn-primary" id="install-button">Instalar PWA</button>
            </div>
            <div id="ios-instructions" class="d-none">
                <img src="https://osuna.cbtpruebas.es/wp-content/uploads/2024/06/logo.png" alt="Cargando...">
                <h6>Revitaliza Osuna</h6> 
                <p>Para añadir esta aplicación a tu pantalla de inicio, sigue estos pasos:</p>
                <ol>
                    <li>Abre el menú de compartir en Safari (icono de cuadrado con flecha hacia arriba).</li>
                    <li>Selecciona "Añadir a la pantalla de inicio".</li>
                </ol>
            </div>
            <div id="loader" class="d-none">
                <img src="https://osuna.cbtpruebas.es/wp-content/uploads/2024/06/logo.png" alt="Cargando...">
                <h6>Revitaliza Osuna</h6>                             
            </div>
        </div>
        <?php echo Osunatheme::get_logos_footer(); ?>
    </div> 
   
</body>
</html>

<?php get_footer(); ?>
