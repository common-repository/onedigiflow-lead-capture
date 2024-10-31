jQuery( document ).ready( function($) {

	var iti = '';

	// Phone field add country field
	$( '.odflc-field-phone input' ).each( function() {
		var input = $( this );
		iti = window.intlTelInput(this, {
			/*initialCountry: "auto",
			geoIpLookup: function(success, failure) {
				$.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
					var countryCode = (resp && resp.country) ? resp.country : "";
					success(countryCode);
				});
			},*/
		});
	} );

	// Ajax to store form
	$( document ).on( 'click', '.odflc-lead-capture-submit', function(e) {
		e.preventDefault();

		var thisObj = $( this );
		var formObj = thisObj.parents( '.odflc-lead-capture-form' );

		// remove error class
		formObj.find( '.odflc-has-error' ).removeClass( 'odflc-has-error' );
		formObj.find( '.odflc-form-msgs' ).removeClass( 'error' ).removeClass( 'success' ).html( '' );

		var errFlag = false;
		// Check if not blank value
		formObj.find( '.odflc-required' ).each( function() {
			if( $(this).val() == '' ) {
				$(this).addClass( 'odflc-has-error' );
				errFlag = true;
			}
		} );

		// Check email valid
		formObj.find( '.odflc-email' ).each( function() {
			if( $(this).val() != '' && ! odflc_is_email_valid($(this).val()) ) {
				$(this).addClass( 'odflc-has-error' );
				errFlag = true;
			}
		} );

		// Get country code
		var countryData = iti.getSelectedCountryData();
		var phoneCode = '';
		if( countryData.dialCode ) phoneCode = countryData.dialCode;

		if( ! phoneCode || phoneCode == '' ) {
			$('.odflc-field-phone input').addClass( 'odflc-has-error' );
			errFlag = true;
		}

		// get product title
		var productTitle = formObj.find( '.odflc-field-skus select' ).children( 'option:selected' ).attr( 'data-title' );

		// check if no error
		if( errFlag ) return false;

		var formData = formObj.serializeArray();

		// add country code data
		formData.push({name: 'phone_code', value: phoneCode});
		formData.push({name: 'product_title', value: productTitle});

		// add action to data variable
		formData.push({name: 'action', value: 'odflc_submit_lead_capture_form'});

		// disable button
		thisObj.prop( 'disabled', true );
		thisObj.next( '.odflc-ajax-loader-img' ).show();

		$.post( ODFLC.ajaxurl, formData, function(response) {

			var res = $.parseJSON( response );
			if( res.status == '1' ) {
				formObj[0].reset();
				formObj.find('.odflc-form-msgs').addClass( 'success' ).html( res.msg );
			} else {
				formObj.find('.odflc-form-msgs').addClass( 'error' ).html( res.msg );
			}

			// enable things button
			thisObj.prop( 'disabled', false );
			thisObj.next( '.odflc-ajax-loader-img' ).hide();
		} );

		return false;
	} );
} );

function odflc_is_email_valid(email) {
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email);
}