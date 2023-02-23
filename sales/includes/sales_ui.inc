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
include_once($path_to_root . '/includes/date_functions.inc');

include_once($path_to_root . '/includes/ui.inc');
include_once($path_to_root . '/includes/banking.inc');

//----------------------------------------------------------------------------
// helper functions for script execution control
//
function processing_start() {
	page_processing(false);
	processing_end();
	$_SESSION['Processing'] = $_SERVER['PHP_SELF'];
}

function processing_end() {
	page_processing(true);
	unset($_SESSION['Processing']);
	if ( isset($_SESSION['Items']) ) {
		unset($_SESSION['Items']->line_items);
		unset($_SESSION['Items']);
	}
}

function processing_active() {
	return (isset($_SESSION['Processing']) && $_SESSION['Processing']==$_SERVER['PHP_SELF']);
}
/*
	Check if the cart was not destroyed during opening the edition page in
	another browser tab.
*/
function check_edit_conflicts($cart_id, $cartname='Items') {
	global $Ajax, $SysPrefs;

	if ((!isset($SysPrefs->no_check_edit_conflicts) || $SysPrefs->no_check_edit_conflicts==0) && $cart_id && isset($_SESSION[$cartname]) && $cart_id != $_SESSION[$cartname]->cart_id) {
		display_error(_('This edit session has been abandoned by opening sales document in another browser tab. You cannot edit more than one sales document at once.'));
		$Ajax->activate('_page_body');
		display_footer_exit();
	}
}


// start 
// Anees Ghazanfar 23/2/2023
// Description: later

function new_table_style($title= null, &$order, $editable = false) {
	

	display_heading($title);

	$th = array(_('Style Id'), _('Quantity'), _('Qoh'), _('Edit'));
	
	div_start('edit_table');
	start_table(TABLESTYLE, "width='30%'");
	table_header($th);
	$orderNO = $_SESSION['Items']->order_no;

	$k = 0;

	$id = find_submit('Edit');
	
	foreach ($order->get_items() as $line_no=>$stock_item) {
	//	var_dump($stock_item);

		echo "id " . $id;
		echo "line" . $line_no;


	if ($id != $line_no) {
		start_row();
			style_list_label('style_id', $orderNO);
			quantity_count('qtyCount',$orderNO, $_REQUEST['style_id']);
			qoh_count('qohCount',$orderNO, $_REQUEST['style_id']);
			edit_button_cell('Edit' . $line_no, _('Edit'), _('Edit line'));

		end_row();
	}
	else{
		start_row();
		echo "naji";
			style_list_cells( null, 'style_id', null, false, true, false, $orderNO);
			qoh_count('qohCount',$orderNO, $_REQUEST['style_id']);
			qoh_count('qohCount',$orderNO, $_REQUEST['style_id']);
			if ($id != -1) {
				button_cell('UpdateItem', _('Update'), _('Confirm changes'), ICON_UPDATE);
				button_cell('CancelItemChanges', _('Cancel'), _('Cancel changes'), ICON_CANCEL);
				
			}
		end_row();
	}

	}

	
	end_table();
	div_end();

	
}

// end