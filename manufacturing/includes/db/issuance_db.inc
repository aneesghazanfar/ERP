<?php
function add_issuance_database($items, $mo_no, $maincat_id,  $ogp, $cmt, $entry_by){
  $sql = "INSERT INTO ".TB_PREF."stock_issue (mo_no,maincat_id,  ogp, cmt, entry_by)
            VALUES (".db_escape($mo_no).",".db_escape($maincat_id).", ".db_escape($ogp).", ".db_escape($cmt).",
            ".db_escape($entry_by).")";
    db_query($sql, 'order Cannot be Added');

    foreach($items as $key => $row) {
        $sql = "INSERT INTO ".TB_PREF."stock_issue_details (si_id, stk_code, required, issued)
            VALUES (".db_escape($mo_no).",".db_escape($row['stk_code']).", ".db_escape($row['required']).", ".db_escape($row['issued']).")";
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
    $sql = "SELECT si_id FROM ".TB_PREF."stock_issue";
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
function get_issuance_detail($mo_no, $maincat_id){
    $sql = "SELECT * FROM ".TB_PREF."stock_issue_details WHERE si_id = ".db_escape($mo_no);
    return db_query($sql, "could not get issuance detail");
}