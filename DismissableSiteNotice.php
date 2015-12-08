<?php
/**
 * DismissableSiteNotice extension - allows users to dismiss (hide)
 * the sitenotice.
 *
 * @file
 * @ingroup Extensions
 * @version 1.1.3
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
		'Dror S. [FFS] ([http://www.kolzchut.org.il Kol-Zchut])'
	),
	'version' => '1.1.3',
	'descriptionmsg' => 'sitenotice-desc',
	'url' => 'https://www.mediawiki.org/wiki/Extension:DismissableSiteNotice',
);

$wgMessagesDirs['DismissableSiteNotice'] = __DIR__ . '/i18n';
$wgAutoloadClasses['DismissableSiteNoticeHooks'] = __DIR__ . '/DismissableSiteNotice.hooks.php';

$wgHooks['SiteNoticeAfter'][] = 'DismissableSiteNoticeHooks::onSiteNoticeAfter';

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


// Default settings
$wgMajorSiteNoticeID = 1;
$wgDismissableSiteNoticeForAnons = false;
$wgDismissableSiteNoticeCloseIcon = false;
