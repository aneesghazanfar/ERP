<?php
//--------------------------------------------------------------------------------
function add_lot($lot_data, $mo_no, $lot_no, $roll_count, $weight, $color_id, $cmt, $entry_by){
     $check = false;
        
    foreach($lot_data as $key => $row) {
        if($row['id'] == null ){
        $sql = "INSERT INTO ".TB_PREF."lot_details (lot_id, roll_no) VALUES 
        (".db_escape($row['lot_id']).", ".db_escape($row['roll_no']).")";
            $check = true;
        }
        else{
            $sql = "UPDATE ".TB_PREF."lot_details SET lot_id = ".db_escape($row['lot_id']).", roll_no = ".db_escape($row['roll_no'])." WHERE id = ".db_escape($row['id']);
        }
        db_query($sql, 'lot Cannot be updated');

    }
    if($check){
        $sql = "INSERT INTO ".TB_PREF."lot (mo_no, lot_no, roll_count, weight, color_id, cmt, entry_by) VALUES 
        (".db_escape($mo_no).", ".db_escape($lot_no).", ".db_escape($roll_count).",
        ".db_escape($weight).", ".db_escape($color_id).", ".db_escape($cmt).", ".db_escape($entry_by).")";
        db_query($sql, 'lot Cannot be Added');

    }
    unset($_SESSION['lot_data']);
    unset($_SESSION['lot_data1']);

}
//--------------------------------------------------------------------------------
function get_sorder_no($mo_no){
    $sql = "SELECT sorder_no FROM ".TB_PREF."purch_order_details WHERE order_no = ".db_escape($mo_no);
    $result = db_query($sql, "could not get sorder number");
    $row = db_fetch_row($result);
    return $row[0];
}
//--------------------------------------------------------------------------------
function get_color($mo_no){
    $sql = "SELECT ic.item_color_name FROM ".TB_PREF."item_color ic, ".TB_PREF."lot l WHERE ic.item_color_id = l.color_id AND l.mo_no = ".db_escape($mo_no);
    $result = db_query($sql, "could not get color");
    $row = db_fetch_row($result);
    return $row[0];
}
//--------------------------------------------------------------------------------
function get_number(){
    $sql = "SELECT lot_no FROM ".TB_PREF."lot ORDER BY lot_no DESC LIMIT 1";
    $result = db_query($sql, "could not get lot number");
    $row = db_fetch_row($result);
    return (end(explode("_", $row[0]))[0] + 1);
}
//--------------------------------------------------------------------------------
function get_roll_weight($roll_no){
    $sql = "SELECT weight FROM ".TB_PREF."stock_rec_details WHERE roll_no = ".db_escape($roll_no);
    $result = db_query($sql, "could not get weight");
    $row = db_fetch_row($result);
    return $row[0];
}
//--------------------------------------------------------------------------------
function get_lot_detail($mo_no){
    $sql = "SELECT * FROM ".TB_PREF."lot_details ld , ".TB_PREF."lot l WHERE ld.lot_id = l.lot_id AND l.mo_no = ".db_escape($mo_no);
    $result = db_query($sql, "could not get lot detail");
    return $result;
}
//-------------------------------------------------------------------------------------
