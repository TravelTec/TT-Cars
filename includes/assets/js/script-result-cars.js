jQuery(function() { 
    var url_atual = window.location.href;

    if(parseInt(jQuery("#type_motor").val()) == 1){
    	setInfoMotorCars();
    }

    const searchCars = async () => { 
	  	try {   
	    	await getDataResultCars();  
	  	} catch (err) { 
	    	console.error(err); 
	  	} 
	} 
	searchCars(); 

});

/* FUNÇÃO QUE SETA OS DADOS NO MOTOR */
function setInfoMotorCars(){
	var dataSearch = JSON.parse(localStorage.getItem("DATA_SEARCH_CARS")); 

	jQuery("#type_delivery").val(parseInt(dataSearch[0].typeDelivery));
	if(parseInt(dataSearch[0].typeDelivery) == 0){
		jQuery(".dropClass").attr("style", "display:none");
	}

	var dataPickupSearch = JSON.parse(dataSearch[0].dataStorePickup);
	jQuery("#localPickup").val(dataPickupSearch[0].namePickup);

	if(parseInt(dataSearch[0].typeDelivery) == 1){
		jQuery("#change_delivery").prop("checked", "checked");
		jQuery("#change_delivery").attr("checked", true);

		var dataDropSearch = JSON.parse(dataSearch[0].dataStoreDrop);
		jQuery("#localDrop").val(dataDropSearch[0].nameDrop);
	}

	jQuery("#selectVehicleRetirada").val(dataSearch[0].timePickup).attr("selected","selected");
	jQuery("#selectVehicleDevolucao").val(dataSearch[0].timeDrop).attr("selected","selected");
}
 
/* FUNÇÃO QUE BUSCA OS DADOS NO FORNECEDOR */
function getDataResultCars(){

	var dataSearch = JSON.parse(localStorage.getItem("DATA_SEARCH_CARS"));

	var typeDelivery = dataSearch[0].typeDelivery;

	var dataPickup = JSON.parse(dataSearch[0].dataStorePickup);
	if(typeDelivery == 1){
		var dataDrop = JSON.parse(dataSearch[0].dataStoreDrop);
		var idDrop = dataDrop[0].idDrop;
	}else{
		var dataDrop = JSON.parse(dataSearch[0].dataStorePickup);
		var idDrop = dataDrop[0].idPickup;
	}

	var data = {  
        'action': 'search_cars', 
        'accessToken': localStorage.getItem("ACCESS_TOKEN_CARS"),
		'locationPickup': dataPickup[0].idPickup,
		'datePickup': dataSearch[0].dataPickup,
		'timePickup': dataSearch[0].timePickup,
		'locationReturn': idDrop,
		'dateReturn': dataSearch[0].dataDrop,
		'timeReturn': dataSearch[0].timeDrop
    }; 

    jQuery.ajax({

        url : jQuery("#url_ajax").val(), 
        type : 'post', 
        data : data, 
        success : function( resposta ) {

        	localStorage.setItem("ALL_RESULT_CARS", resposta.slice(0, -1)); 
        	storage_json_cars();

        }

    });

}

