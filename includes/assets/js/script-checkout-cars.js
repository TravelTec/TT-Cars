jQuery(function() { 
    var url_atual = window.location.href;

    if(url_atual.indexOf("/checkout-cars/") != -1){
    	moment.locale('pt-br');
    	setInfoCheckoutCars();

    	jQuery("#nasc").mask("00/00/0000");
    	jQuery("#celphone").mask("(00) 00000-0000");
    	jQuery("#cpf").mask("000.000.000-00");

    	jQuery("#mm-card").mask("00");
		jQuery("#year-card").mask("00");
		jQuery("#cvc-card").mask("000");

		jQuery("#cep").mask("00000-000");
 
		if(jQuery("#type_reserva_cars") == 2){
			var orderSelected = JSON.parse(localStorage.getItem("SELECTED_ORDER_CARS")); 
			var installments = "";
			var price = orderSelected.price.totalWithTax;
			for(var i = 1; i < 7; i++){
				if(i == 1){
					var option_name = 'À vista no valor de R$ '+formatPriceList(price);
				}else{
					var option_name = 'Em '+i+' vezes no valor de R$ '+formatPriceList((price/i))+' cada parcela';
				}
				installments += '<option value="'+i+';'+(price/i)+'" '+(i == 1 ? 'selected' : '')+'>'+option_name+'</option>';
			}
			jQuery("#installmentsCars").html(installments);
		} 
		 
    }
}); 

function selectInstallmentCar(){
	var installment = (jQuery("#installmentsCars").val()).split(";");
	localStorage.setItem("INSTALLMENT_CARS", installment[0]);
	localStorage.setItem("PRICE_INSTALLMENT_CARS", installment[1]);
}

function setInfoCheckoutCars(){
	var orderSelected = JSON.parse(localStorage.getItem("SELECTED_ORDER_CARS")); 
	var dataSearch = JSON.parse(localStorage.getItem("DATA_SEARCH_CARS")); 

	jQuery("#priceLoc").html('R$ '+formatPriceList(orderSelected.price.totalAmount));
	jQuery("#priceTax").html('R$ '+formatPriceList(orderSelected.price.taxesAmount));
	jQuery("#priceTotal").html('<small style="font-weight:500;font-size: 15px">R$</small> '+formatPriceList(orderSelected.price.totalWithTax));

	jQuery("#nameCar").html(orderSelected.detailIncluded.vehicleGroup[0].name);
	jQuery("#paxCar").html(orderSelected.detailIncluded.vehicleGroup[0].data.passengers+' '+(parseInt(orderSelected.detailIncluded.vehicleGroup[0].data.passengers) > 1 ? 'pessoas' : 'pessoa'));
	jQuery("#dataRental").html('<figure class="elementor-image-box-img"> <img decoding="async" width="313" height="153" src="'+orderSelected.detailIncluded.vehicleRental[0].logo+'" class="attachment-full size-full wp-image-1839" alt="" loading="lazy" srcset="'+orderSelected.detailIncluded.vehicleRental[0].logo+' 313w, '+orderSelected.detailIncluded.vehicleRental[0].logo+' 300w" sizes="(max-width: 313px) 100vw, 313px"> </figure> <div class="elementor-image-box-content"> <h3 class="elementor-image-box-title">'+orderSelected.detailIncluded.vehicleRental[0].carRental+'</h3> </div>'); 

	jQuery("#datePickup").html(moment(dataSearch[0].dataPickup, 'DD-MM-YYYY').format("ddd[.] DD MMM[.] YYYY"));
	jQuery("#timePickup").html(dataSearch[0].timePickup);
	jQuery("#dateDrop").html(moment(dataSearch[0].dataDrop, 'DD-MM-YYYY').format("ddd[.] DD MMM[.] YYYY"));
	jQuery("#timeDrop").html(dataSearch[0].timeDrop);

	var dataPickup = JSON.parse(localStorage.getItem("DATA_STORE_PICKUP_CARS"));
	if(dataSearch.typeDelivery == 1){
		var dataDrop = JSON.parse(localStorage.getItem("DATA_STORE_DROP_CARS"));
		var infoDrop = '<h3 class="elementor-icon-box-title"> <span> Agência da '+orderSelected.detailIncluded.vehicleRental[0].carRental+' em </span> </h3>  <p class="elementor-icon-box-description"> <small style="text-transform:uppercase">'+dataDrop[0].addressDrop+'</small> </p>';
	}else{
		var dataDrop = JSON.parse(localStorage.getItem("DATA_STORE_PICKUP_CARS"));
		var infoDrop = '<h3 class="elementor-icon-box-title"> <span> Agência da '+orderSelected.detailIncluded.vehicleRental[0].carRental+' em </span> </h3>  <p class="elementor-icon-box-description"> <small style="text-transform:uppercase">'+dataPickup[0].addressPickup+'</small> </p>';
	}

	jQuery("#locationRentalPickup").html('<h3 class="elementor-icon-box-title"> <span> Agência da '+orderSelected.detailIncluded.vehicleRental[0].carRental+' em </span> </h3>  <p class="elementor-icon-box-description"> <small style="text-transform:uppercase">'+dataPickup[0].addressPickup+'</small> </p>');
	jQuery("#locationRentalDrop").html(infoDrop); 
	
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

	jQuery("#specifCar").html(specifPassengers+' '+specifDoors+' '+specifBaggage+' '+specifFeatured+' '+specifDistance); 
}

