jQuery(document).ready(function($){

    function initdatepicker(){
        $('.datepicker').datepicker({
            dateFormat : 'dd.mm.yy',
            showWeek: true,
            changeMonth: true,
            changeYear: true,
            firstDay: 1,
            minDate: 0,
            prevText: '&#x3c;zurück', prevStatus: '',
            prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
            nextText: 'Vor&#x3e;', nextStatus: '',
            nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
            currentText: 'heute', currentStatus: '',
            todayText: 'heute', todayStatus: '',
            clearText: '-', clearStatus: '',
            closeText: 'schließen', closeStatus: '',
            monthNames: ['Januar','Februar','März','April','Mai','Juni',
                'Juli','August','September','Oktober','November','Dezember'],
            monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun',
                'Jul','Aug','Sep','Okt','Nov','Dez'],
            dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
            dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
            dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
            weekHeader: "W",
            yearSuffix: "",
            showMonthAfterYear: false
        });
    }

    var holiday_input_container = $('#manual_holiday_inputs');
    var holiday_input     = holiday_input_container.html();

    var special_day_container = $('#special_day_listing');
    var special_day_input   = special_day_container.find('li:first-child').html();

    initdatepicker();
    var manual_holidayCounter = 1;
    var wrap = $(".wrap.openclosed");

    wrap.on('click','#duplicate_manual_holiday', function(){
        holiday_input_container.append(holiday_input.replace(/\[0\]/g,'['+ manual_holidayCounter +']'));
        manual_holidayCounter++;
        initdatepicker();
    });

    var special_dateCounter = special_day_container.find('li').length;
    wrap.on('click','#duplicate_special_date', function(){
        special_day_container.append('<li>'+special_day_input.replace(/\[X\]/g, '['+special_dateCounter+']')+'</li>');
        special_dateCounter++;
        initdatepicker();
    });

    wrap.on('click','.remove_row', function(){
        $(this).parent().parent().remove();
    });
    wrap.on('click','.remove_parent', function(){
        $(this).parent().remove();
    });
    wrap.on('click','.switchtab', function(){
        //change active state on tab-buttons
        $(this).parent().find('.switchtab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');

        //change active state on tabs itself
        $(this).parent().parent().children('.tab').removeClass('active');
        $(this).parent().parent().children($(this).data('target')).addClass('active');
        return false;
    });

    var custom_uploader;
    var self;


    $('.upload_form').on('click', '.button',function(e) {
        self = $(this);

        e.preventDefault();

        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }

        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });

        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();

            self.parent().find('.file').val(attachment.url);
            self = '';
        });

        //Open the uploader dialog
        custom_uploader.open();
    });

    var caching = $('#caching');
    caching.on('click', '#cache_live', function(){
        $('.dashicons.live').toggleClass('dashicons-yes').toggleClass('dashicons-no') .toggleClass('unsaved');
    });
    caching.on('click', '#cache_longterm', function(){
        $('.dashicons.longterm').toggleClass('dashicons-yes').toggleClass('dashicons-no').toggleClass('unsaved');
    });

});