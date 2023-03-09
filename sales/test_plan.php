<?php
/**********************************************************************
	Copyright (C) ASMIte Inc.
	contact@march7.group
***********************************************************************/
$page_security = 'SA_SALESORDER';
$path_to_root = '..';
include_once($path_to_root.'/includes/session.inc');
include_once($path_to_root.'/includes/ui.inc');
$js = '';
if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(900, 600);
if (user_use_date_picker())
	$js .= get_js_date_picker();
page(_($help_context = 'Manage Purchase Plans'), @$_REQUEST['popup'], false, '', $js);
simple_page_mode(true);
//----------------------------------------------------------------------------------------------
$order_no= $_GET['OrderNumber'];
// $ordrow = get_order($order_no);
//----------------------------------------------------------------------------------------------
start_form(true);
tabbed_content_start('tabs', array(
	'dyed' => array(_('Dyed Fabric Purchase Plan'), true),
	'accessory' => array(_('Accessories Purchase Plan'), true),
	));
	switch (get_post('_tabs_sel')) {
		default:
		case 'dyed':
// include_once($path_to_root.'/purchasing/sales_order_plan.php');
include_once($path_to_root.'/sales/sales_order_plan.php');

			break;
			case 'accessory':
			include_once($path_to_root.'/purchasing/sales_order_plan.php');
	};
br();
tabbed_content_end();
end_form();
end_page();
