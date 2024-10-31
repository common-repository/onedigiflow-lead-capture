jQuery( document ).ready( function($) {

	// Create contact by ajax
	$( document ).on( 'click', '.odflc-create-contact', function() {

		var thisObj = $( this );
		var wrapObj = thisObj.parents( '.odflc-contact-meta-wrap' );
		var postid = thisObj.attr( 'data-postid' );

		var data = {
			action: 'odflc_create_contact',
			postid: postid,
		};

		// start loading
		thisObj.prop( 'disabled', true );
		wrapObj.find( '.odflc-ajax-loader-img' ).show();

		// ajax request
		$.post( ODFLC.ajaxurl, data, function(response) {

			var res = $.parseJSON( response );
			if( res.status == '1' ) {
				wrapObj.html( res.html );
			} else{
				alert( res.msg );
			}

			// start loading
			thisObj.prop( 'disabled', false );
			wrapObj.find( '.odflc-ajax-loader-img' ).hide();
		} );

		return false;
	} );

	// Create deal by ajax
	$( document ).on( 'click', '.odflc-create-deal', function() {

		var thisObj = $( this );
		var wrapObj = thisObj.parents( '.odflc-deal-meta-wrap' );
		var postid = thisObj.attr( 'data-postid' );

		var data = {
			action: 'odflc_create_deal',
			postid: postid,
		};

		// start loading
		thisObj.prop( 'disabled', true );
		wrapObj.find( '.odflc-ajax-loader-img' ).show();

		// ajax request
		$.post( ODFLC.ajaxurl, data, function(response) {

			var res = $.parseJSON( response );
			if( res.status == '1' ) {
				wrapObj.html( res.html );
			} else{
				alert( res.msg );
			}

			// start loading
			thisObj.prop( 'disabled', false );
			wrapObj.find( '.odflc-ajax-loader-img' ).hide();
		} );

		return false;
	} );
} ); // Document ready function