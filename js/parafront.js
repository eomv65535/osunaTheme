jQuery(document).ready(function ($) {
    let deferredPrompt;

    // Función para detectar iOS
    function isIos() {
        const userAgent = window.navigator.userAgent.toLowerCase();        
        return /iphone|ipad|ipod/.test(userAgent);
    }

    // Función para detectar Safari en modo independiente
    function isInStandaloneMode() {
        let isInStandaloneMode1=('standalone' in window.navigator)
        let isInStandaloneMode2= window.navigator.standalone !== undefined ? window.navigator.standalone : false;        
        return isInStandaloneMode1 && isInStandaloneMode2        
    }

    // Función para detectar si es un PC
    function isDesktop() {
        const userAgent = window.navigator.userAgent.toLowerCase();
        return !/android|webos|mac|iphone|ipad|ipod|blackberry|iemobile|opera mini/.test(userAgent);
    }

    function showInstallContainer() {
        $('#install-container').removeClass('d-none').addClass('d-block');
    }

    function showLoaderAndRedirect(url) {
        $('#install-container').addClass('d-none');
        $('#ios-instructions').addClass('d-none');
        $('#loader').removeClass('d-none').addClass('d-block');
        setTimeout(() => {
            window.location.href = url;
        }, 2000);
    }
    
    if (isDesktop()) {
        showLoaderAndRedirect('/buscar');
    }

   
    window.addEventListener('beforeinstallprompt', function(event) {
        // Previene que el navegador muestre el prompt automáticamente
        event.preventDefault();
        // Almacena el evento para usarlo más tarde
        deferredPrompt = event;
        
        // Muestra el botón de instalación
        if (!isIos()) {
            showInstallContainer();
        }
    });

    $('#install-button').on('click', function () {
        
        if (deferredPrompt) {
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then(function (choiceResult) {
                if (choiceResult.outcome === 'accepted') {
                    $(".logs").html("PWA fue instalada con éxito<br>");
                    localStorage.setItem('pwa_installed', 'true'); // Marcar la PWA como instalada
                } else {
                    $(".logs").html("PWA no fue instalada<br>");
                }
                deferredPrompt = null;
            });
        }
        else {
            $(".logs").html("No se puede instalar la PWA<br>");
        }
    });

    window.addEventListener('appinstalled', () => {        
        $(".logs").html("PWA fue instalada con éxito"+e+"<br>");
        localStorage.setItem('pwa_installed', 'true'); // Marcar la PWA como instalada
        showLoaderAndRedirect('/buscar');
    });

    
    if (localStorage.getItem('pwa_installed') === 'true') {
        showLoaderAndRedirect('/buscar');
    }
    let instalado= isInStandaloneMode()
    
    if (isIos() && instalado==false) {
        $('#ios-instructions').removeClass('d-none')
    } else if (instalado) {
        showLoaderAndRedirect('/buscar');
    }
    if (!('onbeforeinstallprompt' in window)) {
        if (!isIos()) {
            showLoaderAndRedirect('/buscar');
        }
    }
   /* else
        showInstallContainer();*/
    
});
