jQuery(function() {
	if(jQuery("#type_motor").val() == 0){
		clearItems(); 
	}
});

function clearItems(){
	localStorage.clear();
}

function change_type_delivery(){
	if (jQuery("#change_delivery").is(':checked') == true) { 
		jQuery("#divRetirada").removeClass("elementor-col-40");
		jQuery("#divRetirada").addClass("elementor-col-20");
		jQuery(".labelDevolucao").attr("style", "");
		jQuery("#divDevolucao").attr("style", "");
		
		jQuery(".elementor-element-641173c").removeClass("elementor-col-40");
		jQuery(".elementor-element-641173c").addClass("elementor-col-20");

		jQuery(".elementor-element-ebd2137").attr("style", ""); 
		jQuery(".elementor-element-d530a0d").attr("style", "");
		jQuery(".elementor-element-2116873").attr("style", "");

		jQuery("#type_delivery").val(1);
	}else{
		jQuery("#divRetirada").removeClass("elementor-col-40");
		jQuery("#divRetirada").addClass("elementor-col-20");
		jQuery(".labelDevolucao").attr("style", "display:none");
		jQuery("#divDevolucao").attr("style", "display:none");

		jQuery(".elementor-element-d530a0d").attr("style", "display:none");
		jQuery(".elementor-element-ebd2137").attr("style", "display:none");
		jQuery(".elementor-element-2116873").attr("style", "display:none");
		
		jQuery(".elementor-element-641173c").removeClass("elementor-col-20");
		jQuery(".elementor-element-641173c").addClass("elementor-col-40");
		
		jQuery(".elementor-element-7ea0443").removeClass("elementor-col-20");
		jQuery(".elementor-element-7ea0443").addClass("elementor-col-40");

		jQuery("#type_delivery").val(0);
	}
}

var temporiza;
jQuery("#localPickup").on("input", function(){

   clearTimeout(temporiza);

   	temporiza = setTimeout(function(){ 
      	search_city("localPickup"); 
   	}, 100);

});

var temporizaDrop;
jQuery("#localDrop").on("input", function(){

   clearTimeout(temporizaDrop);

   	temporizaDrop = setTimeout(function(){ 
      	search_city("localDrop"); 
   	}, 100);

});

function search_city(field){
	var local = jQuery("#"+field).val();  
	if(field == "localPickup"){
		var dataDados = "#dataPickup";
		var typeCity = "pickup";
	}else{
		var dataDados = "#dataDrop";
		var typeCity = "drop";
	}

	var data = { 
        'action': 'get_city', 
        'local': local,
        'token': localStorage.getItem("ACCESS_TOKEN_CARS")
    }; 

    if (local.length >= 3){

    	jQuery(dataDados).attr('style', 'background-color:#fff;min-height: 25px;position:absolute;width: 100%;z-index: 9999;top: 54px;');  

        jQuery(dataDados+' ul').html('<li style="border-bottom: none;padding: 12px 16px;font-size: 12px;font-family:Montserrat, sans-serif;cursor:pointer;list-style:none;background-color: #ddd;color: #000;font-weight: 700;margin: 0;"><img src="https://media.tenor.com/images/a742721ea2075bc3956a2ff62c9bfeef/tenor.gif" style="height: 22px;margin-right: 5px;position:absolute;"> <span style="margin-left: 34px;">Buscando resultados...</span></li>');
 
	    jQuery.ajax({ 
	        url: jQuery("#url_ajax").val(), 
	        type: 'post', 
	        data: data, 
	        success : function( resposta ) { 
	            var json = jQuery.parseJSON(resposta.slice(0,-1));
	            var retorno = ""; 

	            /* AEROPORTOS */
	            	retorno += "<li style='border-bottom: none;padding: 12px 16px;font-size: 12px;font-family: \"Montserrat\", sans-serif;cursor:pointer;list-style:none;background-color: #ddd;color: #000;font-weight: 700;'><i class='fa fa-plane' style='margin-right:6px'></i> AEROPORTOS</li>";

	            	var contadorAirport = 0;
	            	jQuery(json).each(function(i, item) { 
	            		if (typeof item.relationships.airport !== "undefined" && contadorAirport < 5 ) { 
	            			var store = item.attributes.store.toLowerCase();
	            			retorno += "<li style='border-bottom: none;padding: 6px 16px;font-size: 13px;font-family: \"Montserrat\", sans-serif;cursor:pointer;list-style:none;font-weight: 600;color: #696969;    text-transform: capitalize;' onclick=\"set_city('"+typeCity+"', '"+item.attributes.allAddress+"', '"+item.attributes.allName+"', '"+item.attributes.email+"', '"+item.attributes.latitude+"', '"+item.attributes.longitude+"', '"+item.attributes.phone+"', '"+item.relationships.airport.data.id+"', '"+dataDados+"')\"  style='line-height: 20px;font-size: 14px;' id='sigla'>"+item.attributes.allName+"</li>";
	            			
	            			contadorAirport ++;
	            		}
	            	});
	            /* AEROPORTOS */

	            /* CIDADES */ 
	            	retorno += "<li style='border-bottom: none;padding: 12px 16px;font-size: 12px;font-family: \"Montserrat\", sans-serif;cursor:pointer;list-style:none;background-color: #ddd;color: #000;font-weight: 700;'><i class='fas fa-city' style='margin-right:6px'></i> LOJAS</li>"; 

	            	var contador = 0;
	            	jQuery(json).each(function(i, item) { 
	            		if (typeof item.relationships.airport == "undefined" && contador < 5 ) { 
	            			var store = (item.attributes.store+', '+item.attributes.country).toLowerCase();
	            			retorno += "<li style='border-bottom: none;padding: 6px 16px;font-size: 13px;font-family: \"Montserrat\", sans-serif;cursor:pointer;list-style:none;font-weight: 600;color: #696969;    text-transform: capitalize;' onclick=\"set_city('"+typeCity+"', '"+item.attributes.allAddress+"', '"+item.attributes.allName+"', '"+item.attributes.email+"', '"+item.attributes.latitude+"', '"+item.attributes.longitude+"', '"+item.attributes.phone+"', '"+item.relationships.city.data.id+"', '"+dataDados+"')\"  style='line-height: 20px;font-size: 14px;' id='sigla' value='"+item.id+"'>"+item.attributes.allName+"</li>";

	            			contador ++;
	            		}
	            	});
	            /* CIDADES */

	            jQuery(dataDados+' ul').html(retorno);
	        } 
	    }); 

	}else{

		jQuery(dataDados).attr('style', 'background-color:#fff;min-height: 25px;position:absolute;width: 100%;z-index: 9999;top: 54px;');  

        jQuery(dataDados+' ul').html('<li style="border-bottom: none;padding: 12px 16px;font-size: 13px;font-family: \'Montserrat\', sans-serif;cursor:pointer;list-style:none;color: #e82121;font-weight: 700;">Digite pelo menos 3 letras.</li>');


	}
}