function storage_json_cars(type = null){
	var data = JSON.parse(localStorage.getItem("ALL_RESULT_CARS"));  

	var dados = data.data;
	var included = data.included;

	/* ORGANIZA ARRAY INCLUSOS 
		* equip
		* coverage
		* vehicleRateType
		* vehicleStoreOperationTime
		* vehicleGroup
		* vehicleCoverage
		* vehicleRate
		* vehicleStore
		* vehicleRental
		* vehicleEquip
	*/  
	var equip = [];
	var coverage = [];
	var vehicleRateType = [];
	var vehicleStoreOperationTime = [];
	var vehicleGroup = [];
	var vehicleCoverage = [];
	var vehicleRate = [];
	var vehicleStore = [];
	var vehicleRental = [];
	var vehicleEquip = []; 
	jQuery(included).each(function(i, item) {
		if(item.type == "equip"){
			equip.push({
				'id': item.id,
				'name': item.attributes.name,
			}); 
		}
		if(item.type == "coverage"){
			coverage.push({
				'id': item.id,
				'name': item.attributes.name,
			});  
		}
		if(item.type == "vehicleRateType"){
			vehicleRateType.push({
				'id': item.id,
				'name': item.attributes.name,
			});  
		}
		if(item.type == "vehicleStoreOperationTime"){
			vehicleStoreOperationTime.push({
				'id': item.id,
				'opening': item.attributes.openingTime,
				'closing': item.attributes.closingTime,
				'data': {
					'sunday': item.attributes.sun,
					'monday': item.attributes.mon, 
					'tuesday': item.attributes.tue, 
					'wednesday': item.attributes.weds, 
					'thursday': item.attributes.thur, 
					'friday': item.attributes.fri, 
					'saturday': item.attributes.sat, 
					'holiday': item.attributes.holiday  
				}
			});   
		}
		if(item.type == "vehicleGroup"){
			vehicleGroup.push({
				'id': item.id,
				'name': item.attributes.vehicle,
				'data': {
					'baggage': item.attributes.baggage,
					'doorCount': item.attributes.doorCount, 
					'featured': item.attributes.featured, 
					'img': item.attributes.img, 
					'passengers': item.attributes.passengers  
				}
			});  
		}
		if(item.type == "vehicleCoverage"){
			vehicleCoverage.push({
				'id': item.id,
				'amount': item.attributes.amount,
				'coverageRelation': item.relationships.coverage.data.id
			}); 
		}
		if(item.type == "vehicleRate"){
			vehicleRate.push({
				'id': item.id,
				'rateDetails': item.attributes.rateDetails,
				'rateName': item.attributes.rateName,
				'rateRelation': item.relationships.rateType.data.id
			}); 
		}
		if(item.type == "vehicleStore"){
			vehicleStore.push({
				'id': item.id,
				'store': item.attributes.store,
				'address': item.attributes.address,
				'generalObservations': item.attributes.generalObservations 
			}); 
		}
		if(item.type == "vehicleRental"){  
			vehicleRental.push({
				'id': item.id,
				'carRental': item.attributes.carRental,
				'logo': item.attributes.logo,
				'policies': item.attributes.policies 
			}); 
		}
		if(item.type == "vehicleEquip"){
			vehicleEquip.push({
				'id': item.id,
				'amount': item.attributes.amount,
				'equipRelation': item.relationships.equip.data.id 
			}); 
		}
	});

	/* ORGANIZA ARRAY DISPONIBILIDADES */

	var dataSearch = JSON.parse(localStorage.getItem("DATA_SEARCH_CARS"));

	var checkin = dataSearch[0].dataPickup; 
	checkin = moment(checkin, 'DD-MM-YYYY').format('YYYY-MM-DD');
    var endDate = moment(checkin, 'YYYY-MM-DD'); 

	var checkout = dataSearch[0].dataDrop; 
	checkout = moment(checkout, 'DD-MM-YYYY').format('YYYY-MM-DD');
    var startDate = moment(checkout, 'YYYY-MM-DD');  

    var days = startDate.diff(endDate, 'days'); 

	var jsonDados = [];
	var jsonFilterPrice = [];
	jQuery(dados).each(function(i, item) {

		var taxes = item.attributes.price.taxs;
		var dataTaxes = [];
		jQuery(taxes).each(function(t, tax) {
			dataTaxes.push({
				'amount': tax.amount,
				'description': tax.description,
				'isRentalTax': tax.isRentalTax
			});
		});

		var infoDetailVehicleGroup = [];
		jQuery(vehicleGroup).each(function(vg, vgroup) {
			if(item.relationships.vehicleGroup.data.id == vgroup.id){
				infoDetailVehicleGroup.push(vgroup);
			}
		});

		var infoDetailVehicleRental = [];
		jQuery(vehicleRental).each(function(vr, vrental) {
			if(item.relationships.vehicleRental.data.id == vrental.id){
				infoDetailVehicleRental.push(vrental);
			}
		});

		var infoDetailVehicleRate = [];
		jQuery(vehicleRate).each(function(vrt, vrate) {
			if(item.relationships.rate.data.id == vrate.id){
				infoDetailVehicleRate.push(vrate);
			}
		});

		jsonFilterPrice.push((item.attributes.price.totalAmount/parseInt(days)));

		jsonDados.push({
			'id': item.id,
			'price': {
				'totalDaily': (item.attributes.price.totalAmount/parseInt(days)),
				'totalAmount': item.attributes.price.totalAmount,
				'taxesAmount': item.attributes.price.taxesAmount,
				'totalWithTax': item.attributes.price.totalWithTax,
				'dataTaxes': dataTaxes
			},
			'rateDistance': {
				'unlimited': item.attributes.rateDistance.unlimited
			},
			'included': {
				'rate': item.relationships.rate.data.id,
				'vehicleGroup': item.relationships.vehicleGroup.data.id,
				'vehicleRental': item.relationships.vehicleRental.data.id 
			},
			'detailIncluded': {
				'rate': infoDetailVehicleRate,
				'vehicleGroup': infoDetailVehicleGroup,
				'vehicleRental': infoDetailVehicleRental
			}
		}); 

	});
	localStorage.setItem("FORMATTED_RESULT_CARS", JSON.stringify(jsonDados)); 
	localStorage.setItem("JSON_FILTER_RENTAL_CARS", JSON.stringify(vehicleRental)); 
	localStorage.setItem("JSON_FILTER_PRICE_CARS", JSON.stringify(jsonFilterPrice)); 

	if(type == 2){
		return jsonDados;
	}
 	
 	if(type == null){
		const listCars = async () => { 
		  	try { 
	 
		    	await start_filters_cars();
		    	await list_results_cars(10, 0);

		  	} catch (err) {  
		    	console.error(err); 
		  	} 
		} 
		listCars(); 
	}

}

