<?php
/**
* Widerrufsformular für Zen Cart 1.5.7 deutsch
* @copyright Copyright 2026 webchills (www.webchills.at)
* @copyright Copyright 2003-2026 Zen Cart Development Team
* Zen Cart German Version - www.zen-cart-pro.at
* @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
* @version $Id: widerrufsformular_builder.php 2026-03-07 09:39:40Z webchills $
*/
define('HEADING_TITLE','Cancellation Form Builder');
define('TBL_HEAD_COUNT', '#');
define('TBL_HEAD_FORM_ID', 'ID');
define('TBL_HEAD_FORM_TITLE', 'Form Title');
define('TBL_HEAD_PAGE_TITLE', 'Page Title');
define('TBL_HEAD_PAGE_HEADING', 'Page Heading');
define('TBL_HEAD_FIELD_COUNT', 'Fields');
define('TBL_HEAD_HIT_COUNT', 'Hits');
define('TBL_HEAD_CREATED', 'Created');
define('TBL_HEAD_ACTION', 'Action');

define('BTN_ADD', 'Add');
define('BTN_ADD_OPTION', 'Add Option');
define('BTN_EDIT_OPTIONS', 'Edit Options');
define('BTN_EDIT', 'Edit');
define('BTN_DELETE', 'Delete');
define('BTN_GET_WIDGET', 'Get Widget');
define('BTN_SHOW_HITS', 'Hits');
define('LBL_YES', 'Yes');
define('LBL_NO', '-');
define('BTN_COPY', 'Copy');
define('JSON_LINE_BREAK_PLACEHOLDER', '|||');

define('INFO_HEAD_FORM_NAME', 'Form');
define('INFO_HEAD_FORM_INFO', 'Form Info');
define('INFO_HEAD_ADD_FORM', 'Create a New Form');
define('INFO_HEAD_DELETE_FORM_CONFIRM', 'Confirm: Delete Form');
define('INFO_HEAD_EDIT_FORM', 'Edit Form');

define('INFO_HEAD_FIELDS_SECTION_TITLE', 'Fields');
define('INFO_HEAD_SELECTED_FIELD_TITLE', 'Selected Field');
define('INFO_HEAD_DELETE_FIELD_CONFIRM', 'Confirm: Delete Form Field');
define('INFO_HEAD_ADD_FIELD', 'Add Field');
define('INFO_HEAD_EDIT_FIELD', 'Edit Field');
define('INFO_HEAD_EXISTING_FIELDS', 'Current Fields');

define('INFO_HEAD_SELECTED_OPTION_TITLE', 'Selected Field Option');
define('INFO_HEAD_DELETE_FIELD_OPTION_CONFIRM', 'Confirm: Delete Form Field Option');
define('INFO_HEAD_ADD_OPTION', 'Add Field Option');
define('INFO_HEAD_EDIT_FIELD_OPTIONS', 'Edit Field Options');
define('INFO_HEAD_EDIT_FIELD_OPTION', 'Edit Option');
define('INFO_HEAD_AVAILABLE_FIELD_OPTIONS', 'Available Option(s)');

define('INFO_FORM_ID_LABEL', 'Form ID');
define('INFO_FORM_TITLE_LABEL', 'Form Title');
define('INFO_FORM_EMAILS_LABEL', 'Email(s)');
define('INFO_FORM_CREATED_LABEL', 'Created');
define('INFO_FORM_CREATOR_LABEL', 'Creator');
define('INFO_FORM_FIELDS_COUNTER_LABEL', 'Fields');
define('INFO_FORM_HITS_COUNTER_LABEL', 'Hits');
define('INFO_FORM_PAGE_LINK_LABEL', 'Page Link');
define('INFO_FORM_DESCRIPTION_LABEL', 'Description');

define('INFO_PAGE_TITLE_LABEL', 'Page Title');
define('INFO_PAGE_HEADER_LABEL', 'Header');
define('INFO_NAVBAR_TITLE_LABEL', 'Navbar Title');

define('INFO_FIELD_ID_LABEL', 'Field ID');
define('INFO_FIELD_TYPE_LABEL', 'Type');
define('INFO_FIELD_NAME_LABEL', 'Name');
define('INFO_FIELD_LABEL_LABEL', 'Label');
define('INFO_FIELD_DESCRIPTION_LABEL', 'Description');
define('INFO_FIELD_REQUIRED_LABEL', 'Required? ');
define('INFO_FIELD_ORDER_LABEL', 'Sort Order');
define('INFO_FIELD_DEFAULT_LABEL', 'Default');
define('INFO_FIELD_DEFAULT_TEXT_LABEL', 'Default Text');
define('INFO_FIELD_REQUIRED_FLAG', '<span style="color:red;">*</span>');

