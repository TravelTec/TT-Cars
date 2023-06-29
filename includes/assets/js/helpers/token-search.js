jQuery(function() {
	get_token(); 
});

function get_token(){

	var data = { 
        'action': 'get_token', 
    }; 

    jQuery.ajax({ 
        url: jQuery("#url_ajax").val(), 
        type: 'post', 
        data: data, 
        success : function( resposta ) { 
        	var token = resposta.slice(0, -1);
            localStorage.setItem("ACCESS_TOKEN_CARS", token);
        } 
    });

}