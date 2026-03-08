##################################################################################
# UNINSTALL Widerrufsformular 1.0.0 - 2026-03-07 - webchills
# UNINSTALL - NUR AUSFÜHREN WENN SIE DAS MODUL KOMPLETT ENTFERNEN WOLLEN!
##################################################################################

SET @gid=0;
SELECT @gid:=configuration_group_id
FROM configuration_group
WHERE configuration_group_title = 'Widerrufsformular' LIMIT 1;
DELETE FROM configuration WHERE configuration_group_id = @gid;
DELETE FROM configuration_group WHERE configuration_group_id = @gid;
DELETE FROM configuration_language WHERE configuration_key LIKE '%USTOM_FORMS%';
DELETE FROM admin_pages WHERE page_key='configWiderrufsformular';
DELETE FROM admin_pages WHERE page_key='WiderrufsformularDashboard';
DELETE FROM admin_pages WHERE page_key='WiderrufsformularBuilder';
DROP TABLE IF EXISTS widerrufsformular;
DROP TABLE IF EXISTS widerrufsformular_fields;
DROP TABLE IF EXISTS widerrufsformular_fields_options;
DROP TABLE IF EXISTS widerrufsformular_hits;
DROP TABLE IF EXISTS widerrufsformular_requests;