define('INFO_OPTION_ID', 'ID');
define('INFO_OPTION_TEXT_LABEL', 'Text/Label');
define('INFO_OPTION_VALUE_LABEL', 'Value');
define('INFO_OPTION_DEFAULT_VALUE_LABEL', 'Default');
define('INFO_OPTION_READ_ONLY_LABEL', 'Read Only?');
define('INFO_OPTION_IS_READ_ONLY', '<span style="color:red;">N/A (Read Only)</span>');
define('INFO_OPTION_SELECTED_LABEL', 'Selected?');
define('INFO_OPTION_ORDER_LABEL', 'Sort Order');
define('INFO_OPTION_SORT_LABEL', 'Sort');
define('INFO_OPTION_OPTIONS_LABEL', 'Option(s):');
define('INFO_OPTION_ACTIONS_LABEL', 'Actions');

define('INFO_ADD_FORM_INFO', 'Please, fill out the following to create a brand new form.');
define('INFO_DELETE_FORM_CONFIRM', 'Are you sure you want to delete the following form?');
define('MSG_FORM_ADDED', 'Form Created!');
define('MSG_FORM_UPDATED', 'Form Updated!');
define('MSG_FORM_DELETED', 'Form Deleted!');
define('MSG_FORM_NOT_ADDED', 'Unable to add form. Please, try again or contact technical support.');
define('MSG_FORM_NOT_UPDATED', 'Unable to update that form. Please, try again or contact technical support.');
define('MSG_FORM_NOT_DELETED', 'Unable to delete that form. Please, try again or contact technical support.');

define('INFO_NO_FORM', 'There are no form created yet.<br />Click the add button to create a form.');
define('INFO_NO_FORM_ID', 'Form ID missing! Please, select a form and try again.');

define('INFO_DELETE_FIELD_CONFIRM', 'Are you sure you want to delete the following form field?');
define('MSG_FORM_FIELD_ADDED', 'Form Field Added!');
define('MSG_FORM_FIELD_UPDATED', 'Form Field Updated!');
define('MSG_FORM_FIELD_DELETED', 'Form Field Deleted!');
define('MSG_FORM_FIELD_NOT_ADDED', 'Unable to add form field. Please, try again or contact technical support.');
define('MSG_FORM_FIELD_NOT_UPDATED', 'Unable to update that form field. Please, try again or contact technical support.');
define('MSG_FORM_FIELD_NOT_DELETED', 'Unable to delete that form field. Please, try again or contact technical support.');
define('MSG_FIELD_INFO_NOT_AVAILABLE', 'Field information not available');
define('INFO_NO_FIELD_ID', 'Field ID missing! Please, select a form field and try again.');

define('INFO_DELETE_OPTION_CONFIRM', 'Are you sure you want to delete the following form field option?');
define('MSG_OPTION_ADDED', 'Field Option Added!');
define('MSG_OPTION_UPDATED', 'Field Option Updated!');
define('MSG_OPTION_DELETED', 'Field Option Deleted!');
define('MSG_OPTION_NOT_ADDED', 'Unable to add that field option. Please, try again or contact technical support.');
define('MSG_OPTION_NOT_UPDATED', 'Unable to update that field option. Please, try again or contact technical support.');
define('MSG_OPTION_NOT_DELETED', 'Unable to delete that field option. Please, try again or contact technical support.');
define('MSG_OPTION_INFO_NOT_AVAILABLE', 'Option Information not available');

define('MSG_TITLE_REQUIRED_ERROR', 'Please, add a title.');

define('MSG_PAGE_TITLE_REQUIRED_ERROR', 'Page Title required.');
define('MSG_HEADER_REQUIRED_ERROR', 'Header Title required.');
define('MSG_NAVBAR_TITLE_REQUIRED_ERROR', 'Navbar Title required.');

define('MSG_INSTALL_ERROR', 'There seems to be a problem with this plugin. Please, make sure the plugin was properly installed.');
define('TEXT_INFO_SELECT_FILE', 'Select a Page');
define('LABEL_SELECT_PAGE', 'Hosting Page');
define('LABEL_BUTTON_WIDGET', 'Button Widget');
define('LABEL_WIDGET_URL', 'Link');

define('PAGINATION_LABEL','Displaying %s to %s (of %s items)');

define('REPORT_HITS_TITLE','Hits Report');
define('REPORT_HITS_REFERER','Referer');
define('REPORT_HITS_COUNT','Hits');
define('REPORT_HITS_LAST_TIME', 'Last Hit');
define('REPORT_WIDGET_TITLE', 'Get Widget');