function start_filters_cars(){

	/* ************************************************** */
		/* FILTRO PREÇO */
		var dataPrice = JSON.parse(localStorage.getItem("JSON_FILTER_PRICE_CARS"));  

		var retornoPriceCars = "";

		retornoPriceCars += '<input type="hidden" id="min_price_cars" value="'+Math.min.apply(Math, dataPrice)+'">';
		retornoPriceCars += '<input type="hidden" id="max_price_cars" value="'+Math.max.apply(Math, dataPrice)+'">'; 

		retornoPriceCars += '<input type="hidden" id="rental_filter" value="all">';

		retornoPriceCars += '<div class="accordion accordion-flush" id="accordionFlushPrice">';
			retornoPriceCars += '<div class="accordion-item">';
			    retornoPriceCars += '<h2 class="accordion-header" id="flush-headingPrice">';
			      	retornoPriceCars += '<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-price" aria-expanded="true" aria-controls="flush-collapseOne">';
			        	retornoPriceCars += 'Preço';
			      	retornoPriceCars += '</button>';
			    retornoPriceCars += '</h2>';
			    retornoPriceCars += '<div id="flush-price" class="accordion-collapse collapse show" aria-labelledby="flush-headingPrice" data-bs-parent="#accordionFlushPrice">';
			      	retornoPriceCars += '<div class="accordion-body"> ';
			      		retornoPriceCars += '<div class="row">';
			      			retornoPriceCars += '<div class="col-lg-6 col-6">';
			      				retornoPriceCars += '<label class="price-range-left">Mín.</label>';
			      			retornoPriceCars += '</div>';
			      			retornoPriceCars += '<div class="col-lg-6 col-6 text-right">';
			      				retornoPriceCars += '<label class="price-range-right">Máx.</label>';
			      			retornoPriceCars += '</div>';
			      		retornoPriceCars += '</div>';
			      		retornoPriceCars += '<div class="row">';
			      			retornoPriceCars += '<div class="col-lg-12 col-12 range">';
								retornoPriceCars += '<div id="steps-slider-cars" class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr">';
								retornoPriceCars += '</div>  ';
							retornoPriceCars += '</div>  ';
						retornoPriceCars += '</div>    ';
			      	retornoPriceCars += '</div>';
			    retornoPriceCars += '</div>';
			retornoPriceCars += '</div>';
		retornoPriceCars += '</div>';

		if(dataPrice.length > 0){

			jQuery(".filter-price-cars").html(retornoPriceCars);
			jQuery(".filter-price-cars").removeClass("row-is-loading");

			var stepsSlider = document.getElementById('steps-slider-cars'); 

			noUiSlider.create(stepsSlider, {
			    start: [parseInt(Math.min.apply(Math, dataPrice)), parseInt(Math.max.apply(Math, dataPrice))],
			    connect: true,
			    tooltips: [true, wNumb({decimals: 1})],
			    range: {
			        'min': parseInt(Math.min.apply(Math, dataPrice)),
			        'max': parseInt(Math.max.apply(Math, dataPrice))
			    }
			});

			stepsSlider.noUiSlider.on('change', function (values, handle) { 
			    if(handle == 0){
			    	jQuery("#min_price_cars").val(values[handle]); 
			    }
			    if(handle == 1){ 
			    	jQuery("#max_price_cars").val(values[handle]);
			    }  

			    filter_results_cars();
			}); 

		}else{
			jQuery(".filter-price-cars").removeClass("row-is-loading");
		}
	/* ************************************************** */

	/* ************************************************** */
		/* FILTRO LOCADORAS */
		var retornoRental = ""; 
		var dataRental = JSON.parse(localStorage.getItem("JSON_FILTER_RENTAL_CARS"));  

		retornoRental += '<div class="accordion accordion-flush" id="accordionFlushRental">';
			retornoRental += '<div class="accordion-item">';
			    retornoRental += '<h2 class="accordion-header" id="flush-headingRental">';
			      	retornoRental += '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-rental" aria-expanded="false" aria-controls="flush-collapseOne">';
			        	retornoRental += 'Locadoras';
			      	retornoRental += '</button>';
			    retornoRental += '</h2>';
			    retornoRental += '<div id="flush-rental" class="accordion-collapse collapse" aria-labelledby="flush-headingRental" data-bs-parent="#accordionFlushRental">';
			      	retornoRental += '<div class="accordion-body"> ';

			      		retornoRental += '<div class="row all-rentals">';
			      			retornoRental += '<div class="col-lg-12 col-12">';
			      				retornoRental += '<div class="form-check form-check-inline" style="margin-bottom: 10px;">';
								  	retornoRental += '<input class="form-check-input" type="checkbox" id="inlineCheckbox6" value="all" checked disabled onclick="change_filter_rental(\'all\')">';
								  	retornoRental += '<label class="form-check-label" for="inlineCheckbox6" style="color:#303030">Todas as opções</label>';
								retornoRental += '</div>';
			      			retornoRental += '</div> ';
			      		retornoRental += '</div> ';  

			      		jQuery(dataRental).each(function(i, item) { 
			      			retornoRental += '<div class="row rental'+item.id+'">';
				      			retornoRental += '<div class="col-lg-12 col-12">';
				      				retornoRental += '<div class="form-check form-check-inline" style="margin-bottom: 10px;">';
									  	retornoRental += '<input class="form-check-input" type="checkbox" id="inlineCheckbox'+i+'" value="'+item.carRental+'" onclick="change_filter_rental(\''+item.id+'\')">';
									  	retornoRental += '<label class="form-check-label" for="inlineCheckbox'+i+'" style="display: inline-flex;font-size: 13px;font-weight: 500;"><img src="'+item.logo+'" style="width: 56px; height: 25px;margin-right: 10px;"> <span style="padding: 5px 0;">'+item.carRental+'</span></label>';
									retornoRental += '</div>';
				      			retornoRental += '</div> ';
				      		retornoRental += '</div> '; 
			      		});

			      	retornoRental += '</div>';
			    retornoRental += '</div>';
			retornoRental += '</div>';
		retornoRental += '</div>'; 

		if(dataPrice.length > 0){
			jQuery(".filter-rental-cars").html(retornoRental);
			jQuery(".filter-rental-cars").removeClass("row-is-loading");
		}else{
			jQuery(".filter-rental-cars").removeClass("row-is-loading");
		}
	/* ************************************************** */ 
	
}

