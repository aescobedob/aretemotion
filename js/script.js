$(document).ready(function() {


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
                    //response(data);
                    //$(".search-preloader").hide();
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
	    console.log("click!");
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
                    //response(data);
                    //$(".search-preloader").hide();
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