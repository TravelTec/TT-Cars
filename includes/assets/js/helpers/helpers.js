function formatPriceList(price){
	var xx = new Intl.NumberFormat('pt-BR', {  
	  	currency: 'BRL', 
	  	minimumFractionDigits: 2, 
	  	maximumFractionDigits: 2 
	});
	return xx.format(price);
}

function sortCarsByPriceMinus(a, b){

	var aName = a.price.totalDaily; 
  	var bName = b.price.totalDaily; 

  	return ((aName < bName) ? -1 : ((aName > bName) ? 1 : 0));

}

function sortCarsByPriceMaxis(b, a){

	var aName = a.price.totalDaily; 
  	var bName = b.price.totalDaily; 

  	return ((aName < bName) ? -1 : ((aName > bName) ? 1 : 0));

}

function select_card(){
	var number = jQuery("#number-card").val();
	
	var operadora = checkCreditCard(number);
	var img = "";
	if(operadora == "Mastercard"){
		img = '<img src="https://logodownload.org/wp-content/uploads/2014/07/mastercard-logo-6-1.png" style="width: 58%;float: right;">';
	}else if(operadora == "visa"){
		img = '<img src="https://logodownload.org/wp-content/uploads/2016/10/visa-logo-19.png" style="width: 58%;float: right;">';
	}else if(operadora == "Diners"){
		img = '<img src="https://logodownload.org/wp-content/uploads/2016/10/Diners-Club-Logo-5.png" style="width: 58%;float: right;">';
	}else if(operadora == "Amex"){
		img = '<img src="https://logodownload.org/wp-content/uploads/2014/04/amex-american-express-logo-4.png" style="width: 58%;float: right;">';
	}
	jQuery(".bank-card__operadora").html(img);
}

function checkCreditCard(num){
	var msg = Array();
	var tipo = null;
	var operadora = "";
	
	if(num.length > 16 || num[0]==0){
		
		msg.push("Número de cartão inválido");
		
	} else {
		
		var total = 0;
		var arr = Array();
		
		for(i=0;i<num.length;i++){
			if(i%2==0){
				dig = num[i] * 2;
					
				if(dig > 9){
					dig1 = dig.toString().substr(0,1);
					dig2 = dig.toString().substr(1,1);
					arr[i] = parseInt(dig1)+parseInt(dig2);
				} else {
					arr[i] = parseInt(dig);
				}
							
				total += parseInt(arr[i]);
	
			} else {
	
				arr[i] =parseInt(num[i]);
				total += parseInt(arr[i]);
			} 
		}
				
		switch(parseInt(num[0])){
			case 0:
				msg.push("Número incorreto");
				break;
			case 1:
				tipo = "Empresas Aéreas";
				break;
			case 2:
				tipo = "Empresas Aéreas";
				break
			case 3:
				tipo = "Viagens e Entretenimento";
				if(parseInt(num[0]+num[1]) == 34 || parseInt(num[0]+num[1])==37){	operadora = "Amex";	} 
				if(parseInt(num[0]+num[1]) == 36){	operadora = "Diners";	} 
				break
			case 4:
				tipo = "Bancos e Instituições Financeiras";
				operadora = "visa";
				break
			case 5:
				if(parseInt(num[0]+num[1]) >= 51 && parseInt(num[0]+num[1])<=55){	operadora = "Mastercard";	} 
				tipo = "Bancos e Instituições Financeiras";
				operadora = "Mastercard"
				break;
			case 6:
				tipo = "Bancos e Comerciais";
				operadora = "";
				break
			case 7:
				tipo = "Companhias de petróleo";
				operadora = "";
				break
			case 8:
				tipo = "Companhia de telecomunicações";
				operadora = "";
				break
			case 9:
				tipo = "Nacionais";
				operadora = "";
				break
			default:
				msg.push("Número incorreto");
				break;
		}

	}
	return operadora;

}

function limpaFormCep() {
    // Limpa valores do formulário de cep.
    jQuery("#endereco").val("");
    jQuery("#bairro").val("");
    jQuery("#cidade").val("");
    jQuery("#estado").val(""); 
}

//Quando o campo cep perde o foco.
jQuery("#cep").blur(function() {

    //Nova variável "cep" somente com dígitos.
    var cep = jQuery(this).val().replace('-', '').replace(/\D/g, ''); 

    //Verifica se campo cep possui valor informado.
    if (cep != "") { 

        //Preenche os campos com "..." enquanto consulta webservice.
        jQuery("#endereco").val("...");
        jQuery("#bairro").val("...");
        jQuery("#cidade").val("...");
        jQuery("#estado").val("..."); 

        //Consulta o webservice viacep.com.br/
        jQuery.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

            if (!("erro" in dados)) {
                //Atualiza os campos com os valores da consulta.
                jQuery("#endereco").val(dados.logradouro);
                jQuery("#bairro").val(dados.bairro);
                jQuery("#cidade").val(dados.localidade);
                jQuery("#estado").val(dados.uf); 
            } //end if.
            else {
                //CEP pesquisado não foi encontrado.
                limpaFormCep();
                swal({
		            title: "CEP não encontrado.",
		            icon: "warning",
		        }); 
		        return false;
            }
        });
         
    } //end if.
    else {
        //cep sem valor, limpa formulário.
        limpaFormCep();
    }
});