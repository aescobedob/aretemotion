$(document).ready(function() {

	var max_num_pantallas = 8;

	// Mostrar form de agregar categoría

	$(document).on("click", ".add-cat-link", showAddCatForm);

	function showAddCatForm() {
	    event.preventDefault();
	    event.stopPropagation();
	    $(".add-cat-form").fadeToggle();
	}



	// Click del botón de + para agregar categoría

	$(document).on("click", ".add-cat-button", addCat);

	function addCat(event) {
	    event.preventDefault();
	    event.stopPropagation();
	    cat_nombre = $(".add-cat-nombre");
	    $.ajax({
                url: "/addcat",
                data: { nombre_categoria: cat_nombre.val() },
                beforeSend: function() {
                 console.log("antes de enviar cat al server");
                 console.log(cat_nombre.val());
                },
                dataType: "json",
                type: "POST",
                success: function(data){
                    console.log("Categoría agregada OK");
                    console.log(data);
                    console.log(data.last_inserted_id);
                    addCatToList(cat_nombre.val(), data.last_inserted_id);

                },
                error: function(jqxhr, status, e) {
			    	//console.log(jqxhr);
			    	console.log(status);
			    	console.log(e.message);
			  	}
              });
	}

	function addCatToList(cat_nombre, last_inserted_id) {
		var nueva_categoria = $("<li><a href='/editcat?catid=" + last_inserted_id +"' data-id-categoria='"+ last_inserted_id +"'>" + cat_nombre + "</a> (<a href='#' class='del-cat-link' data-id-categoria='"+ last_inserted_id +"'>Borrar</a>)</li>");
		$(".lista-categorias").append(nueva_categoria);
	}




	// Click del link de Borrar para borrar categoría

	$(document).on("click", ".del-cat-link", delCat);

	function delCat(event) {
		event.preventDefault();
	    event.stopPropagation();
	    cat = $(this);
	    cat_id = $(this).data("id-categoria");
	    $.ajax({
                url: "/delcat",
                data: { id_categoria: cat_id },
                beforeSend: function() {
                 console.log("antes de enviar cat al server");
                 console.log(cat_id);
                },
                dataType: "json",
                type: "POST",
                success: function(data){
                    console.log("Categoría borrada OK");
                    console.log(data);
                    console.log("Query status: " + data.query_status);
                    // Quitamos el elemento de la lista
                    cat.parent().fadeOut("fast", function() { $(this).remove();});
                    //delCatFromList(cat, data.last_inserted_id);
                },
                error: function(jqxhr, status, e) {
			    	//console.log(jqxhr);
			    	console.log(status);
			    	console.log(e.message);
			  	}
              });
	}

	$(document).on("click", ".edit-set-link", showPantallasInfoEdit);

	function showPantallasInfoEdit(event) {
		event.preventDefault();
	    event.stopPropagation();

	    set = $(this);
	    set_id = $(this).data("id-set");
	    console.log(set_id);

	    var setLoaded = set.parent().data("set-loaded");

	    if(setLoaded != "1") { 
		    $.ajax({
	                url: "/listpants",
	                data: { id_set: set_id },
	                beforeSend: function() {
	                 console.log("antes de enviar set al server");
	                 console.log(set_id);
	                },
	                dataType: "json",
	                type: "GET",
	                success: function(data){
	                    console.log("Pantallas loaded OK");
	                    console.log(data);
	                    console.log(data.datos);
	                    console.log(data.datos["0"]);
	                    console.log(data.datos["0"].sTitulo);
	                    // Quitamos el elemento de la lista
	                    //cat.parent().fadeOut("fast", function() { $(this).remove();});
	                    //delCatFromList(cat, data.last_inserted_id);

	                    // Agregamos las pantallas para que sean editadas

	                    //set.parent().append("<div class='edit-set-pantallas-list col-md-12'></div>");

	                    console.log("div: " + set.closest(".set-edit-item").find(".edit-set-pantallas-list").text());

	                    var pantalla_edit_item = $(".pantalla-edit-item-clone");
	                    var pantalle_edit_item_clone;

	                    var pantalla_count = 0;

	                    $.each(data.datos, function(key, pantalla) {
	                    	//set.parent().find(".edit-set-pantallas-list").append("<div class='pantalla-edit-item'>"+ pantalla.sTitulo +"</li>");
	                    	pantalla_edit_item_clone = pantalla_edit_item.clone();
	                    	pantalla_edit_item_clone.removeClass("pantalla-edit-item-clone");
	                    	pantalla_edit_item_clone.find(".pantalla-titulo-edit").val(pantalla.sTitulo);
	                    	pantalla_edit_item_clone.find(".pantalla-url-edit").val(pantalla.sURL);
	                    	set.closest(".set-edit-item").find(".edit-set-pantallas-list").append(pantalla_edit_item_clone.fadeIn());


	                    	console.log("pantalla: " + pantalla.sURL);

	                    	pantalla_count++;

	                    });

	                    // Declaramos el set como cargado para no volver a hacer el request
	                    set.parent().attr("data-set-loaded", "1");
	                    // Mostramos la lista de pantallas del set cargado
	                    set.closest(".set-edit-item").find(".edit-set-pantallas-list").fadeIn();
	                    // Si el número máximo de pantallas no se ha alcanzado, mostrar un botón para permitir agregar más
	                    if(pantalla_count < max_num_pantallas) {
	                    	var pantalla_add_btn = $(".btn-add-pantalla-row-clone");
		                    var pantalla_add_btn_clone;
		                    pantalla_add_btn_clone = pantalla_add_btn.clone();
	                    	pantalla_add_btn_clone.removeClass("btn-add-pantalla-row-clone");
		                    set.closest(".set-edit-item").find(".edit-set-pantallas-list").append(pantalla_add_btn_clone.fadeIn());
	                    console.log("pantalla count: "+ pantalla_count);
	                    }
	                },
	                error: function(jqxhr, status, e) {
				    	//console.log(jqxhr);
				    	console.log(status);
				    	console.log(e.message);
				  	}
	              });
		} else {
			console.log("set ya cargado.");
		}
	}


	$(document).on("click", ".edit-cat-name-link", editCatNameChangeToInput);

	function editCatNameChangeToInput(event) {
		event.preventDefault();
	    event.stopPropagation();

	    $(".edit-cat-name").hide();
	    $(".edit-cat-name-link").hide();
	    $(".edit-cat-name-form").fadeIn().focus();
	}



	$(document).on("click", ".btn-cancelar-cat-name", editCatNameChangeToInputCancel);

	function editCatNameChangeToInputCancel(event) {
		event.preventDefault();
	    event.stopPropagation();

	    $(".edit-cat-name-form").hide();
	    $(".edit-cat-name").fadeIn();
	    $(".edit-cat-name-link").fadeIn();
	}



	$(document).on("click", ".btn-guardar-cat-name", editCatNameChangeToInputSave);

	function editCatNameChangeToInputSave(event) {
		event.preventDefault();
	    event.stopPropagation();

	    cat_nombre = $(".edit-cat-name-input").val();
	    cat_id = $(".edit-cat-name-input").data("cat-id");

	    $.ajax({
                url: "/savecatname",
                data: { id_categoria: cat_id, nombre_categoria: cat_nombre },
                beforeSend: function() {
                 console.log("antes de editar cat al server");
                 console.log(cat_id);
                },
                dataType: "json",
                type: "POST",
                success: function(data){
                    console.log("Categoría editada OK");
                    console.log(data);
                    console.log("Query status: " + data.query_status);
                    console.log("Affected rows: " + data.affected_rows);
                    $(".edit-cat-name").text($(".edit-cat-name-input").val()).fadeIn();
                    $(".edit-cat-name-form").hide();
	    			$(".edit-cat-name").fadeIn();
	    			$(".edit-cat-name-link").fadeIn();
                    // Quitamos el elemento de la lista
                    //cat.parent().fadeOut("fast", function() { $(this).remove();});
                    //delCatFromList(cat, data.last_inserted_id);
                },
                error: function(jqxhr, status, e) {
			    	//console.log(jqxhr);
			    	console.log(status);
			    	console.log(e.message);
			  	}
              });

	    
	}


	$(document).on("click", ".btn-add-set", addSet);

	function addSet(event) {
		event.preventDefault();
	    event.stopPropagation();

	    cat_id = $(".edit-cat-name-input").data("cat-id");
	    set_orden_next = $(".btn-add-set").data("orden-set");

	    $.ajax({
                url: "/addset",
                data: { id_categoria: cat_id, orden_set: set_orden_next },
                beforeSend: function() {
                 console.log("antes agregar set al server");
                },
                dataType: "json",
                type: "POST",
                success: function(data){
                    console.log("Set agregado OK");
                    console.log(data);
                    console.log("Query status: " + data.query_status);
                    console.log("Last inserted : " + data.last_inserted_id);

                    var set_edit_item;
                    var set_edit_item_clone = $(".set-edit-item-clone");

                    set_edit_item = set_edit_item_clone.clone();
                    //set_edit_item

                    // Quitamos el elemento de la lista
                    //cat.parent().fadeOut("fast", function() { $(this).remove();});
                    //delCatFromList(cat, data.last_inserted_id);
                },
                error: function(jqxhr, status, e) {
			    	//console.log(jqxhr);
			    	console.log(status);
			    	console.log(e.message);
			  	}
              });

	    
	}






});























