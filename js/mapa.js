jQuery(document).ready(function ($) {
    function loadGoogleMaps(callback) {
        var script = document.createElement('script');
        script.src = 'https://maps.googleapis.com/maps/api/js?key=' + themeOptions.google_maps_api_key + '&callback=' + callback;
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }

    function initMap() {

        map = new google.maps.Map(document.getElementById('dynamic-map'), {
            zoom: 17,
            center: {
                lat: parseFloat(themeOptions.latitude),
                lng: parseFloat(themeOptions.longitude)
            }
        });
        
        var markers = ajaxmapa.markers
        var mi_icon = {
            url: themeOptions.mimarcador, // URL del icono personalizado
            scaledSize: new google.maps.Size(50, 50), // Tamaño escalado del icono
            origin: new google.maps.Point(0, 0), // Origen del icono (0,0)
            anchor: new google.maps.Point(25, 25) // Anclaje del icono (centro)
        };
        personMarker = new google.maps.Marker({
            map: map,
            icon: mi_icon
        })

        var icon_casita = {
            url: themeOptions.iconcasita, // URL del icono personalizado

        };

        markers.forEach(function (marker) {
            var position = {
                lat: parseFloat(marker.lat),
                lng: parseFloat(marker.lng)
            };
            var mapMarker = new google.maps.Marker({
                position: position,
                map: map,
                title: marker.title,
                icon: icon_casita
            });

            var infowindow = new google.maps.InfoWindow({
                content: marker.content
            });

            mapMarker.addListener('click', function () {
                infowindow.open(map, mapMarker);
            });
        });
    }

    
    async function initMap2() {
        
        const { Map, InfoWindow } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary(
            "marker",
        );
        map = new google.maps.Map(document.getElementById("dynamic-map"), {
            zoom: 12,
            center: {
                lat: parseFloat(themeOptions.latitude),
                lng: parseFloat(themeOptions.longitude)
            },
            mapId: "OSUNA__MAP_ID",
        });
        const infoWindow = new google.maps.InfoWindow({
            content: "",
            disableAutoPan: true,
        });
        var mi_icon = {
            url: themeOptions.mimarcador, // URL del icono personalizado
            scaledSize: new google.maps.Size(50, 50), // Tamaño escalado del icono
            origin: new google.maps.Point(0, 0), // Origen del icono (0,0)
            anchor: new google.maps.Point(25, 25) // Anclaje del icono (centro)
        };
        personMarker = new google.maps.Marker({
            map: map,
            icon: mi_icon
        })
        // Create an array of alphabetical characters used to label the markers.
        const labels = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        var locations = ajaxmapa.markers

        var markers = locations.map((marcador, i) => {

            const label = labels[i % labels.length];
            const pinGlyph = new google.maps.marker.PinElement({
                glyph: label,
                glyphColor: "white",
            });
            var position = {
                lat: parseFloat(marcador.lat),
                lng: parseFloat(marcador.lng)
            };
            const marker = new google.maps.marker.AdvancedMarkerElement({
                position,
                content: pinGlyph.element,
                title: marcador.title
            });

            // markers can only be keyboard focusable when they have click listeners
            // open info window when marker is clicked
            marker.addListener("click", () => {
                infoWindow.setContent(marcador.content);
                infoWindow.open(map, marker);
            });

            return marker;
        });

        // Add a marker clusterer to manage the markers.
        //new MarkerClusterer({ markers, map });
        new markerClusterer.MarkerClusterer({ markers, map });
        get_marcadores()
    }


    window.initMap2 = initMap2;
    loadGoogleMaps('initMap2');
    var map = null;
    //var intervalo = setInterval(get_marcadores, 25000);
    var personMarker = null
    
    

    function get_marcadores() {

        const options = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0,
        };

        navigator.geolocation.getCurrentPosition(posiciona, error_posi, options);
    }

    function posiciona(pos) {
        const crd = pos.coords;
        let lat = crd.latitude
        let lng = crd.longitude
        var posicion = {
            lat: lat,
            lng: lng
        }
        personMarker.setPosition(posicion)
        map.setCenter(posicion);

    }

    function error_posi(err) {
        console.warn(`ERROR(${err.code}): ${err.message}`);
    }

    
});