function see_info_loc(){
	var orderSelected = JSON.parse(localStorage.getItem("SELECTED_ORDER_CARS")); 
	var info = orderSelected.detailIncluded.vehicleRental[0].policies;

	bootbox.dialog({ 
      	title: 'Informações sobre a locação', 
      	message: info 
  	});
}

function see_info_cancel(){
	var orderSelected = JSON.parse(localStorage.getItem("SELECTED_ORDER_CARS")); 
	var info = orderSelected.detailIncluded.rate[0].rateDetails;
	
	bootbox.dialog({ 
      	title: 'Políticas e cancelamento', 
      	message: info 
  	});
}

function send_order_cars(type){
	var nameTitular = jQuery("#nameTitular").val();
	var surnameTitular = jQuery("#surnameTitular").val();
	var cpf = jQuery("#cpf").val();
	var nasc = jQuery("#nasc").val();
	var emailTitular = jQuery("#emailTitular").val();
	var celphone = jQuery("#celphone").val();

	if(nameTitular == ""){
        swal({
            title: "É necessário preencher o nome do titular da reserva.",
            icon: "warning",
        }); 
        return false;
    }else if(surnameTitular == ""){
        swal({
            title: "É necessário preencher o sobrenome do titular da reserva.",
            icon: "warning",
        }); 
        return false;
    }else if(cpf == ""){
        swal({
            title: "É necessário preencher o CPF do titular da reserva.",
            icon: "warning",
        }); 
        return false;
    }else if(nasc == ""){
        swal({
            title: "É necessário preencher a data de nascimento do titular da reserva.",
            icon: "warning",
        }); 
        return false;
    }else if(emailTitular == ""){
        swal({
            title: "É necessário preencher o e-mail do titular da reserva.",
            icon: "warning",
        }); 
        return false;
    }else if(celphone == ""){
        swal({
            title: "É necessário preencher o celular do titular da reserva.",
            icon: "warning",
        }); 
        return false;
    }else{ 

    	if(type == 2){
			var cep = jQuery("#cep").val().replace("-", "");
			var endereco = jQuery("#endereco").val();
			var numero = jQuery("#numero").val();
			var complemento = jQuery("#complemento").val();
			var bairro = jQuery("#bairro").val();
			var cidade = jQuery("#cidade").val();
			var estado = jQuery("#estado").val();

			if(cep == ""){
		        swal({
		            title: "É necessário preencher o CEP.",
		            icon: "warning",
		        }); 
		        return false;
		    }else if(endereco == ""){
		        swal({
		            title: "É necessário preencher o endereço.",
		            icon: "warning",
		        }); 
		        return false;
		    }else if(numero == ""){
		        swal({
		            title: "É necessário preencher o número.",
		            icon: "warning",
		        }); 
		        return false;
		    }else if(bairro == ""){
		        swal({
		            title: "É necessário preencher o bairro.",
		            icon: "warning",
		        }); 
		        return false;
		    }else if(cidade == ""){
		        swal({
		            title: "É necessário preencher a cidade.",
		            icon: "warning",
		        }); 
		        return false;
		    }else if(estado == ""){
		        swal({
		            title: "É necessário preencher o estado.",
		            icon: "warning",
		        }); 
		        return false;
		    }

		    var holder = jQuery("#holder-card").val();
			var number = jQuery("#number-card").val();
			var month = jQuery("#mm-card").val();
			var year = jQuery("#year-card").val();
			var cvc = jQuery("#cvc-card").val();

			if(holder == ""){
				swal({
		            title: "É necessário preencher o titular do cartão.",
		            icon: "warning",
		        }); 
		        return false;
			}else if(number == ""){
				swal({
		            title: "É necessário preencher o número do cartão.",
		            icon: "warning",
		        }); 
		        return false;
			}else if(month == ""){
				swal({
		            title: "É necessário preencher o mês.",
		            icon: "warning",
		        }); 
		        return false;
			}else if(year == ""){
				swal({
		            title: "É necessário preencher o ano.",
		            icon: "warning",
		        }); 
		        return false;
			}else if(year < 23){
				swal({
		            title: "É necessário preencher o ano da forma correta.",
		            icon: "warning",
		        }); 
		        return false;
			}else if(cvc == ""){
				swal({
		            title: "É necessário preencher o CVC.",
		            icon: "warning",
		        }); 
		        return false;
			}else{ 

				localStorage.setItem("HOLDER_CARS", holder);
				localStorage.setItem("NUMBER_CARS", number);
				localStorage.setItem("MONTH_CARS", month);
				localStorage.setItem("YEAR_CARS", year);
				localStorage.setItem("CVC_CARS", cvc);

			} 
		}

    	jQuery(".btnSelect").html('<img src="https://media.tenor.com/images/a742721ea2075bc3956a2ff62c9bfeef/tenor.gif" style="height: 22px;position:absolute;"> <p style="margin-left: 30px;margin-bottom: 0;">Finalizando...</p>');
	    jQuery(".btnSelect").attr("disabled", "disabled");
	    jQuery(".btnSelect").prop("disabled", true);

		var jsonReserva = '{ "data": { "attributes": {"name": "'+nameTitular+'", "lastName": "'+surnameTitular+'", "email": "'+emailTitular+'", "phone": "'+celphone+'", "paymentsTypes": "invoice_only_daily", "customerName": "'+nameTitular+' '+surnameTitular+'", "costumerEmail": "'+emailTitular+'", "customerIdentity": "'+cpf+'" } } }'; 
		localStorage.setItem("ORDER_ACCEPTED", jsonReserva);

		window.location.href = '/order-cars/';

	}
	
}