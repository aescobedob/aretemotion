$(document).ready(function() {


	$(".add-cat-link").on("click", showAddCatForm);

	function showAddCatForm() {
	    event.preventDefault();
	    event.stopPropagation();
	    $(".add-cat-form").fadeIn();
	  }

	$(".add-cat-button").on("click", addCat);

	function addCat() {
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
		$(".lista-categorias").append("<li><a href='#' data-id-categoria='"+ last_inserted_id +"'>"+ cat_nombre +"</a> (<a href='#' data-id-categoria='"+ last_inserted_id +"'>Borrar</a>)</li>");
	}

	function delCat() {
		// aqui me quede
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