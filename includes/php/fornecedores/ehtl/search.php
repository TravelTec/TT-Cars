<?php 

	/* FUNÇÃO GET TOKEN EHTL */
	add_action( 'wp_ajax_get_token', 'get_token' ); 
	add_action( 'wp_ajax_nopriv_get_token', 'get_token' );  
	function get_token() { 

	    $curl = curl_init(); 
	    curl_setopt_array($curl, array( 
	      	CURLOPT_URL => "https://quasar.e-htl.com.br/oauth/access_token", 
	      	CURLOPT_RETURNTRANSFER => TRUE, 
	      	CURLOPT_ENCODING => "", 
	      	CURLOPT_MAXREDIRS => 10, 
	      	CURLOPT_TIMEOUT => 30, 
	      	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, 
	      	CURLOPT_CUSTOMREQUEST => "POST", 
	      	CURLOPT_POSTFIELDS => "username=".get_option( 'user_ehtl' )."&password=".get_option( 'pass_ehtl' ), 
	      	CURLOPT_HTTPHEADER => array( 
	        	"cache-control: no-cache",  
	        	"x-detailed-error: " 
	      	), 
	    )); 

	    $response = curl_exec($curl);

	    $err = curl_error($curl); 

	    curl_close($curl); 

	    if ($err) {

	        echo "cURL Error #:" . $err;

	    } else {

	        $itens = json_decode($response, true);

	        $token = $itens["access_token"]; 

	        echo $token;

	    }   

	}
	/* FIM FUNÇÃO GET TOKEN EHTL */

	/* FUNÇÃO GET CITY */	
	add_action( 'wp_ajax_get_city', 'get_city' ); 
	add_action( 'wp_ajax_nopriv_get_city', 'get_city' );  
	function get_city() {

		$local = removeAcentsString(str_replace(" ", "%20", $_POST['local']));
		$token = $_POST['token'];

	    $curl = curl_init(); 
		curl_setopt_array($curl, array( 
			CURLOPT_URL => "https://quasar.e-htl.com.br/vehicle/stores?filter[store]=".$local, 
			CURLOPT_RETURNTRANSFER => true, 
			CURLOPT_ENCODING => "", 
			CURLOPT_MAXREDIRS => 10, 
			CURLOPT_TIMEOUT => 500, 
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, 
			CURLOPT_CUSTOMREQUEST => "GET", 
			CURLOPT_HTTPHEADER => array( 
				"authorization: Bearer ".$token,
				"cache-control: no-cache", 
				"content-type: application/json" 
			), 
		)); 

		$response = curl_exec($curl);

		$err = curl_error($curl); 

		curl_close($curl); 

		if ($err) { 
			echo "cURL Error #:" .
			$err; 
		} else {

		    $itens = json_decode($response, true);
		    $resultados = $itens["data"];

		    $retorno = [];
		    for($i = 0; $i < 15; $i++){
		    	if($resultados[$i] !== null){
		    		$retorno[] = $resultados[$i];
		    	}
		    }

			echo json_encode($retorno); 

		} 

	}

	/* FIM FUNÇÃO GET CITY */ 