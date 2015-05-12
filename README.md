DismissableSiteNotice extension for MediaWiki
=============================================

The DismissableSiteNotice extension allows users to close the [sitenotice][mw-sitenotice], using
Javascript and cookies.

[mw-sitenotice]: https://www.mediawiki.org/wiki/Manual:Interface/Sitenotice

## Configuration

- `$wgDismissableSiteNoticeForAnons`: false by default. Set to true to allow unregistered users to
  dismiss the sitenotice.

- `$wgDismissableSiteNoticeCloseIcon`: false by default. Set to true to replace the textual close link
  with an icon.

- When you add a new sitenotice and want everyone to see it, you can change one of the following
  (the code takes them both into account):
    - *[[MediaWiki:Sitenotice id]]* - set to a higher number than the current.
    - `$wgSiteNoticeId`: set to a higher number than last.

- To change the text of the "Hide" link, you can edit *[[MediaWiki:Sitenotice_close]]*. If you want
  to remove or change the enclosing parantheses, edit *[[MediaWiki:sitenotice_close-brackets]]*.
