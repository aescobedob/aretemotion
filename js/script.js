$(document).ready(function() {

	var max_num_pantallas = 8;

	var lista_sets = $(".lista-categorias");


	lista_sets.sortable({
		handle: ".reorder-set-handle", 
		//cursorAt: {bottom: 100}, 
		cursor: "move", 
		axis: "y", 
		distance: 10, 
		forceHelperSize: true,
		helper: "clone",
		opacity: 0.5,
		update: function () { guardaOrdenSets() }
	});

	function guardaOrdenSets() {
		var orden_set = 1;
	    lista_sets.find(".set-edit-item").each(function (i) {
	        $(this).attr("data-orden-set", orden_set);
	        $(this).find(".edit-set-link").attr("data-orden-set", orden_set);
	        console.log("orden: " + orden_set);
	        console.log("this: " + $(this).attr("class"));
	        orden_set++;
	    });
	}

	



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
		var nueva_categoria = $("<div class='cat-edit-item'><a href='/editcat?catid=" + last_inserted_id +"' data-id-categoria='"+ last_inserted_id +"'>" + cat_nombre + "</a> (<a href='#' class='del-cat-link' data-id-categoria='"+ last_inserted_id +"'>Borrar</a>)</li>");

		var cat_item_clone = $(".cat-edit-item-clone");
        var cat_item;

		// Agregamos la categoria del clon del DOM
        cat_item = cat_item_clone.clone();
        cat_item.removeClass("cat-edit-item-clone");
        cat_item.find(".cat-link").text(cat_nombre)
        cat_item.attr("data-id-categoria", last_inserted_id);
        cat_item.find(".cat-link").attr("href", "/editcat?catid=" + last_inserted_id);
        cat_item.find(".del-cat-link").attr("data-id-categoria", last_inserted_id);
        cat_item.find(".cat-link").attr("data-id-categoria", last_inserted_id);
        $(".lista-categorias").append(cat_item.fadeIn());


		//$(".lista-categorias").append(nueva_categoria);
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
                    cat.closest(".cat-edit-item").fadeOut("fast", function() { $(this).remove();});
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
	    set_id = set.data("set-id");
	    console.log(set_id);
	    set_collapsed = set.closest(".set-edit-item").attr("data-set-collapsed");
	    console.log("set_collapsed: " + set_collapsed);
	    num_pantallas_set = countPantallasSet(set);

	    var set_loaded = set.closest(".set-edit-item").data("set-loaded");

	    //Si el set ya está cargado y está colapsado su elemento de pantallas
	    if(set_collapsed == "1") {
	    	set.closest(".set-edit-item").find(".set-edit-item-wrapper").fadeIn();
	    	set.closest(".set-edit-item").attr("data-set-collapsed", "0");
	    } else {
	    	set.closest(".set-edit-item").find(".set-edit-item-wrapper").fadeOut();
	    	set.closest(".set-edit-item").attr("data-set-collapsed", "1");
	    }

	    


	    // Si no se ha cargado el set, hay que hacer el request para cargarlo
	    if(set_loaded != "1") { 
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
	                    console.log("num_pantallas_set: " + data.num_pantallas_set);
	                    //console.log(data.datos["0"]);
	                    //console.log(data.datos["0"].sTitulo);

	                    var num_pantallas_set = parseInt(data.num_pantallas_set);
	                    console.log("num_pantallas_set parseint: " + num_pantallas_set);


	                    console.log("div: " + set.closest(".set-edit-item").find(".edit-set-pantallas-list").text());

	                    // Conteo para verificar numero de pantallas al final del proceso
                    	var pantalla_count = 0;

	                    // Si en la DB no hay pantallas en ese set, 
	                    if(num_pantallas_set == "0") {
		                    // Si al hacer click en el set, no tiene pantallas, agregar una vacía por default
						    if(set_loaded != "1" && num_pantallas_set == 0) {
						    	// Agregamos una pantalla y mostramos el botón de +, con el título y url vacías
						    	// Agregar pantalla automatico por ahora deshabilitado, descomentar la siguiente linea:
						    	//agregaPantallaASet(set, "", "");
		                    	set.closest(".set-edit-item").find(".btn-add-pantalla-row").fadeIn();
						    }
	                    } else {
                    		var pantalla_edit_item_clone = $(".pantalla-edit-item-clone");
                    		var pantalla_edit_item;
	                    	$.each(data.datos, function(key, pantalla) {
	                    		// Clonamos el elemento de una pantalla y lo agregamos
		                    	pantalla_edit_item = pantalla_edit_item_clone.clone();
		                    	pantalla_edit_item.removeClass("pantalla-edit-item-clone");
		                    	pantalla_edit_item.find(".pantalla-titulo-edit").val(pantalla.sTitulo);
		                    	pantalla_edit_item.find(".pantalla-url-edit").val(pantalla.sURL);
		                    	pantalla_edit_item.attr("data-set-id", pantalla.idSet);
		                    	pantalla_edit_item.attr("data-pantalla-id", pantalla.ID);
		                    	pantalla_edit_item.attr("data-orden-pantalla", pantalla_count+1);
		                    	set.closest(".set-edit-item").find(".edit-set-pantallas-list").append(pantalla_edit_item.fadeIn());

		                    	console.log("pantalla: " + pantalla.sURL);

		                    	pantalla_count++;
	                    	});
	                    }


	                    // Declaramos el set como cargado para no volver a hacer el request
	                    set.closest(".set-edit-item").attr("data-set-loaded", "1");
	                    // Mostramos la lista de pantallas del set cargado
	                    set.closest(".set-edit-item").find(".edit-set-pantallas-list").fadeIn();

	                    
	                    // Si el número máximo de pantallas no se ha alcanzado, mostrar un botón para permitir agregar más
	                    var pantalla_add_btn;
		                var pantalla_add_btn_clone = $(".btn-add-pantalla-row-clone");
	                    if(pantalla_count < max_num_pantallas) {
		                    //pantalla_add_btn = pantalla_add_btn_clone.clone();
	                    	//pantalla_add_btn.removeClass("btn-add-pantalla-row-clone");
		                    set.closest(".set-edit-item").find(".btn-add-pantalla-row").fadeIn();
		                    //set.closest(".set-edit-item").find(".set-edit-item-wrapper").append(pantalla_add_btn.fadeIn());
	                    	console.log("pantalla count: "+ pantalla_count);
	                    }
	                    

	                    // Hacemos sortable la lista de pantallas
	                    set.closest(".set-edit-item").find(".edit-set-pantallas-list").sortable({ 
	                    	containment: "parent", 
	                    	handle: ".reorder-pantalla-handle",
	                    	cursor: "move",
	                    	axis: "y", 
                    		distance: 10,
                    		forceHelperSize: true,
                    		helper: "clone",
                    		opacity: 0.5,
                    		update: guardaOrdenPantallas 
	                    });
	                },
	                error: function(jqxhr, status, e) {
				    	//console.log(jqxhr);
				    	console.log("status: " + status);
				    	console.log("error: " + e.message);
				    	console.log(jqxhr);
				  	}
	              });
		} else {
			console.log("set ya cargado.");
		}
	}

	function guardaOrdenPantallas(event, ui) {
		var orden_pantalla = 1;
	    $(this).find(".pantalla-edit-item").each(function (i) {
	        $(this).attr("data-orden-pantalla", orden_pantalla);
	        $(this).find(".edit-set-link").attr("data-orden-set", orden_pantalla);
	        console.log("orden pantalla: " + orden_pantalla);
	        console.log("this: " + $(this).attr("class"));
	        orden_pantalla++;
	    });

	    console.log(event);
	    console.log(ui);
	}

	// Agrega una pantalla al elemento del dom correspondiente, con su Título y URL
	function agregaPantallaASet(set, sTitulo, sURL) {

		var pantalla_edit_item_clone = $(".pantalla-edit-item-clone");
	    var pantalla_edit_item;

	    pantalla_edit_item = pantalla_edit_item_clone.clone();
    	pantalla_edit_item.removeClass("pantalla-edit-item-clone");
    	pantalla_edit_item.find(".pantalla-titulo-edit").val(sTitulo);
    	pantalla_edit_item.find(".pantalla-url-edit").val(sURL);
        set.closest(".set-edit-item").find(".edit-set-pantallas-list").append(pantalla_edit_item.fadeIn());

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
	    set_orden_next = $(".btn-add-set").data("orden-set-next");
	    btn_add_set = $(this);
	    set_inicial = 0;

	    $.ajax({
                url: "/addset",
                data: { id_categoria: cat_id, orden_set: set_orden_next, set_inicio: set_inicial },
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
                    var new_set_id = data.last_inserted_id;

                    // Clonamos el elemento del set y le asignamos las propiedades correspondientes

                    set_edit_item = set_edit_item_clone.clone();

                    set_edit_item.find(".edit-set-link").attr("data-set-id", new_set_id);
                    set_edit_item.find(".edit-set-link").attr("data-orden-set", set_orden_next);
                    set_edit_item.find(".edit-set-link").attr("href", "/editset?setid=" + new_set_id);
                    set_edit_item.find(".edit-set-link").text("Set " + set_orden_next);
                    set_edit_item.find(".del-set-link").attr("data-set-id", new_set_id);
                    set_edit_item.attr("data-orden-set", set_orden_next);
                    set_edit_item.addClass("set-edit-item");
                    set_edit_item.removeClass("set-edit-item-clone");

                    //+1 al botón de agregar set
                    btn_add_set.attr("data-orden-set-next", set_orden_next + 1);

                    // Lo agregamos al DOM en donde va, antes del botón de agregar
                    //btn_add_set.parent().before(set_edit_item);
                    $(".lista-categorias").append(set_edit_item);

                    // Finalmente mostramos el nuevo elemento
                    set_edit_item.fadeIn();


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

	// Acción de borrar un set (click al botón de X )
	$(document).on("click", ".del-set-link", delSet);

	function delSet(event) {
		event.preventDefault();
	    event.stopPropagation();

	    btn_del_set = $(this);
	    set_id = btn_del_set.data("set-id");
	    console.log("Set id a enviar: " + set_id);
	    btn_add_set = $(".btn-add-set");
	    set_orden_next = btn_add_set.data("orden-set-next") - 1;


	    $.ajax({
                url: "/delset",
                data: { id_set: set_id },
                beforeSend: function() {
                 console.log("antes enviar instruccion de borrar set al server");
                },
                dataType: "json",
                type: "POST",
                success: function(data){

                    // Quitamos el elemento de la lista
                    btn_del_set.closest(".set-edit-item").fadeOut("fast", function() { $(this).remove();});

                    //-1 al botón de agregar set
                    btn_add_set.attr("data-orden-set-next", set_orden_next);

                    //Mensajes de control
                    console.log("Set borrado OK");
                    console.log(data);
                    console.log("Query status: " + data.query_status);

                },
                error: function(jqxhr, status, e) {
			    	//console.log(jqxhr);
			    	console.log(status);
			    	console.log(e.message);
			  	}
              });   
	}

	// Acción de agregar una pantalla (click al + en pantallas )
	$(document).on("click", ".btn-add-pantalla", addPantalla);

	function addPantalla(event) {
		event.preventDefault();
	    event.stopPropagation();


	    btn_add_pantalla = $(this);
	    //set_id = btn_del_set.data("set-id");

	    //var num_pantallas_set = btn_add_pantalla.closest(".set-edit-item").find(".edit-set-pantallas-list .pantalla-edit-item").length;
	    var num_pantallas_set = countPantallasSet(btn_add_pantalla);

	    console.log("num_pantallas_set:  " + num_pantallas_set);

	    if(num_pantallas_set < max_num_pantallas) {
	    	var pantalla_edit_item_clone = $(".pantalla-edit-item-clone");
            var pantalla_edit_item;

            var set_id = $(this).closest(".set-edit-item").attr("data-set-id");
            var pantalla_titulo = "";
            var pantalla_url = "";
            var pantalla_orden = num_pantallas_set+1;

            console.log("set id id id: " + set_id);
            console.log("orden sss: " + pantalla_orden);

             $.ajax({
                url: "/addpantalla",
                data: { id_set: set_id, p_titulo: pantalla_titulo, p_url: pantalla_url, p_orden: pantalla_orden },
                beforeSend: function() {
                	// hacer algo antes de enviar?
                },
                dataType: "json",
                type: "POST",
                success: function(data){
                    //Mensajes de control
                    console.log("Pantalla agregada OK");
                    var pantalla_id = data.last_inserted_id;
                    console.log("Last p id: " + data.last_inserted_id);

                    // Agregamos la pantalla del clon del DOM
		            pantalla_edit_item = pantalla_edit_item_clone.clone();
		            pantalla_edit_item.removeClass("pantalla-edit-item-clone");
		            pantalla_edit_item.attr("data-set-id", set_id);
		            pantalla_edit_item.attr("data-pantalla-id", pantalla_id);
		            pantalla_edit_item.attr("data-orden-pantalla", pantalla_orden);
		            btn_add_pantalla.closest(".set-edit-item").find(".edit-set-pantallas-list").append(pantalla_edit_item.fadeIn());
		            if(num_pantallas_set == (max_num_pantallas -1)) {
			    		btn_add_pantalla.hide();
			    	}
                },
                error: function(jqxhr, status, e) {
			    	console.log(status);
			    	console.log(e.message);
			  	}
              });   
	    }
	}

	// Función recibe elemento del DOM en el que se encuentran las pantallas

	function countPantallasSet(set) {
		return set.closest(".set-edit-item").find(".edit-set-pantallas-list .pantalla-edit-item").length;
	}


	// Acción de borrar una pantalla de un set (click al + en pantallas )
	$(document).on("click", ".btn-del-pantalla", delPantalla);

	function delPantalla(event) {
		event.preventDefault();
	    event.stopPropagation();

	    btn_del_pantalla = $(this);
	    //set_id = btn_del_set.data("set-id");

	    var num_pantallas_set = btn_del_pantalla.closest(".set-edit-item").find(".edit-set-pantallas-list .pantalla-edit-item").length;
	    btn_add_pantalla = btn_del_pantalla.closest(".set-edit-item").find(".btn-add-pantalla");

	    pantalla_id = $(this).closest(".pantalla-edit-item").attr("data-pantalla-id");

	    console.log("pantalla_id: " + pantalla_id);
	    console.log("num_pantallas_set:  " + num_pantallas_set);

	    $.ajax({
                url: "/delpantalla",
                data: { id_pantalla: pantalla_id },
                beforeSend: function() {
                 //console.log("antes enviar instruccion de borrar pantalla al server");
                },
                dataType: "json",
                type: "POST",
                success: function(data){

                    if(num_pantallas_set == max_num_pantallas) {
				    	btn_add_pantalla.fadeIn();
				    }

                    // Quitamos el elemento de la lista
				    btn_del_pantalla.closest(".pantalla-edit-item").fadeOut("fast", function() { $(this).remove();});

                    //Mensajes de control
                    console.log("Pantalla borrada OK");
                    //console.log(data);
                    console.log("affected_rows: " + data.affected_rows);

                },
                error: function(jqxhr, status, e) {
			    	//console.log(jqxhr);
			    	console.log(status);
			    	console.log(e.message);
			  	}
              }); 
	}


	// Acción de borrar una pantalla de un set (click al + en pantallas )
	$(document).on("click", ".btn-guardar", guardar);

	function guardar(event) {
		event.preventDefault();
	    event.stopPropagation();

	    var pantalla_count = 0;

	    var pantallas_update = [];
	    var pantallas_insert = [];


	    var orden_set = 1;
	    $(".set-edit-item").each( function (i) {
	    	//console.log("set id:" + $(this).attr("data-set-id"));
	    	//console.log("class:" + $(this).attr("class"));
	    	var pantalla_edit_item = $(this).find(".pantalla-edit-item");

	    	$(this).attr("data-orden-set", orden_set);

	    	if($(this).find(".edit-set-pantallas-list").find(".pantalla-edit-item").length > 0) {
	    		console.log("OK now sort!:");
	    		$(this).find(".edit-set-pantallas-list").sortable('option', 'update');
	    		$(this).find(".edit-set-pantallas-list").trigger('sortupdate');

	    		// ordenar pantallas
	    	}
		

	    	var orden_pantalla = 1;
	    	pantalla_edit_item.each( function (j) {
	    		var pantalla = $(this);
	    		if(pantalla_edit_item.length > 0 ) {
	    			console.log("class: " + pantalla.attr("class"));

	    			// Arreglar el orden de las pantallas
	    			$(this).attr("data-orden-pantalla", orden_pantalla);
		         	orden_pantalla++;

					pantalla_id = pantalla.attr("data-pantalla-id");
					pantalla_set_id = pantalla.attr("data-set-id");
					pantalla_orden = pantalla.attr("data-orden-pantalla");
	    			pantalla_titulo = pantalla.find(".pantalla-titulo-edit").val();
	    			pantalla_url = pantalla.find(".pantalla-url-edit").val();

	    			item = {};

	    			item["id"] = pantalla_id;
	    			item["titulo"] = pantalla_titulo;
	    			item["url"] = pantalla_url;
	    			item["set_id"] = pantalla_set_id;
	    			item["orden"] = pantalla_orden;

	    			console.log("id pantalla: " + pantalla_id);

	    			if(pantalla_id == "0") {
	    				if(item["titulo"] != "" && item["url"] != "")
	    				pantallas_insert.push(item);
	    			} else {
	    				pantallas_update.push(item);
	    			}

	    			pantalla_count++;

	    			// Ciclo de todas las pantallas, falta checar que los campos no estén vacíos y enviar cada pantalla a la DB y ya!

	    			// enviar pantallas_insert y pantallas_update a la db y ya! (checar los que no estén vacíos)
	    		}
	    		
	    	});
	    });

	    console.log("pantalla_count: " + pantalla_count);
	    console.log("pantallas_insert: ");
	    console.log(pantallas_insert);
	    console.log("pantallas_update: ");
	    console.log(pantallas_update);


	    $.ajax({
                url: "/editpantallas",
                data: {p_insert: JSON.stringify(pantallas_insert), p_update: JSON.stringify(pantallas_update) },
                beforeSend: function() {
                 console.log("antes enviar instruccion de add pantalla set al server");
                },
                dataType: "json",
                type: "POST",
                success: function(data){

                    //Mensajes de control
                    console.log("Pantallas guardadas OK");
                    console.log(data);
                    console.log("p_insert: " + data.p_insert);
                    console.log("p_update: " + data.p_update);
                    console.log("Last inserted id: " + data.last_inserted_id);
                    console.log("Query status: " + data.query_status);

                },
                error: function(jqxhr, status, e) {
			    	//console.log(jqxhr);
			    	console.log(status);
			    	console.log(e.message);
			  	}
              });   






	    var sorted = lista_sets.sortable( "serialize", { key: "sort" } );
	    var sortedArray = lista_sets.sortable( "toArray", "data-orden-set" );
		console.log(sorted);
		console.log(sortedArray);

	}

	function showError(error) {

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