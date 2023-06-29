<?php 

	/* FUNÇÃO GET TOKEN EHTL */
	add_action( 'wp_ajax_search_cars', 'search_cars' ); 
	add_action( 'wp_ajax_nopriv_search_cars', 'search_cars' );  
	function search_cars() { 

		$token          = $_POST['accessToken'];
		$locationPickup = $_POST['locationPickup'];
		$locationReturn = $_POST['locationReturn'];
		$datePickup     = implode("-", array_reverse(explode("/", $_POST['datePickup'])));
		$timePickup     = $_POST['timePickup'];
		$dateReturn     = implode("-", array_reverse(explode("/", $_POST['dateReturn'])));
		$timeReturn     = $_POST['timeReturn']; 

		$curl = curl_init();

        curl_setopt_array($curl, array(
          	CURLOPT_URL => "https://quasar.e-htl.com.br/vehicle/availabilities",
          	CURLOPT_RETURNTRANSFER => true,
          	CURLOPT_ENCODING => "",
          	CURLOPT_MAXREDIRS => 10,
          	CURLOPT_TIMEOUT => 400,
          	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          	CURLOPT_CUSTOMREQUEST => "POST",
          	CURLOPT_POSTFIELDS => '{ "data": { "attributes": { "pickUpLocation": "'.$locationPickup.'", "returnLocation": "'.$locationReturn.'", "pickUpDateTime": "'.$datePickup.' '.$timePickup.':00", "returnDateTime": "'.$dateReturn.' '.$timeReturn.':00" } } }',
          	CURLOPT_HTTPHEADER => array(
            	"authorization: Bearer ".$token,  
            	"content-type: application/json" 
          	),
        ));

        $response2 = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            
            echo $response2;  

        }   
	}