function list_results_cars(contador_prox, contador_prev){

    var data = JSON.parse(localStorage.getItem("FORMATTED_RESULT_CARS"));

	var dataSearch = JSON.parse(localStorage.getItem("DATA_SEARCH_CARS"));

	var checkin = dataSearch[0].dataPickup; 
	checkin = moment(checkin, 'DD-MM-YYYY').format('YYYY-MM-DD');
    var endDate = moment(checkin, 'YYYY-MM-DD'); 

	var checkout = dataSearch[0].dataDrop; 
	checkout = moment(checkout, 'DD-MM-YYYY').format('YYYY-MM-DD');
    var startDate = moment(checkout, 'YYYY-MM-DD');  

    var days = startDate.diff(endDate, 'days'); 

    var html = ""; 
    var contador = 0; 

    jQuery(data).each(function(i, item) { 
    	contador++;
    	if(i < contador_prox && i >= contador_prev){
    		html += '<section class="elementor-section elementor-inner-section elementor-element elementor-element-d48f1da rowOffer elementor-section-boxed elementor-section-height-default elementor-section-height-default wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no" data-id="d48f1da" data-element_type="section">';
                html += '<div class="elementor-container elementor-column-gap-default">';
                    html += '<div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-aec4f94" data-id="aec4f94" data-element_type="column">';
                        html += '<div class="elementor-widget-wrap elementor-element-populated">';
                            html += '<div class="elementor-element elementor-element-4e90518 elementor-position-left elementor-vertical-align-middle elementor-widget elementor-widget-image-box" data-id="4e90518" data-element_type="widget" data-widget_type="image-box.default">';
                                html += '<div class="elementor-widget-container"> ';
                                    html += '<div class="elementor-image-box-wrapper">';
                                        html += '<figure class="elementor-image-box-img">';
                                            html += '<img decoding="async" width="155" height="90" src="'+item.detailIncluded.vehicleGroup[0].data.img+'" class="attachment-full size-full wp-image-1833" alt="" loading="lazy" style="width: 76%">';
                                        html += '</figure>';
                                        html += '<div class="elementor-image-box-content">';
                                            html += '<h3 class="elementor-image-box-title">'+item.detailIncluded.vehicleGroup[0].name+'</h3>'; 
                                        html += '</div>';
                                    html += '</div>';
                                html += '</div>';
                            html += '</div>';
                            html += '<div class="elementor-element elementor-element-05a5a5d elementor-icon-list--layout-inline elementor-mobile-align-center elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="05a5a5d" data-element_type="widget" data-widget_type="icon-list.default">';
                                html += '<div class="elementor-widget-container">';
                                    html += '<link rel="stylesheet" href="https://rslvportfolio.com.br/wp-content/plugins/elementor/assets/css/widget-icon-list.min.css">';
                                    html += '<ul class="elementor-icon-list-items elementor-inline-items">';
                                        html += '<li class="elementor-icon-list-item elementor-inline-item">';
                                            html += '<span class="elementor-icon-list-icon">';
                                                html += '<i aria-hidden="true" class="fas fa-users"></i>';
                                            html += '</span>';
                                            html += '<span class="elementor-icon-list-text">'+item.detailIncluded.vehicleGroup[0].data.passengers+' '+(item.detailIncluded.vehicleGroup[0].data.passengers > 1 ? 'pessoas' : 'pessoa')+'</span>';
                                        html += '</li>';
                                        if(item.detailIncluded.vehicleGroup[0].data.baggage !== null){
	                                        html += '<li class="elementor-icon-list-item elementor-inline-item">';
	                                            html += '<span class="elementor-icon-list-icon">';
	                                                html += '<i aria-hidden="true" class="fas fa-luggage-cart"></i>';
	                                            html += '</span>';
	                                            html += '<span class="elementor-icon-list-text">'+item.detailIncluded.vehicleGroup[0].data.baggage+' '+(item.detailIncluded.vehicleGroup[0].data.baggage > 1 ? 'malas' : 'mala')+'</span>';
	                                        html += '</li>'; 
	                                    }	 
                                        if(item.rateDistance.unlimited === true){
	                                        html += '<li class="elementor-icon-list-item elementor-inline-item">';
	                                            html += '<span class="elementor-icon-list-icon">';
	                                                html += '<i aria-hidden="true" class="fas fa-map"></i>';
	                                            html += '</span>';
	                                            html += '<span class="elementor-icon-list-text">Ilimitada</span>';
	                                        html += '</li>';
	                                    }
                                    html += '</ul>';
                                html += '</div>';
                            html += '</div> ';
                            html += '<div class="elementor-element elementor-element-b82a85e elementor-widget-divider--view-line elementor-widget elementor-widget-divider" data-id="b82a85e" data-element_type="widget" data-widget_type="divider.default">';
                                html += '<div class="elementor-widget-container"> ';
                                    html += '<div class="elementor-divider">';
                                        html += '<span class="elementor-divider-separator"></span>';
                                    html += '</div>';
                                html += '</div>';
                            html += '</div>';
                            html += '<div class="elementor-element elementor-element-3da3149 elementor-position-left elementor-vertical-align-middle elementor-widget elementor-widget-image-box" data-id="3da3149" data-element_type="widget" data-widget_type="image-box.default">';
                                html += '<div class="elementor-widget-container">';
                                    html += '<div class="elementor-image-box-wrapper">';
                                        html += '<figure class="elementor-image-box-img">';
                                            html += '<img decoding="async" width="280" height="140" src="'+item.detailIncluded.vehicleRental[0].logo+'" class="attachment-full size-full wp-image-1838" alt="" loading="lazy">';
                                        html += '</figure>'; 
                                    html += '</div>';
                                html += '</div>';
                            html += '</div> ';
                            html += '<div class="elementor-element elementor-element-c91dff4 elementor-widget-divider--view-line elementor-widget elementor-widget-divider" data-id="c91dff4" data-element_type="widget" data-widget_type="divider.default">';
                                html += '<div class="elementor-widget-container">';
                                    html += '<div class="elementor-divider">';
                                        html += '<span class="elementor-divider-separator"></span>';
                                    html += '</div>';
                                html += '</div>';
                            html += '</div>';
                            html += '<div class="elementor-element elementor-element-4e77f7e elementor-icon-list--layout-inline elementor-align-left elementor-mobile-align-left elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="4e77f7e" data-element_type="widget" data-widget_type="icon-list.default">';
                                html += '<div class="elementor-widget-container">';
                                    html += '<ul class="elementor-icon-list-items elementor-inline-items">';
                                        html += '<li class="elementor-icon-list-item elementor-inline-item">'; 
                                            html += '<span class="elementor-icon-list-text">'+item.detailIncluded.rate[0].rateName+'</span>';
                                        html += '</li> ';
                                    html += '</ul>';
                                html += '</div>';
                            html += '</div>';
                        html += '</div>';
                    html += '</div>';
                    html += '<div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-2d89453" data-id="2d89453" data-element_type="column">';
                        html += '<div class="elementor-widget-wrap elementor-element-populated">';
                            html += '<div class="elementor-element elementor-element-838e4f4 elementor-widget elementor-widget-heading" data-id="838e4f4" data-element_type="widget" data-widget_type="heading.default">';
                                html += '<div class="elementor-widget-container">';
                                    html += '<h2 class="elementor-heading-title elementor-size-default">Preço total por dia</h2>';
                                html += '</div>';
                            html += '</div>';
                            html += '<div class="elementor-element elementor-element-0a0ab2c elementor-widget elementor-widget-heading" data-id="0a0ab2c" data-element_type="widget" data-widget_type="heading.default">';
                                html += '<div class="elementor-widget-container">';
                                    html += '<h2 class="elementor-heading-title elementor-size-default">';
                                        html += '<small style="font-size:15px;font-weight:500">R$</small>'+formatPriceList(item.price.totalDaily);
                                    html += '</h2>';
                                html += '</div>';
                            html += '</div>';
                            html += '<div class="elementor-element elementor-element-ac85155 elementor-widget elementor-widget-heading" data-id="ac85155" data-element_type="widget" data-widget_type="heading.default">';
                                html += '<div class="elementor-widget-container">';
                                    html += '<h2 class="elementor-heading-title elementor-size-default">Preço final por '+days+' '+(days > 1 ? 'dias' : 'dia')+' <strong>R$ '+formatPriceList(item.price.totalAmount)+'</strong>';
                                    html += '</h2>';
                                html += '</div>';
                            html += '</div>';
                            html += '<div class="elementor-element elementor-element-a8b2e28 elementor-mobile-align-justify elementor-align-justify elementor-widget elementor-widget-button" data-id="a8b2e28" data-element_type="widget" data-widget_type="button.default">';
                                html += '<div class="elementor-widget-container">';
                                    html += '<div class="elementor-button-wrapper">';
                                        html += '<a class="elementor-button elementor-button-link elementor-size-md" onclick="select_car_order(\''+i+'\')" style="cursor:pointer;text-decoration:none;color:#fff">';
                                            html += '<span class="elementor-button-content-wrapper">';
                                                html += '<span class="elementor-button-text">Reservar</span>';
                                            html += '</span>';
                                        html += '</a>';
                                    html += '</div>';
                                html += '</div>';
                            html += '</div>';
                        html += '</div>';
                    html += '</div>';
                html += '</div>';
            html += '</section>';
    	}
    }); 

    /* PAGINATION */
	html += '<div class="container" style="margin: 30px 0px;font-family: \'Montserrat\';">'; 
		html += '<div class="row justify-content-center">'; 
			html += '<div class="col-lg-12 col-12 text-center">'; 
				html += '<div class="">'; 
					html += "<input type='hidden' id='pageActiveCars' value='1'>"; 

					var total_pages = Math.ceil(contador/10);  
					for(var i = 1; i <= total_pages; i++){ 
						if(i == jQuery("#pageActiveCars").val()){ 
							html += '<span style="padding: 10px;font-size: 17px;font-weight: 800;color: #000000;cursor:pointer" onclick="show_page_cars('+i+')">'+i+'</span>'; 
						}else{ 
							html += '<span style="padding: 10px;font-size: 17px;cursor:pointer" onclick="show_page_cars('+i+')">'+i+'</span>'; 
						} 
					} 

				html += '</div>';  
			html += '</div>'; 
		html += '</div>'; 
	html += '</div>';
    /* FIM PAGINATION */

    if(data.length < 1){
    	html = ""; 

		html += '<div class="container" style="margin: 0;font-family: \'Montserrat\';background-color: #ffdfbf;border: 1px solid #f2cca5;border-radius: 7px;color: #000;">'; 
			html += '<div class="row justify-content-center">'; 
				html += '<div class="col-lg-12 col-12" style="padding: 20px;">'; 
					html += '<h4>'; 
					html += '<i class="fa fa-exclamation"></i> Não encontramos resultados para a busca informada.</h4>'; 
					html += '<a style="color:#000081;cursor:pointer"><strong style="color:#000081;cursor:pointer">Por favor, considere refazer a pesquisa para encontrar resultados.</strong></a>'; 
				html += '</div>'; 
			html += '</div>'; 
		html += '</div>'; 
    }else if(contador == 0){
		html = "";

		var dataPrice = JSON.parse(localStorage.getItem("JSON_FILTER_PRICE_CARS"));   
		jQuery("#min_price_cars").val(Math.min.apply(Math, dataPrice));  
		jQuery("#max_price_cars").val(Math.max.apply(Math, dataPrice)); 

		jQuery("#rental_filter").val("all"); 

		html += '<div class="container" style="margin: 0;font-family: \'Montserrat\';background-color: #ffdfbf;border: 1px solid #f2cca5;border-radius: 7px;color: #000;">'; 
			html += '<div class="row justify-content-center">'; 
				html += '<div class="col-lg-12 col-12" style="padding: 20px;">'; 
					html += '<h4>'; 
					html += '<i class="fa fa-exclamation"></i> Não encontramos resultados para os filtros selecionados.</h4>'; 
					html += '<a onclick="filter_results_cars(1)" style="color:#000081;cursor:pointer"><strong style="color:#000081;cursor:pointer">Remover os filtros para ver todos os resultados.</strong></a>'; 
				html += '</div>'; 
			html += '</div>'; 
		html += '</div>';
	}


	jQuery("#offersCars").html(html);
	
}

