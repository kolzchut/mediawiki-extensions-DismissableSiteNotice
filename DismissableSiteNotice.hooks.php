<?php

class DismissableSiteNoticeHooks {

	/**
	 * @param string $notice
	 * @param Skin $skin
	 * @return bool true
	 */
	public static function onSiteNoticeAfter( &$notice, $skin ) {
		global $wgMajorSiteNoticeID, $wgDismissableSiteNoticeForAnons;

		if ( !$notice ) {
			return true;
		}

		// Dismissal for anons is configurable
		if ( $wgDismissableSiteNoticeForAnons || $skin->getUser()->isLoggedIn() ) {
			// Cookie value consists of two parts
			$major = (int) $wgMajorSiteNoticeID;
			$minor = (int) $skin->msg( 'sitenotice_id' )->inContentLanguage()->text();

			$out = $skin->getOutput();
			$out->addModuleStyles( 'ext.dismissableSiteNotice.styles' );
			$out->addModules( 'ext.dismissableSiteNotice' );
			$out->addJsConfigVars( 'wgSiteNoticeId', "$major.$minor" );

			$html = Html::openElement( 'div', array( 'class' => 'mw-dismissable-notice' ) );
			$html .= DismissableSiteNoticeHooks::makeCloseLink( $skin );
			$html .= Html::rawElement( 'div', array( 'class' => 'mw-dismissable-notice-body' ), $notice );
			$html .= Html::closeElement( 'div' );

			$notice = $html;
		}

		if ( $skin->getUser()->isAnon() ) {
			// Hide the sitenotice from search engines (see bug T11209 comment 4)
			// XXX: Does this actually work?
			$notice = Html::inlineScript( Xml::encodeJsCall( 'document.write', array( $notice ) ) );
		}

		return true;
	}

	static function makeCloseLink( Skin $skin ) {
		global $wgDismissableSiteNoticeCloseIcon, $wgExtensionAssetsPath;

		$closeText = $skin->msg( 'sitenotice_close' )->text();

		if ( $wgDismissableSiteNoticeCloseIcon === true ) {
			$iconUrl = "{$wgExtensionAssetsPath}/DismissableSiteNotice/resources/closewindow.png";

			$linkContent = Html::element(
				'img',
				array( 'src' => $iconUrl, 'alt' => $closeText )
			);
		} else {
			$linkContent = $closeText;
		}


		$link = Html::rawElement( 'a', array( 'href' => '#', 'role' => 'button' ), $linkContent );

		$out = Html::openElement( 'div', array( 'class' => 'mw-dismissable-notice-close' ) );
		if ( $wgDismissableSiteNoticeCloseIcon !== true ) {
			$link = $skin->msg( 'sitenotice_close-brackets' )
				         ->rawParams(
							$link
				         )
				         ->escaped();
		}

		$out .= $link;
		$out .= Html::closeElement( 'div' );

		return $out;
	}
}
