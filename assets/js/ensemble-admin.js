/** global ensbl_vars */
jQuery(document).ready(function($) {

	// Multi-select selectWoo instances.
	$('select[multiple="multiple"]').each(function () {
		$(this).selectWoo({
			width: '100%',
			theme: 'bootstrap'
		});
	});

	// selectWoo instances for selects.
	$('select').each(function () {
		// Skip it for list table bulk actions selects.
		if ( ! $( this ).parent().hasClass( 'bulkactions' ) ) {
			$(this).selectWoo({
				width: '100%',
				theme: 'bootstrap'
			});
		}
	});

	// Datepickers.
	$('.date').each(function () {
		console.log($(this));
		$(this).datepicker({
			dateFormat: 'mm/dd/yy',
			minDate: 0
		});
	});

} );
