$j = jQuery.noConflict();

var url         = WPURLS.siteurl;
var url_ajax    = WPURLS.urlajax;
var url_theme   = WPURLS.urltheme;

jQuery(document).ready(function() {
    if ($j(document).find('.date-field').length) set_datepicker();
});

function set_datepicker() {
	$j('.date-field').datepicker({
        dateFormat:         'dd/mm/yy',
        dayNames:           ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
        dayNamesMin:        ['D','S','T','Q','Q','S','S','D'],
        dayNamesShort:      ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
        monthNames:         ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort:    ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
        nextText:           'Próximo',
        prevText:           'Anterior'
    });
}

// Shortcodes
function get_media( target ) {
    window.mb = window.mb || {};

    window.mb.frame = wp.media({
        title: 'Escolher arquivo',
        button: {
            text: 'Escolher'
        },
        multiple: true
    });

    window.mb.frame.on( 'open', function() {
        var input_value = $j( '#' + target ).val();
        var selection = window.mb.frame.state().get( 'selection' );
        var ids = input_value.split( ',' );
        var count = ids.length;
        if ( count > 0 ) {
            for ( var i=0; i<count; i++ ) {
                attachment = wp.media.attachment( ids[ i ] );
                attachment.fetch();
                selection.add( attachment ? [attachment] : [] );
            }
        }
    });

    window.mb.frame.on( 'select', function() {
        attachment = window.mb.frame.state().get('selection').toJSON();

        var post_id = new Array();
        for ( i in attachment ) {
            post_id.push( attachment[ i ].id );
        }

        $j( '#' + target ).val( post_id );
    });

    window.mb.frame.open();
}
