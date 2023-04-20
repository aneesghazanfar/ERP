<?php

function get_issuance_data($mo_no=null, $unset=false){
    if ($unset) {
        unset($_SESSION['issuance_data']);
    }

    $result = get_issuance_detail($mo_no);
    $line_no = 1;
    while ($myrow = db_fetch($result)) {
        $issuance_data['line_no'] = $line_no++;
        $issuance_data['id'] = $myrow['id'];
        $issuance_data['si_id'] = $myrow['si_id'];
        $issuance_data['stk_code'] = $myrow['stk_code'];
        $issuance_data['required'] = $myrow['required'];
        $issuance_data['qoh'] = get_qoh_on_date($myrow['stk_code']);        
        $issuance_data['issued'] = $myrow['issued'];
        $issuance_data['lot_no'] = $myrow['lot_no'];
        $existing_data = isset($_SESSION['issuance_data']) ? $_SESSION['issuance_data'] : array();

        $existing_data[] = $issuance_data;
        $_SESSION['issuance_data'] = $existing_data;
        


    }
}
//--------------------------------------------------------------------------------------
function required_bags($stock_id,$maincat_id){
    $sql = "SELECT act_req FROM ".TB_PREF."purch_plan_summary WHERE stk_code = ".db_escape($stock_id)." AND maincat_id = ".db_escape($maincat_id);
    $result = db_query($sql, "could not get required bags");
    $row = db_fetch_row($result);
    return ($row[0]/45.36);
}


function get_item_cat($order_no){
$sql = "SELECT item_code FROM ".TB_PREF."purch_order_details WHERE order_no = ".db_escape($order_no)." ORDER BY item_code DESC";
    $result = db_query($sql, "could not get item code");
    $row = db_fetch_row($result);
    return substr($row[0], 0, 5);

}

function already_issued($mo_no,$stk_code){
    $sql = "SELECT SUM(sdetail.issued) FROM ".TB_PREF."stock_issue_details sdetail , ".TB_PREF."stock_issue sissue 
    WHERE sissue.mo_no = ".db_escape($mo_no)." AND sissue.si_id = sdetail.si_id AND sdetail.stk_code = ".db_escape($stk_code);

    $result = db_query($sql, "could not get issued");
    $row = db_fetch_row($result);
    return $row[0];
}

//Start - MUZZAMMIL - Added function to get required from stock_issue_details table
function get_required($mo_no){
    $sql = "SELECT sdetail.required FROM ".TB_PREF."stock_issue_details sdetail , ".TB_PREF."stock_issue sissue 
    WHERE sissue.mo_no = ".db_escape($mo_no)." AND sissue.si_id = sdetail.si_id";

    $result = db_query($sql, "could not get required");
    $row = db_fetch_row($result);
    return $row[0];
}
//End - MUZZAMMIL - Added function to get required from stock_issue_details table

//--------------------------------------------------------------------------------------------

function get_Rolls_count($lot_no){
    $sql = "SELECT roll_count FROM ".TB_PREF."lot WHERE lot_no = ".db_escape($lot_no);
    $result = db_query($sql, "could not get roll count");
    $row = db_fetch_row($result);
    return $row[0];
}
//--------------------------------------------------------------------------------------------
function get_weight($lot_no){
    $sql = "SELECT weight FROM ".TB_PREF."lot WHERE lot_no = ".db_escape($lot_no);
    $result = db_query($sql, "could not get weight");
    $row = db_fetch_row($result);
    return $row[0];
}



//---------------------------------------------------------------------------------------------

function get_date($form_no){
    $sql = "SELECT issue_date FROM ".TB_PREF."stock_issue WHERE si_id = ".db_escape($form_no);
    $result = db_query($sql, "could not get date");
    $row = db_fetch_row($result);
    return $row[0];
}

//---------------------------------------------------------------------------------------------

function get_user_name($form){
    $sql = "SELECT u.real_name FROM ".TB_PREF."stock_issue s , ".TB_PREF."users u WHERE s.si_id = ".db_escape($form)." AND s.entry_by = u.id";
    $result = db_query($sql, "could not get user");
    $row = db_fetch_row($result);
    return $row[0];
}

