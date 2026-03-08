<?php
/**
* Widerrufsformular für Zen Cart 1.5.7 deutsch
* @copyright Copyright 2026 webchills (www.webchills.at)
* @copyright Copyright 2003-2026 Zen Cart Development Team
* Zen Cart German Version - www.zen-cart-pro.at
* @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
* @version $Id: 1.0.0.php 2026-03-08 10:47:40Z webchills $
*/
 
$db->Execute("CREATE TABLE IF NOT EXISTS " . TABLE_WIDERRUFSFORMULAR . " (
 `form_id` int(11) NOT NULL AUTO_INCREMENT,
 `form_title` varchar(128) DEFAULT NULL,
 `page_title` varchar(128) DEFAULT NULL,
 `page_heading` varchar(128) DEFAULT NULL,
 `navbar_title` varchar(64) DEFAULT NULL,
 `form_description` text,
 `created_by` int(11) DEFAULT NULL,
 `timestamp` datetime DEFAULT NULL,
 PRIMARY KEY (`form_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");

$db->Execute(" INSERT INTO " . TABLE_WIDERRUFSFORMULAR . " (`form_id`, `form_title`, `page_title`, `page_heading`, `navbar_title`, `form_description`, `created_by`, `timestamp`) VALUES
(1, 'Teilwiderruf oder Kommentar', 'Widerruf', 'Widerruf', 'Widerruf', '<p>Sie haben das Recht, binnen vierzehn Tagen ohne Angabe von Gründen diesen Vertrag zu widerrufen.<br>\r\nDie Widerrufsfrist beträgt vierzehn Tage ab dem Tag, an dem Sie oder ein von Ihnen benannter Dritter, der nicht der Beförderer ist, die letzte Ware in Besitz genommen haben bzw. hat.<br>\r\nUm Ihr Widerrufsrecht auszuüben, können Sie dieses Formular ausfüllen und absenden. Sie erhalten danach sofort per Email eine Bestätigung über den Eingang Ihres Widerrufs.<br>\r\n<br>\r\nWenn Sie nur bestimmte Artikel aus der Bestellung widerrufen wollen (Teilwiderruf), dann tragen Sie unten die Namen der Artikel ein.<br>\r\nFür einen vollständigen Widerruf können Sie das Feld einfach leer lassen.</p>', 1, '2026-03-07 16:20:24'),
(2, 'Partial cancellation or comment', 'Cancellation', 'Cancellation', 'Cancellation', '<p>You have the right to withdraw from this contract within fourteen days without giving any reason.<br>\r\nThe withdrawal period is fourteen days from the day on which you or a third party named by you, who is not the carrier, took possession of the last goods. <br>\r\nTo exercise your right of withdrawal, you can fill out and submit this form. You will then immediately receive confirmation of receipt of your withdrawal by email.<br>\r\n<br>\r\nIf you only want to withdraw certain items from the order (partial withdrawal), please enter the names of the items below. <br>\r\nFor a complete withdrawal, simply leave the field blank.</p>', 2, '2026-03-07 16:20:24')");


$db->Execute("CREATE TABLE IF NOT EXISTS " . TABLE_WIDERRUFSFORMULAR_FIELDS . " (
 `form_field_id` int(11) NOT NULL AUTO_INCREMENT,
 `form_id` int(11) NOT NULL DEFAULT '0',
 `field_type` enum('Text','Text Area') NOT NULL DEFAULT 'Text',
 `field_name` varchar(64) NOT NULL,
 `label` varchar(64) NOT NULL,
 `description` varchar(1024) DEFAULT NULL,
 `required` tinyint(1) NOT NULL DEFAULT '0',
 `sort_order` int(3) NOT NULL DEFAULT '0',
 `modified_by` int(11) DEFAULT NULL,
 `timestamp` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`form_field_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");

$db->Execute(" INSERT INTO " . TABLE_WIDERRUFSFORMULAR_FIELDS . " (`form_field_id`, `form_id`, `field_type`, `field_name`, `label`, `description`, `required`, `sort_order`, `modified_by`, `timestamp`) VALUES
(1, 1, 'Text Area', 'txaBestellteArtikelOderKommentar', 'Bestellte Artikel oder Kommentar', 'Falls Sie nur bestimmte Artikel aus dieser Bestellung widerrufen wollen (Teilwiderruf), tragen Sie hier bitte die Namen der Artikel ein.', 0, 2, 1, '2026-03-08 08:09:19'),
(2, 2, 'Text Area', 'txaOrderedProductsOrComment', 'Ordered products or comment', 'If you only wish to cancel certain items from this order (partial cancellation), please enter the names of the items here.', 0, 2, 1, '2026-03-08 08:09:19')");

$db->Execute("CREATE TABLE IF NOT EXISTS " . TABLE_WIDERRUFSFORMULAR_FIELDS_OPTIONS . " (
 `form_field_option_id` int(11) NOT NULL AUTO_INCREMENT,
 `form_field_id` int(11) NOT NULL,
 `field_text` varchar(256) NOT NULL,
 `field_value` varchar(1024) DEFAULT NULL,
 `selected` tinyint(1) NOT NULL DEFAULT '0',
 `read_only` tinyint(1) NOT NULL DEFAULT '0',
 `sort_order` int(3) NOT NULL DEFAULT '0',
 PRIMARY KEY (`form_field_option_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;");

$db->Execute("CREATE TABLE IF NOT EXISTS " . TABLE_WIDERRUFSFORMULAR_HITS . " (
 `form_hit_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `form_id` int(11) NOT NULL, 
 `referer` varchar(256) DEFAULT NULL,
 `timestamp` datetime DEFAULT NULL,
 PRIMARY KEY (`form_hit_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");

$db->Execute("CREATE TABLE IF NOT EXISTS " . TABLE_WIDERRUFSFORMULAR_REQUESTS . " (
 `request_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `form_id` int(11) DEFAULT NULL,
 `customer_name` varchar(64) DEFAULT NULL,
 `customer_email` varchar(128) DEFAULT NULL, 
 `order_id` int(11) DEFAULT NULL,
 `message` text NOT NULL,
 `status` varchar(32) DEFAULT NULL,
 `message_timestamp` datetime DEFAULT NULL,
 PRIMARY KEY (`request_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");
 
$db->Execute(" SELECT @gid:=configuration_group_id
FROM ".TABLE_CONFIGURATION_GROUP."
WHERE configuration_group_title= 'Widerrufsformular'
LIMIT 1;");


$db->Execute("INSERT IGNORE INTO ".TABLE_CONFIGURATION." (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, last_modified, use_function, set_function) VALUES
('Email address', 'WIDERRUFSFORMULAR_RECIPIENT_EMAIL', '', 'E-mail address of customer service or administrative staff assigned to respond to customer inquiries sent over using cancellation form.', @gid, 2, now(), now(), '', ''),
('Number of Rows', 'WIDERRUFSFORMULAR_NUMBER_ROWS', '25', 'Number of rows to display on the responses dashboard and built forms in Admin.', @gid, 3, now(), now(), '', ''),
('Widget Button Text', 'WIDERRUFSFORMULAR_WIDGET_BUTTON_TEXT', 'Anpassen', 'Text that will display on the button tag created by the widget option in the form builder interface.', @gid, 4, now(), now(), '', ''),
('Text Char Limit', 'WIDERRUFSFORMULAR_TEXT_MAX_CHAR', '32', 'Maximum number of characters allowed on text fields.', @gid, 5, now(), now(), '', '')");

$db->Execute("REPLACE INTO ".TABLE_CONFIGURATION_LANGUAGE." (configuration_title, configuration_key, configuration_description, configuration_language_id) VALUES
('Email Adresse', 'WIDERRUFSFORMULAR_RECIPIENT_EMAIL', 'An welche E-Mail Adresse sollen die Widerrufsformulare gesendet werden?', 43),
('Zeilenanzahl', 'WIDERRUFSFORMULAR_NUMBER_ROWS', 'Anzahl der Zeilen, die im Widerrufsformular-Dashboard im Admin angezeigt werden sollen.', 43),
('Button Text im Widget', 'WIDERRUFSFORMULAR_WIDGET_BUTTON_TEXT','Text, der auf dem Button-Tag angezeigt wird, der von der Widget-Option in der Form Builder-Oberfläche erstellt wurde.', 43),
('Zeichenlimit für Textfelder', 'WIDERRUFSFORMULAR_TEXT_MAX_CHAR','Maximal zulässige Anzahl von Zeichen in Textfeldern', 43)");


// delete old configuration/tools menu
$admin_page = 'configWiderrufsformular';
$db->Execute("DELETE FROM " . TABLE_ADMIN_PAGES . " WHERE page_key = '" . $admin_page . "' LIMIT 1;");
$admin_page_tools = 'WiderrufsformularDashboard';
$db->Execute("DELETE FROM " . TABLE_ADMIN_PAGES . " WHERE page_key = '" . $admin_page_tools . "' LIMIT 1;");
$admin_page_builder = 'WiderrufsformularBuilder';
$db->Execute("DELETE FROM " . TABLE_ADMIN_PAGES . " WHERE page_key = '" . $admin_page_builder . "' LIMIT 1;");
// add configuration/tools menu
if (!zen_page_key_exists($admin_page)) {
$db->Execute(" SELECT @gid:=configuration_group_id
FROM ".TABLE_CONFIGURATION_GROUP."
WHERE configuration_group_title= 'Widerrufsformular'
LIMIT 1;");
$db->Execute("INSERT IGNORE INTO " . TABLE_ADMIN_PAGES . " (page_key,language_key,main_page,page_params,menu_key,display_on_menu,sort_order) VALUES 
('configWiderrufsformular','BOX_WIDERRUFSFORMULAR','FILENAME_CONFIGURATION',CONCAT('gID=',@gid),'configuration','Y',@gid)");
$db->Execute("INSERT IGNORE INTO " . TABLE_ADMIN_PAGES . " (page_key,language_key,main_page,page_params,menu_key,display_on_menu,sort_order) VALUES 
('WiderrufsformularDashboard','BOX_WIDERRUFSFORMULAR_DASHBOARD','FILENAME_WIDERRUFSFORMULAR_DASHBOARD','','customers','Y',101)");
$db->Execute("INSERT IGNORE INTO " . TABLE_ADMIN_PAGES . " (page_key,language_key,main_page,page_params,menu_key,display_on_menu,sort_order) VALUES 
('WiderrufsformularBuilder','BOX_WIDERRUFSFORMULAR_BUILDER','FILENAME_WIDERRUFSFORMULAR_BUILDER','','catalog','Y',101)");
$messageStack->add('Widerrufsformular erfolgreich installiert.', 'success');  
}