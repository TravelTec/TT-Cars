jQuery(function() {
    moment.locale('pt-br');

    var url_atual = window.location.href;

    'use strict'; 

    if(url_atual.indexOf("/offers-cars/") != -1){ 

        if(localStorage.getItem("CHECKIN_CARS") !== ""){
            var checkin = localStorage.getItem("CHECKIN_CARS");
            var start = moment(checkin, 'DD-MM-YYYY').format('MM/DD/YYYY'); 
            var past = moment(checkin, 'DD-MM-YYYY').format('YYYY-MM-DD'); 
        }else{ 
            var start = moment().format('DD/MM/YYYY');
            var past = start;
        }
        
        jQuery('input[name="datePickup"]').val(localStorage.getItem("CHECKIN_CARS")); 

        if(localStorage.getItem("CHECKOUT_CARS") !== ""){
            var checkout = localStorage.getItem("CHECKOUT_CARS");
            var end = moment(checkout, 'DD-MM-YYYY').format('MM/DD/YYYY'); 
            var now = moment(checkout, 'DD-MM-YYYY').format('YYYY-MM-DD'); 
        }else{ 
            var end = moment().format('DD/MM/YYYY');
            var now = end;
        }
        
        jQuery('input[name="datePickout"]').val(localStorage.getItem("CHECKOUT_CARS"));  
      
        var endDate = moment(past, 'YYYY-MM-DD');
        var startDate = moment(now, 'YYYY-MM-DD'); 
        var days = startDate.diff(endDate, 'days');
    }

    jQuery('input[name="datePickup"]').daterangepicker({
        singleDatePicker: true,
        autoUpdateInput: false,
        startDate: start,
        endDate: end,
        minDate: moment(),
        format: 'DD/MM/YYYY', 
        autoApply: true,
        separator: ' - ',
        locale: {
            cancelLabel: 'Cancelar',
            applyLabel: 'Aplicar',
            fromLabel: 'De',
            toLabel: 'Até',
            customRangeLabel: 'Opção',
            daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            firstDay: 1
        }
    });  

    jQuery('input[name="datePickup"]').on('apply.daterangepicker', function(ev, picker) {
        const now = moment(picker.endDate.format('YYYY-MM-DD')); // Data de hoje
        const past = moment(picker.startDate.format('YYYY-MM-DD')); // Outra data no passado
        const duration = moment.duration(now.diff(past)); 

        jQuery('input[name="datePickup"]').val(picker.startDate.format('DD/MM/YYYY'));   
        localStorage.setItem("CHECKIN_CARS", picker.startDate.format('DD/MM/YYYY'));

        var datePickup = jQuery('input[name="datePickup"]').val();
        jQuery('input[name="datePickout"]').daterangepicker({
            singleDatePicker: true,
            autoUpdateInput: false,
            startDate: picker.startDate, 
            minDate: picker.startDate, 
            format: 'DD/MM/YYYY', 
            autoApply: true,
            separator: ' - ',
            locale: {
                cancelLabel: 'Cancelar',
                applyLabel: 'Aplicar',
                fromLabel: 'De',
                toLabel: 'Até',
                customRangeLabel: 'Opção',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                firstDay: 1
            }
        }); 
        jQuery('input[name="datePickout"]').on('apply.daterangepicker', function(ev, picker) {
            const now = moment(picker.endDate.format('YYYY-MM-DD')); // Data de hoje
            const past = moment(picker.startDate.format('YYYY-MM-DD')); // Outra data no passado
            const duration = moment.duration(now.diff(past)); 

            jQuery('input[name="datePickout"]').val(picker.startDate.format('DD/MM/YYYY'));  
            localStorage.setItem("CHECKOUT_CARS", picker.startDate.format('DD/MM/YYYY'));   
        });
    });
});