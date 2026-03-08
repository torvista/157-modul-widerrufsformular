<?php
/**
* Widerrufsformular für Zen Cart 1.5.7 deutsch
* @copyright Copyright 2026 webchills (www.webchills.at)
* @copyright Copyright 2003-2026 Zen Cart Development Team
* Zen Cart German Version - www.zen-cart-pro.at
* @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
* @version $Id: widerrufsformular.php 2026-03-08 15:39:40Z webchills $
*/

define('TEXT_SUCCESS', 'Ihr Widerruf wurde erfolgreich versandt.');

define('BTN_WIDERRUFSFORMULAR_CONTINUE','Weiter');
define('BTN_WIDERRUFSFORMULAR_SEND','Widerruf bestätigen');
define('BTN_BACK','Zurück');

define('EMAIL_CONTACT_TITLE', 'Widerruf');
define('EMAIL_PRODUCT_DESCRIPTION_TITLE', 'Bestellte Artikel oder Kommentar');

define('HEAD_WIDERRUF_INFORMATION', 'Widerruf der Bestellung');
define('LABEL_ORDER_NUMBER', 'Bestellnummer');
define('LABEL_TIMESTAMP', 'Datum und Uhrzeit des Widerrufs:');
define('LABEL_CUSTOMER_NAME', 'Name');
define('LABEL_CUSTOMER_EMAIL', 'E-Mail Adresse');
define('REQUIRED_FLAG', '<span style="color:red;">*</span>');

define('HEAD_CONFIRMATION', 'Bestätigung: Bitte Daten nochmal prüfen und dann absenden mit Click auf Widerruf bestätigen.');
define('HEAD_SUCCESS', 'Widerruf gesendet!<br>Eine Empfangsbestätigung erhalten Sie in Kürze per Email.');

define('DEFAULT_REQUEST_STATUS', 'erhalten');

define('EMAIL_SUBJECT_ADMIN', 'Widerruf einer Bestellung bei ' . STORE_NAME . ' - Widerruf ID: %s');
define('EMAIL_SUBJECT_CUSTOMER', 'Empfangsbestätigung Ihres Widerrufs bei ' . STORE_NAME);
define('EMAIL_FOOTER_CUSTOMER', 'Wir werden Ihren Widerruf nun prüfen und weiterbearbeiten. Danach melden wir uns mit weiteren Informationen wieder.');

define('JSON_LINE_BREAK_PLACEHOLDER', '|||');

define('MESSAGE_REQUIRED_FIELD_MISSING', 'Pflichtfeld nicht ausgefüllt: %s');
define('MESSAGE_FORM_SUBMITION_SUCCESS', 'Ihr Widerruf wurde erfolgreich versandt, eine Empfangsbestätigung erhalten Sie in Kürze per Email.  Wir werden Ihren Widerruf nun prüfen und weiterbearbeiten. Danach melden wir uns mit weiteren Informationen wieder.');
define('MESSAGE_FORM_SUBMITION_ERROR', 'Beim Senden Ihres Widerrufs ist ein Fehler aufgetreten. Bitte kontaktieren Sie uns telefonisch oder per E-Mail, um uns von dem Problem in Kenntnis zu setzen. Wir bedauern die Unannehmlichkeiten.');
define('MESSAGE_NO_CUSTOM_FORM', 'Dieses Formular ist nicht verfügbar! Bitte wenden Sie sich an den technischen Support.');