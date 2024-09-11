<section class="row p-4">
    <div class="col-md-12">            
        <article class="p-5 rounded shadow-sm border-left mb-4">
            <?php 
             $id_usuario = get_current_user_id();
             $id_comercio = strval(get_user_meta($id_usuario,'usuario_comercio', true)[0]);
             
             BackComercios::get_comercios_qr($id_comercio); ?>           
        </article>
    </div>
</section>
