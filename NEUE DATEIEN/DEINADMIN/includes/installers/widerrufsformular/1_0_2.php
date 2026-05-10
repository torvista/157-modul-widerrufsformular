<?php
$db->Execute(" SELECT @gid:=configuration_group_id
FROM ".TABLE_CONFIGURATION_GROUP."
WHERE configuration_group_title= 'Widerrufsformular'
LIMIT 1;");

$db->Execute("INSERT IGNORE INTO ".TABLE_CONFIGURATION." (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES
('Show Link?', 'WIDERRUFSFORMULAR_SHOW_LINK', 'false', 'Should the link to the cancellation form (Cancel Contract) be displayed?', @gid, 6, NOW(), NULL, 'zen_cfg_select_option(array(''true'', ''false''),')");

$db->Execute("REPLACE INTO ".TABLE_CONFIGURATION_LANGUAGE." (configuration_title, configuration_key, configuration_description, configuration_language_id) VALUES
('Link anzeigen?', 'WIDERRUFSFORMULAR_SHOW_LINK','Soll der Link zum Widerrufsformular (Vertrag widerrufen) angezeigt werden?', 43)");

$db->Execute("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '1.0.2' WHERE configuration_key = 'WIDERRUFSFORMULAR_VERSION';");