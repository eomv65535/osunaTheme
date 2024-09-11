jQuery(document).ready(function ($) {
    jQuery('input[data-key="fec_nac"]').attr('type', 'date').addClass('fechitas');

    $(".flechita").on("click", function () {
        if ($("#cat_oculta").hasClass("categorias-ocultas")) {
            $("#cat_oculta").removeClass("categorias-ocultas").addClass("categorias-visibles");
            $(".flechita").css("transform", "rotate(180deg)");
        } else {
            $("#cat_oculta").removeClass("categorias-visibles").addClass("categorias-ocultas");
            $(".flechita").css("transform", "rotate(0deg)");
        }

    });

    $('#open-sidebar').click(() => {
        // add class active on #sidebar
        $('#sidebar').addClass('active');
        // show sidebar overlay
        $('#sidebar-overlay').removeClass('d-none');
    });

    $('#sidebar-overlay').click(function () {
        // add class active on #sidebar
        $('#sidebar').removeClass('active');
        // show sidebar overlay
        $(this).addClass('d-none');
    });

    $('#eliminar-thumbnail').click(function () {
        var inputLogo = document.createElement("input");
        var imagenId = this.getAttribute("data-id");
        inputLogo.setAttribute("type", "hidden");
        inputLogo.setAttribute("name", "eliminar_thumbnail");
        inputLogo.setAttribute("value", imagenId);
        this.parentNode.appendChild(inputLogo);
        this.closest(".mb-3").style.display = "none";
    });

    $('#eliminar-icono').click(function () {
        var inputLogo = document.createElement("input");
        var imagenId = this.getAttribute("data-id");
        inputLogo.setAttribute("type", "hidden");
        inputLogo.setAttribute("name", "eliminar_icono");
        inputLogo.setAttribute("value", imagenId);
        this.parentNode.appendChild(inputLogo);
        this.closest(".mb-3").style.display = "none";
    });

    $('#eliminar-logo').click(function () {
        var inputLogo = document.createElement("input");
        var imagenId = this.getAttribute("data-id");
        inputLogo.setAttribute("type", "hidden");
        inputLogo.setAttribute("name", "eliminar_logo");
        inputLogo.setAttribute("value", imagenId);
        this.parentNode.appendChild(inputLogo);
        this.closest(".mb-3").style.display = "none";
    });

    $('.eliminar-imagen').click(function () {
        var inputImagen = document.createElement("input");
        var imagenId = this.getAttribute("data-id");        
        inputImagen.setAttribute("type", "hidden");
        inputImagen.setAttribute("name", "eliminar_imagen[]");
        inputImagen.setAttribute("value", imagenId);
        this.parentNode.appendChild(inputImagen);
        this.closest(".mb-3").style.display = "none";

    });
    $('#comercios_campana optgroup').on('click', function (e) {
        // Solo continuar si se hace clic directamente en el <optgroup>
        if (e.target.tagName.toLowerCase() === 'optgroup') {
            e.preventDefault(); // Prevenir la acción por defecto del evento de clic

            // Seleccionar todos los hijos <option> del <optgroup> clicado
            $(this).find('option').prop('selected', !$(this).find('option').prop('selected'));
        }
    });

    $('#comercios_campana option').on('click', function (e) {            
        e.stopPropagation(); 
    });
  
    /*document.getElementById('btn-submit-participacion').addEventListener('submit', function() {
        document.getElementById('btn-submit-participacion').disabled = true;
    });*/
    
});

function borrar_busqueda(cual) {
    jQuery("#" + cual).val("")
    jQuery("#forma_busq_comer").submit()
}   

function abre_modal_ticket(url){
    jQuery(".modal-body img").attr("src", url);
    jQuery(".modal-body a").attr("href", url);
    jQuery("#modal_ticket").modal("show");
}

function getRandomRgb() {
    const r = Math.floor(Math.random() * 120); // Valor entre 0 y 128
    const g = Math.floor(Math.random() * 110);
    const b = Math.floor(Math.random() * 100);
    return `rgb(${r}, ${g}, ${b},0.8)`;
}
