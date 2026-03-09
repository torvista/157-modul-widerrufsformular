<?php
/**
 * Page Template
 *
 * Displays EZ-Pages footer-bar content.
 *
 * @package templateSystem
 * @copyright Copyright 2003-2026 Zen Cart Development Team
 * Zen Cart German Version - www.zen-cart-pro.at
 * @copyright Portions Copyright 2003 osCommerce
 * @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
 * @version $Id: tpl_ezpages_bar_footer.php for Widerrufsbutton 2026-03-09 17:24:58Z webchills $
 */
/**
 * require code to show EZ-Pages list
 */
  include(DIR_WS_MODULES . zen_get_module_directory('ezpages_bar_footer.php'));
?>
<?php if (!empty($var_linksList)) { ?>
<?php for ($i=1, $n=sizeof($var_linksList); $i<=$n; $i++) {  ?>
  <li><a href="<?php echo $var_linksList[$i]['link']; ?>"><?php echo $var_linksList[$i]['name']; ?></a></li>  
<?php } ?>
<?php if ($_SESSION['language']=='german') {?>
  <li><a class="widerrufsbutton" href="index.php?main_page=widerrufsformular&form_id=1">Vertrag widerrufen</a></li>
<?php  } else { ?>
	<li><a class="widerrufsbutton" href="index.php?main_page=widerrufsformular&form_id=2">Cancel contract</a></li>
<?php } ?> 
<?php } ?>