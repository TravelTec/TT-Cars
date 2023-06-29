jQuery(function() { 
    var url_atual = window.location.href;

    if(url_atual.indexOf("/order-cars/") != -1){
    	moment.locale('pt-br');
    	setInfoOrderCars();
    }
});

function setInfoOrderCars(){
	var orderSelected = JSON.parse(localStorage.getItem("SELECTED_ORDER_CARS")); 
	var dataSearch = JSON.parse(localStorage.getItem("DATA_SEARCH_CARS")); 
	var orderAccepted = JSON.parse(localStorage.getItem("ORDER_ACCEPTED")); 

	jQuery(".hotel_reserva").html('Operadora '+orderSelected.detailIncluded.vehicleRental[0].carRental);
	jQuery("#checkin_reserva").html(moment(dataSearch[0].dataPickup, 'DD-MM-YYYY').format("ddd[.] DD MMM[.] YYYY")+' às '+dataSearch[0].timePickup);

	var dataPickup = JSON.parse(localStorage.getItem("DATA_STORE_PICKUP_CARS"));
	var infoNamePickup = dataPickup[0].namePickup;
	if(parseInt(dataSearch[0].typeDelivery) == 1){
		var dataDrop = JSON.parse(localStorage.getItem("DATA_STORE_DROP_CARS"));
		var infoDrop = dataDrop[0].addressDrop;
		var infoNameDrop = dataDrop[0].nameDrop;
	}else{
		var dataDrop = JSON.parse(localStorage.getItem("DATA_STORE_PICKUP_CARS"));
		var infoDrop = dataPickup[0].addressPickup;
		var infoNameDrop = dataPickup[0].namePickup;
	}

	jQuery("#endereco_hotel").html(dataPickup[0].addressPickup);
	jQuery("#mapa_hotel").html('<iframe width="" height="150" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" id="gmap_canvas" src="https://maps.google.com/maps?height=150&amp;hl=en&amp;q='+dataPickup[0].addressPickup+'+('+orderSelected.detailIncluded.vehicleRental[0].carRental+')&amp;t=&amp;z=16&amp;ie=UTF8&amp;iwloc=B&amp;output=embed" style="width:100%"></iframe>');

	var checkin = dataSearch[0].dataPickup; 
	checkin = moment(checkin, 'DD-MM-YYYY').format('YYYY-MM-DD');
    var endDate = moment(checkin, 'YYYY-MM-DD'); 

	var checkout = dataSearch[0].dataDrop; 
	checkout = moment(checkout, 'DD-MM-YYYY').format('YYYY-MM-DD');
    var startDate = moment(checkout, 'YYYY-MM-DD');  

    var days = startDate.diff(endDate, 'days'); 

	jQuery("#desc_dia_room_reserva").html(orderSelected.detailIncluded.vehicleGroup[0].name);
	jQuery("#desc_sua_reserva_para").html(days+' '+(days > 1 ? 'dias' : 'dia'));
	jQuery("#desc_sua_reserva_checkin").html(infoNamePickup+' - <strong>'+dataSearch[0].dataPickup+'</strong> às <strong>'+dataSearch[0].timePickup+'</strong>');
	jQuery("#desc_sua_reserva_checkout").html(infoNameDrop+' - <strong>'+dataSearch[0].dataDrop+'</strong> às <strong>'+dataSearch[0].timeDrop+'</strong>');

	jQuery("#desc_room_reserva").html('R$ '+formatPriceList(orderSelected.price.totalAmount));
	jQuery("#desc_taxa_reserva").html('R$ '+formatPriceList(orderSelected.price.taxesAmount));
	jQuery("#price_total").html('R$ '+formatPriceList(orderSelected.price.totalWithTax));

	jQuery("#desc_titular").html(orderAccepted.data.attributes.name+' '+orderAccepted.data.attributes.lastName+' <br> '+orderAccepted.data.attributes.phone+' <br> '+orderAccepted.data.attributes.email); 
	
	var specifDoors      = '<li class="elementor-icon-list-item"> <span class="elementor-icon-list-icon"> <i aria-hidden="true" class="fas fa-door-open" style="color:'+jQuery("#cor_cars").val()+'"></i> </span> <span class="elementor-icon-list-text">'+orderSelected.detailIncluded.vehicleGroup[0].data.doorCount+' '+(parseInt(orderSelected.detailIncluded.vehicleGroup[0].data.doorCount) > 1 ? 'portas' : 'porta')+'</span> </li>';
	var specifPassengers = '<li class="elementor-icon-list-item"> <span class="elementor-icon-list-icon"> <i aria-hidden="true" class="fas fa-users" style="color:'+jQuery("#cor_cars").val()+'"></i> </span> <span class="elementor-icon-list-text">'+orderSelected.detailIncluded.vehicleGroup[0].data.passengers+' '+(parseInt(orderSelected.detailIncluded.vehicleGroup[0].data.passengers) > 1 ? 'pessoas' : 'pessoa')+'</span> </li>'; 

	var specifFeatured = '';
	if(orderSelected.detailIncluded.vehicleGroup[0].data.featured !== 0){
		specifFeatured   = '<li class="elementor-icon-list-item"> <span class="elementor-icon-list-icon"> <i aria-hidden="true" class="fas fa-check" style="color:'+jQuery("#cor_cars").val()+'"></i> </span> <span class="elementor-icon-list-text"> Recomendamos pelo custo x benefício</span> </li>'; 
	}

	var specifBaggage = '';
	if(orderSelected.detailIncluded.vehicleGroup[0].data.baggage !== null){
		specifBaggage    = '<li class="elementor-icon-list-item"> <span class="elementor-icon-list-icon"> <i aria-hidden="true" class="fas fa-luggage-cart" style="color:'+jQuery("#cor_cars").val()+'"></i> </span> <span class="elementor-icon-list-text">'+orderSelected.detailIncluded.vehicleGroup[0].data.baggage+' '+(parseInt(orderSelected.detailIncluded.vehicleGroup[0].data.baggage) > 1 ? 'malas' : 'mala')+'</span> </li>';
	}

	var specifDistance = '';
	if(orderSelected.rateDistance.unlimited === true){
		specifDistance    = '<li class="elementor-icon-list-item"> <span class="elementor-icon-list-icon"> <i aria-hidden="true" class="fas fa-map" style="color:'+jQuery("#cor_cars").val()+'"></i> </span> <span class="elementor-icon-list-text">Ilimitada</span> </li>';
	}

	jQuery("#desc_qtd_rooms").html('<ul style="list-style:none;">'+specifPassengers+' '+specifDoors+' '+specifBaggage+' '+specifFeatured+' '+specifDistance+'</ul>'); 

	var desc_titular_card = '';
	var desc_number_card = '';
	var desc_validade_card = '';
	var desc_parcelas_card = '';
	if(jQuery("#type_reserva_cars").val() == 2){
		jQuery("#desc_titular_card").html(localStorage.getItem("HOLDER_CARS"));
		jQuery("#desc_number_card").html('**** **** **** '+localStorage.getItem("NUMBER_CARS").substr(localStorage.getItem("NUMBER_CARS").length - 4));
		jQuery("#desc_validade_card").html(localStorage.getItem("MONTH_CARS")+'/'+localStorage.getItem("YEAR_CARS"));

		if(localStorage.getItem("INSTALLMENT_CARS") == 1){ 
			var parcelas = 'À vista'; 
		}else{ 
			var parcelas = localStorage.getItem("INSTALLMENT_CARS")+'x no valor de R$ '+xx.format(localStorage.getItem("PRICE_INSTALLMENT_CARS"))+' cada parcela'; 
		}

		jQuery("#desc_parcelas_card").html(parcelas);

		var desc_titular_card = localStorage.getItem("HOLDER_CARS");
		var desc_number_card = '**** **** **** '+localStorage.getItem("NUMBER_CARS").substr(localStorage.getItem("NUMBER_CARS").length - 4);
		var desc_validade_card = localStorage.getItem("MONTH_CARS")+'/'+localStorage.getItem("YEAR_CARS");
		var desc_parcelas_card = parcelas;
	}

	var type_reserva = jQuery("#type_reserva_cars").val();
	var plugin_dir_url = jQuery("#plugin_dir_url").val();
	var cor_cars = jQuery("#cor_cars").val();
	var hotel_reserva = 'Operadora '+orderSelected.detailIncluded.vehicleRental[0].carRental;
	var checkin_reserva = moment(dataSearch[0].dataPickup, 'DD-MM-YYYY').format("ddd[.] DD MMM[.] YYYY")+' às '+dataSearch[0].timePickup;
	var endereco_hotel = dataPickup[0].addressPickup;
	var mapa_hotel = '<iframe width="" height="150" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" id="gmap_canvas" src="https://maps.google.com/maps?height=150&amp;hl=en&amp;q='+dataPickup[0].addressPickup+'+('+orderSelected.detailIncluded.vehicleRental[0].carRental+')&amp;t=&amp;z=16&amp;ie=UTF8&amp;iwloc=B&amp;output=embed" style="width:100%"></iframe>';
	var desc_dia_room_reserva = orderSelected.detailIncluded.vehicleGroup[0].name;
	var desc_sua_reserva_para = days+' '+(days > 1 ? 'dias' : 'dia');
	var desc_sua_reserva_checkin = infoNamePickup+' - <strong>'+dataSearch[0].dataPickup+'</strong> às <strong>'+dataSearch[0].timePickup+'</strong>';
	var desc_sua_reserva_checkout = infoNameDrop+' - <strong>'+dataSearch[0].dataDrop+'</strong> às <strong>'+dataSearch[0].timeDrop+'</strong>';
	var desc_room_reserva = 'R$ '+formatPriceList(orderSelected.price.totalAmount);
	var desc_taxa_reserva = formatPriceList(orderSelected.price.taxesAmount);
	var price_total = formatPriceList(orderSelected.price.totalWithTax);
	var desc_titular = orderAccepted.data.attributes.name+' '+orderAccepted.data.attributes.lastName+' <br> '+orderAccepted.data.attributes.phone+' <br> '+orderAccepted.data.attributes.email;
	var desc_qtd_rooms = '<ul style="list-style:none;">'+specifPassengers+' '+specifDoors+' '+specifBaggage+' '+specifFeatured+' '+specifDistance+'</ul>'; 
	var email_order = orderAccepted.data.attributes.costumerEmail;

	jQuery.ajax({ 
        url : jQuery("#url_ajax").val(), 
        type : 'post',  
        data : {'action': 'send_mail_confirm_order', plugin_dir_url:plugin_dir_url, cor_cars:cor_cars, hotel_reserva:hotel_reserva, checkin_reserva:checkin_reserva, endereco_hotel:endereco_hotel, mapa_hotel:mapa_hotel, desc_dia_room_reserva:desc_dia_room_reserva, desc_sua_reserva_para:desc_sua_reserva_para, desc_sua_reserva_checkin:desc_sua_reserva_checkin, desc_sua_reserva_checkout:desc_sua_reserva_checkout, desc_room_reserva:desc_room_reserva, desc_taxa_reserva:desc_taxa_reserva, price_total:price_total, desc_titular:desc_titular, desc_qtd_rooms:desc_qtd_rooms, desc_titular_card:desc_titular_card, desc_number_card:desc_number_card, desc_validade_card:desc_validade_card, desc_parcelas_card:desc_parcelas_card, type_reserva:type_reserva, email_order:email_order },

        success : function( resposta ) {
 

        }

    });
}