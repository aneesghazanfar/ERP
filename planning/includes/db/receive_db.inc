<?php
function add_receive(&$receive_data, $form_no, $mo_no, $maincat_id, $igp, $cmt, $entry_by) {
    if($maincat_id == '00-01')
        $maincat_id = 3;
    else if($maincat_id == '00-02')
        $maincat_id = 4;
    $check = false;
        
    foreach($receive_data as $key => $row) {

        if($row['id'] == null ){
            $sql = "INSERT INTO ".TB_PREF."stock_rec_details (sr_id, sorder_no, style_id, roll_no, weight, width, gsm, knit, tuck, q_loop, ply, fault_id, fault_allow, result) VALUES
            (".db_escape($row['form_no']).", ".db_escape($row['sorder_no']).", ".db_escape($row['style_id']).", ".db_escape($row['roll_no']).", ".db_escape($row['weight']).", 
            ".db_escape($row['width']).", ".db_escape($row['gsm']).", ".db_escape($row['knit']).", 
            ".db_escape($row['tuck']).", ".db_escape($row['q_loop']).", ".db_escape($row['ply']).", 
            ".db_escape($row['fault_id']).", ".db_escape($row['fault_allow']).", ".db_escape($row['result']).")";
           // db_query($sql, 'order Cannot be Added');
            $check = true;

        }
        else{
            $sql = "UPDATE ".TB_PREF."stock_rec_details SET
            sorder_no=".db_escape($row['sorder_no']).",
            style_id=".db_escape($row['style_id']).",
            roll_no=".db_escape($row['roll_no']).",
            weight=".db_escape($row['weight']).",
            width=".db_escape($row['width']).",
            gsm=".db_escape($row['gsm']).", 
            knit=".db_escape($row['knit']).", 
            tuck=".db_escape($row['tuck']).", 
            q_loop=".db_escape($row['q_loop']).", 
            ply=".db_escape($row['ply']).", 
            fault_id=".db_escape($row['fault_id']).", 
            fault_allow=".db_escape($row['fault_allow']).", 
            result=".db_escape($row['result'])." 
            WHERE id=".db_escape($row['id']);
        }
            db_query($sql, 'order Cannot be updated');
}
if($check){
    $sql = "INSERT INTO ".TB_PREF."stock_rec (form_no, mo_no, maincat_id, igp, cmt, entry_by) VALUES
        (".db_escape($form_no).", ".db_escape($mo_no).", ".db_escape($maincat_id).", ".db_escape($igp).", ".db_escape($cmt).", ".db_escape($entry_by).")";
        db_query($sql, 'order Cannot be Added');
}

}
//-------------------------------------------------------------------------------------------------
function get_rec_qty($mo_no){
    $sql = "SELECT SUM(d.weight) AS qty FROM ".TB_PREF."stock_rec_details d, ".TB_PREF."stock_rec r WHERE r.mo_no=".db_escape($mo_no)
        ." AND r.sr_id=d.sr_id";
    $result = db_query($sql, "could not get rec qty");
    $row = db_fetch_row($result);
    return $row[0];
}
//---------------------------------------------------------------------------------------------
function get_fault_description($fab_ids){
    $sql = "SELECT descr FROM ".TB_PREF."faults WHERE id IN ($fab_ids)";
    $result = db_query($sql, "could not get description");
    $descriptions = array();
    while ($row = db_fetch_row($result)) {
        $descriptions[] = $row[0]; // Store descriptions in an array
    }
    return $descriptions;

	//$result = db_query($sql, "could not get description");
   // $row = db_fetch_row($result);
	//return $row[0];
}
//----------------------------------------------------------------------------------------
function get_req_qty($mo_no){
    $sql = "SELECT act_req FROM ".TB_PREF."purch_plan_summary WHERE sorder_no = ".db_escape($mo_no);
    $result = db_query($sql, "could not get Description");
    $row = db_fetch_row($result);
    return $row[0];
}

//----------------------------------------------------------------------------------------

function temp_check_status($mo_no,$table_name){
    $sql = "SELECT approval_date FROM ".TB_PREF.$table_name."WHERE mo_no = ".db_escape($mo_no);
    $result = db_query($sql, "could not get supplier id");
    $row = db_fetch_row($result);
    if (strtotime($row[0]) === false)
        return  'Draft';
    else
        return  'Approved';
}
//----------------------------------------------------------------------------------------
function get_fab_description($mo_no, $sorder = null){
    $sql = "SELECT description FROM ".TB_PREF."purch_order_details WHERE order_no = ".db_escape($mo_no);
    if($sorder)
        $sql .= " AND sorder_no = ".db_escape($sorder);
    $result = db_query($sql, "could not get Description");
    $row = db_fetch_row($result);
    return $row[0];
}
//----------------------------------------------------------------------------------------
function get_uom_style($column_name, $mo_no){
$sql = "SELECT s.$column_name FROM ".TB_PREF."purch_order_details pd, ".TB_PREF."stock_master s WHERE pd.order_no = ".db_escape($mo_no)." 
    AND pd.stk_code = s.stock_id";


    $result = db_query($sql, "could not get Description");
    $row = db_fetch_row($result);
    return $row[0];
}
//----------------------------------------------------------------------------------------
function get_next_roll_no($mo_no){
    $sql = "SELECT s.supp_ref FROM ".TB_PREF."suppliers s, ".TB_PREF."purch_orders p WHERE p.order_no = ".db_escape($mo_no)." AND p.supplier_id = s.supplier_id";
    $result = db_query($sql, "could not get supplier ref");
    $row = db_fetch_row($result);
    return explode(" ", $row[0])[0];
}
//----------------------------------------------------------------------------------------
function get_roll_qty($mo_no){
    $sql = "SELECT SUM(roll_count) FROM ".TB_PREF."lot WHERE mo_no = ".db_escape($mo_no);
    $result = db_query($sql, "could not get roll_count");
    $row = db_fetch_row($result);
    return $row[0];

}
//----------------------------------------------------------------------------------------
function get_weight_qty($mo_no){
    $sql = "SELECT SUM(weight) FROM ".TB_PREF."lot WHERE mo_no = ".db_escape($mo_no);
    $result = db_query($sql, "could not get weight");
    $row = db_fetch_row($result);
    return $row[0];

}
//----------------------------------------------------------------------------------------  
function get_receive_detail($mo_no, $maincat_id){
    $sql = "SELECT sd.* FROM ".TB_PREF."stock_rec s, ".TB_PREF."stock_rec_details sd WHERE s.mo_no = ".db_escape($mo_no)."
    AND s.sr_id = sd.sr_id
    AND s.maincat_id = ".db_escape($maincat_id);
    $result = db_query($sql, "could not get receive detail");
    return $result;
}