<?php
/**
 * DismissableSiteNotice extension - allows users to dismiss (hide)
 * the sitenotice.
 *
 * @file
 * @ingroup Extensions
 * @version 1.1.2
 * @author Brion Vibber
 * @author Kevin Israel
 * @author Dror S. [FFS]
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @link http://www.mediawiki.org/wiki/Extension:DismissableSiteNotice Documentation
 */

// Not a valid entry point, skip unless MEDIAWIKI is defined
if ( !defined( 'MEDIAWIKI' ) ) {
	echo "This is a MediaWiki extension";
	exit( 1 );
}

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'DismissableSiteNotice',
	'author' => array(
		'Brion Vibber',
		'Kevin Israel',
		'Dror S. [FFS]',
	),
	'version' => '1.1.2',
	'descriptionmsg' => 'sitenotice-desc',
	'url' => 'https://www.mediawiki.org/wiki/Extension:DismissableSiteNotice',
);

$wgMessagesDirs['DismissableSiteNotice'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['DismissableSiteNotice'] = __DIR__ . '/DismissableSiteNotice.i18n.php';

$wgResourceModules['ext.dismissableSiteNotice'] = array(
	'localBasePath' => __DIR__ . '/modules',
	'remoteExtPath' => 'DismissableSiteNotice/modules',
	'scripts' => 'ext.dismissableSiteNotice.js',
	'styles' => 'ext.dismissableSiteNotice.css',
	'dependencies' => array(
		'mediawiki.cookie',
		'mediawiki.util',
	),
	'targets' => array( 'desktop', 'mobile' ),
	'position' => 'top',
);

/**
 * @param string $notice
 * @param Skin $skin
 * @return bool true
 */
$wgHooks['SiteNoticeAfter'][] = function( &$notice, $skin ) {
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
		$out->addModules( 'ext.dismissableSiteNotice' );
		$out->addJsConfigVars( 'wgSiteNoticeId', "$major.$minor" );

		$html = Html::openElement( 'div', array( 'class' => 'mw-dismissable-notice' ) );
		$html .= makeCloseLink( $skin );
		$html .= Html::rawElement( 'div', array( 'class' => 'mw-dismissable-notice-body' ), $notice );
		$html .= Html::closeElement( 'div' );

		$notice = $html;
	}

	if ( $skin->getUser()->isAnon() ) {
		// Hide the sitenotice from search engines (see bug 9209 comment 4)
		// XXX: Does this actually work?
		$notice = Html::inlineScript( Xml::encodeJsCall( 'document.write', array( $notice ) ) );
	}

	return true;
};

function makeCloseLink( Skin $skin ) {
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

// Default settings
$wgMajorSiteNoticeID = 1;
$wgDismissableSiteNoticeForAnons = false;
$wgDismissableSiteNoticeCloseIcon = false;
