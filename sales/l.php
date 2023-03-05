"
foreach($data as $key => $row) {

	$sql = "UPDATE ".TB_PREF."purch_plan_details SET sorder_no = ".db_escape($order_no).", style_id = ".db_escape($row['style_id']).", stk_type = ".db_escape($row['stk_type']).", stk_code = ".db_escape($row['stock_id']).", perpc = ".db_escape($row['perpc']).", stk_extra = ".db_escape($row['stk_extra']).", stk_total = ".db_escape($row['stk_total']).", filename = ".db_escape($row['filename']).", unique_name = ".db_escape($row['unique_name']).", req_date = ".db_escape($row['req_date'])." WHERE id = ".db_escape($row['id']).";

    (id,sorder_no, style_id, stk_type, stk_code, perpc, stk_extra, stk_total, filename, unique_name, req_date)
            VALUES (".db_escape($row['id']).",".db_escape($order_no).", ".db_escape($row['style_id']).", ".db_escape($row['stk_type']).", ".db_escape($row['stock_id']).", ".db_escape($row['perpc']).", ".db_escape($row['stk_extra']).", ".db_escape($row['stk_total']).", ".db_escape($row['filename']).", ".db_escape($row['unique_name']).", ".db_escape($row['req_date']).");

}
"
i have this pice of code i want to perform 2 operation in one loop first one is UPDATE the exiting record by checking the id if id is null it insert new record and if $row['id'] is not null update that row in database




