<?php   

/* ********************************************************************************* */

	/* HELPERS */
	function removeAcentsString($string){ 
		return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string); 
	}  

	function actionWpMailFailedCars($wp_error){ 
		return error_log(print_r($wp_error, true));
	} 

    function setContentTypeCarsMail(){ 
        return "text/html"; 
    } 

    add_filter( 'wp_mail_content_type','setContentTypeCarsMail' );  
    add_action( 'wp_mail_failed', 'actionWpMailFailedCars', 10, 1 );

	/* INSERE PÁGINAS DO FLUXO */

	/* RECUPERA PÁGINA PELO SLUG NO BANCO DE DADOS */
	function getPageSlugCars($page_slug, $output = OBJECT, $post_type = 'page' ) { 

	  	global $wpdb; 

	   	$page = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s AND post_status = 'publish'", $page_slug, $post_type ) ); 

		if ( $page ) 

	        return get_post($page, $output); 

	    return false; 

	}

	/* INSERE PÁGINA DO FLUXO */
	function insertPageCars($titlePost, $namePost, $contentPost){ 
		global $wpdb;
		$wpdb->insert($wpdb->posts, array(  
	        'comment_status' => 'close',  
	        'ping_status'    => 'close',  
	        'post_author'    => 1,  
	        'post_title'     => ucwords($titlePost),  
	        'post_name'      => $namePost,  
	        'post_status'    => 'publish',  
	        'post_content'   => $contentPost,  
	        'post_type'      => 'page'  
	    ));
	}

	$check_page_exist = getPageSlugCars('offers-cars');   
	if(!$check_page_exist) { 
		insertPageCars('Resultados - Veículos', 'offers-cars', '[TTBOOKING_MOTOR_RESULTS_CARS]');
	}

	$check_page_exist_checkout = getPageSlugCars('checkout-cars');   
	if(!$check_page_exist_checkout) { 
		insertPageCars('Finalizar compra - Veículos', 'checkout-cars', '[TTBOOKING_MOTOR_CHECKOUT_CARS]');
	}

	$check_page_exist_final = getPageSlugCars('order-cars');   
	if(!$check_page_exist_final) { 
		insertPageCars('Pedido recebido - Veículos', 'order-cars', '[TTBOOKING_MOTOR_ORDER_CARS]');
	}
	/* FIM INSERE PÁGINAS DO FLUXO */

/* ********************************************************************************* */