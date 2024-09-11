<?php
class Correo {
    
    public static function cuerpo_correo($contenido){
    $reto= '
    <!doctype html>
    <html lang="es">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Revitaliza Osuna</title>
        <style media="all" type="text/css">
            @media all {
                .btn-primary table td:hover {
                    background-color: #ec0867 !important;
                }

                .btn-primary a:hover {
                    background-color: #ec0867 !important;
                    border-color: #ec0867 !important;
                }
            }
            @media only screen and (max-width: 640px) {
                .main p,
                .main td,
                .main span {
                    font-size: 16px !important;
                }

                .wrapper {
                    padding: 8px !important;
                }

                .content {
                    padding: 0 !important;
                }

                .container {
                    padding: 0 !important;
                    padding-top: 8px !important;
                    width: 100% !important;
                }

                .main {
                    border-left-width: 0 !important;
                    border-radius: 0 !important;
                    border-right-width: 0 !important;
                }

                .btn table {
                    max-width: 100% !important;
                    width: 100% !important;
                }

                .btn a {
                    font-size: 16px !important;
                    max-width: 100% !important;
                    width: 100% !important;
                }
            }
            @media all {
                .ExternalClass {
                    width: 100%;
                }

                .ExternalClass,
                .ExternalClass p,
                .ExternalClass span,
                .ExternalClass font,
                .ExternalClass td,
                .ExternalClass div {
                    line-height: 100%;
                }

                .apple-link a {
                    color: inherit !important;
                    font-family: inherit !important;
                    font-size: inherit !important;
                    font-weight: inherit !important;
                    line-height: inherit !important;
                    text-decoration: none !important;
                }

                #MessageViewBody a {
                    color: inherit;
                    text-decoration: none;
                    font-size: inherit;
                    font-family: inherit;
                    font-weight: inherit;
                    line-height: inherit;
                }
            }
    </style>
    </head>
    <body style="font-family: Helvetica, sans-serif; -webkit-font-smoothing: antialiased; font-size: 16px; line-height: 1.3; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; background-color: #ffffff; margin: 0; padding: 0;">
        
    <div style="max-width: 560px; padding: 20px; background: #ffffff; border-radius: 15px; margin: 40px auto; font-family: Open Sans,Helvetica,Arial; font-size: 15px; color: #666;">
        <div style="color: #444444; font-weight: normal;">
        <div style="text-align: center;"><img src="https://osuna.cbtpruebas.es/wp-content/uploads/2024/06/logo.png">
        </div>
        <div style="text-align: center; font-weight: 600; font-size: 26px; padding: 10px 0; border-bottom: solid 3px #eeeeee;">Revitaliza Osuna</div>
        <div style="clear: both;"> </div>
        </div>
        <div style="padding: 0 30px 30px 30px; border-bottom: 3px solid #eeeeee;">
        <div style="padding: 30px 0; font-size: 22px; line-height: 40px;">
            <p>'.$contenido.'</p>
        </div>        
        <div style="padding: 20px;">Si tiene algún problema, favor contactarnos a <a style="color: #3ba1da; text-decoration: none;" href="mailto:info@osuna.cbtpruebas.es">info@osuna.cbtpruebas.es</a></div>
        </div>
        <div style="color: #999; padding: 20px 30px;">
        <div>&copy; 2024 - <a style="color: #3ba1da; text-decoration: none;" href="https://osuna.es">Ayuntamiento de Osuna</a> </div>
        </div>
        </div>
    </body>
    </html>';
     return $reto;    
    }
}