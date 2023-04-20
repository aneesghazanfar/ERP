<?php

function add_issuance_database($items, $mo_no, $maincat_id,  $ogp, $cmt, $entry_by, $form_no,$sorder_no ){

            
$check = false;

    foreach($items as $key => $row) {
        if($row['id'] == null ){
        $sql = "INSERT INTO ".TB_PREF."stock_issue_details (si_id, sorder_no, stk_code, required, issued, lot_no)
            VALUES (".db_escape($row['si_id']).",".db_escape($sorder_no).",".db_escape($row['stk_code']).", ".db_escape($row['required']).", ".db_escape($row['issued']).", ".db_escape($row['lot_no']).")";
        $check = true;    
        }
        else{
            $sql = "UPDATE ".TB_PREF."stock_issue_details SET required = ".db_escape($row['required']).", issued = ".db_escape($row['issued']).", lot_no = ".db_escape($row['lot_no'])."
            SET sorder_no = ".db_escape($sorder_no)."
            WHERE id = ".db_escape($row['id']);
        }
        db_query($sql, 'order Cannot be Added');

    }
    if($check){
        $sql = "INSERT INTO ".TB_PREF."stock_issue (mo_no, form_no, maincat_id,  ogp, cmt, entry_by)
            VALUES (".db_escape($mo_no).",".db_escape($form_no).",".db_escape($maincat_id).", ".db_escape($ogp).", ".db_escape($cmt).",
            ".db_escape($entry_by).")";
    db_query($sql, 'order Cannot be Added');
    }

}
//---------------------------------------------------------------------------------
function get_sup_name($mo_no){
    $sql = "SELECT s.supp_name FROM ".TB_PREF."purch_orders p, ".TB_PREF."suppliers s 
    WHERE p.order_no = ".db_escape($mo_no)." 
    AND p.supplier_id = s.supplier_id";

    
    $result = db_query($sql, "could not get supplier id");
    $row = db_fetch_row($result);
    return $row[0];
}
//---------------------------------------------------------------------------------
function get_del_add($mo_no){
    $sql = "SELECT delivery_address FROM ".TB_PREF."purch_orders WHERE order_no = ".db_escape($mo_no);
    $result = db_query($sql, "could not get supplier id");
    $row = db_fetch_row($result);
    return $row[0];
}
//---------------------------------------------------------------------------------
function form_no(){
    $sql = "SELECT MAX(si_id) AS last_si_id FROM ".TB_PREF."stock_issue";
    $result = db_query($sql, "could not get supplier id");
    $row = db_fetch_row($result);
    return ($row[0] + 1 );
}
//---------------------------------------------------------------------------------
function check_status($mo_no){
    $sql = "SELECT approval_date FROM ".TB_PREF."stock_issue WHERE mo_no = ".db_escape($mo_no);
    $result = db_query($sql, "could not get supplier id");
    $row = db_fetch_row($result);
    if (strtotime($row[0]) === false)
        return  Draft;
    else
        return  Approved;
}
//---------------------------------------------------------------------------------
function get_issuance_detail($mo_no){
    //$sql = "SELECT * FROM ".TB_PREF."stock_issue_details WHERE si_id = ".db_escape($form_no);
    $sql = "SELECT * FROM ".TB_PREF."stock_issue_details sdetail , ".TB_PREF."stock_issue sissue 
    WHERE sissue.mo_no = ".db_escape($mo_no)." AND sissue.approval_date IS NULL AND sissue.si_id = sdetail.si_id";

    return db_query($sql, "could not get issuance detail");
}
//---------------------------------------------------------------------------------
function check_rec_qty($mo_no,$sorder){
    $sql = "SELECT quantity_received FROM ".TB_PREF."purch_order_details WHERE order_no = ".db_escape($mo_no)." AND sorder_no = ".db_escape($sorder);
    $result = db_query($sql, "could not get received quantity");
    $row = db_fetch_row($result);
    return $row[0];
}
//---------------------------------------------------------------------------------
function get_qoh($stock_id, $mo_no, $sorder){
    $sql = "SELECT (pod.quantity_received - SUM(sid.issued)) as qoh FROM ".TB_PREF."purch_order_details pod,
        ".TB_PREF."stock_issue si,
        ".TB_PREF."stock_issue_details sid
    WHERE pod.order_no = ".db_escape($mo_no)." 
        AND pod.sorder_no = ".db_escape($sorder)." 
        AND pod.stk_code = ".db_escape($stock_id)."
        AND si.mo_no = ".db_escape($mo_no)."
        AND si.si_id = sid.si_id
        AND sid.sorder_no = ".db_escape($sorder)."
        AND sid.stk_code = ".db_escape($stock_id);
    
    
    
    
    
    $result = db_query($sql, "could not get received quantity");
    $row = db_fetch_row($result);
    return $row[0];


}