/*

<!--
                              ````````                                                              
                        ``.,::::::,,,..``                                                           
                     `.,:;;;:::::,,,,,...`                                                          
                  `.,:;;;::::::::,,,,,,...`                                                         
                `.,;;;;;;:::::::::,,,,,,...`                                                        
              .::''''''''''''';,,,,,,,,,....`                                                       
            `:;;;;;;;;;;;;;'''++':,,,,,,,,..`                                                       
            ;;;;;;;;;;;;;;;;'''',``,,,,,,,...                                                       
            ,::;;;;;;;;;;;;;:,`     ,,,,,,,..                                                       
              ``...,,,...``         `,,,,,,..                                                       
                                     ,,,,,,..`                                                `.`   
                                     ,,,,,,.``                                   `.          `;,    
                                    `,,,,,,.`                                    .;          .`     
                                    .,,,,,,.`     .,:::,.   ```,:,.`  `.,:::,.  ,;',,,,.   .,,,,,`  
                                 `,;:,,,,,,.`    ,+:::::+:  :+':,:';  ;':,,,:+, ,;',,,:.  ;;,,,,:;. 
                              `,;''',:,,,,.`     :,     `+  :;    `+``'      `'  .;      .;      .: 
                           `,:::;;+:::,,,,.      `,;;;;:`'` ::     '`.+:::::::'` ,'      ,;,,,,,,:; 
                         `,::::;;'',::::,.`     `';,,,,:;+  ::       .':::::::,  ,'    . ,;,,,,,,,, 
                       .:::::::;;'::::::,.      `+`      +  ::       .'       .  .;   `' ,:       ` 
                     .:::::::::;':::::::.       `+.``````+  ::       `+.`   `,+` .'`  .' .;`    `,; 
                   .::::::::::;':::::::,         ,+''''+;+` ;:        .+++++++.   :+''+.  ,;;;;;;;` 
                 `:::::::::::;;:::::::,            `````                `````      ```      `````   
               `,:::::::::::;;::::;;:,                                                              
              ,::::::::::::;;:::;;;;,                                                               
            .::::::::::::;;:::;;;;;.                                                                
           ,::::::::::::;;:::;;;;:`                                                                 
         .::::::::::::;;;::;;;;;.                                                                   
        ,::::::::::::;;::;;;;;,`                                                                    
      `::::::::::::;;;:;;;;;,`                                                                      
     .:::::::::::;;;::;;;:,`                                                                        
    ,:::::::::;;;;::;;;:.                                                                           
  `:::::::;;;;;::::::.`                                                                             
 `::::;;;;;;:::::,.`                                                                                
 :;;;;;:::::,,``                                                                                    
 ,::::,,,.``                                                                                        

-->


  */