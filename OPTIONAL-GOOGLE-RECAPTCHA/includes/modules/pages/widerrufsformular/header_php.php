<?php
/**
* Widerrufsformular für Zen Cart 1.5.7 deutsch
* @copyright Copyright 2026 webchills (www.webchills.at)
* @copyright Copyright 2003-2026 Zen Cart Development Team
* Zen Cart German Version - www.zen-cart-pro.at
* @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
* @version $Id: header_php.php for Google Recaptcha V3 2026-03-09 09:39:40Z webchills $
*/
 // set notifier for reCaptcha V3
 
 $zco_notifier->notify('NOTIFY_HEADER_START_WIDERRUFSFORMULAR');
require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));
// include template specific file name defines
$define_page = zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_WIDERRUFSFORMULAR, 'false');
require(DIR_WS_CLASSES . 'widerrufsformular.php');
$cf = new widerrufsformular();
$breadcrumb->add( $cf->GetNavbarTitle() );
define('META_TAG_TITLE', $cf->GetPageTitle() ); 