function show_page_cars(page){

	jQuery("#pageActiveCars").val(page);

	var contador_prox = page*10;

	var contador_prev = contador_prox-10;

	list_results_cars(contador_prox, contador_prev);

	jQuery("html, body").animate({ scrollTop: 0 }, "slow"); 

}

function changeOrderResults(){
	var typeOrder = jQuery("#type_order").val();
	var data = JSON.parse(localStorage.getItem("FORMATTED_RESULT_CARS"));

	if(typeOrder == 0){
		storage_json_cars(1);
		list_results_cars(10, 0);
	}else if(typeOrder == 1){
		data.sort(sortCarsByPriceMinus);
		localStorage.setItem("FORMATTED_RESULT_CARS", JSON.stringify(data));
		list_results_cars(10, 0);
	}else if(typeOrder == 2){
		data.sort(sortCarsByPriceMaxis);
		localStorage.setItem("FORMATTED_RESULT_CARS", JSON.stringify(data));
		list_results_cars(10, 0);
	}   
	
}

function change_filter_rental(rental){
	var val_filter = jQuery("#rental_filter").val();

	var desc_val_filter = [];

	if(rental !== "all"){

		jQuery("#inlineCheckbox6").removeAttr("disabled"); 
		jQuery("#inlineCheckbox6").removeAttr("checked"); 

		var innerObj = {};

		for(var i = 0; i < 5; i++){

			if (jQuery("#inlineCheckbox"+i).is(':checked') == true) { 
				desc_val_filter.push(jQuery("#inlineCheckbox"+i).val()); 
			}

		} 

		jQuery("#rental_filter").val(JSON.stringify(desc_val_filter));

	}else{

		for(var i = 0; i < 5; i++){ 
			jQuery("#inlineCheckbox"+i).removeAttr("checked"); 
		} 

		jQuery("#inlineCheckbox6").attr("disabled", "disabled");  
		jQuery("#inlineCheckbox6").prop("disabled", true);

		jQuery("#rental_filter").val("all");

	} 

	if(jQuery("#inlineCheckbox0").is(':checked') == false && jQuery("#inlineCheckbox1").is(':checked') == false && jQuery("#inlineCheckbox2").is(':checked') == false && jQuery("#inlineCheckbox3").is(':checked') == false && jQuery("#inlineCheckbox4").is(':checked') == false && jQuery("#inlineCheckbox5").is(':checked') == false){  

		jQuery("#inlineCheckbox6").attr("disabled", "disabled"); 
		jQuery("#inlineCheckbox6").prop("disabled", true);  

		jQuery("#inlineCheckbox6").attr("checked", "checked"); 
		jQuery("#inlineCheckbox6").prop("checked", true);
 
		jQuery("#rental_filter").val("all"); 

	}

	filter_results_cars();
}