function lot_no_item_list($name, $submit_on_change=false, $mo_no,$selected_id=null ) {
    $sql = "SELECT lot_no, lot_no FROM ".TB_PREF."lot WHERE mo_no = ".db_escape($mo_no);
    return combo_input($name, $selected_id, $sql, 'lot_no', 'lot_no',
		array(
			'spec_id' => -1,
			'search_submit' => true,
			'select_submit'=> $submit_on_change,
			'async' => false,
	));
}

function lot_no_item_list_cells($label, $name, $submit_on_change=false, $mo_no = null) {
	
    
	if ($label != null)
		echo "<td class='label'>".$label."</td>\n";
echo "<td>";
	echo lot_no_item_list($name, $submit_on_change, $mo_no); 
    echo "</td>\n";
}


//---------------------------------------------------------------------------------------------
function temp_form_no($mo_no,$table_name){

$sql = "SELECT form_no  AS form_no
FROM ".TB_PREF.$table_name."
WHERE mo_no =".db_escape($mo_no)."
UNION ALL
SELECT COALESCE(MAX(form_no)) + 1 AS form_no
FROM ".TB_PREF.$table_name."
WHERE NOT EXISTS (SELECT 1 FROM ".TB_PREF.$table_name." WHERE mo_no =".db_escape($mo_no).")";

//$sql = "SELECT form_no AS last_form_no FROM ".TB_PREF."stock_rec WHERE mo_no = ".db_escape($mo_no);
    $result = db_query($sql, "could not get form no");
    $row = db_fetch_row($result);
    if ($row[0] == null)
        return 1;
    else
    return ($row[0] );
}
//----------------------------------------------------------------------------------------
function detail_id($column_name, $table_name){
    $sql = "SELECT $column_name FROM ".TB_PREF.$table_name." ORDER BY ".$column_name." DESC LIMIT 1";
    $result = db_query($sql, "could not get id");
    $row = db_fetch_row($result);
    return ($row[0] + 1);
}
//----------------------------------------------------------------------------------------

//Start - MUZZAMMIL - Updated 11-Apr-2023
//Adding functions to get data from stock_issue table
function get_si_data($mo_no, $table_name){
    $sql = "SELECT * FROM ".TB_PREF.$table_name." WHERE mo_no = ".db_escape($mo_no);
    $result = db_query($sql, "could not get data");
    return $result;
}

// Adding function to get data from stock_issue_details table
function get_line_details($column_name, $table_name, $value){
    $sql = "SELECT * FROM ".TB_PREF.$table_name." WHERE $column_name = ".db_escape($value);
    $result = db_query($sql, "could not get si line details");
    
    return $result;
}

//Adding approve function to approve stock issue
//Anees Ghazanfar - 20-Apr-2023
// function updated
function approve_si_sr($table_name, $column_name, $si_sr_id){
    $sql = "UPDATE ".TB_PREF.$table_name." SET approval_date = NOW(), approve_by = ".db_escape($_SESSION["wa_current_user"]->user)." WHERE ".$column_name." = ".db_escape($si_sr_id);
    db_query($sql, "Error approving order");		
}

//End - MUZZAMMIL - 11-Apr-2023

//Start - MUZZAMMIL - Updated 14-Apr-2023
//Getting form no from stock_issue and stock_rec table
function get_form_no($mo_no, $table_name){
    //get first si_id from stock_issue table where mo_no = $mo_no
    $sql = "SELECT form_no FROM ".TB_PREF.$table_name." WHERE mo_no = ".db_escape($mo_no); 
    $result = db_query($sql, "could not get form no");
    $row = db_fetch_row($result);
    return $row[0];
}
//End - MUZZAMMIL - 14-Apr-2023
//-------------------------------------------------------------------------------------
function sale_dropdown_list($name, $submit_on_change=false, $mo_no, $selected_id=null) {

    $sql = "SELECT DISTINCT(sorder_no), sorder_no FROM ".TB_PREF."purch_order_details WHERE order_no = ".db_escape($mo_no);

    return combo_input($name, $selected_id, $sql, sorder_no, sorder_no,
		array(
			'spec_id' => -1,
			'search_submit' => true,
			'select_submit'=> $submit_on_change,
			'async' => false,
	));
}

function sale_dropdown_list_cells($label, $name, $submit_on_change=false, $mo_no) {
	
    
	if ($label != null)
		echo "<td class='label'>".$label."</td>\n";
    echo "<td>";
	    echo sale_dropdown_list($name, $submit_on_change, $mo_no); 
    echo "</td>\n";
}


