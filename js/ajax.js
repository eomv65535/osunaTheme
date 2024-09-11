    jQuery(document).ready(function($) {
        $('.megusta-button').on('click', function() {
            var botoncito = $(this)
            var post_id = botoncito.data('post-id');
            var has_megusta = botoncito.hasClass('megusta');
            $.ajax({
                url: mi_ajax.ajaxurl,
                type: 'post',
                data: {
                    action: 'handle_megusta',
                    post_id: post_id
                },
                success: function(response) {
                    if (response.success) {
                        if (has_megusta) {
                            botoncito.removeClass('megusta');
                        }
                        else {
                            botoncito.addClass('megusta');
                        }
                    }
                }
            });
        });

        $('.megusta-button2').on('click', function() {
            var botoncito = $(this)
            var post_id = botoncito.data('post-id');
            var has_megusta = botoncito.hasClass('megusta');
            $.ajax({
                url: mi_ajax.ajaxurl,
                type: 'post',
                data: {
                    action: 'handle_megusta',
                    post_id: post_id
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                }
            });
        });

        $('#um_account_submit_general').on('click', function(e) {
           e.preventDefault();
            $.ajax({
                url: mi_ajax.ajaxurl,   
                type: 'post',
                data: $('#micuentaform').serialize(),
                success: function(response) {
                    
                    if (response.success) {
                       $('#micuentaform').submit();
                    }
                }   
            });
        });
    });

    
    function posiciona_usr(pos) {
        const crd = pos.coords;
        let _lat_usr = crd.latitude
        let _lng_usr = crd.longitude  
        jQuery.ajax({
            url: mi_ajax.ajaxurl,
            type: 'post',
            dataType: 'html',
            data: {
                action: 'get_productos_cerca',
                lat: _lat_usr,
                lng: _lng_usr
            },
            success: function(response) {
                jQuery('#contenedor-productos-cercanos').html(response);
                jQuery('#productosCarousel').carousel();
               
            }
        });      
    }

    function error_posi_usr(err) {
        console.warn(`ERROR(${err.code}): ${err.message}`);
    }
    
    function trae_comercios_cercanos(){
        const options_usr = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0,
        };
        navigator.geolocation.getCurrentPosition(posiciona_usr, error_posi_usr, options_usr);        
    }
    
      