function filter_results_cars(type = null){

	if(type == 1){
		for(var i = 0; i < 5; i++){ 
			jQuery("#inlineCheckbox"+i).removeAttr("checked"); 
		}

		jQuery("#inlineCheckbox6").attr("disabled", "disabled"); 
		jQuery("#inlineCheckbox6").prop("disabled", true);  
		jQuery("#inlineCheckbox6").attr("checked", "checked");  
		jQuery("#inlineCheckbox6").prop("checked", true);

		var stepsSlider = document.getElementById('steps-slider-cars'); 
		stepsSlider.noUiSlider.destroy();
		var dataPrice = JSON.parse(localStorage.getItem("JSON_FILTER_PRICE_CARS")); 

		noUiSlider.create(stepsSlider, {
		    start: [parseInt(Math.min.apply(Math, dataPrice)), parseInt(Math.max.apply(Math, dataPrice))],
		    connect: true,
		    tooltips: [true, wNumb({decimals: 1})],
		    range: {
		        'min': parseInt(Math.min.apply(Math, dataPrice)),
		        'max': parseInt(Math.max.apply(Math, dataPrice))
		    }
		}); 

		stepsSlider.noUiSlider.on('change', function (values, handle) { 
		    if(handle == 0){
		    	jQuery("#min_price_cars").val(values[handle]); 
		    }
		    if(handle == 1){ 
		    	jQuery("#max_price_cars").val(values[handle]);
		    }  

		    filter_results_cars();
		}); 

	}

	var data = JSON.parse(localStorage.getItem("ALL_RESULT_CARS"));

	var resultFormatted = storage_json_cars(2);

	var minPrice = jQuery("#min_price_cars").val();
	var maxPrice = jQuery("#max_price_cars").val();

	var rental = jQuery("#rental_filter").val(); 
	if(rental == "all"){
		var jsonFilterRental = JSON.parse(localStorage.getItem("JSON_FILTER_RENTAL_CARS"));
		var dataJsonFilterRental = [];
		jQuery(jsonFilterRental).each(function(i, item) { 
			dataJsonFilterRental.push(item.carRental);
		});
		var arrayRental = dataJsonFilterRental;
	}else{
		var arrayRental = JSON.parse(rental);
	} 

	var dataFiltered = [];
	var contador = 0;
	jQuery(resultFormatted).each(function(i, item) {   

		var innerObj = {};   

		if((item.price.totalDaily <= maxPrice && item.price.totalDaily >= minPrice) && (jQuery.inArray(item.detailIncluded.vehicleRental[0].carRental, arrayRental) !== -1)){

			innerObj = item; 

			dataFiltered.push(innerObj);  

			contador = contador+1; 
		}  

	}); 
 
	dataFiltered.sort(sortCarsByPriceMinus);
	localStorage.setItem("FORMATTED_RESULT_CARS", JSON.stringify(dataFiltered)); 
	list_results_cars(10, 0);

}

function select_car_order(id){
	var jsonResultCars = JSON.parse(localStorage.getItem("FORMATTED_RESULT_CARS"));
	localStorage.setItem("SELECTED_ORDER_CARS", JSON.stringify(jsonResultCars[id]));
	window.location.href = '/checkout-cars/';
}