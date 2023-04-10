<?php
function get_cost_detail($check){
	$sql = "SELECT * FROM ".TB_PREF."cost_sheet_details WHERE processing != ".db_escape($check);
	return db_query($sql, "could not get all detail");
}
function get_cs_id(){
    $sql = "SELECT cs_id FROM ".TB_PREF."cost_sheet ORDER BY cs_id DESC LIMIT 1";
    $result = db_query($sql, "could not get all detail");
    $row = db_fetch_row($result);
    return ($row[0] + 1);
    }
//-----------------------------------------------------------------------------------
function add_cost_data_to_db(&$cost_data, &$fab_data, $cs_id, $style, $shipping, $cmt, $labour, $overhead, $freight, $container, $insurance, $commission, $profit, $curr, $exch, $ufilename, $entry_by){
    foreach($cost_data as $key => $row) {
        foreach($fab_data as $key1 => $row1){
            if($row['fab_id'] == $row1['fab_id']){
                $waste = $row1['Knitting_waste'];
            }
        }
        if($row['id'] == null ) {
            $sql = "INSERT INTO ".TB_PREF."cost_sheet_details (cs_id, fab_id, stk_code, consume, rate, processing, waste) VALUES
                    (".db_escape($row['cs_id']).", ".db_escape($row['fab_id']).", ".db_escape($row['stk_code']).", ".db_escape($row['consume']).", ".db_escape($row['rate']).", ".db_escape($row['processing']).", ".db_escape($waste).")";

        } else {
            $sql = "UPDATE ".TB_PREF."cost_sheet_details SET 
                    consume = ".db_escape($row['consume']).", 
                    rate = ".db_escape($row['rate']).", 
                    processing = ".db_escape($row['processing']).", 
                    waste = ".db_escape($row['waste'])." 
                    
                    WHERE cs_id = ".db_escape($row['cs_id'])." AND fab_id = ".db_escape($row['fab_id']);
        }
        db_query($sql, 'order Cannot be Added');
    }
    
    foreach($fab_data as $key => $row){
        $sql = "INSERT INTO ".TB_PREF."cost_sheet_details (cs_id, fab_id, stk_code, consume, rate, processing, waste) VALUES
                (".db_escape($cs_id).", ".db_escape($row['fab_id']).", ".db_escape($row['dye_stk_code']).", ".db_escape($row['dfab_cost_perpc']).", ".db_escape(0).", ".db_escape($row['processing']).", ".db_escape($row['Dyeing_Waste']).")";
        db_query($sql, 'order Cannot be Added');
    }
 
    $sql1 = "INSERT INTO ".TB_PREF."cost_sheet (style_id, shipping, cmt, labour, overhead, freight, container, insurance, commission, profit, curr, exch, ufilename, entry_by) VALUES
            (".db_escape($style).", ".db_escape($shipping).", ".db_escape($cmt).", ".db_escape($labour).", 
            ".db_escape($overhead).", ".db_escape($freight).", ".db_escape($container).", ".db_escape($insurance).", 
            ".db_escape($commission).", ".db_escape($profit).", ".db_escape($curr).", ".db_escape($exch).", 
            ".db_escape($ufilename).", ".db_escape($entry_by).")";

    db_query($sql1, 'order Cannot be Added');
}
