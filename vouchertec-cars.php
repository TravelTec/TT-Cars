<?php   

	/* 
		Plugin Name: Voucher Tec - Veículos 
		Plugin URI: https://github.com/TravelTec/TT-Cars
		GitHub Plugin URI: https://github.com/TravelTec/TT-Cars  
		Description:  O Plugin Travel Tec - Veículos é um plugin desenvolvido para agências e operadoras de turismo que precisam tratar reserva de veículos de fornecedores, com integração ao fornecedor E-htl. 
		Version: 1.0.0 
		Author: Travel Tec 
		Author URI: https://traveltec.com.br 
		License: GPLv2  
	*/ 

	require 'includes/php/mail/Mail.php';

	require 'includes/php/fornecedores/ehtl/search.php';
	require 'includes/php/fornecedores/ehtl/results.php';

	require 'includes/php/helpers.php';
	require 'plugin-update-checker-4.10/plugin-update-checker.php';

	/* VERIFICA ATUALIZAÇÕES NO GITHUB */
	add_action( 'admin_init', 'cars_update_checker_setting' );  
	function cars_update_checker_setting() {  
		register_setting( 'vouchertec-cars', 'serial' );  

	    if ( ! is_admin() || ! class_exists( 'Puc_v4_Factory' ) ) {  
	        return;   
	    }   

	    $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(  
	        'https://github.com/TravelTec/TT-Cars',   
	        __FILE__,   
	        'TT-Cars'  
	    );   

	    $myUpdateChecker->setBranch('main');   
	}
	/* FIM VERIFICA ATUALIZAÇÕES NO GITHUB */

	/* FUNÇÕES ADMIN */

		/* INSERE SCRIPTS ADMIN */
		add_action( 'admin_enqueue_scripts', 'enqueue_scripts_admin_cars' ); 
		function enqueue_scripts_admin_cars() {

		    wp_enqueue_script( 'sweetalert-cars', 'https://unpkg.com/sweetalert/dist/sweetalert.min.js');

		    wp_enqueue_script( 
		        'scripts-admin-cars',
		        plugin_dir_url( __FILE__ ) . 'includes/assets/js/cars-admin.js?v='.date("dmYHis"),
		        array( 'jquery' ),
		        false,
		        true
		    );

		    wp_localize_script( 
		        'scripts-admin-cars',
		        'wp_ajax',
		        array( 
		            'ajaxurl' => admin_url( 'admin-ajax.php' ),
		            'dede' => 1234
		        )                 
		    );

		} 
		/* FIM INSERE SCRIPTS ADMIN */

		/* INSERE PÁGINA VEÍCULOS NO ADMIN */
		add_action('admin_menu', 'menu_cars');  
    	function menu_cars() {  
  			add_menu_page(   
      			'Veículos',  
      			'TT - Veículos',  
      			'edit_posts',  
      			'ttcars',  
      			'gerador_de_conteudo_cars',  
      			'dashicons-car'  
     		); 
		} 

	    function gerador_de_conteudo_cars() { ?>

	        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css?ver=1.0">

	        <style>

	        	.footer,.tab-content{background-color:#fff}#copied_tip,.tip{animation-fill-mode:both}#wpcontent{background-color:#f0f0f0;padding:0;font-family:Montserrat}#wpfooter{display:none}.header{padding:25px 30px}.content{padding:25px 0;min-height:200px}.footer{padding:20px;position:absolute;bottom:0;width:100%}.header h2{font-size:36px;font-weight:400;font-family:Montserrat}.header p,.nav-link{font-size:14px;font-family:Montserrat}.header p{margin-bottom:0}.footer p{font-family:Montserrat;font-size:11px}.footer p.copyright,.footer p.links{margin-bottom:7px}.footer p.redes{margin-bottom:0}.footer p.links .divisor{font-weight:600;color:#858585;margin:0 4px}.footer p.copyright{font-weight:600;color:#858585}.footer p.redes i{font-size:16px;color:#858585;margin-right:4px}.nav-item{margin-bottom:-1px}.nav-link{border:none;padding:12px 25px;font-weight:600}.nav-tabs{border:none;padding:0 30px}.nav-tabs .nav-item.show .nav-link,.nav-tabs .nav-link.active,.nav-tabs .nav-link:focus,.nav-tabs .nav-link:hover{border:0}.tab-content{padding:45px 30px}.tip,.tip:before{background-color:#263646;position:absolute}.copy-button{height:36px;margin-left:-4px;margin-top:-2px;border-radius:0 5px 5px 0;margin-right:5px}.tip{padding:0 14px;line-height:27px;border-radius:4px;z-index:100;color:#fff;font-size:12px;animation-name:tip;animation-duration:.6s}.tip:before{content:"";height:10px;width:10px;display:block;transform:rotate(45deg);top:-4px;left:17px}#copied_tip{animation-name:come_and_leave;animation-duration:1s;bottom:-35px;left:2px}.text-line{font-weight:600;background-color:#d5d5d5;padding:8px;border-radius:5px 0 0 5px;margin-left:5px}.btn-check:active+.btn-primary:focus,.btn-check:checked+.btn-primary:focus,.btn-primary.active:focus,.btn-primary:active:focus,.show>.btn-primary.dropdown-toggle:focus{box-shadow:none!important}.form-label{font-size:14px;font-weight:600}.form-control{height:40px;border:1px solid #e2e2e2!important;border-radius:0!important}.wp-core-ui p .button{padding:10px 20px;font-size:15px}

	        </style>

	        <div class="header">
	        	<h2>Locação de Veículos</h2> 
	        	<p>Integre facilmente o seu fornecedor de veículos ao seu site.</p>
	        </div>

	        <div class="content">

		        <ul class="nav nav-tabs" id="myTab" role="tablist">
		  			<li class="nav-item" role="presentation">
		    			<button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Sobre</button>
		  			</li>
		  			<li class="nav-item" role="presentation">
		    			<button class="nav-link" id="profileCars-tab" data-bs-toggle="tab" data-bs-target="#profileCars" type="button" role="tab" aria-controls="profileCars" aria-selected="false">Configuração</button>
		  			</li>
		  			<li class="nav-item" role="presentation">
		    			<button class="nav-link" id="contactCars-tab" data-bs-toggle="tab" data-bs-target="#contactCars" type="button" role="tab" aria-controls="contactCars" aria-selected="false">Licenciamento</button>
		  			</li>
				</ul>
				<div class="tab-content" id="myTabContent">
		  			<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab"> 

		  				<p style="font-size:17px;line-height: 1.8"> O Plugin <strong>Travel Tec - Veículos</strong> é um plugin desenvolvido para agências e operadoras de turismo que precisam tratar reserva de veículos de fornecedores, com integração ao fornecedor E-htl.</p>

		  				<p style="font-size:17px;line-height: 1.8">Use o shortcode <span class="text-line">[TTBOOKING_MOTOR_RESERVA_CARS]</span>  <button onclick="copyCars('[TTBOOKING_MOTOR_RESERVA_CARS]','#copy_button_cars')" id="copy_button_cars" class="btn btn-sm btn-primary copy-button" data-toggle="tolltip" data-placement="top" tilte="Copiar shortcode">Copiar</button> para adicionar o motor de reserva em qualquer página.</p>

		  			</div>
		  			<div class="tab-pane fade" id="profileCars" role="tabpanel" aria-labelledby="profileCars-tab">

		  				<div class="row">
		  					<div class="col-lg-6 col-12">
		  						<ul class="nav nav-tabs" id="myTabCredencial" role="tablist" style="padding: 0px;">
						  			<li class="nav-item" role="presentation">
						    			<button class="nav-link active" id="credencial-tab" data-bs-toggle="tab" data-bs-target="#credencial" type="button" role="tab" aria-controls="home" aria-selected="true" style="border: none;background-color: #ebebeb;">Credenciais</button>
						  			</li>
								</ul>
								<div class="tab-content" id="myTabContentCredencial" style="background-color: #ebebeb;height: 355px;">
						  			<div class="tab-pane fade show active" id="credencial" role="tabpanel" aria-labelledby="credencial-tab">  

						  				<h5 style="margin-bottom:20px;">E-Htl</h5>

					  					<div style="height: 160px;">

							  				<div class="mb-3"> 
												<label for="user_ehtl" class="form-label">Usuário</label> 
												<input type="text" class="form-control" id="user_ehtl" name="user_ehtl" value="<?=(empty(get_option( 'user_ehtl' )) ? '' : get_option( 'user_ehtl' ))?>"> 
											</div>

							  				<div class="mb-3"> 
												<label for="pass_ehtl" class="form-label">Senha</label> 
												<input type="text" class="form-control" id="pass_ehtl" name="pass_ehtl" value="<?=(empty(get_option( 'pass_ehtl' )) ? '' : get_option( 'pass_ehtl' ))?>"> 
											</div>  

										</div>

										<?php submit_button(); ?> 

						  			</div>
						  		</div>
		  					</div> 

		  					<div class="col-lg-6 col-12">
		  						<ul class="nav nav-tabs" id="myTabEstilo" role="tablist" style="padding: 0px;">
						  			<li class="nav-item" role="presentation">
						    			<button class="nav-link active" id="estilo-tab" data-bs-toggle="tab" data-bs-target="#estilo" type="button" role="tab" aria-controls="home" aria-selected="true" style="border: none;background-color: #ebebeb;">Estilização</button>
						  			</li> 
								</ul>
								<div class="tab-content" id="myTabContentEstilo" style="background-color: #ebebeb;height: 355px;">
						  			<div class="tab-pane fade show active" id="estilo" role="tabpanel" aria-labelledby="estilo-tab"> 

					  					<div style="height: 190px;">

					  						<input type="hidden" id="type_reserva_cars" value="<?=get_option( 'type_reserva_cars' )?>">

					  						<div class="mb-3">
						  						<div class="form-check form-check-inline">
												  	<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1" style="    margin-top: 4px;" <?=(get_option( 'type_reserva_cars' ) == 1 ? 'checked' : '')?> onclick="set_type_reserva_cars(1)">
												  	<label class="form-check-label" for="inlineRadio1" style="    font-size: 14px;">Cotação</label>
												</div>
												<div class="form-check form-check-inline">
												  	<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="2" style="    margin-top: 4px;" <?=(get_option( 'type_reserva_cars' ) == 2 ? 'checked' : '')?> onclick="set_type_reserva_cars(2)">
												  	<label class="form-check-label" for="inlineRadio2" style="    font-size: 14px;">Reserva</label>
												</div> 
												<p style="font-size: 11px;margin: 11px 0px;">Selecione o tipo da solicitação: reserva, para compra online, e cotação, para envio dos dados por e-mail.</p>
											</div> 

											<div class="row">
												<div class="col-lg-6 col-12">
									  				<div class="mb-3">
														<label for="cor_cars" class="form-label">Cor principal</label>
														<input type="color" class="form-control form-control-color" id="cor_cars" name="cor_cars" value="<?=(empty(get_option( 'cor_cars' )) ? '#000000' : get_option( 'cor_cars' ))?>" title="Selecione uma cor">
														<p style="font-size: 11px;margin: 11px 0px;">A cor informada será utilizada ao longo de todo o sistema.</p>
													</div> 
												</div>
												<div class="col-lg-6 col-12">
									  				<div class="mb-3">
														<label for="cor_botao_cars" class="form-label">Cor dos botões</label>
														<input type="color" class="form-control form-control-color" id="cor_botao_cars" name="cor_botao_cars" value="<?=(empty(get_option( 'cor_botao_cars' )) ? '#000000' : get_option( 'cor_botao_cars' ))?>" title="Selecione uma cor"> 
													</div> 
												</div>
											</div>

										</div>

										<?php submit_button(); ?>

						  			</div>
						  		</div>
		  					</div>
		  				</div>

		  			</div>
		  			<div class="tab-pane fade" id="contactCars" role="tabpanel" aria-labelledby="contact-tab">

	  					<div class="col-lg-6 col-12">
	  						<ul class="nav nav-tabs" id="myTabCredencial" role="tablist" style="padding: 0px;">
					  			<li class="nav-item" role="presentation">
					    			<button class="nav-link active" id="credencialFlights-tab" data-bs-toggle="tab" data-bs-target="#credencialFlights" type="button" role="tab" aria-controls="home" aria-selected="true" style="border: none;background-color: #ebebeb;">Dados da licença</button>
					  			</li>
							</ul>
							<div class="tab-content" id="myTabContentCredencial" style="background-color: #ebebeb;height: 355px;">
					  			<div class="tab-pane fade show active" id="credencialCars" role="tabpanel" aria-labelledby="credencial-tab">  

				  					<div style=" ">

						  				<div class="mb-3">
											<label for="chave_licenca_cars" class="form-label">Chave</label>
											<input type="text" class="form-control" id="chave_licenca_cars" name="chave_licenca_cars" value="<?=(empty(get_option( 'chave_licenca_cars' )) ? '' : get_option( 'chave_licenca_cars' ))?>">
										</div> 

									</div>

									<?php submit_button(); ?> 

					  			</div>
					  		</div>
	  					</div> 

		  			</div>
				</div> 

			</div>

			<div class="footer text-center"> 
				<p class="copyright">
					<img src="https://traveltec.com.br/wp-content/uploads/2021/08/Logotipo-Pequeno.png" style="height: 20px;margin-bottom: 10px;">
					<br>
					Desenvolvido por Travel Tec © <?=date("Y")?> - Todos os direitos reservados
				</p>
				<p class="links">
					<a href="/">Suporte</a> <span class="divisor">|</span> <a href="/">Site oficial</a> <span class="divisor">|</span> <a href="/">Outros plugins</a>
				</p>
				<p class="redes">
					<i class="fa fa-instagram"></i>
					<i class="fa fa-youtube"></i>
				</p>
			</div>

			<script>
				jQuery(function(){
					jQuery("[data-toggle='tooltip']").tooltip();

					jQuery("#copy_button_cars").attr('title', 'Copiar shortcode').tooltip('_fixTitle');
				});

				function copyCars(text, target) {
					navigator.clipboard.writeText('[TTBOOKING_MOTOR_RESERVA_CARS]');

					jQuery("#copy_button_cars").attr('title', 'Copiado!').tooltip('_fixTitle').tooltip('show');

					setTimeout(function() {
						jQuery("#copy_button_cars").attr('title', 'Copiar shortcode').tooltip('_fixTitle').tooltip('show');
					}, 800);
				}
			</script>

			<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
			<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script> 

	        <?php 

	    } 
	    /* FIM INSERE PÁGINA VEÍCULOS NO ADMIN */

	    /* SALVA INFO CREDENCIAIS + ESTILO CARS */ 
	    add_action( 'wp_ajax_save_data_cars', 'save_data_cars' ); 
		add_action( 'wp_ajax_nopriv_save_data_cars', 'save_data_cars' ); 
	    function save_data_cars(){

	    	$user_ehtl      = $_POST['user_ehtl']; 
			$pass_ehtl      = $_POST['pass_ehtl']; 
			$cor_cars       = $_POST['cor_cars'];  
			$cor_botao_ehtl = $_POST['cor_botao_cars']; 
			$type_reserva   = $_POST['type_reserva']; 
			$licenca        = $_POST['licenca']; 

			update_option('user_ehtl', $user_ehtl); 
			update_option('pass_ehtl', $pass_ehtl);  
			update_option('cor_cars', $cor_cars); 
			update_option('cor_botao_cars', $cor_botao_ehtl); 
			update_option('type_reserva_cars', $type_reserva); 
			update_option('chave_licenca_cars', $licenca);

	    }
	    /* FIM SALVA INFO CREDENCIAIS + ESTILO CARS */

	/* FIM FUNÇÕES ADMIN */

	/* FUNÇÕES SITE */

		/* FUNÇÃO MOTOR DE PESQUISA */
		add_shortcode('TTBOOKING_MOTOR_RESERVA_CARS', 'shortcode_motor_reserva_cars');  
		function shortcode_motor_reserva_cars(){

			$retorno = ''; 

			$retorno .= '<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">';
			$retorno .= '<link rel="preconnect" href="https://fonts.googleapis.com">
						<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
						<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
				<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
				<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" />
				<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css?ver=1.0"> '; 

			$retorno .= '<style>.elementor-element.elementor-element-acb6c2c:not(.elementor-motion-effects-element-type-background),.elementor-element.elementor-element-acb6c2c>.elementor-motion-effects-container>.elementor-motion-effects-layer{background-color:'.(empty(get_option( 'cor_cars' )) ? '#000000' : get_option( 'cor_cars' )).'}.elementor-element.elementor-element-acb6c2c,.elementor-element.elementor-element-acb6c2c>.elementor-background-overlay{border-radius:20px}.elementor-element.elementor-element-acb6c2c{transition:background .3s,border .3s,border-radius .3s,box-shadow .3s;padding:20px 20px 0}.elementor-element.elementor-element-acb6c2c>.elementor-background-overlay{transition:background .3s,border-radius .3s,opacity .3s}.elementor-element.elementor-element-d9d121a{margin-top:0;margin-bottom:20px}.elementor-bc-flex-widget .elementor-element.elementor-element-01adafa.elementor-column .elementor-widget-wrap,.elementor-bc-flex-widget .elementor-element.elementor-element-3b1e8b6.elementor-column .elementor-widget-wrap,.elementor-bc-flex-widget .elementor-element.elementor-element-42a6a99.elementor-column .elementor-widget-wrap,.elementor-bc-flex-widget .elementor-element.elementor-element-641173c.elementor-column .elementor-widget-wrap,.elementor-bc-flex-widget .elementor-element.elementor-element-b23b9b1.elementor-column .elementor-widget-wrap,.elementor-bc-flex-widget .elementor-element.elementor-element-ebd2137.elementor-column .elementor-widget-wrap{align-items:center}.elementor-element.elementor-element-01adafa.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated,.elementor-element.elementor-element-3b1e8b6.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated,.elementor-element.elementor-element-42a6a99.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated,.elementor-element.elementor-element-641173c.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated,.elementor-element.elementor-element-b23b9b1.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated,.elementor-element.elementor-element-ebd2137.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated{align-content:center;align-items:center}.elementor-element.elementor-element-288335b .elementor-heading-title{color:#fff;font-family:Montserrat,Sans-serif;font-size:19px;font-weight:600}.elementor-element.elementor-element-01adafa>.elementor-element-populated,.elementor-element.elementor-element-3b1e8b6>.elementor-element-populated,.elementor-element.elementor-element-641173c>.elementor-element-populated,.elementor-element.elementor-element-ebd2137>.elementor-element-populated{padding:0 10px}.elementor-element.elementor-element-22f1764 .elementor-heading-title,.elementor-element.elementor-element-584d968 .elementor-heading-title,.elementor-element.elementor-element-a233389 .elementor-heading-title,.elementor-element.elementor-element-a4d2ecf .elementor-heading-title,.elementor-element.elementor-element-c02e80b .elementor-heading-title,.elementor-element.elementor-element-d17db1e .elementor-heading-title,.elementor-element.elementor-element-dce890b .elementor-heading-title,.elementor-element.elementor-element-f7427f1 .elementor-heading-title{color:#fff;font-family:Montserrat,Sans-serif;font-size:13px;font-weight:400}.elementor-bc-flex-widget .elementor-element.elementor-element-435780d.elementor-column .elementor-widget-wrap,.elementor-bc-flex-widget .elementor-element.elementor-element-5c6491f.elementor-column .elementor-widget-wrap,.elementor-bc-flex-widget .elementor-element.elementor-element-6950c01.elementor-column .elementor-widget-wrap,.elementor-bc-flex-widget .elementor-element.elementor-element-7ea0443.elementor-column .elementor-widget-wrap,.elementor-bc-flex-widget .elementor-element.elementor-element-d530a0d.elementor-column .elementor-widget-wrap{align-items:flex-start}.elementor-element.elementor-element-435780d.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated,.elementor-element.elementor-element-5c6491f.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated,.elementor-element.elementor-element-6950c01.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated,.elementor-element.elementor-element-7ea0443.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated,.elementor-element.elementor-element-d530a0d.elementor-column.elementor-element[data-element_type=column]>.elementor-widget-wrap.elementor-element-populated{align-content:flex-start;align-items:flex-start}.elementor-element.elementor-element-7ea0443>.elementor-element-populated{padding:10px 10px 0}.elementor-element.elementor-element-7b4195e>.elementor-widget-container{margin:0 0 -20px}.elementor-element.elementor-element-435780d>.elementor-element-populated,.elementor-element.elementor-element-5c6491f>.elementor-element-populated,.elementor-element.elementor-element-d530a0d>.elementor-element-populated{padding:10px}.elementor-element.elementor-element-50198cd .elementor-button .elementor-align-icon-right{margin-left:8px}.elementor-element.elementor-element-50198cd .elementor-button .elementor-align-icon-left{margin-right:8px}.elementor-element.elementor-element-50198cd .elementor-button{font-family:Montserrat,Sans-serif;font-weight:500;background-color:'.(empty(get_option( 'cor_botao_cars' )) ? '#000000' : get_option( 'cor_botao_cars' )).';border-radius:0 10px 10px 0}@media(max-width:767px){.elementor-element.elementor-element-7b4195e>.elementor-widget-container{margin:0 0 -24px}.elementor-element.elementor-element-18ecb14>.elementor-widget-container{margin:0 0 10px}.elementor-element.elementor-element-435780d>.elementor-widget-wrap>.elementor-widget:not(.elementor-widget__width-auto):not(.elementor-widget__width-initial):not(:last-child):not(.elementor-absolute),.elementor-element.elementor-element-5c6491f>.elementor-widget-wrap>.elementor-widget:not(.elementor-widget__width-auto):not(.elementor-widget__width-initial):not(:last-child):not(.elementor-absolute),.elementor-element.elementor-element-d530a0d>.elementor-widget-wrap>.elementor-widget:not(.elementor-widget__width-auto):not(.elementor-widget__width-initial):not(:last-child):not(.elementor-absolute){margin-bottom:10px}.elementor-element.elementor-element-e25685a>.elementor-widget-container{margin:0 0 -16px}.elementor-element.elementor-element-79efa4a>.elementor-widget-container,.elementor-element.elementor-element-f46449b>.elementor-widget-container{margin:0 0 -30px;padding:0}.elementor-element.elementor-element-50198cd .elementor-button{border-radius:20px}}@media(min-width:768px){.daterangepicker{width: 310px;}.elementor-element.elementor-element-3b1e8b6,.elementor-element.elementor-element-5c6491f{width:22.522%}.elementor-element.elementor-element-01adafa{width:21.885%}.elementor-element.elementor-element-b23b9b1{width:15.14%}.elementor-element.elementor-element-435780d{width:22.314%}.elementor-element.elementor-element-6950c01{width:15.143%}}@media(max-width:566px){.inputTextVehicle,.selectVehicle{font-size:13px!important;font-family:Montserrat;border:0!important}.input-group-text{background-color:#fff!important;border:0!important;padding:10px 2px 10px 10px!important}}.otherDevol{color:#fff;font-size:13px;font-family:Montserrat;margin-bottom:0}@media(min-width:567px){.inputTextVehicle,.selectVehicle{font-size:13px!important;font-family:Montserrat;border:0!important}.input-group-text{background-color:#fff!important;border:0!important;padding:10px 2px 10px 10px!important;height:47px}.elementor-element-3dbb6cd,.elementor-element-9408668{width:42%!important;padding:0 4px!important;bottom:46px!important;position:absolute!important;right:0}#selectVehicleDevolucao,#selectVehicleRetirada{height:47px!important}}.form-control:focus{box-shadow: none!important} .daterangepicker{box-shadow:0 1rem 3rem rgba(0,0,0,.175)!important;border:none;}
			.daterangepicker td.in-range{background-color:'.(empty(get_option( 'cor_cars' )) ? '#000000' : get_option( 'cor_cars' )).'54;cor:'.(empty(get_option( 'cor_cars' )) ? '#000000' : get_option( 'cor_cars' )).'54;}
			.daterangepicker td.active, .daterangepicker td.active:hover {background-color:'.(empty(get_option( 'cor_cars' )) ? '#000000' : get_option( 'cor_cars' )).';border-color:transparent;color:#fff;}
			.daterangepicker td.available:hover, .daterangepicker th.available:hover{background-color:'.(empty(get_option( 'cor_cars' )) ? '#000000' : get_option( 'cor_cars' )).'99;color:#fff;border-radius:40px} .dados,.dados:after{position:absolute}.dados ul li{margin-top:0!important}.dados:after{bottom:100%;left:15px;border:7px solid transparent;content:" ";height:0;width:0;pointer-events:none;border-bottom-color:#ddd;margin-left:-7px}.dados ul li:hover{background-color:#f1f1f1}
			.daterangepicker .calendar-table th, .daterangepicker .calendar-table td{font-family:Montserrat;padding:9px}</style>';

			$retorno .= '<input type="hidden" id="url_ajax" value="'.admin_url('admin-ajax.php').'">';
			$retorno .= '<input type="hidden" id="type_delivery" value="0">';
			$retorno .= '<input type="hidden" id="type_motor" value="0">';

			$retorno .= '<section class="elementor-section elementor-top-section elementor-element elementor-element-eaf436a elementor-section-boxed elementor-section-height-default elementor-section-height-default wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no" data-id="eaf436a" data-element_type="section">
			    <div class="elementor-container elementor-column-gap-default">
			        <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-9d08813" data-id="9d08813" data-element_type="column">
			            <div class="elementor-widget-wrap elementor-element-populated">
			                <div class="elementor-element elementor-element-429c752 elementor-widget elementor-widget-wpr-post-content" data-id="429c752" data-element_type="widget" data-widget_type="wpr-post-content.default">
			                    <div class="elementor-widget-container">
			                        <div class="wpr-post-content">
			                            <div data-elementor-type="wp-page" data-elementor-id="1805" class="elementor elementor-1805">
			                                <section class="elementor-section elementor-top-section elementor-element elementor-element-acb6c2c elementor-section-boxed elementor-section-height-default elementor-section-height-default wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no" data-id="acb6c2c" data-element_type="section" data-settings=\'{"background_background":"classic"}\'>
			                                    <div class="elementor-container elementor-column-gap-default">
			                                        <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-d8753b8" data-id="d8753b8" data-element_type="column">
			                                            <div class="elementor-widget-wrap elementor-element-populated">
			                                                <section class="elementor-section elementor-inner-section elementor-element elementor-element-d9d121a elementor-section-boxed elementor-section-height-default elementor-section-height-default wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no"
			                                                    data-id="d9d121a" data-element_type="section">
			                                                    <div class="elementor-container elementor-column-gap-default">
			                                                        <div class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-42a6a99" data-id="42a6a99" data-element_type="column">
			                                                            <div class="elementor-widget-wrap elementor-element-populated">
			                                                                <div class="elementor-element elementor-element-288335b elementor-widget elementor-widget-heading" data-id="288335b" data-element_type="widget" data-widget_type="heading.default">
			                                                                    <div class="elementor-widget-container">
			                                                                        <style>
			                                                                            /*! elementor - v3.14.0 - 26-06-2023 */.elementor-heading-title{padding:0;margin:0;line-height:1}.elementor-widget-heading .elementor-heading-title[class*=elementor-size-]>a{color:inherit;font-size:inherit;line-height:inherit}.elementor-widget-heading .elementor-heading-title.elementor-size-small{font-size:15px}.elementor-widget-heading .elementor-heading-title.elementor-size-medium{font-size:19px}.elementor-widget-heading .elementor-heading-title.elementor-size-large{font-size:29px}.elementor-widget-heading .elementor-heading-title.elementor-size-xl{font-size:39px}.elementor-widget-heading .elementor-heading-title.elementor-size-xxl{font-size:59px}
			                                                                        </style>
			                                                                        <h2 class="elementor-heading-title elementor-size-default">Aluguel de carros</h2>
			                                                                    </div>
			                                                                </div>
			                                                            </div>
			                                                        </div>
			                                                    </div>
			                                                </section>
			                                                <section class="elementor-section elementor-inner-section elementor-element elementor-element-36f537b elementor-section-height-min-height elementor-section-boxed elementor-section-height-default wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no"
			                                                    data-id="36f537b" data-element_type="section">
			                                                    <div class="elementor-container elementor-column-gap-default">
			                                                        <div class="elementor-column elementor-col-20 elementor-inner-column elementor-element elementor-element-641173c" data-id="641173c" data-element_type="column">
			                                                            <div class="elementor-widget-wrap elementor-element-populated">
			                                                                <div class="elementor-element elementor-element-22f1764 elementor-widget elementor-widget-heading"
			                                                                    data-id="22f1764"
			                                                                    data-element_type="widget"
			                                                                    data-widget_type="heading.default">
			                                                                    <div class="elementor-widget-container">
			                                                                        <h2 class="elementor-heading-title elementor-size-default">Local de retirada</h2>
			                                                                    </div>
			                                                                </div>
			                                                            </div>
			                                                        </div>
			                                                        <div class="elementor-column elementor-col-20 elementor-inner-column elementor-element elementor-element-ebd2137" data-id="ebd2137" data-element_type="column">
			                                                            <div class="elementor-widget-wrap elementor-element-populated">
			                                                                <div class="elementor-element elementor-element-a233389 elementor-hidden-mobile elementor-widget elementor-widget-heading" data-id="a233389" data-element_type="widget" data-widget_type="heading.default">
			                                                                    <div class="elementor-widget-container">
			                                                                        <h2 class="elementor-heading-title elementor-size-default labelDevolucao" style="display:none">Local de devolução</h2>
			                                                                    </div>
			                                                                </div>
			                                                            </div>
			                                                        </div>
			                                                        <div class="elementor-column elementor-col-20 elementor-inner-column elementor-element elementor-element-3b1e8b6" data-id="3b1e8b6" data-element_type="column">
			                                                            <div class="elementor-widget-wrap elementor-element-populated">
			                                                                <div class="elementor-element elementor-element-584d968 elementor-hidden-mobile elementor-widget elementor-widget-heading" data-id="584d968" data-element_type="widget" data-widget_type="heading.default">
			                                                                    <div class="elementor-widget-container">
			                                                                        <h2 class="elementor-heading-title elementor-size-default">Data e hora de retirada</h2>
			                                                                    </div>
			                                                                </div>
			                                                            </div>
			                                                        </div>
			                                                        <div class="elementor-column elementor-col-20 elementor-inner-column elementor-element elementor-element-01adafa" data-id="01adafa" data-element_type="column">
			                                                            <div class="elementor-widget-wrap elementor-element-populated">
			                                                                <div class="elementor-element elementor-element-dce890b elementor-hidden-mobile elementor-widget elementor-widget-heading" data-id="dce890b" data-element_type="widget" data-widget_type="heading.default">
			                                                                    <div class="elementor-widget-container">
			                                                                        <h2 class="elementor-heading-title elementor-size-default">Data e hora de devolução</h2>
			                                                                    </div>
			                                                                </div>
			                                                            </div>
			                                                        </div>
			                                                        <div class="elementor-column elementor-col-20 elementor-inner-column elementor-element elementor-element-b23b9b1 elementor-hidden-mobile" data-id="b23b9b1" data-element_type="column">
			                                                            <div class="elementor-widget-wrap elementor-element-populated"></div>
			                                                        </div>
			                                                    </div>
			                                                </section>
			                                                <section
			                                                    class="elementor-section elementor-inner-section elementor-element elementor-element-f24bef6 elementor-section-boxed elementor-section-height-default elementor-section-height-default wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no" data-id="f24bef6" data-element_type="section">
			                                                    <div class="elementor-container elementor-column-gap-default">
			                                                        <div class="elementor-column elementor-col-40 elementor-inner-column elementor-element elementor-element-7ea0443" data-id="7ea0443" data-element_type="column" id="divRetirada">
			                                                            <div class="elementor-widget-wrap elementor-element-populated">
			                                                                <div class="elementor-element elementor-element-7b4195e elementor-widget elementor-widget-html" data-id="7b4195e" data-element_type="widget" data-widget_type="html.default">
			                                                                    <div class="elementor-widget-container">  
			                                                                        <div class="input-group mb-3">
			                                                                            <div class="input-group-prepend">
			                                                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-map-marker"></i></span>
			                                                                            </div>
			                                                                            <input type="text" class="form-control inputTextVehicle" id="localPickup" placeholder="Onde será alugado" aria-label="Onde será alugado" aria-describedby="basic-addon1" autocomplete="off" onfocus="this.value=\'\'"/>
			                                                                            <div class="dados" id="dataPickup" style="display:none">
																							<ul style="padding:0;margin: 0;"></ul>
																						</div>
			                                                                        </div>
			                                                                    </div>
			                                                                </div>
			                                                                <div class="elementor-element elementor-element-18ecb14 elementor-widget elementor-widget-html" data-id="18ecb14" data-element_type="widget" data-widget_type="html.default">
			                                                                    <div class="elementor-widget-container">
			                                                                        <p class="otherDevol"><input type="checkbox" id="change_delivery" onclick="change_type_delivery()"> Devolver em outra loja</p>
			                                                                    </div>
			                                                                </div>
			                                                            </div>
			                                                        </div>
			                                                        <div class="elementor-column elementor-col-20 elementor-inner-column elementor-element elementor-element-d530a0d" data-id="d530a0d" data-element_type="column" style="display:none">
			                                                            <div class="elementor-widget-wrap elementor-element-populated">
			                                                                <div class="elementor-element elementor-element-c02e80b elementor-hidden-desktop elementor-widget elementor-widget-heading" data-id="c02e80b" data-element_type="widget" data-widget_type="heading.default">
			                                                                    <div class="elementor-widget-container">
			                                                                        <h2 class="elementor-heading-title elementor-size-default labelDevolucao" style="display:none">Local de devolução</h2>
			                                                                    </div>
			                                                                </div>
			                                                                <div class="elementor-element elementor-element-e25685a elementor-widget elementor-widget-html" data-id="e25685a" data-element_type="widget" data-widget_type="html.default" id="divDevolucao">
			                                                                    <div class="elementor-widget-container">
			                                                                        <div class="input-group mb-3">
			                                                                            <div class="input-group-prepend">
			                                                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-map-marker"></i></span>
			                                                                            </div>
			                                                                            <input type="text" class="form-control inputTextVehicle" id="localDrop" placeholder="Onde vai devolver" aria-label="Onde vai devolver" aria-describedby="basic-addon1" autocomplete="off" onfocus="this.value=\'\'"/ />
			                                                                            <div class="dados" id="dataDrop" style="display:none">
																							<ul style="padding:0;margin: 0;"></ul>
																						</div>
			                                                                        </div>
			                                                                    </div>
			                                                                </div>
			                                                            </div>
			                                                        </div>
			                                                        <div class="elementor-column elementor-col-20 elementor-inner-column elementor-element elementor-element-5c6491f" data-id="5c6491f" data-element_type="column">
			                                                            <div class="elementor-widget-wrap elementor-element-populated">
			                                                                <div class="elementor-element elementor-element-f7427f1 elementor-hidden-desktop elementor-widget elementor-widget-heading" data-id="f7427f1" data-element_type="widget" data-widget_type="heading.default">
			                                                                    <div class="elementor-widget-container">
			                                                                        <h2 class="elementor-heading-title elementor-size-default">Data e hora de retirada</h2>
			                                                                    </div>
			                                                                </div>
			                                                                <div class="elementor-element elementor-element-79efa4a elementor-widget elementor-widget-html" data-id="79efa4a" data-element_type="widget" data-widget_type="html.default" style="z-index:0">
			                                                                    <div class="elementor-widget-container">
			                                                                        <div class="input-group mb-3">
			                                                                            <div class="input-group-prepend">
			                                                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar-alt"></i></span>
			                                                                            </div>
			                                                                            <input type="text" name="datePickup" class="form-control inputTextVehicle" placeholder="Quando?" aria-label="Quando?" aria-describedby="basic-addon1" />
			                                                                        </div>
			                                                                    </div>
			                                                                </div>
			                                                                <div class="elementor-element elementor-element-3dbb6cd elementor-widget elementor-widget-html" data-id="3dbb6cd" data-element_type="widget" data-widget_type="html.default">
			                                                                    <div class="elementor-widget-container">
			                                                                        <select class="form-control selectVehicle" id="selectVehicleRetirada">
			                                                                            <option value="00:00">00:00</option>
			                                                                            <option value="00:30">00:30</option>
			                                                                            <option value="01:00">01:00</option>
			                                                                            <option value="01:30">01:30</option>
			                                                                            <option value="02:00">02:00</option>
			                                                                            <option value="02:30">02:30</option>
			                                                                            <option value="03:00">03:00</option>
			                                                                            <option value="03:30">03:30</option>
			                                                                            <option value="04:00">04:00</option>
			                                                                            <option value="04:30">04:30</option>
			                                                                            <option value="05:00">05:00</option>
			                                                                            <option value="05:30">05:30</option>
			                                                                            <option value="06:00">06:00</option>
			                                                                            <option value="06:30">06:30</option>
			                                                                            <option value="07:00">07:00</option>
			                                                                            <option value="07:30">07:30</option>
			                                                                            <option value="08:00">08:00</option>
			                                                                            <option value="08:30">08:30</option>
			                                                                            <option value="09:00">09:00</option>
			                                                                            <option value="09:30">09:30</option>
			                                                                            <option value="10:00">10:00</option>
			                                                                            <option value="10:30">10:30</option>
			                                                                            <option value="11:00" selected>11:00</option>
			                                                                            <option value="11:30">11:30</option>
			                                                                            <option value="12:00">12:00</option>
			                                                                            <option value="12:30">12:30</option>
			                                                                            <option value="13:00">13:00</option>
			                                                                            <option value="13:30">13:30</option>
			                                                                            <option value="14:00">14:00</option>
			                                                                            <option value="14:30">14:30</option>
			                                                                            <option value="15:00">15:00</option>
			                                                                            <option value="15:30">15:30</option>
			                                                                            <option value="16:00">16:00</option>
			                                                                            <option value="16:30">16:30</option>
			                                                                            <option value="17:00">17:00</option>
			                                                                            <option value="17:30">17:30</option>
			                                                                            <option value="18:00">18:00</option>
			                                                                            <option value="18:30">18:30</option>
			                                                                            <option value="19:00">19:00</option>
			                                                                            <option value="19:30">19:30</option>
			                                                                            <option value="20:00">20:00</option>
			                                                                            <option value="20:30">20:30</option>
			                                                                            <option value="21:00">21:00</option>
			                                                                            <option value="21:30">21:30</option>
			                                                                            <option value="22:00">22:00</option>
			                                                                            <option value="22:30">22:30</option>
			                                                                            <option value="23:00">23:00</option>
			                                                                            <option value="23:30">23:30</option>
			                                                                        </select>
			                                                                    </div>
			                                                                </div>
			                                                            </div>
			                                                        </div>
			                                                        <div class="elementor-column elementor-col-20 elementor-inner-column elementor-element elementor-element-435780d" data-id="435780d" data-element_type="column">
			                                                            <div class="elementor-widget-wrap elementor-element-populated">
			                                                                <div class="elementor-element elementor-element-a4d2ecf elementor-hidden-desktop elementor-widget elementor-widget-heading" data-id="a4d2ecf" data-element_type="widget" data-widget_type="heading.default">
			                                                                    <div class="elementor-widget-container">
			                                                                        <h2 class="elementor-heading-title elementor-size-default">Data e hora de devolução</h2>
			                                                                    </div>
			                                                                </div>
			                                                                <div class="elementor-element elementor-element-f46449b elementor-widget elementor-widget-html" data-id="f46449b" data-element_type="widget" data-widget_type="html.default" style="z-index:0">
			                                                                    <div class="elementor-widget-container">
			                                                                        <div class="input-group mb-3">
			                                                                            <div class="input-group-prepend">
			                                                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar-alt"></i></span>
			                                                                            </div>
			                                                                            <input type="text" name="datePickout" class="form-control inputTextVehicle" placeholder="Quando?" aria-label="Quando?" aria-describedby="basic-addon1" />
			                                                                        </div>
			                                                                    </div>
			                                                                </div>
			                                                                <div class="elementor-element elementor-element-9408668 elementor-widget elementor-widget-html" data-id="9408668" data-element_type="widget" data-widget_type="html.default">
			                                                                    <div class="elementor-widget-container">
			                                                                        <select class="form-control selectVehicle" id="selectVehicleDevolucao">
			                                                                            <option value="00:00">00:00</option>
			                                                                            <option value="00:30">00:30</option>
			                                                                            <option value="01:00">01:00</option>
			                                                                            <option value="01:30">01:30</option>
			                                                                            <option value="02:00">02:00</option>
			                                                                            <option value="02:30">02:30</option>
			                                                                            <option value="03:00">03:00</option>
			                                                                            <option value="03:30">03:30</option>
			                                                                            <option value="04:00">04:00</option>
			                                                                            <option value="04:30">04:30</option>
			                                                                            <option value="05:00">05:00</option>
			                                                                            <option value="05:30">05:30</option>
			                                                                            <option value="06:00">06:00</option>
			                                                                            <option value="06:30">06:30</option>
			                                                                            <option value="07:00">07:00</option>
			                                                                            <option value="07:30">07:30</option>
			                                                                            <option value="08:00">08:00</option>
			                                                                            <option value="08:30">08:30</option>
			                                                                            <option value="09:00">09:00</option>
			                                                                            <option value="09:30">09:30</option>
			                                                                            <option value="10:00">10:00</option>
			                                                                            <option value="10:30">10:30</option>
			                                                                            <option value="11:00" selected>11:00</option>
			                                                                            <option value="11:30">11:30</option>
			                                                                            <option value="12:00">12:00</option>
			                                                                            <option value="12:30">12:30</option>
			                                                                            <option value="13:00">13:00</option>
			                                                                            <option value="13:30">13:30</option>
			                                                                            <option value="14:00">14:00</option>
			                                                                            <option value="14:30">14:30</option>
			                                                                            <option value="15:00">15:00</option>
			                                                                            <option value="15:30">15:30</option>
			                                                                            <option value="16:00">16:00</option>
			                                                                            <option value="16:30">16:30</option>
			                                                                            <option value="17:00">17:00</option>
			                                                                            <option value="17:30">17:30</option>
			                                                                            <option value="18:00">18:00</option>
			                                                                            <option value="18:30">18:30</option>
			                                                                            <option value="19:00">19:00</option>
			                                                                            <option value="19:30">19:30</option>
			                                                                            <option value="20:00">20:00</option>
			                                                                            <option value="20:30">20:30</option>
			                                                                            <option value="21:00">21:00</option>
			                                                                            <option value="21:30">21:30</option>
			                                                                            <option value="22:00">22:00</option>
			                                                                            <option value="22:30">22:30</option>
			                                                                            <option value="23:00">23:00</option>
			                                                                            <option value="23:30">23:30</option>
			                                                                        </select>
			                                                                    </div>
			                                                                </div>
			                                                            </div>
			                                                        </div>
			                                                        <div class="elementor-column elementor-col-20 elementor-inner-column elementor-element elementor-element-6950c01" data-id="6950c01" data-element_type="column">
			                                                            <div class="elementor-widget-wrap elementor-element-populated">
			                                                                <div class="elementor-element elementor-element-50198cd elementor-mobile-align-center elementor-widget elementor-widget-button" data-id="50198cd" data-element_type="widget" data-widget_type="button.default">
			                                                                    <div class="elementor-widget-container">
			                                                                        <div class="elementor-button-wrapper">
			                                                                            <a class="elementor-button elementor-button-link elementor-size-md" onclick="set_search_cars()" style="color:#fff;cursor:pointer;">
			                                                                                <span class="elementor-button-content-wrapper">
			                                                                                    <span class="elementor-button-icon elementor-align-icon-left"> <i aria-hidden="true" class="fas fa-search"></i> </span>
			                                                                                    <span class="elementor-button-text">Buscar</span>
			                                                                                </span>
			                                                                            </a>
			                                                                        </div>
			                                                                    </div>
			                                                                </div>
			                                                            </div>
			                                                        </div>
			                                                    </div>
			                                                </section>
			                                            </div>
			                                        </div>
			                                    </div>
			                                </section>
			                            </div>
			                        </div>
			                    </div>
			                </div>
			            </div>
			        </div>
			    </div>
			</section>';

			$retorno .= '<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
			<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
			<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> 
			<script src="https://www.jqueryscript.net/demo/Customizable-Animated-Dropdown-Plugin-with-jQuery-CSS3-Nice-Select/js/jquery.nice-select.js"></script>
			<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment-with-locales.min.js"></script>
			<script src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/js/helpers/date.js?v='.date("YmdHis").'" id="date-cars-js"></script>
			<script src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/js/helpers/token-search.js?v='.date("YmdHis").'" id="token-search-cars-js"></script>
			<script src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/js/helpers/search-city.js?v='.date("YmdHis").'" id="search-city-cars-js"></script>
			<script src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/js/script-search-cars.js?v='.date("YmdHis").'" id="scripts-cars-js"></script>';

			return $retorno;

		}
		/* FIM FUNÇÃO MOTOR DE PESQUISA */

		/* FUNÇÃO MOTOR DE PESQUISA LATERAL - PÁGINA DE RESULTADOS */
		add_shortcode('TTBOOKING_MOTOR_RESERVA_LATERAL_CARS', 'shortcode_motor_reserva_lateral_cars');  
		function shortcode_motor_reserva_lateral_cars(){
			$retorno = '';

			$retorno .= '<input type="hidden" id="url_ajax" value="'.admin_url('admin-ajax.php').'">';
			$retorno .= '<input type="hidden" id="type_delivery" value="">';
			$retorno .= '<input type="hidden" id="type_motor" value="1">';

			$retorno .= '<div class="elementor-widget-wrap elementor-element-populated">
			    <section class="elementor-section elementor-inner-section elementor-element elementor-element-74c3e13 elementor-section-boxed elementor-section-height-default elementor-section-height-default wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no" data-id="74c3e13" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
			        <div class="elementor-container elementor-column-gap-default">
			            <div class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-bcac923" data-id="bcac923" data-element_type="column">
			                <div class="elementor-widget-wrap elementor-element-populated">
			                    <div class="elementor-element elementor-element-bb9746e elementor-widget elementor-widget-heading" data-id="bb9746e" data-element_type="widget" data-widget_type="heading.default">
			                        <div class="elementor-widget-container">
			                            <style>
			                                /*! elementor - v3.14.0 - 26-06-2023 */.elementor-heading-title{padding:0;margin:0;line-height:1}.elementor-widget-heading .elementor-heading-title[class*=elementor-size-]>a{color:inherit;font-size:inherit;line-height:inherit}.elementor-widget-heading .elementor-heading-title.elementor-size-small{font-size:15px}.elementor-widget-heading .elementor-heading-title.elementor-size-medium{font-size:19px}.elementor-widget-heading .elementor-heading-title.elementor-size-large{font-size:29px}.elementor-widget-heading .elementor-heading-title.elementor-size-xl{font-size:39px}.elementor-widget-heading .elementor-heading-title.elementor-size-xxl{font-size:59px}
			                            </style>
			                            <h2 class="elementor-heading-title elementor-size-default">Aluguel de carros</h2>
			                        </div>
			                    </div>
			                    <div class="elementor-element elementor-element-92b1c80 elementor-widget elementor-widget-heading" data-id="92b1c80" data-element_type="widget" data-widget_type="heading.default">
			                        <div class="elementor-widget-container">
			                            <h2 class="elementor-heading-title elementor-size-default">Local de retirada</h2>
			                        </div>
			                    </div>
			                    <div class="elementor-element elementor-element-c968fd4 elementor-widget elementor-widget-html" data-id="c968fd4" data-element_type="widget" data-widget_type="html.default" id="divRetirada">
			                        <div class="elementor-widget-container"> 
			                            <div class="input-group mb-3">
			                                <div class="input-group-prepend">
			                                    <span class="input-group-text" id="basic-addon1">
			                                        <i class="fa fa-map-marker-alt"></i>
			                                    </span>
			                                </div>
			                                <input type="text" class="form-control inputTextVehicle" id="localPickup" placeholder="Onde será alugado" aria-label="Onde será alugado" aria-describedby="basic-addon1" autocomplete="off" onfocus="this.value=\'\'"/>
                                            <div class="dados" id="dataPickup" style="display:none">
												<ul style="padding:0;margin: 0;"></ul>
											</div>
			                            </div>
			                        </div>
			                    </div>
			                    <div class="elementor-element elementor-element-3e13054 elementor-widget elementor-widget-html" data-id="3e13054" data-element_type="widget" data-widget_type="html.default">
			                        <div class="elementor-widget-container">
			                            <p class="otherDevol">
			                            	<input type="checkbox" id="change_delivery" onclick="change_type_delivery()"> Devolver em outra loja
			                       		</p>
			                        </div>
			                    </div>
			                    <div class="elementor-element elementor-element-2116873 elementor-widget elementor-widget-heading dropClass" data-id="2116873" data-element_type="widget" data-widget_type="heading.default">
			                        <div class="elementor-widget-container">
			                            <h2 class="elementor-heading-title elementor-size-default" id="labelDevolucao">Local de devolução</h2>
			                        </div>
			                    </div>
			                    <div class="elementor-element elementor-element-3325459 elementor-widget elementor-widget-html dropClass" data-id="3325459" data-element_type="widget" data-widget_type="html.default" id="divDevolucao">
			                        <div class="elementor-widget-container">
			                            <div class="input-group mb-3">
			                                <div class="input-group-prepend">
			                                    <span class="input-group-text" id="basic-addon1">
			                                        <i class="fa fa-map-marker-alt"></i>
			                                    </span>
			                                </div>
			                                <input type="text" class="form-control inputTextVehicle" id="localDrop" placeholder="Onde vai devolver" aria-label="Onde vai devolver" aria-describedby="basic-addon1" autocomplete="off" onfocus="this.value=\'\'"/ />
			                                <div class="dados" id="dataDrop" style="display:none">
												<ul style="padding:0;margin: 0;"></ul>
											</div>
			                            </div>
			                        </div>
			                    </div>
			                    <div class="elementor-element elementor-element-c133e2d elementor-widget elementor-widget-heading" data-id="c133e2d" data-element_type="widget" data-widget_type="heading.default">
			                        <div class="elementor-widget-container">
			                            <h2 class="elementor-heading-title elementor-size-default">Data e hora de retirada</h2>
			                        </div>
			                    </div>
			                    <div class="elementor-element elementor-element-88debe7 elementor-widget elementor-widget-html" data-id="88debe7" data-element_type="widget" data-widget_type="html.default">
			                        <div class="elementor-widget-container">
			                            <div class="input-group mb-3">
			                                <div class="input-group-prepend">
			                                    <span class="input-group-text" id="basic-addon1">
			                                        <i class="fa fa-calendar-alt"></i>
			                                    </span>
			                                </div>
			                                <input type="text" name="datePickup" class="form-control inputTextVehicle" placeholder="Quando?" aria-label="Quando?" aria-describedby="basic-addon1" />
			                            </div>
			                        </div>
			                    </div>
			                    <div class="elementor-element elementor-element-a1ade1d elementor-widget elementor-widget-html" data-id="a1ade1d" data-element_type="widget" data-widget_type="html.default">
			                        <div class="elementor-widget-container">
			                            <select class="form-control selectVehicle" id="selectVehicleRetirada">
                                            <option value="00:00">00:00</option>
                                            <option value="00:30">00:30</option>
                                            <option value="01:00">01:00</option>
                                            <option value="01:30">01:30</option>
                                            <option value="02:00">02:00</option>
                                            <option value="02:30">02:30</option>
                                            <option value="03:00">03:00</option>
                                            <option value="03:30">03:30</option>
                                            <option value="04:00">04:00</option>
                                            <option value="04:30">04:30</option>
                                            <option value="05:00">05:00</option>
                                            <option value="05:30">05:30</option>
                                            <option value="06:00">06:00</option>
                                            <option value="06:30">06:30</option>
                                            <option value="07:00">07:00</option>
                                            <option value="07:30">07:30</option>
                                            <option value="08:00">08:00</option>
                                            <option value="08:30">08:30</option>
                                            <option value="09:00">09:00</option>
                                            <option value="09:30">09:30</option>
                                            <option value="10:00">10:00</option>
                                            <option value="10:30">10:30</option>
                                            <option value="11:00">11:00</option>
                                            <option value="11:30">11:30</option>
                                            <option value="12:00">12:00</option>
                                            <option value="12:30">12:30</option>
                                            <option value="13:00">13:00</option>
                                            <option value="13:30">13:30</option>
                                            <option value="14:00">14:00</option>
                                            <option value="14:30">14:30</option>
                                            <option value="15:00">15:00</option>
                                            <option value="15:30">15:30</option>
                                            <option value="16:00">16:00</option>
                                            <option value="16:30">16:30</option>
                                            <option value="17:00">17:00</option>
                                            <option value="17:30">17:30</option>
                                            <option value="18:00">18:00</option>
                                            <option value="18:30">18:30</option>
                                            <option value="19:00">19:00</option>
                                            <option value="19:30">19:30</option>
                                            <option value="20:00">20:00</option>
                                            <option value="20:30">20:30</option>
                                            <option value="21:00">21:00</option>
                                            <option value="21:30">21:30</option>
                                            <option value="22:00">22:00</option>
                                            <option value="22:30">22:30</option>
                                            <option value="23:00">23:00</option>
                                            <option value="23:30">23:30</option>
                                        </select>
			                        </div>
			                    </div>
			                    <div class="elementor-element elementor-element-55f43d9 elementor-widget elementor-widget-heading" data-id="55f43d9" data-element_type="widget" data-widget_type="heading.default">
			                        <div class="elementor-widget-container">
			                            <h2 class="elementor-heading-title elementor-size-default">Data e hora de devolução</h2>
			                        </div>
			                    </div>
			                    <div class="elementor-element elementor-element-a352ca4 elementor-widget elementor-widget-html" data-id="a352ca4" data-element_type="widget" data-widget_type="html.default">
			                        <div class="elementor-widget-container">
			                            <div class="input-group mb-3">
			                                <div class="input-group-prepend">
			                                    <span class="input-group-text" id="basic-addon1">
			                                        <i class="fa fa-calendar-alt"></i>
			                                    </span>
			                                </div>
			                                <input type="text" name="datePickout" class="form-control inputTextVehicle" placeholder="Quando?" aria-label="Quando?" aria-describedby="basic-addon1" />
			                            </div>
			                        </div>
			                    </div>
			                    <div class="elementor-element elementor-element-31332b8 elementor-widget elementor-widget-html" data-id="31332b8" data-element_type="widget" data-widget_type="html.default">
			                        <div class="elementor-widget-container">
			                            <select class="form-control selectVehicle" id="selectVehicleDevolucao">
	                                        <option value="00:00">00:00</option>
	                                        <option value="00:30">00:30</option>
	                                        <option value="01:00">01:00</option>
	                                        <option value="01:30">01:30</option>
	                                        <option value="02:00">02:00</option>
	                                        <option value="02:30">02:30</option>
	                                        <option value="03:00">03:00</option>
	                                        <option value="03:30">03:30</option>
	                                        <option value="04:00">04:00</option>
	                                        <option value="04:30">04:30</option>
	                                        <option value="05:00">05:00</option>
	                                        <option value="05:30">05:30</option>
	                                        <option value="06:00">06:00</option>
	                                        <option value="06:30">06:30</option>
	                                        <option value="07:00">07:00</option>
	                                        <option value="07:30">07:30</option>
	                                        <option value="08:00">08:00</option>
	                                        <option value="08:30">08:30</option>
	                                        <option value="09:00">09:00</option>
	                                        <option value="09:30">09:30</option>
	                                        <option value="10:00">10:00</option>
	                                        <option value="10:30">10:30</option>
	                                        <option value="11:00">11:00</option>
	                                        <option value="11:30">11:30</option>
	                                        <option value="12:00">12:00</option>
	                                        <option value="12:30">12:30</option>
	                                        <option value="13:00">13:00</option>
	                                        <option value="13:30">13:30</option>
	                                        <option value="14:00">14:00</option>
	                                        <option value="14:30">14:30</option>
	                                        <option value="15:00">15:00</option>
	                                        <option value="15:30">15:30</option>
	                                        <option value="16:00">16:00</option>
	                                        <option value="16:30">16:30</option>
	                                        <option value="17:00">17:00</option>
	                                        <option value="17:30">17:30</option>
	                                        <option value="18:00">18:00</option>
	                                        <option value="18:30">18:30</option>
	                                        <option value="19:00">19:00</option>
	                                        <option value="19:30">19:30</option>
	                                        <option value="20:00">20:00</option>
	                                        <option value="20:30">20:30</option>
	                                        <option value="21:00">21:00</option>
	                                        <option value="21:30">21:30</option>
	                                        <option value="22:00">22:00</option>
	                                        <option value="22:30">22:30</option>
	                                        <option value="23:00">23:00</option>
	                                        <option value="23:30">23:30</option>
	                                    </select>
			                        </div>
			                    </div>
			                    <div class="elementor-element elementor-element-cdff066 elementor-mobile-align-center elementor-align-center elementor-widget elementor-widget-button" data-id="cdff066" data-element_type="widget" data-widget_type="button.default">
			                        <div class="elementor-widget-container">
			                            <div class="elementor-button-wrapper">
			                                <a class="elementor-button elementor-button-link elementor-size-md"onclick="set_search_cars()" style="color:#fff;cursor:pointer;">
			                                    <span class="elementor-button-content-wrapper">
			                                        <span class="elementor-button-icon elementor-align-icon-left">
			                                            <i aria-hidden="true" class="fas fa-search"></i>
			                                        </span>
			                                        <span class="elementor-button-text">Buscar</span>
			                                    </span>
			                                </a>
			                            </div>
			                        </div>
			                    </div>
			                </div>
			            </div>
			        </div>
			    </section>
			</div>';

            return $retorno;
		}
		/* FIM FUNÇÃO MOTOR DE PESQUISA LATERAL - PÁGINA DE RESULTADOS */

		/* FUNÇÃO LISTAGEM DOS RESULTADOS */ 
		add_shortcode('TTBOOKING_MOTOR_RESULTS_CARS', 'shortcode_results_cars');  
		function shortcode_results_cars(){

			$retorno = ""; 

			$retorno .= '<link rel="preconnect" href="https://fonts.googleapis.com"> 
				<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> 
				<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
				<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet"> 
				<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
				<link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css" rel="stylesheet">
				<link href="'.plugin_dir_url( __FILE__ ) . 'includes/assets/css/result-cars.css?v='.date("YmdHis").'" rel="stylesheet">';

			$retorno .= '<style>  
				.elementor-element.elementor-element-74c3e13:not(.elementor-motion-effects-element-type-background), .elementor-element.elementor-element-74c3e13 > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-color:'.(empty(get_option( 'cor_cars' )) ? '#000000' : get_option( 'cor_cars' )).'}
				.elementor-element.elementor-element-cdff066 .elementor-button, .elementor-element.elementor-element-a8b2e28 .elementor-button{
					background-color:'.(empty(get_option( 'cor_botao_cars' )) ? '#000000' : get_option( 'cor_botao_cars' )).'
				}
				.elementor-element.elementor-element-3da3149 .elementor-image-box-title, .elementor-element.elementor-element-4e90518 .elementor-image-box-description, .elementor-element.elementor-element-70b5143 .elementor-image-box-title{
					margin: 0 0px 0px 11px;
				}
				.elementor-element.elementor-element-2d89453{
					background-color: #f0f0f0;
    				border-radius: 0 15px 15px 0;
				}
				.elementor-element.elementor-element-05a5a5d .elementor-icon-list-icon i{
					color: '.(empty(get_option( 'cor_cars' )) ? '#000000' : get_option( 'cor_cars' )).'
				}
				.elementor-element.elementor-element-05a5a5d .elementor-icon-list-icon svg{
					fill: '.(empty(get_option( 'cor_cars' )) ? '#000000' : get_option( 'cor_cars' )).'
				}
				.daterangepicker{box-shadow:0 1rem 3rem rgba(0,0,0,.175)!important;border:none;}
				.daterangepicker td.in-range{background-color:'.(empty(get_option( 'cor_cars' )) ? '#000000' : get_option( 'cor_cars' )).'54;cor:'.(empty(get_option( 'cor_cars' )) ? '#000000' : get_option( 'cor_cars' )).'54;}
				.daterangepicker td.active, .daterangepicker td.active:hover {background-color:'.(empty(get_option( 'cor_cars' )) ? '#000000' : get_option( 'cor_cars' )).';border-color:transparent;color:#fff;}
				.daterangepicker td.available:hover, .daterangepicker th.available:hover{background-color:'.(empty(get_option( 'cor_cars' )) ? '#000000' : get_option( 'cor_cars' )).'99;color:#fff;border-radius:40px} .dados,.dados:after{position:absolute}.dados ul li{margin-top:0!important}.dados:after{bottom:100%;left:15px;border:7px solid transparent;content:" ";height:0;width:0;pointer-events:none;border-bottom-color:#ddd;margin-left:-7px}.dados ul li:hover{background-color:#f1f1f1}
				.daterangepicker .calendar-table th, .daterangepicker .calendar-table td{font-family:Montserrat;padding:9px}

		        .row-is-loading{
		            background: #eee;
		            background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
		            border-radius: 5px;
		            background-size: 200% 100%;
		            animation: 1.5s shine linear infinite;
		            min-height: 75px;
		        } 
				@keyframes shine {
				  	to {
				    	background-position-x: -200%;
				  	}
				}

				.filter hr{
					margin: 20px 0;
		    		border-top: 1px solid #6f6f6f; 
				}
				.filter .accordion-button, .filter .accordion-body{
					background-color: #f0f0f0 !important;
					color: #000 !important;
					border: none !important;
					font-family: \'Montserrat\' !important;
					box-shadow: none !important;
				}
				.filter .accordion-button{
					font-weight: 700;
				} 
				.filter h4{
					font-family: \'Montserrat\';
				    font-size: 16px;
				    font-weight: 700;
				    color: #575757;
				    margin-bottom: 10px;
				}
				.filter select{
					font-family: \'Montserrat\';
				    font-size: 14px;
				}
				.price-range-right, .price-range-left{
					font-weight: 700;
		    		font-size: 13px;
		    		padding: 3px 0;
				}
				.price-range-right{
					float: right;
				}
				.price-range-left{
					float: left;
				}
				.rowOffer:hover{
					box-shadow: 6px 6px 10px #e0e0e0;
				}
			</style>'; 

			$retorno .= '<section class="elementor-section elementor-top-section elementor-element elementor-element-cec63cb elementor-section-boxed elementor-section-height-default elementor-section-height-default wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no" data-id="cec63cb" data-element_type="section">
			    <div class="elementor-container elementor-column-gap-default">
			        <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-3860faa" data-id="3860faa" data-element_type="column" style="display: inline">
			            <div class="elementor-widget-wrap elementor-element-populated">';
			                $retorno .= do_shortcode('[TTBOOKING_MOTOR_RESERVA_LATERAL_CARS]');
			        	$retorno .= '</div>';

			        	$retorno .= '<div id="filter" class="filter" style="padding: 10px;">

			        		<h4> Ordenar por</h4>
			        		<select class="form-control" id="type_order" onchange="changeOrderResults()"> 
			        			<option value="0">Recomendado</option>
			        			<option value="1">Menor preço</option>
			        			<option value="2">Maior preço</option>
			        		</select>

							<hr> 

							<div class="filter-price-cars row-is-loading">
								
							</div> 

							<hr> 

							<div class="filter-rental-cars row-is-loading">
								 
							</div> 

						</div>'; 

			        $retorno .= '</div>
			        <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-cf45779" data-id="cf45779" data-element_type="column">
			            <div class="elementor-widget-wrap elementor-element-populated" id="offersCars"> ';

			            	for($i=0; $i < 8; $i++){

			                	$retorno .= '<section class="elementor-section elementor-inner-section elementor-element elementor-element-d48f1da rowOffer elementor-section-boxed elementor-section-height-default elementor-section-height-default wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no" data-id="d48f1da" data-element_type="section">
        			                    <div class="elementor-container elementor-column-gap-default">
        			                        <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-aec4f94" data-id="aec4f94" data-element_type="column">
        			                            <div class="elementor-widget-wrap elementor-element-populated">
        			                                <div class="elementor-element elementor-element-4e90518 elementor-position-left elementor-vertical-align-middle elementor-widget elementor-widget-image-box" data-id="4e90518" data-element_type="widget" data-widget_type="image-box.default">
        			                                    <div class="elementor-widget-container row-is-loading"> 
        			                                         
        			                                    </div>
        			                                </div>  
        			                                <div class="elementor-element elementor-element-b82a85e elementor-widget-divider--view-line elementor-widget elementor-widget-divider" data-id="b82a85e" data-element_type="widget" data-widget_type="divider.default">
        			                                    <div class="elementor-widget-container"> 
        			                                        <div class="elementor-divider">
        			                                            <span class="elementor-divider-separator"></span>
        			                                        </div>
        			                                    </div>
        			                                </div>
        			                                <div class="elementor-element elementor-element-3da3149 elementor-position-left elementor-vertical-align-middle elementor-widget elementor-widget-image-box" data-id="3da3149" data-element_type="widget" data-widget_type="image-box.default">
        			                                    <div class="elementor-widget-container">
        			                                        <div class="elementor-image-box-wrapper row-is-loading">
        
        			                                        </div>
        			                                    </div>
        			                                </div>  
        			                                <div class="elementor-element elementor-element-c91dff4 elementor-widget-divider--view-line elementor-widget elementor-widget-divider" data-id="c91dff4" data-element_type="widget" data-widget_type="divider.default">
        			                                    <div class="elementor-widget-container">
        			                                        <div class="elementor-divider">
        			                                            <span class="elementor-divider-separator"></span>
        			                                        </div>
        			                                    </div>
        			                                </div>
        			                                <div class="elementor-element elementor-element-4e77f7e elementor-icon-list--layout-inline elementor-align-left elementor-mobile-align-left elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="4e77f7e" data-element_type="widget" data-widget_type="icon-list.default">
        			                                    <div class="elementor-widget-container row-is-loading">
        			                                         
        			                                    </div>
        			                                </div>
        			                            </div>
        			                        </div>
        			                        <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-2d89453" data-id="2d89453" data-element_type="column">
        			                            <div class="elementor-widget-wrap elementor-element-populated">
        			                                <div class="elementor-element elementor-element-838e4f4 elementor-widget elementor-widget-heading" data-id="838e4f4" data-element_type="widget" data-widget_type="heading.default">
        			                                    <div class="elementor-widget-container row-is-loading">
        			                                         
        			                                    </div>
        			                                </div>   
        			                            </div>
        			                        </div>
        			                    </div>
        			                </section>';

        			        }

			            $retorno .= '</div>
			        </div>
			    </div>
			</section>';

			$retorno .= '<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js" crossorigin="anonymous"></script>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js" crossorigin="anonymous"></script>
				<script src="https://refreshless.com/nouislider/documentation/assets/wNumb.js" crossorigin="anonymous"></script>
				<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
				<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment-with-locales.min.js"></script>
				<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> 
				<script src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/js/helpers/helpers.js?v='.date("dmYHis").'" crossorigin="anonymous"></script>
				<script src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/js/script-search-cars.js?v='.date("dmYHis").'" crossorigin="anonymous"></script>
				<script src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/js/script-result-cars.js?v='.date("dmYHis").'" crossorigin="anonymous"></script>
				<script src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/js/helpers/date.js?v='.date("YmdHis").'" id="date-cars-js"></script>';

			return $retorno;	 

		}
		/* FIM FUNÇÃO LISTAGEM DOS RESULTADOS */

		/* FUNÇÃO CHECKOUT */
		add_shortcode('TTBOOKING_MOTOR_CHECKOUT_CARS', 'shortcode_checkout_cars');  
		function shortcode_checkout_cars(){
			$retorno = "";

			$retorno .= '<link rel="preconnect" href="https://fonts.googleapis.com"> 
				<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> 
				<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
				<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet"> 
				<link href="'.plugin_dir_url( __FILE__ ) . 'includes/assets/css/checkout-cars.css?v='.date("YmdHis").'" rel="stylesheet">';

			$retorno .= '<style>
				.row{
					width: 100%;
				}
				.row .elementor-widget-heading{
					margin-bottom: 8px;
				}
				.bank-card__side_front, .bank-card__side_back:before{
		    		background-color: '.(empty(get_option( 'cor_cars' )) ? '#f0f0ee' : get_option( 'cor_cars' )).'; 
		    	}
		    	@media screen and (max-width: 480px){   
				    .bank-card__side{ 
				        background-color: '.(empty(get_option( 'cor_cars' )) ? '#f0f0ee' : get_option( 'cor_cars' )).'; 
				    } 
				}
				.input-group-prepend{  
				    color: '.(empty(get_option( 'cor_cars' )) ? '#000000' : get_option( 'cor_cars' )).';  
		    	}  
		    	.btnSelect{
					background-color: '.(empty(get_option( 'cor_botao_cars' )) ? '#000000' : get_option( 'cor_botao_cars' )).'d4; 
				}
				.btnSelect:hover{
					background-color: '.(empty(get_option( 'cor_botao_cars' )) ? '#000000' : get_option( 'cor_botao_cars' )).'; 
					color: #fff;
					cursor: pointer;
				}

				.btnBootbox{
					font-family: \'Montserrat\';
				    font-size: 10px;
				    border: 1px solid #ddd;
				    border-radius: 100px;
				    padding: 12px 26px;
				    background-color: #ddd;
				    width: 100%;
				    color: '.(empty(get_option( 'cor_botao_cars' )) ? '#000000' : get_option( 'cor_botao_cars' )).'; 
				    font-weight: 700;
				    text-transform: uppercase; 
				}
				.btnBootbox:hover{
				    background-color: '.(empty(get_option( 'cor_botao_cars' )) ? '#000000' : get_option( 'cor_botao_cars' )).';
				    color: #fff
				}

				.btnBootboxCancel{
					font-family: \'Montserrat\';
				    font-size: 10px;
				    border: 1px solid #000;
				    border-radius: 100px;
				    padding: 12px 26px; 
				    width: 100%;
				    color: #000; 
				    font-weight: 700;
				    text-transform: uppercase; 
				    margin-top: 20px;
				}
				.btnBootboxCancel:hover{
				    background-color: #000;
				    color: #fff;
				}
				.input-group .form-control{
					font-family: \'Montserrat\';
				}
			</style>';

			$retorno .= '<input type="hidden" id="cor_cars" value="'.(empty(get_option( 'cor_cars' )) ? '#f0f0ee' : get_option( 'cor_cars' )).'">';
			$retorno .= '<input type="hidden" id="url_ajax" value="'.admin_url('admin-ajax.php').'">';
			$retorno .= '<input type="hidden" id="type_reserva_cars" value="'.get_option('type_reserva_cars').'">';

			$retorno .= '<section class="elementor-section elementor-top-section elementor-element elementor-element-ed66d0f elementor-reverse-mobile elementor-section-boxed elementor-section-height-default elementor-section-height-default wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no" data-id="ed66d0f" data-element_type="section">
			    <div class="elementor-container elementor-column-gap-default">
			        <div class="elementor-column elementor-col-66 elementor-top-column elementor-element elementor-element-b8f60ea" data-id="b8f60ea" data-element_type="column">
			            <div class="elementor-widget-wrap elementor-element-populated">

			                <section class="elementor-section elementor-inner-section elementor-element elementor-element-cfffbd1 elementor-section-boxed elementor-section-height-default elementor-section-height-default wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no" data-id="cfffbd1" data-element_type="section">
			                    <div class="elementor-container elementor-column-gap-default">
			                        <div class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-f35fd21" data-id="f35fd21" data-element_type="column">
			                            <div class="elementor-widget-wrap elementor-element-populated">
			                                <div class="elementor-element elementor-element-dbfe648 elementor-widget elementor-widget-heading" data-id="dbfe648" data-element_type="widget" data-widget_type="heading.default">
			                                    <div class="elementor-widget-container">  
			                                        <h2 class="elementor-heading-title elementor-size-default">Falta pouco! Complete seus dados e finalize sua '.(get_option('type_reserva_cars') == 2 ? 'reserva' : 'solicitação').'. </h2>
			                                    </div>
			                                </div>
			                            </div>
			                        </div>
			                    </div>
			                </section>
 
			                <section class="elementor-section elementor-inner-section elementor-element elementor-element-e54b98c elementor-section-boxed elementor-section-height-default elementor-section-height-default wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no" data-id="e54b98c" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
			                    <div class="elementor-container elementor-column-gap-default">
			                        <div class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-672f3eb" data-id="672f3eb" data-element_type="column">
			                            <div class="elementor-widget-wrap elementor-element-populated">
			                                <div class="elementor-element elementor-element-7896315 elementor-widget elementor-widget-heading" data-id="7896315" data-element_type="widget" data-widget_type="heading.default">
			                                    <div class="elementor-widget-container">
			                                        <h2 class="elementor-heading-title elementor-size-default">Quem vai viajar?</h2>
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-9e81248 elementor-widget elementor-widget-heading" data-id="9e81248" data-element_type="widget" data-widget_type="heading.default">
			                                    <div class="elementor-widget-container">
			                                        <h2 class="elementor-heading-title elementor-size-default">Motorista</h2>
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-6a333b0 elementor-widget elementor-widget-heading" data-id="6a333b0" data-element_type="widget" data-widget_type="heading.default">
			                                    <div class="elementor-widget-container">
			                                        <p class="elementor-heading-title elementor-size-default">Lembre-se que para ser motorista, a pessoa deve ter mais de 21 anos. </p>
			                                    </div>
			                                </div>

			                                <div class="row">
			                                	<div class="col-lg-6 col-12"> 
					                                <div class="elementor-element elementor-element-aa1bc33 elementor-widget elementor-widget-heading" data-id="aa1bc33" data-element_type="widget" data-widget_type="heading.default">
					                                    <div class="elementor-widget-container">
					                                        <p class="elementor-heading-title elementor-size-default">Nome</p>
					                                    </div>
					                                </div>
					                                <div class="elementor-element elementor-element-063e3e2 elementor-widget elementor-widget-html" data-id="063e3e2" data-element_type="widget" data-widget_type="html.default">
					                                    <div class="elementor-widget-container">
					                                        <input class="form-control inputTextCheckout" type="text" placeholder="Insira o nome do passageiro" id="nameTitular">
					                                    </div>
					                                </div>
					                            </div>
					                            <div class="col-lg-6 col-12"> 
					                                <div class="elementor-element elementor-element-1cbbff7 elementor-widget elementor-widget-heading" data-id="1cbbff7" data-element_type="widget" data-widget_type="heading.default">
					                                    <div class="elementor-widget-container">
					                                        <p class="elementor-heading-title elementor-size-default">sobrenome</p>
					                                    </div>
					                                </div>
					                                <div class="elementor-element elementor-element-43e7773 elementor-widget elementor-widget-html" data-id="43e7773" data-element_type="widget" data-widget_type="html.default">
					                                    <div class="elementor-widget-container">
					                                        <input class="form-control inputTextCheckout" type="text" placeholder="Insira o último sobrenome do passageiro" id="surnameTitular">
					                                    </div>
					                                </div>
					                            </div>
					                        </div>

					                        <div class="row">
					                            <div class="col-lg-6 col-12">
					                            	<div class="elementor-element elementor-element-f6d2c3a elementor-widget elementor-widget-heading" data-id="f6d2c3a" data-element_type="widget" data-widget_type="heading.default">
					                                    <div class="elementor-widget-container">
					                                        <p class="elementor-heading-title elementor-size-default">Data de Nascimento</p>
					                                    </div>
					                                </div>
					                                <div class="elementor-element elementor-element-591c423 elementor-widget elementor-widget-html" data-id="591c423" data-element_type="widget" data-widget_type="html.default">
					                                    <div class="elementor-widget-container">
					                                        <input class="form-control inputTextCheckout" type="text" placeholder="dd/mm/yyyy" id="nasc">
					                                    </div>
					                                </div>
					                            </div>
					                            <div class="col-lg-6 col-12">
					                            	<div class="elementor-element elementor-element-f6d2c3a elementor-widget elementor-widget-heading" data-id="f6d2c3a" data-element_type="widget" data-widget_type="heading.default">
					                                    <div class="elementor-widget-container">
					                                        <p class="elementor-heading-title elementor-size-default">CPF</p>
					                                    </div>
					                                </div>
					                                <div class="elementor-element elementor-element-591c423 elementor-widget elementor-widget-html" data-id="591c423" data-element_type="widget" data-widget_type="html.default">
					                                    <div class="elementor-widget-container">
					                                        <input class="form-control inputTextCheckout" type="text" placeholder="000.000.000-00" id="cpf">
					                                    </div>
					                                </div>
					                            </div>
					                        </div>

			                                <div class="row">
			                                	<div class="col-lg-6 col-12">
					                                <div class="elementor-element elementor-element-b2dd4d2 elementor-widget elementor-widget-heading" data-id="b2dd4d2" data-element_type="widget" data-widget_type="heading.default">
					                                    <div class="elementor-widget-container">
					                                        <p class="elementor-heading-title elementor-size-default">E-mail</p>
					                                    </div>
					                                </div>
					                                <div class="elementor-element elementor-element-d83aa06 elementor-widget elementor-widget-html" data-id="d83aa06" data-element_type="widget" data-widget_type="html.default">
					                                    <div class="elementor-widget-container">
					                                        <input class="form-control inputTextCheckout" type="text" placeholder="meunome@exemplo.com" id="emailTitular">
					                                    </div>
					                                </div> 
					                            </div>
					                            <div class="col-lg-6 col-12"> 
					                                <div class="elementor-element elementor-element-affe04d elementor-widget elementor-widget-heading" data-id="affe04d" data-element_type="widget" data-widget_type="heading.default">
					                                    <div class="elementor-widget-container">
					                                        <p class="elementor-heading-title elementor-size-default">Celular</p>
					                                    </div>
					                                </div>
					                                <div class="elementor-element elementor-element-4ffa4b7 elementor-widget elementor-widget-html" data-id="4ffa4b7" data-element_type="widget" data-widget_type="html.default">
					                                    <div class="elementor-widget-container">
					                                        <input class="form-control inputTextCheckout" type="text" placeholder="(00) 00000-0000" id="celphone">
					                                    </div>
					                                </div>
					                            </div>
					                        </div>

			                            </div>
			                        </div>
			                    </div>
			                </section>'; 

			                if(get_option('type_reserva_cars') == 2){
				                $retorno .= '<section class="elementor-section elementor-inner-section elementor-element elementor-element-7d92252 elementor-section-boxed elementor-section-height-default elementor-section-height-default wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no" data-id="7d92252" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				                    <div class="elementor-container elementor-column-gap-default">
				                        <div class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-fbc8afb" data-id="fbc8afb" data-element_type="column">
				                            <div class="elementor-widget-wrap elementor-element-populated">
				                                <div class="elementor-element elementor-element-7722c1e elementor-widget elementor-widget-heading" data-id="7722c1e" data-element_type="widget" data-widget_type="heading.default">
				                                    <div class="elementor-widget-container">
				                                        <h2 class="elementor-heading-title elementor-size-default">Dados de pagamento</h2>
				                                    </div>
				                                </div>

				                                <div class="demo"> 
													<form class="payment-card"> 
														<div class="bank-card">  
															<div class="bank-card__side bank-card__side_front"> 
																<div class="bank-card__inner"> 
																	<label class="bank-card__label bank-card__label_holder"> 
																		<span class="bank-card__hint">Nome do titular</span> 
																		<input type="text" class="bank-card__field" placeholder="Nome do titular" pattern="[A-Za-z, ]{2,}" id="holder-card" required> 
																	</label> 
																</div>

																<div class="bank-card__inner"> 
																	<label class="bank-card__label bank-card__label_number"> 
																		<span class="bank-card__hint">Número do cartão</span> 
																		<input type="text" class="bank-card__field" placeholder="Número do cartão" pattern="[0-9]{16}" id="number-card" onfocusout="select_card()"  required> 
																	</label> 
																</div> 

																<div class="bank-card__inner bank-card__footer"> 
																	<label class="bank-card__label bank-card__month"> 
																		<span class="bank-card__hint">Mês</span> 
																		<input type="text" class="bank-card__field" placeholder="MM" maxlength="2" pattern="[0-9]{2}" id="mm-card" name="mm-card" required> 
																	</label>

																	<span class="bank-card__separator">/</span>

																	<label class="bank-card__label bank-card__year"> 
																		<span class="bank-card__hint">Ano</span> 
																		<input type="text" class="bank-card__field" placeholder="YY" maxlength="2" pattern="[0-9]{2}" id="year-card" name="year-card" required> 
																	</label>

																	<label class="bank-card__label bank-card__operadora"> 

																	</label> 
																</div> 
															</div>

															<div class="bank-card__side bank-card__side_back"> 
																<div class="bank-card__inner"> 
																	<label class="bank-card__label bank-card__cvc"> 
																		<span class="bank-card__hint">CVC</span> 
																		<input type="text" class="bank-card__field" placeholder="CVC" maxlength="3" pattern="[0-9]{3}" name="cvc-card" id="cvc-card" required> 
																	</label> 
																</div> 
															</div> 
														</div>  
													</form> 
												</div> 

												<label style="color: #3F3F3F;font-family: \'Montserrat\', Sans-serif;font-size: 13px;font-weight: 700;text-transform: uppercase;margin-bottom: 8px;"><strong>Parcelamento</strong></label>

												<select class="form-control" id="installmentsCars" style="font-family: \'Montserrat\'" onchange="selectInstallmentCar()"> 

												</select>

				                            </div>
				                        </div>
				                    </div>
				                </section>

				                <section class="elementor-section elementor-inner-section elementor-element elementor-element-f20219b elementor-section-boxed elementor-section-height-default elementor-section-height-default wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no" data-id="f20219b" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				                    <div class="elementor-container elementor-column-gap-default">
				                        <div class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-d845943" data-id="d845943" data-element_type="column">
				                            <div class="elementor-widget-wrap elementor-element-populated">
				                                <div class="elementor-element elementor-element-a809190 elementor-widget elementor-widget-heading" data-id="a809190" data-element_type="widget" data-widget_type="heading.default">
				                                    <div class="elementor-widget-container">
				                                        <h2 class="elementor-heading-title elementor-size-default">Dados de faturamento</h2>
				                                    </div>
				                                </div>

				                                <div class="row address">

													<div class="col-lg-6 col-12"> 
														<label>CEP</label> 
														<div class="input-group mb-4"> 
															<div class="input-group-prepend"> 
																<i class="fa fa-map"></i> 
															</div> 
															<input type="text" class="form-control" placeholder="" aria-label="Insira seu CEP" aria-describedby="basic-addon1" id="cep" autocomplete="off"> 
														</div> 
													</div>

												</div> 

												<div class="row address">

													<div class="col-lg-9 col-12"> 
														<label>Endereço</label> 
														<div class="input-group mb-4"> 
															<div class="input-group-prepend"> 
																<i class="fa fa-house-user"></i> 
															</div> 
															<input type="text" class="form-control" placeholder="" aria-label="Insira seu endereço" id="endereco" aria-describedby="basic-addon1" autocomplete="off"> 
														</div> 
													</div>  

													<div class="col-lg-3 col-12"> 
														<label>Número</label> 
														<div class="input-group mb-4">  
															<div class="input-group-prepend"> # </div> 
															<input type="text" class="form-control" placeholder="" aria-label="Insira o número" id="numero" aria-describedby="basic-addon1" autocomplete="off"> 
														</div> 
													</div> 

													<div class="col-lg-12 col-12"> 
														<label>Complemento</label> 
														<div class="input-group mb-4">  
															<div class="input-group-prepend"> 
																<i class="fa fa-info"></i> 
															</div> 
															<input type="text" class="form-control" placeholder="" aria-label="Insira o complemento" id="complemento" aria-describedby="basic-addon1" autocomplete="off"> 
														</div> 
													</div> 

													<div class="col-lg-4 col-12"> 
														<label>Bairro</label> 
														<div class="input-group mb-4"> 
															<div class="input-group-prepend"> 
																<i class="fa fa-warehouse"></i> 
															</div> 
															<input type="text" class="form-control" placeholder="" aria-label="Insira seu bairro" id="bairro" aria-describedby="basic-addon1" autocomplete="off"> 
														</div> 
													</div> 

													<div class="col-lg-4 col-12"> 
														<label>Cidade</label> 
														<div class="input-group mb-4"> 
															<div class="input-group-prepend"> 
																<i class="fa fa-building"></i> 
															</div> 
															<input type="text" class="form-control" placeholder="" aria-label="Insira a cidade" id="cidade" aria-describedby="basic-addon1" autocomplete="off"> 
														</div> 
													</div> 

													<div class="col-lg-4 col-12"> 
														<label>Estado</label>  
														<div class="input-group mb-4"> 
															<div class="input-group-prepend"> 
																<i class="fa fa-flag"></i> 
															</div> 
															<input type="text" class="form-control" placeholder="" aria-label="Insira o estado" id="estado" aria-describedby="basic-addon1" autocomplete="off"> 
														</div> 
													</div> 

												</div>  
				                            </div>
				                        </div>
				                    </div>
				                </section>';
				            }

			                $retorno .= '<div class="row certiSign"> 
								<div class="col-lg-8 col-12"> 
									<p> 
										<i class="fa fa-lock"></i> <strong>Este site é um site seguro.</strong> 
									</p> 
									<p> 
										Utilizamos conexões seguras para proteger sua informação. 
									</p> 
								</div>

								<div class="col-lg-4 col-12"> 
									<img src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/img/logo-ssl.png" style="">  
								</div> 
							</div>

			            </div>
			        </div>

			        <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-bc93ce8" data-id="bc93ce8" data-element_type="column">
			            <div class="elementor-widget-wrap elementor-element-populated">

			                <div class="elementor-element elementor-element-3bda344 elementor-widget elementor-widget-heading" data-id="3bda344" data-element_type="widget" data-widget_type="heading.default">
			                    <div class="elementor-widget-container">
			                        <h2 class="elementor-heading-title elementor-size-default" style="margin: 10px 0 10px 0">Detalhe do pagamento</h2>
			                    </div>
			                </div>

			                <section class="elementor-section elementor-inner-section elementor-element elementor-element-ff3d6a6 elementor-section-boxed elementor-section-height-default elementor-section-height-default wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no" data-id="ff3d6a6" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
			                    <div class="elementor-container elementor-column-gap-default">
			                        <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-e6394ef" data-id="e6394ef" data-element_type="column">
			                            <div class="elementor-widget-wrap elementor-element-populated">
			                                <div class="elementor-element elementor-element-23b2b67 elementor-widget elementor-widget-heading" data-id="23b2b67" data-element_type="widget" data-widget_type="heading.default">
			                                    <div class="elementor-widget-container">
			                                        <h2 class="elementor-heading-title elementor-size-default">Locação</h2>
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-4b3110d elementor-widget elementor-widget-heading" data-id="4b3110d" data-element_type="widget" data-widget_type="heading.default">
			                                    <div class="elementor-widget-container">
			                                        <h2 class="elementor-heading-title elementor-size-default">Taxas e encargos</h2>
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-7fb9c7f elementor-widget-divider--view-line elementor-widget elementor-widget-divider" data-id="7fb9c7f" data-element_type="widget" data-widget_type="divider.default">
			                                    <div class="elementor-widget-container"> 
			                                        <div class="elementor-divider">
			                                            <span class="elementor-divider-separator"></span>
			                                        </div>
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-512aba4 elementor-widget elementor-widget-heading" data-id="512aba4" data-element_type="widget" data-widget_type="heading.default">
			                                    <div class="elementor-widget-container">
			                                        <h2 class="elementor-heading-title elementor-size-default">TOTAL</h2>
			                                    </div>
			                                </div>
			                            </div>
			                        </div>
			                        <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-e65931d" data-id="e65931d" data-element_type="column">
			                            <div class="elementor-widget-wrap elementor-element-populated">
			                                <div class="elementor-element elementor-element-303f853 elementor-widget elementor-widget-heading" data-id="303f853" data-element_type="widget" data-widget_type="heading.default">
			                                    <div class="elementor-widget-container">
			                                        <h2 class="elementor-heading-title elementor-size-default" id="priceLoc"> </h2>
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-cfa96b0 elementor-widget elementor-widget-heading" data-id="cfa96b0" data-element_type="widget" data-widget_type="heading.default">
			                                    <div class="elementor-widget-container">
			                                        <h2 class="elementor-heading-title elementor-size-default" id="priceTax"> </h2>
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-11de608 elementor-widget-divider--view-line elementor-widget elementor-widget-divider" data-id="11de608" data-element_type="widget" data-widget_type="divider.default">
			                                    <div class="elementor-widget-container">
			                                        <div class="elementor-divider">
			                                            <span class="elementor-divider-separator"></span>
			                                        </div>
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-90fb2cd elementor-widget elementor-widget-heading" data-id="90fb2cd" data-element_type="widget" data-widget_type="heading.default">
			                                    <div class="elementor-widget-container">
			                                        <h2 class="elementor-heading-title elementor-size-default" id="priceTotal">
			                                            
			                                        </h2>
			                                    </div>
			                                </div>
			                            </div>
			                        </div>
			                    </div>
			                </section>

			                <div class="elementor-element elementor-element-4703058 elementor-widget elementor-widget-heading" data-id="4703058" data-element_type="widget" data-widget_type="heading.default">
			                    <div class="elementor-widget-container">
			                        <h2 class="elementor-heading-title elementor-size-default" style="margin-top: 14px;">Detalhe da '.(get_option('type_reserva_cars') == 2 ? 'reserva' : 'solicitação').'</h2>
			                    </div>
			                </div>

			                <section class="elementor-section elementor-inner-section elementor-element elementor-element-6f55711 elementor-section-boxed elementor-section-height-default elementor-section-height-default wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no" data-id="6f55711" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
			                    <div class="elementor-container elementor-column-gap-default">
			                        <div class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-bc2d0d8" data-id="bc2d0d8" data-element_type="column">
			                            <div class="elementor-widget-wrap elementor-element-populated">
			                                <div class="elementor-element elementor-element-20673eb elementor-widget elementor-widget-image" data-id="20673eb" data-element_type="widget" data-widget_type="image.default">
			                                    <div class="elementor-widget-container"> 
			                                        <img decoding="async" width="128" height="128" src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/img/icon-car.png" class="attachment-large size-large wp-image-1869" alt="" loading="lazy" srcset="'.plugin_dir_url( __FILE__ ) . 'includes/assets/img/icon-car.png 128w, '.plugin_dir_url( __FILE__ ) . 'includes/assets/img/icon-car.png 100w" sizes="(max-width: 128px) 100vw, 128px">
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-510e234 elementor-widget elementor-widget-heading" data-id="510e234" data-element_type="widget" data-widget_type="heading.default">
			                                    <div class="elementor-widget-container">
			                                        <h2 class="elementor-heading-title elementor-size-default" id="nameCar"> </h2>
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-6d1fa9b elementor-widget elementor-widget-heading" data-id="6d1fa9b" data-element_type="widget" data-widget_type="heading.default">
			                                    <div class="elementor-widget-container">
			                                        <h2 class="elementor-heading-title elementor-size-default" id="paxCar"> </h2>
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-31661f5 elementor-position-left elementor-vertical-align-top elementor-widget elementor-widget-image-box" data-id="31661f5" data-element_type="widget" data-widget_type="image-box.default">
			                                    <div class="elementor-widget-container"> 
			                                        <div class="elementor-image-box-wrapper imgRental" id="dataRental">
			                                            
			                                        </div>
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-3d18535 elementor-widget-divider--view-line elementor-widget elementor-widget-divider" data-id="3d18535" data-element_type="widget" data-widget_type="divider.default">
			                                    <div class="elementor-widget-container">
			                                        <div class="elementor-divider">
			                                            <span class="elementor-divider-separator"></span>
			                                        </div>
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-f1295c2 elementor-widget elementor-widget-heading" data-id="f1295c2" data-element_type="widget" data-widget_type="heading.default">
			                                    <div class="elementor-widget-container">
			                                        <h2 class="elementor-heading-title elementor-size-default">Retirada</h2>
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-1bfa476 elementor-widget elementor-widget-heading" data-id="1bfa476" data-element_type="widget" data-widget_type="heading.default">
			                                    <div class="elementor-widget-container">
			                                        <h2 class="elementor-heading-title elementor-size-default" id="datePickup"> </h2>
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-a3cbb80 elementor-widget elementor-widget-heading" data-id="a3cbb80" data-element_type="widget" data-widget_type="heading.default">
			                                    <div class="elementor-widget-container">
			                                        <h2 class="elementor-heading-title elementor-size-default" id="timePickup"> </h2>
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-921eb20 elementor-position-left elementor-view-default elementor-mobile-position-top elementor-vertical-align-top elementor-widget elementor-widget-icon-box" data-id="921eb20" data-element_type="widget" data-widget_type="icon-box.default">
			                                    <div class="elementor-widget-container">
			                                        <link rel="stylesheet" href="'.plugin_dir_url( __FILE__ ) . 'includes/assets/css/widget-icon-box.min.css">
			                                        <div class="elementor-icon-box-wrapper">
			                                            <div class="elementor-icon-box-icon">
			                                                <span class="elementor-icon elementor-animation-">
			                                                    <i aria-hidden="true" class="fas fa-city"></i>
			                                                </span>
			                                            </div>
			                                            <div class="elementor-icon-box-content" id="locationRentalPickup">
			                                                
			                                            </div>
			                                        </div>
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-b1e73e5 elementor-widget elementor-widget-heading" data-id="b1e73e5" data-element_type="widget" data-widget_type="heading.default">
			                                    <div class="elementor-widget-container">
			                                        <h2 class="elementor-heading-title elementor-size-default">Devolução</h2>
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-567d564 elementor-widget elementor-widget-heading" data-id="567d564" data-element_type="widget" data-widget_type="heading.default">
			                                    <div class="elementor-widget-container">
			                                        <h2 class="elementor-heading-title elementor-size-default" id="dateDrop"> </h2>
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-e717eb5 elementor-widget elementor-widget-heading" data-id="e717eb5" data-element_type="widget" data-widget_type="heading.default">
			                                    <div class="elementor-widget-container">
			                                        <h2 class="elementor-heading-title elementor-size-default" id="timeDrop"> </h2>
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-01f5dad elementor-position-left elementor-view-default elementor-mobile-position-top elementor-vertical-align-top elementor-widget elementor-widget-icon-box" data-id="01f5dad" data-element_type="widget" data-widget_type="icon-box.default">
			                                    <div class="elementor-widget-container">
			                                        <div class="elementor-icon-box-wrapper">
			                                            <div class="elementor-icon-box-icon">
			                                                <span class="elementor-icon elementor-animation-">
			                                                    <i aria-hidden="true" class="fas fa-city"></i>
			                                                </span>
			                                            </div>
			                                            <div class="elementor-icon-box-content" id="locationRentalDrop">
			                                                 
			                                            </div>
			                                        </div>
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-5321127 elementor-widget elementor-widget-heading" data-id="5321127" data-element_type="widget" data-widget_type="heading.default">
			                                    <div class="elementor-widget-container">
			                                        <h2 class="elementor-heading-title elementor-size-default">Características do veículo</h2>
			                                    </div>
			                                </div>
			                                <div class="elementor-element elementor-element-d35a4cd elementor-mobile-align-center elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="d35a4cd" data-element_type="widget" data-widget_type="icon-list.default">
			                                    <div class="elementor-widget-container">
			                                        <link rel="stylesheet" href="'.plugin_dir_url( __FILE__ ) . 'includes/assets/css/widget-icon-list.min.css">
			                                        <ul class="elementor-icon-list-items" id="specifCar">
			                                            
			                                        </ul>
			                                    </div>
			                                </div> 
			                                <a onclick="see_info_loc()"><button class="btn btnBootbox"><i class="fa fa-info-circle"></i> Mais informações sobre a locação</button></a>
			                                <br>

			                                <a onclick="see_info_cancel()"><button class="btn btnBootboxCancel"><i class="fa fa-info-circle"></i> Políticas e Cancelamentos</button></a>
			                            </div>
			                        </div>
			                    </div>
			                </section> 

			                <a onclick="send_order_cars('.get_option('type_reserva_cars').')">
			                	<button class="btn btnSelect">
			                		Finalizar '.(get_option('type_reserva_cars') == 2 ? 'reserva' : 'solicitação').'
			                	</button>
			                </a> 

			            </div>
			        </div>

			    </div>
			</section>';

			$retorno .= '<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js" crossorigin="anonymous"></script> 
				<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
				<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment-with-locales.min.js"></script> 
				<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
				<script src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/js/helpers/helpers.js?v='.date("dmYHis").'" crossorigin="anonymous"></script>
				<script src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/js/script-checkout-cars.js?v='.date("dmYHis").'" crossorigin="anonymous"></script>';

			return $retorno;
		}
		/* FIM FUNÇÃO CHECKOUT */

		/* FUNÇÃO FINALIZAR COMPRA */
		add_shortcode('TTBOOKING_MOTOR_ORDER_CARS', 'shortcode_order_cars');  
		function shortcode_order_cars(){  
			$retorno = ''; 

			$retorno .= '  
				<link rel="preconnect" href="https://fonts.googleapis.com"> 
				<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> 
				<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
				<link href="'.plugin_dir_url( __FILE__ ) . 'includes/assets/css/order-cars.css?v='.date("YmdHis").'" rel="stylesheet"> ';  

			$logo = esc_url( wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0] );  
		 	$tipoReserva = 'solicitação'; 

			if(get_option( 'type_reserva_cars' ) == 2){ 
				$htmlAdicional = '<br style="display: block !important"> <span>Número de confirmação: '.$_GET['order'].'</span>'; 
				$tipoReserva = 'reserva';  
			} 

			$retorno .= '<input type="hidden" id="url_ajax" value="'.admin_url('admin-ajax.php').'">';
			$retorno .= '<input type="hidden" id="type_reserva_cars" value="'.get_option( 'type_reserva_cars' ).'">'; 
			$retorno .= '<input type="hidden" id="order" value="'.$_GET['order'].'">'; 
			$retorno .= '<input type="hidden" id="plugin_dir_url" value="'.plugin_dir_url( __FILE__ ).'">';  
			$retorno .= '<input type="hidden" id="cor_cars" value="'.(empty(get_option( 'cor_cars' )) ? '#000000' : get_option( 'cor_cars' )).'">';  

			$retorno .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
				<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br"> 
			    	<head> 
				        <meta name="viewport" content="width=device-width" /> 
				        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> 
				        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>  
			       	</head> 

			        <table align="center" border="0" cellpadding="0" class="larguraTabel" cellspacing="0" style="border-collapse:collapse;border: none;width: 640px;margin: 0 auto;" > 
			            <tbody style="background-color: '.(empty(get_option( 'cor_cars' )) ? '#000000' : get_option( 'cor_cars' )).';">  
			                <tr>'; 
				                if(!empty($logo)){ 
				                    $retorno .= '<td align="center" height="0" style="width:35%;border: none;" ><img src="https://traveltec.com.br/wp-content/uploads/2021/08/Logotipo-Pequeno.png" style=""></td>'; 
				                } 
			                    $retorno .= '<td align="center" height="0" style="border: none;word-break: break-word;font-family:\'Montserrat\';color: #fff;padding: 20px;font-size: 11px;text-align: right;" ><strong>SEU PEDIDO FOI RECEBIDO COM SUCESSO!</strong> '.$htmlAdicional.'</td> 
			                </tr> 
			            </tbody> 
			        </table> 

			        <table align="center" border="0" cellpadding="0" class="larguraTabel" style="border-collapse:collapse;border: none;margin: 0 auto" > 
			            <tbody style="background-color: #ddd;"> 
			                <tr> 
			                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: justify;border: none;" valign="top"  > 
			                        <font style="font-family:\'Montserrat\', sans-serif;font-size:12px;color:#666666;margin:1em 0"> ';  
			                            $retorno .= ' 
			                            	<p style="margin: 0">  
			                            		<img src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/img/icon-check-round.png" style="    display: inline-flex;height: 21px;margin-right: 5px;"> <small style="font-size: 13px;font-weight: 600;">Agradecemos sua '.$tipoReserva.'!</small> <h6 style="margin: 0;font-weight: 700;">Sua '.$tipoReserva.' de locação de veículos está confirmada.</h6> 
			                            	</p> 
			                            	<p style="margin: 5px 0"> 
			                            		<img src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/img/icon-check.png" style="    display: inline-flex;margin-right: 5px;"> A <strong class="hotel_reserva"> </strong> estará à sua espera em <strong id="checkin_reserva"> </strong>. 
			                            	</p> 
			                            	<p style="margin: 5px 0"> 
			                            		<img src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/img/icon-check.png" style="    display: inline-flex;margin-right: 5px;"> <span id="info_payment">Entraremos em contato para cuidar do pagamento.</span> 
			                            	</p>  
		                    	</td> 
		                	</tr> 
			            	<tr> 
			                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: justify;border: none;" valign="top"  > 
			                        <font style="font-family:\'Montserrat\', sans-serif;font-size:12px;color:#666666;margin:1em 0"> ';  
			                            $retorno .= ' 
			                            	<h5 class="hotel_reserva" style="margin: 14px 0;color:'.(empty(get_option( 'cor_cars' )) ? '#000000' : get_option( 'cor_cars' )).';font-size: 18px;font-weight:600">Operadora <strong> </strong></h5> 
			                            	<p style="margin: 5px 0;font-size:13px;" id="endereco_hotel"> </p> 
			                            	<p style="margin: 5px 0" id="mapa_hotel"> 
			                            	</p>   
			                	</td> 
			           		</tr> 
			            </tbody> 
			        </table> 

			        <table align="center" border="0" cellpadding="0" class="larguraTabel" style="border-collapse:collapse;border: none;margin: 0 auto" > 
			            <tbody style="background-color: #ddd;"> 
			            	<tr style="border-bottom: 1px solid #f0f0f0;"> 
			                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: justify;border: none;width: 35%;" valign="top"  >  
			                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">  ';  
			                            $retorno .= '<strong>Sua reserva</strong> 
			                	</td>  
			                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  > 
			                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0"> ';  
			                            $retorno .= '<span id="desc_dia_room_reserva"> </span> 
			                	</td> 
			            	</tr> 
			            	<tr style="border-bottom: 1px solid #f0f0f0;"> 
			                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: justify;border: none;" valign="top"  > 
			                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0"> '; 
			                            $retorno .= '<strong>Sua reserva é para</strong> 
			                	</td> 
			                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  > 
			                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0"> ';  
			                            $retorno .= '<span id="desc_sua_reserva_para"> </span> 
			               		</td> 
			            	</tr> 
			            	<tr style="border-bottom: 1px solid #f0f0f0;"> 
			                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: justify;border: none;" valign="top"  > 
			                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0"> ';  
			                            $retorno .= '<strong>Retirada</strong> 
			               		</td> 
			                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  > 
			                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0"> ';  
			                            $retorno .= '<span id="desc_sua_reserva_checkin"> </span> 
			               		</td> 
			            	</tr> 
			           		<tr style=""> 
			                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: justify;border: none;" valign="top"  > 
			                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">  ';  
			                            $retorno .= '<strong>Devolução</strong> 
			                	</td> 
			                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  > 
			                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">  ';  
			                            $retorno .= '<span id="desc_sua_reserva_checkout"> </span> 
			                	</td> 
			            	</tr> 
			            </tbody> 
			        </table> 

			        <table align="center" border="0" cellpadding="0" class="larguraTabel" style="border-collapse:collapse;border: none;margin: 0 auto" > 
			            <tbody style="background-color: #ddd;"> 
			            	<tr style="border-bottom: 1px solid #f0f0f0;"> 
			                    <td align="center" height="" style="font-family:\'Montserrat\';word-break: break-word;background-color: '.(empty(get_option( 'cor_cars' )) ? '#000000' : get_option( 'cor_cars' )).';text-align: justify;border: none;color: #fff;padding: 20px;" valign="top"  > 
		                    		<p style="margin-bottom:5px;font-weight:600;font-size:14px;" id="desc_room_reserva"> </p> 
		                    		<p style="margin-bottom:5px;font-weight:600;font-size:14px;" id="desc_taxa_reserva"></p> 
		                    		<p style="margin:5px 0;font-weight:600;font-size:19px;">Total <span style="float:right;" id="price_total"> </span></p> 
		                    		<p style="margin:5px 0; font-size:13px;"> 
		                    			Aguarde entrarmos em contato para cuidarmos do pagamento. 
		                    		</p> 
		                    		<p style="margin:5px 0; font-size:13px;"> 
										Por favor, observe que pedidos adicionais (por exemplo, condutor adicional) não estão incluídos neste valor. 
		                    		</p> 
									<p style="margin:5px 0; font-size:13px;">  
										O preço total mostrado é o valor que você pagará à locadora. Não cobramos dos passageiros nenhuma taxa de reserva, administrativa ou de qualquer outro tipo.  
		                    		</p> 
									<p style="margin:5px 0; font-size:13px;"> 
										Se você cancelar, impostos aplicáveis ainda podem ser cobrados pela locadora.
									</p> 
									<p style="margin:5px 0; font-size:13px;">  
										Se você não comparecer sem cancelar com antecedência, a locadora poderá cobrar o valor total da reserva. 
		                    		</p>  
		                    	</td> 
		                	</tr> 
			            </tbody> 
			        </table> 

			        <table align="center" border="0" cellpadding="0" class="larguraTabel" style="border-collapse:collapse;border: none;margin: 0 auto" > 
			            <tbody style="background-color: #ddd;">  
			           		<tr> 
			                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: justify;border: none;padding: 0px 14px;" valign="top"  > 
			                        <font style="font-family:\'Montserrat\', sans-serif;font-size:12px;color:#666666;margin:1em 0"> ';  
			                            $retorno .= ' 
		                    		<h5 class="" style="margin: 14px 0;color:'.(empty(get_option( 'cor_cars' )) ? '#000000' : get_option( 'cor_cars' )).';font-size: 18px;"><strong>Informações sobre a reserva</strong></h5>  
			                	</td> 
			           		</tr> 
			            </tbody> 
			        </table> 

			        <table align="center" border="0" cellpadding="0" class="larguraTabel" style="border-collapse:collapse;border: none;margin: 0 auto" > 
			            <tbody style="background-color: #ddd;"> 
			            	<tr style="border-bottom: 1px solid #f0f0f0;"> 
			                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: left;border: none;" valign="top"  > 
			                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0"> ';  
			                            $retorno .= '<strong>Nome do titular</strong> 
			                	</td> 
			                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  > 
			                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0"> '; 
			                            $retorno .= '<span id="desc_titular"> </span> 
			                	</td> 
			           		</tr>
			           		<tr style="border-bottom: 1px solid #f0f0f0;"> 
			                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: left;border: none;" valign="top"  >  
			                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0"> ';  
			                            $retorno .= '<strong>Sobre o veículo</strong> 
			                	</td> 
			                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  > 
			                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0"> ';  
			                            $retorno .= '<span id="desc_qtd_rooms"> </span> 
			                	</td>  
			           		</tr>   
			            </tbody> 
			        </table> 

			        <table align="center" border="0" cellpadding="0" class="larguraTabel" style="border-collapse:collapse;border: none;margin: 0 auto" >  
			            <tbody style="background-color: #ddd;">  
			            	<tr> 
			                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: justify;border: none;padding: 0px 14px;" valign="top"  >  
			                        <font style="font-family:\'Montserrat\', sans-serif;font-size:12px;color:#666666;margin:1em 0"> '; 
			                            $retorno .= ' 
			                            	<h5 class="" style="margin: 14px 0;color:'.(empty(get_option( 'cor_cars' )) ? '#000000' : get_option( 'cor_cars' )).';font-size: 18px;"><strong>Pagamento</strong></h5>   
			                	</td> 
			            	</tr> 
			            </tbody> 
			        </table>'; 

			        if(get_option( 'type_reserva_cars' ) == 2){ 

				        $retorno .= '<table align="center" border="0" cellpadding="0" class="larguraTabel" style="border-collapse:collapse;border: none;margin: 0 auto" id="payment_card">  
				            <tbody style="background-color: #ddd;"> 
				            	<tr style="border-bottom: 1px solid #f0f0f0;"> 
				                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: left;border: none;" valign="top"  > 
				                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0"> ';   
				                            $retorno .= '<strong>Nome do titular</strong>  
				                	</td> 
				                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  > 
				                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">  ';  
				                            $retorno .= '<span id="desc_titular_card"> </span> 
				                	</td> 
				            	</tr>
				            	<tr style="border-bottom: 1px solid #f0f0f0;"> 
				                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: left;border: none;" valign="top"  > 
				                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0"> ';  
				                            $retorno .= '<strong>Número do cartão</strong> 
				                	</td> 
				                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  > 
				                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0"> ';  
				                            $retorno .= '<span id="desc_number_card"> </span> 
				                	</td> 
				            	</tr>
				            	<tr style="border-bottom: 1px solid #f0f0f0;"> 
				                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: left;border: none;" valign="top"  > 
				                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0"> ';  
				                            $retorno .= '<strong>Validade</strong> 
				                	</td> 
				                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  > 
				                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0"> ';  
				                            $retorno .= '<span id="desc_validade_card"> </span> 
				                	</td> 
				            	</tr>
				            	<tr style=""> 
				                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: left;border: none;" valign="top"  > 
				                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0"> ';  
				                            $retorno .= '<strong>Parcelas</strong> 
				                	</td> 
				                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  > 
				                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0"> ';  
				                            $retorno .= '<span id="desc_parcelas_card"> </span> 
				                	</td> 
				            	</tr> 
				            </tbody> 
				        </table>'; 

				    }else{ 

				        $retorno .= '<table align="center" border="0" cellpadding="0" class="larguraTabel" style="border-collapse:collapse;border: none;margin: 0 auto" id="payment_agency"> 
				            <tbody style="background-color: #ddd;"> 
				       			<tr style=" "> 
				                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: left;border: none;" valign="top"  > 
				                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0"> Entraremos em contato para cuidar das informações de pagamento. Mas não se preocupe, sua solicitação foi recebida! 
				                	</td>
				            	</tr>  
				            </tbody> 
				        </table>'; 

			       	} 

			        $retorno .= '<table align="center" border="0" cellpadding="0" class="larguraTabel" style="border-collapse:collapse;border: none;margin: 0 auto" > 
			            <tbody style="background-color: #ddd;">
			            	<tr style="border-top: 1px solid #f0f0f0;"> 
			                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: left;border: none;" valign="top"  > 
			                    	<p style="font-family:\'Montserrat\', sans-serif; color:#666666;text-align:center;font-size:12px;"><a href="https://traveltec.com.br" target="_blank">Travel Tec</a> © 2023. Todos os direitos reservados.</p> 
			                    </td> 
			            	</tr>
			            </tbody>
			        </table>  
				</html>'; 

			$retorno .= ' 
				<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
				<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment-with-locales.min.js"></script>
				<script src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/js/helpers/helpers.js?v='.date("dmYHis").'" crossorigin="anonymous"></script>
				<script src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/js/script-order-cars.js?v='.date("dmYHis").'" crossorigin="anonymous"></script> ';	 

			return $retorno; 
		}
		/* FIM FUNÇÃO FINALIZAR COMPRA */

		/* FUNÇÃO QUE ENVIA O E-MAIL DA COMPRA */
		add_action( 'wp_ajax_send_mail_confirm_order', 'send_mail_confirm_order' );  
		add_action( 'wp_ajax_nopriv_send_mail_confirm_order', 'send_mail_confirm_order' ); 
		function send_mail_confirm_order(){ 

			$headers = "From: Travel Tec <sac@traveltec.com.br>";  

			$html = ''; 

		 	$tipoReserva = 'solicitação de cotação'; 
		 	$htmlAdicional = ''; 

			if($_POST['type_reserva'] == 2){ 
				$htmlAdicional = '<br style="display: block !important"> <span>Número de confirmação: '.$order.'</span>';  
				$tipoReserva = 'reserva';  
			} 

			$type_reserva = $_POST['type_reserva'];
			$plugin_dir_url = $_POST['plugin_dir_url'];
			$cor_cars = $_POST['cor_cars'];
			$hotel_reserva = $_POST['hotel_reserva'];
			$checkin_reserva = $_POST['checkin_reserva'];
			$endereco_hotel = $_POST['endereco_hotel'];
			$mapa_hotel = $_POST['mapa_hotel'];
			$desc_dia_room_reserva = $_POST['desc_dia_room_reserva'];
			$desc_sua_reserva_para = $_POST['desc_sua_reserva_para'];
			$desc_sua_reserva_checkin = $_POST['desc_sua_reserva_checkin'];
			$desc_sua_reserva_checkout = $_POST['desc_sua_reserva_checkout'];
			$desc_room_reserva = $_POST['desc_room_reserva'];
			$desc_taxa_reserva = $_POST['desc_taxa_reserva'];
			$price_total = $_POST['price_total'];
			$desc_titular = $_POST['desc_titular'];
			$desc_qtd_rooms = $_POST['desc_qtd_rooms'];
			$desc_titular_card = $_POST['desc_titular_card'];
			$desc_number_card = $_POST['desc_number_card'];
			$desc_validade_card = $_POST['desc_validade_card'];
			$desc_parcelas_card = $_POST['desc_parcelas_card']; 


			$html .= '<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"> 
			    <head> 
			        <meta charset="utf-8"> 
			        <meta name="viewport" content="width=device-width"> 
			        <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
			        <meta name="x-apple-disable-message-reformatting"> 
					<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet"> 
			        <title></title> 
			        <style> 
			            html, body { 
			                margin: 0 auto !important; 
			                padding: 0 !important; 
			                height: 100% !important; 
			                width: 100% !important; 
			                background: #f1f1f1; 
			                font-family: \'Montserrat\' 
			            }  
			            * { 
			                -ms-text-size-adjust: 100%; 
			                -webkit-text-size-adjust: 100%; 
			            } 
			            div[style*="margin: 16px 0"] { 
			                margin: 0 !important; 
			            }  
			            table, td { 
			                mso-table-lspace: 0pt !important; 
			                mso-table-rspace: 0pt !important; 
			            } 
			            table { 
			                border-spacing: 0 !important; 
			                border-collapse: collapse !important; 
			                table-layout: fixed !important; 
			                margin: 0 auto !important; 
			            }   
			            img { 
			                -ms-interpolation-mode: bicubic; 
			            } 
			            a { 
			                text-decoration: none; 
			            } 
			            *[x-apple-data-detectors], .unstyle-auto-detected-links *, .aBn { 
			                border-bottom: 0 !important; 
			                cursor: default !important; 
			                color: inherit !important; 
			                text-decoration: none !important; 
			                font-size: inherit !important; 
			                font-family: inherit !important; 
			                font-weight: inherit !important; 
			                line-height: inherit !important; 
			            } 
			            .a6S { 
			                display: none !important; 
			                opacity: 0.01 !important; 
			            } 
			            .im { 
			                color: inherit !important; 
			            } 
			            img.g-img+div { 
			                display: none !important;  
			            } 
			            @media only screen and (min-device-width: 320px) and (max-device-width: 374px) { 
			                u~div .email-container { 
			                    min-width: 320px !important; 
			                } 
			            } 
			            @media only screen and (min-device-width: 375px) and (max-device-width: 413px) { 
			                u~div .email-container { 
			                    min-width: 375px !important; 
			                } 
			            } 
			            @media only screen and (min-device-width: 414px) { 
			                u~div .email-container { 
			                    min-width: 414px !important; 
			                } 
			            }  
			            .primary { 
			                background: #17bebb; 
			            } 
			            .bg_white { 
			                background: #ffffff; 
			            } 
			            .bg_ehtl { 
			                background: '.$cor_cars.'; 
			            } 
			            .bg_light { 
			                background: #f7fafa; 
			            } 
			            .bg_black { 
			                background: #000000; 
			            }  
			            .bg_dark { 
			                background: rgba(0, 0, 0, .8); 
			            } 
			            .email-section { 
			                padding: 2.5em; 
			            }  
			            .btn { 
			                padding: 10px 15px; 
			                display: inline-block; 
			            } 
			            .btn.btn-primary { 
			                border-radius: 5px; 
			                background: #17bebb; 
			                color: #ffffff; 
			            } 
			            .btn.btn-white { 
			                border-radius: 5px; 
			                background: #ffffff; 
			                color: #000000; 
			            } 
			            .btn.btn-white-outline { 
			                border-radius: 5px; 
			                background: transparent; 
			                border: 1px solid #fff; 
			                color: #fff; 
			            } 
			            .btn.btn-black-outline { 
			                border-radius: 0px; 
			                background: transparent; 
			                border: 2px solid #000; 
			                color: #000; 
			                font-weight: 700; 
			            } 
			            .btn-custom { 
			                color: rgba(0, 0, 0, .3); 
			                text-decoration: underline; 
			            } 
			            h1, h2, h3, h4, h5, h6 { 
			                color: #fff; 
			                margin-top: 0; 
			                font-weight: 600; 
			                font-size: 12px; 
			                font-family: \'Montserrat\' 
			            } 
			            body { 
			                font-weight: 400; 
			                font-size: 15px; 
			                line-height: 1.8; 
			                color: rgba(0, 0, 0, .4); 
			                font-family: \'Montserrat\' 
			            }  
			            a { 
			                color: #17bebb; 
			            } 
			            table {} 
			            .logo h1 { 
			                margin: 0; 
			            } 
			            .logo h1 a { 
			                color: #17bebb; 
			                font-size: 24px; 
			                font-weight: 700; 
			            } 
			            .hero { 
			                position: relative; 
			                z-index: 0; 
			            } 
			            .hero .text { 
			                color: rgba(0, 0, 0, .3); 
			            }  
			            .hero .text h2 { 
			                color: #000; 
			                font-size: 14px; 
			                margin-bottom: 15px; 
			                font-weight: 400; 
			                line-height: 1.2; 
			            } 
			            .hero .text h3 { 
			                font-size: 12px; 
	    					font-weight: 600; 
	    					color: #000; 
			            } 
			            .hero .text h2 span { 
			                font-weight: 600; 
			                color: #000; 
			            }  
			            .product-entry { 
			                display: block; 
			                position: relative; 
			                float: left; 
			                padding-top: 20px; 
			            } 
			            .product-entry .text { 
			                width: calc(100% - 125px);  
			                padding-left: 20px; 
			            } 
			            .product-entry .text h3 { 
			                margin-bottom: 0; 
			                padding-bottom: 0; 
			            }  
			            .product-entry .text p { 
			                margin-top: 0; 
			            } 
			            .product-entry img, .product-entry .text { 
			                float: left; 
			            } 
			            ul.social { 
			                padding: 0; 
			            } 
			            ul.social li { 
			                display: inline-block; 
			                margin-right: 10px; 
			            }  
			            .footer { 
			                border-top: 1px solid rgba(0, 0, 0, .05); 
			                color: rgba(0, 0, 0, .5); 
			            } 
			            .footer .heading { 
			                color: #fff; 
			                font-size: 18px; 
			                font-weight: 600; 
			                font-family: \'Montserrat\'; 
			            } 
			            .footer p { 
			                color: #fff; 
			                font-size: 14px;  
			                font-family: \'Montserrat\'; 
			            } 
			            .footer ul { 
			                margin: 0; 
			                padding: 0; 
			            } 
			            .footer ul li { 
			                list-style: none; 
			                margin-bottom: 10px; 
			            } 
			            .footer ul li a { 
			                color: rgba(0, 0, 0, 1); 
			            } 
			            @media screen and (max-width: 500px) {} 
			        </style> 
			        <meta name="robots" content="noindex, follow">  
			        <style type="text/css"></style> 
			    </head>

			    <body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #f1f1f1;"> 
			        <center style="width: 100%; background-color: #f1f1f1;"> 
			            <div style="display: none; font-size: 1px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;"> ‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; </div> 
			            <div style="max-width: 600px; margin: 0 auto;" class="email-container"> 
			                <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;"> 
			                    <tbody> 
			                        <tr> 
			                            <td valign="top" class="bg_ehtl" style="padding: 1em 2.5em 1em 2.5em;"> 
			                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%"> 
			                                    <tbody> 
			                                        <tr> 
			                                            <td class="logo" style="text-align: right;text-transform:uppercase;font-size:18px;font-weight:600"> 
			                                                <h1> Seu pedido foi recebido com sucesso! '.$htmlAdicional.'</h1> 
			                                            </td> 
			                                        </tr> 
			                                    </tbody>

			                                </table>

			                            </td>

			                        </tr>

			                        <tr>

			                            <td valign="middle" class="hero bg_white" style="padding: 2em 0 2em 0;">

			                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">

			                                    <tbody>

			                                        <tr>

			                                            <td style="padding: 0 2.5em; text-align: left;">

			                                                <div class="text">

			                                                    <h2><img src="cid:icon-check-round" style="height:20px"> Agradecemos sua solicitação!<br>Sua '.$tipoReserva.' de locação de veículos foi recebida.</h2>';



			                                                    if($type_reserva == 2){



			                                                    	$html .= '<h2><img src="cid:icon-check">A <strong>'.$hotel_reserva.'</strong> estará à sua espera em <strong>'.$checkin_reserva.'</strong>.</h2>';



			                                                    }else{



			                                                    	$html .= '<h2><img src="cid:icon-check"> Solicitação feita para a <strong>'.$hotel_reserva.'</strong> e dia <strong>'.$checkin_reserva.'</strong>.</h2>';



			                                                    } 



			                                                    $html .= '<h2><img src="cid:icon-check"> Entraremos em contato para cuidar do pagamento.</h2>';



			                                                $html .= '</div>

			                                            </td>

			                                        </tr>

			                                    </tbody>

			                                </table>

			                            </td>

			                        </tr>

			                        <tr></tr>

			                    </tbody>

			                </table>



			                <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">

			                    <tbody> 

			                        <tr>

			                            <td valign="middle" class="hero bg_white" style="padding: 0;">

			                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">

			                                    <tbody>

			                                        <tr>

			                                            <td style="padding: 0 2.5em; text-align: left;">

			                                                <div class="text">

			                                                    <h2 style="color:'.$cor_cars.' !important;font-weight:600;font-size:18px !important;font-family: \'Montserrat\' !important;">'.$hotel_reserva.'</h2> 



			                                                	<h3>'.$endereco_hotel.'</h3> 



			                                                </div>

			                                            </td>

			                                        </tr>

			                                    </tbody>

			                                </table>

			                            </td>

			                        </tr>

			                        <tr></tr>

			                    </tbody>

			                </table>



			                <table class="bg_white" role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">

			                    <tbody>

			                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">

			                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Sua reserva</th>

			                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">'.$desc_dia_room_reserva.'</th>

			                        </tr> 

			                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">

			                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Sua reserva é para</th>

			                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">'.$desc_sua_reserva_para.'</th>

			                        </tr> 

			                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">

			                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Retirada</th>

			                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">'.$desc_sua_reserva_checkin.'</th>

			                        </tr> 

			                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">

			                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Devolução</th>

			                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">'.$desc_sua_reserva_checkout.'</th>

			                        </tr> 

			                    </tbody>

			                </table>



			                <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">

			                    <tbody>

			                        <tr>

			                            <td valign="middle" class="bg_ehtl footer email-section">

			                                <table>

			                                    <tbody>

			                                        <tr>

			                                            <td valign="top" width="100%" style="padding-top: 20px;">

			                                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">

			                                                    <tbody>

			                                                        <tr>

			                                                            <td style="text-align: left; padding-right: 10px;">

			                                                                <h3 class="heading">

				                                                                '.$desc_room_reserva.'

				                                                                <br>

				                                                                Taxa de R$ '.$desc_taxa_reserva.' inclusa

				                                                                <br>

				                                                                Total <span style="float:right">R$ '.$price_total.'</span>

			                                                                </h3> 

			                                                                <p> Aguarde entrarmos em contato para cuidarmos do pagamento. </p>



																			<p> Por favor, observe que pedidos adicionais (por exemplo, condutor adicional) não estão incluídos neste valor. </p>



																			<p> O preço total mostrado é o valor que você pagará à locadora. Não cobramos dos passageiros nenhuma taxa de reserva, administrativa ou de qualquer outro tipo.</p>



																			<p> Se você cancelar, impostos aplicáveis ainda podem ser cobrados pela locadora.</p>



																			<p> Se você não comparecer sem cancelar com antecedência, a locadora poderá cobrar o valor total da reserva. </p>

			                                                            </td>

			                                                        </tr>

			                                                    </tbody>

			                                                </table>

			                                            </td> 

			                                        </tr>

			                                    </tbody>

			                                </table>

			                            </td>

			                        </tr> 

			                    </tbody>

			                </table>



			                <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">

			                    <tbody> 

			                        <tr>

			                            <td valign="middle" class="hero bg_white" style="padding: 2em 0 0 0;">

			                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">

			                                    <tbody>

			                                        <tr>

			                                            <td style="padding: 0 2.5em; text-align: left;">

			                                                <div class="text">

			                                                    <h2 style="color:'.$cor_cars.' !important;font-weight:600;font-size:18px !important;font-family: \'Montserrat\' !important;">Informações sobre a reserva</h2>  



			                                                </div>

			                                            </td>

			                                        </tr>

			                                    </tbody>

			                                </table>

			                            </td>

			                        </tr>

			                        <tr></tr>

			                    </tbody>

			                </table>



			                <table class="bg_white" role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">

			                    <tbody>

			                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">

			                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Titular</th>

			                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">'.$desc_titular.'</th>

			                        </tr> 

			                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">

			                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Sobre o veículo	</th>

			                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">'.$desc_qtd_rooms.'</th>

			                        </tr>  

			                    </tbody>

			                </table>



			                <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">

			                    <tbody> 

			                        <tr>

			                            <td valign="middle" class="hero bg_white" style="padding: 2em 0 1em 0;">

			                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">

			                                    <tbody>

			                                        <tr>

			                                            <td style="padding: 0 2.5em; text-align: left;">

			                                                <div class="text">

			                                                    <h2 style="color:'.$cor_cars.' !important;font-weight:600;font-size:18px !important;font-family: \'Montserrat\' !important;">Pagamento</h2>  



			                                                </div>

			                                            </td>

			                                        </tr>

			                                    </tbody>

			                                </table>

			                            </td>

			                        </tr>

			                        <tr></tr>

			                    </tbody>

			                </table>';



			                if($type_reserva == 2){

				                $html .= '<table class="bg_white" role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">

				                    <tbody>

				                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">

				                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Nome do titular</th>

				                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">'.$desc_titular_card.'</th>

				                        </tr> 

				                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">

				                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Número do cartão</th>

				                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">'.$desc_number_card.'</th>

				                        </tr> 

				                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">

				                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Validade</th>

				                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">'.$desc_validade_card.'</th>

				                        </tr> 

				                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">

				                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Parcelas</th>

				                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">'.$desc_parcelas_card.'</th>

				                        </tr> 

				                    </tbody>

				                </table>';

				            }else{

				                $html .= '<table class="bg_white" role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">

				                    <tbody>

				                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);"> 

				                            <th width="100%" style="text-align:left;padding:0 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">Entraremos em contato para cuidar das informações de pagamento. Mas não se preocupe, sua solicitação foi recebida!</th>

				                        </tr>  

				                    </tbody>

				                </table>';

				            }



			                $html .= '<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">

			                    <tbody> 

			                        <tr>

			                            <td class="bg_white" style="text-align: center;">

			                                <p style="font-family: \'Montserrat\' !important;"><a href="https://traveltec.com.br" target="_blank">Travel Tec</a> © '.date("Y").'. Todos os direitos reservados.</a>

			                                </p>

			                            </td>

			                        </tr>

			                    </tbody>

			                </table>



			            </div>

			        </center> 

			    </body>

			</html>'; 

			/* Set mail parameters */

			$to = 'raabe@montenegroev.com.br'; 
			$subject = "Locação de Veículos - Nova cotação"; 
			$body = $html; 
			$headers = "Content-type: text/html"; 
			$my_attachments = [ 
			    [ 
			        "cid" => "icon-check-round",  
			        "path" => plugin_dir_path(__FILE__) . 'includes/assets/img/icon-check-round.png', 
			    ], 
			    [ 
			        "cid" => "icon-check", 
			        "path" => plugin_dir_path(__FILE__) . 'includes/assets/img/icon-check.png',  
			    ],  
			]; 

			$custom_mailer = new Mail_Service_Car(); 
			$custom_mailer->send($_POST['email_order'], 'Pedido efetuado com sucesso!', $body, $headers, $my_attachments); 

			//$custom_mailer->send(get_option( 'admin_email' ), $subject, $body, $headers, $my_attachments);  
		}
		/* FIM FUNÇÃO QUE ENVIA O E-MAIL DA COMPRA */

	/* FIM FUNÇÕES SITE */

	/* INSERE SCRIPTS GERAIS */
	add_action( 'wp_enqueue_scripts', 'enqueue_scripts_cars' ); 
	function enqueue_scripts_cars() {   
	    wp_enqueue_script( 'sweetalert-cars', 'https://unpkg.com/sweetalert/dist/sweetalert.min.js');  
	} 
	/* FIM INSERE SCRIPTS GERAIS */