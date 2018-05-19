/** global ensbl_vars */
jQuery( document ).ready( function( $ ) {

	// Multi-select selectWoo instances.
	$( 'select[ multiple="multiple"]' ).each( function () {
		$( this ).selectWoo( {
			width: '100%',
			theme: 'bootstrap'
		} );
	} );

	// selectWoo instances for selects.
	$( 'select' ).each( function () {
		// Skip it for list table bulk actions selects.
		if ( ! $( this ).parent().hasClass( 'bulkactions' ) && ! $( this ).parent().hasClass( 'actions' ) ) {
			$( this ).selectWoo({
				width: '100%',
				theme: 'bootstrap'
			} );
		}
	} );

	// Datepickers.
	$( '.date' ).each( function () {
		var $args = {
			dateFormat: 'mm/dd/yy'
		};

		if ( ! $( this ).hasClass( 'allow-past-dates' ) ) {
			$args.minDate = 0;
		}

		$( this ).datepicker( $args );
	} );

	$( '.next' ).on( 'click', function( event ) {

		var nextId = $( this ).parents( '.tab-pane' ).next().attr( 'id' );

		$('[href=#' + nextId + ']' ).tab( 'show' );

		return false;
	} );

	$( 'a[data-toggle="tab"]' ).on( 'shown.bs.tab', function ( event ) {

		var step = $( event.target ).data( 'step' );
		var percent = ( parseInt( step ) / 5 ) * 100;

		$( '.progress-bar' ).css( {
			width: percent + '%'
		} );

		$( '.progress-bar' ).text( 'Step ' + step + ' of 5' );

	} );

	$( '.first' ).on( 'click', function( event ) {

		$( '#myWizard a:first' ).tab( 'show' );

	} );

} );
