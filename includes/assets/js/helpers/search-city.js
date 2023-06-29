function get_city(local){

	var data = { 
        'action': 'get_city', 
        'local': 'sao',
        'token': localStorage.getItem("ACCESS_TOKEN_CARS")
    }; 

    var retorno = [];
    jQuery.ajax({ 
        url: jQuery("#url_ajax").val(), 
        type: 'post', 
        data: data, 
        success : function( resposta ) { 
            retorno = resposta.slice(0,-1);
        } 
    });
    return retorno;

}