function set_city(typePick, addressStore, nameStore, emailStore, latitudeStore, longitudeStore, phoneStore, idStore, dataDados){

    if(dataDados == "#dataPickup"){
		jQuery("#localPickup").val(nameStore);  

		var dataStorePickup = []; 

		dataStorePickup.push({
	        'idPickup': idStore,  
	        'addressPickup': addressStore,  
	        'namePickup': nameStore,  
	        'mailPickup': emailStore,  
	        'phonePickup': phoneStore,  
	        'latitudPickup': latitudeStore,  
	        'longitudPickup': longitudeStore,  
	    });

	    localStorage.setItem("DATA_STORE_PICKUP_CARS", JSON.stringify(dataStorePickup)); 

	}

    if(typePick == "drop"){  

    	if(jQuery("#localPickup").val() == nameStore){
    		swal({ 
	            title: "É necessário selecionar uma loja diferente para devolução do veículo.", 
	            icon: "warning", 
	        });  
	        return false;
    	}

		var dataStoreDrop = []; 

    	jQuery("#localDrop").val(nameStore);

		dataStoreDrop.push({
	        'idDrop': idStore,  
	        'addressDrop': addressStore,  
	        'nameDrop': nameStore,  
	        'mailDrop': emailStore,  
	        'phoneDrop': phoneStore,  
	        'latitudDropp': latitudeStore,  
	        'longitudDrop': longitudeStore,  
	    });

	    localStorage.setItem("DATA_STORE_DROP_CARS", JSON.stringify(dataStoreDrop)); 
    }

	jQuery(dataDados).attr('style', 'display:none');   
}

function set_search_cars(){

	var typeDelivery = jQuery("#type_delivery").val(); 

	var dataSearch = [];  

	dataSearch.push({
        'typeDelivery': jQuery("#type_delivery").val(),  
        'dataStorePickup': localStorage.getItem("DATA_STORE_PICKUP_CARS"),  
        'dataPickup': localStorage.getItem("CHECKIN_CARS"),  
        'timePickup': jQuery("#selectVehicleRetirada").val(),  
        'dataStoreDrop': localStorage.getItem("DATA_STORE_DROP_CARS"),  
        'dataDrop': localStorage.getItem("CHECKOUT_CARS"),  
        'timeDrop': jQuery("#selectVehicleDevolucao").val()  
    });

	localStorage.setItem("DATA_SEARCH_CARS", JSON.stringify(dataSearch)); 

    window.location.href = '/offers-cars/';

}