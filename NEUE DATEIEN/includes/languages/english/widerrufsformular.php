<?php
/**
* Widerrufsformular für Zen Cart 1.5.7 deutsch
* @copyright Copyright 2026 webchills (www.webchills.at)
* @copyright Copyright 2003-2026 Zen Cart Development Team
* Zen Cart German Version - www.zen-cart-pro.at
* @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
* @version $Id: widerrufsformular.php 2026-03-08 15:39:40Z webchills $
*/

define('TEXT_SUCCESS', 'Your message has been successfully sent.');

define('BTN_WIDERRUFSFORMULAR_CONTINUE','Continue');

define('BTN_WIDERRUFSFORMULAR_SEND','Confirm cancellation');
define('BTN_BACK','Backk');

define('EMAIL_CONTACT_TITLE', 'Cancellation');
define('EMAIL_PRODUCT_DESCRIPTION_TITLE', 'Ordered products or comments');

define('HEAD_WIDERRUF_INFORMATION', 'Order Cancellation');
define('LABEL_ORDER_NUMBER', 'Order Number');
define('LABEL_TIMESTAMP', 'Date and Time of Cancellation:');
define('LABEL_CUSTOMER_NAME', 'Name');
define('LABEL_CUSTOMER_EMAIL', 'E-Mail Address');
define('REQUIRED_FLAG', '<span style="color:red;">*</span>');

define('HEAD_CONFIRMATION', 'Confirmation: Please check your details again and then submit by clicking on Confirm cancellation.');
define('HEAD_SUCCESS', 'Cancellation sent!<br>You will receive a confirmation of receipt by email shortly.');

define('DEFAULT_REQUEST_STATUS', 'reveived');

define('EMAIL_SUBJECT_ADMIN', 'Cancellatio0n of an order at ' . STORE_NAME . ' - Cancellation ID: %s');
define('EMAIL_SUBJECT_CUSTOMER', 'Confirmation of receipt of your withdrawal at ' . STORE_NAME);
define('EMAIL_FOOTER_CUSTOMER', 'We will now review and process your cancellation. We will then get back to you with further information.');

define('JSON_LINE_BREAK_PLACEHOLDER', '|||');

define('MESSAGE_REQUIRED_FIELD_MISSING', 'Required Field Missing: %s');
define('MESSAGE_FORM_SUBMITION_SUCCESS', 'Your cancellation has been successfully sent. You will receive a confirmation of receipt by email shortly. We will now review your cancellation and process it. We will then get back to you with further information.');
define('MESSAGE_FORM_SUBMITION_ERROR', 'There was an error sending your request. Please, call or send us an e-mail to report the incident. We do apologize for the inconvenience.');
define('MESSAGE_NO_CUSTOM_FORM', 'Form not available! Please, contact technical support.');

