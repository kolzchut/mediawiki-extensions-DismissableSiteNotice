( function ( mw, $ ) {
	'use strict';

	var cookieName = 'dismissSiteNotice',
		siteNoticeId = mw.config.get( 'wgSiteNoticeId' );

	// If no siteNoticeId is set, exit.
	if ( !siteNoticeId ) {
		return;
	}

	// If the user has the notice dismissal cookie set, exit.
	if ( mw.cookie.get( cookieName ) === siteNoticeId ) {
		return;
	}

	// Otherwise, show the notice ...
	mw.util.addCSS( '.client-js .mw-dismissable-notice { display: block; }' );

	// ... and enable the dismiss button.
	$( function () {
		$( '.mw-dismissable-notice-close' )
			.css( 'visibility', 'visible' )
			.find( 'a' )
				.click( function ( e ) {
					e.preventDefault();
					$( this ).closest( '.mw-dismissable-notice' ).hide();
					mw.cookie.set( cookieName, siteNoticeId );
				} );
	} );

}( mediaWiki, jQuery ) );
