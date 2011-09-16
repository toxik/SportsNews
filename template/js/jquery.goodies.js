$(document).ready(function() {
	
	$('.dateControl').datepicker();
	/*
	$('button,input[type!=text][type!=password][type!=checkbox],a[rel=button]').button()
	*/
    /*$('#butoane-paginatie input').button();*/
    $('#butoane-paginatie .grup').buttonset()
	$('button.add').button({icons: { primary: 'ui-icon-plusthick' } })
	$('button.search').button({icons: { primary: 'ui-icon-search' } })
	$('button.reset').button({icons: { primary: 'ui-icon-cancel' } })
	$('button.delete').button({icons: { primary: 'ui-icon-cancel' } })
	$('button.confirm').button({icons: { primary: 'ui-icon-check' } })
	$('button.back').button({icons: { primary: 'ui-icon-arrowreturnthick-1-w' } })
	$('button.edit').button({icons: { primary: 'ui-icon-pencil' } })
	$('button.script').button({icons: { primary: 'ui-icon-script' } })
	$('button.stats').button({icons: { primary: 'ui-icon-signal' } })
	$('button.hire').button({icons: { primary: 'ui-icon-folder-open' } })
	$('button.list').button({icons: { primary: 'ui-icon-note' } })
    $('input[type=radio],input[type=submit],input[type=checkbox],button.link').button();
    $('button.link').click(function(e) {
        e.preventDefault(); var ok = true;
        if ( $(this).hasClass('delete') ) {
            ok = confirm('Sunteți sigur(ă) că doriți să ștergeți această înregistrare?');
        }
        if (ok)
            window.location = $(this).attr('href')
        return false;
    });
    
    //$('input[type=submit]').css( { 'float' : 'right', 'display' : 'block' } );
	
	$('input[type=reset],button[type=reset]').click( function() {
		$(':input','.filtru')
			 .not(':button, :submit, :reset, :hidden')
			 .val('')
			 .removeAttr('checked')
			 .removeAttr('selected');
		$('.filtru').submit()
		return false;
	})
    
    $('.slider').each(function(i) {
        var $this = $(this),
            minim = parseInt($this.attr('min'))     || 1,
            maxim = parseInt($this.attr('max'))     || 10,
            pasul = parseInt($this.attr('step'))    || 1,
            val   = parseInt($this.attr('val')) || parseInt((Math.random() * ( maxim - minim ))) + minim;
        $(this).prev().append(
            $('<br /><input style="float: right;width: 20px; border:none" size="3" readonly id="amount_slider'+i+'" value="'+val+'"/>')
        ).next().slider({
            range: 'min',
            animate: true,
            value: val,
            min: minim,
            max: maxim,
            step: pasul,
            slide: function( event, ui ) {
				$( '#amount_slider'+i ).val( ui.value );
			}
        });
    });
    
    $('[placeholder]').focus(function() {
      var input = $(this);
      if (input.val() == input.attr('placeholder')) {
        input.val('');
        input.removeClass('placeholder');
      }
    }).blur(function() {
      var input = $(this);
      if (input.val() == '' || input.val() == input.attr('placeholder')) {
        input.addClass('placeholder');
        input.val(input.attr('placeholder'));
      }
    }).blur();
    
    $.datepicker.regional['ro'] = {
		closeText: 'Închide',
		prevText: '&laquo; Luna precedentă',
		nextText: 'Luna următoare &raquo;',
		currentText: 'Azi',
		monthNames: ['Ianuarie','Februarie','Martie','Aprilie','Mai','Iunie',
		'Iulie','August','Septembrie','Octombrie','Noiembrie','Decembrie'],
		monthNamesShort: ['Ian', 'Feb', 'Mar', 'Apr', 'Mai', 'Iun',
		'Iul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
		dayNames: ['Duminică', 'Luni', 'Marţi', 'Miercuri', 'Joi', 'Vineri', 'Sâmbătă'],
		dayNamesShort: ['Dum', 'Lun', 'Mar', 'Mie', 'Joi', 'Vin', 'Sâm'],
		dayNamesMin: ['Du','Lu','Ma','Mi','Jo','Vi','Sâ'],
		dateFormat: 'dd.mm.yy', firstDay: 1,
		isRTL: false,
        maxDate: 0
    };
	$.datepicker.setDefaults($.datepicker.regional['ro']);
})