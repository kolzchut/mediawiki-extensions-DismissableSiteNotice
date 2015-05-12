( function ( mw, $ ) {
	'use strict';

	var cookieName = 'dismissSiteNotice',
		siteNoticeId = mw.config.get( 'wgSiteNoticeId' );

	if ( !siteNoticeId ) {
		return;
	}

	if ( mw.cookie.get( cookieName ) === siteNoticeId ) {
		mw.util.addCSS( '.mw-dismissable-notice { display: none; }' );
		return;
	}

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
