<?php
/**********************************************************************
	Copyright (C) FrontAccounting, LLC.
	Released under the terms of the GNU General Public License, GPL, 
	as published by the Free Software Foundation, either version 3 
	of the License, or (at your option) any later version.
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
	See the License here <http://www.gnu.org/licenses/gpl-3.0.html>.
***********************************************************************/
$page_security = 'SA_SALESTYPES';
$path_to_root = '../..';
include_once($path_to_root . '/includes/session.inc');

page(_($help_context = 'Sales Types'));

include_once($path_to_root . '/includes/ui.inc');
include_once($path_to_root . '/sales/includes/db/sales_types_db.inc');
include_once($path_to_root . '/sales/includes/ui/sales_order_ui.inc');


simple_page_mode(true);

$result = get_all_sales_types(check_value('show_inactive'));

start_form();

$th = array (_('Type Name'), _('Factor'), _('Tax Incl'), '','');
$k = 0;
// $data = db_fetch($result);

while ($myrow = db_fetch($result)) {

	$rows = new stdClass();
	$rows->_1 = $myrow['id'];
	$rows->_2 = $myrow['sales_type'];
	$rows->_3 = $myrow['tax_included'] ? _('Yes') : _('No');
	$rows->_4 = number_format2($myrow['factor'], 4);
	$data[] = $rows;
}




new_table_style(null, $th, $_SESSION['Items'], true);






//----------------------------------------------------------------------------------------------------


end_form();

end_page();