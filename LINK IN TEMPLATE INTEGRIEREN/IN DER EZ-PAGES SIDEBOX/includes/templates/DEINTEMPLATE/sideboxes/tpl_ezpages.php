<?php
/**
 * Side Box Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2026 Zen Cart Development Team
 * Zen Cart German Version - www.zen-cart-pro.at
 * @copyright Portions Copyright 2003 osCommerce
 * @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
 * @version $Id: tpl_ezpages.php for Widerrufsbutton 2026-05-10 11:33:58Z webchills $
 */
  $content = "";
  $content .= '<div id="' . str_replace('_', '-', $box_id . 'Content') . '" class="sideBoxContent">';
  $content  .= "\n" . '<ul class="list-links">' . "\n";
  for ($i=1, $n=sizeof($var_linksList); $i<=$n; $i++) { 
    $content .= '<li><a href="' . $var_linksList[$i]['link'] . '">' . $var_linksList[$i]['name'] . '</a></li>' . "\n" ;
  } // end FOR loop
  if (defined('WIDERRUFSFORMULAR_SHOW_LINK') && WIDERRUFSFORMULAR_SHOW_LINK == 'true') {
  if ($_SESSION['language']=='german') {
  $content .= '<li><a class="widerrufsbutton" href="index.php?main_page=widerrufsformular&form_id=1">Vertrag widerrufen</a></li>';
} else { 
	$content .= '<li><a class="widerrufsbutton" href="index.php?main_page=widerrufsformular&form_id=2">Cancel contract</a></li>';
 } 
 } 
  $content  .= '</ul>' . "\n";
  $content .= '</div>';