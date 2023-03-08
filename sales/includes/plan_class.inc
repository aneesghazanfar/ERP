<?php

class purch_plan {

	var $line_items;
	

	function __construct() {
		$this->line_items = array();
	}
	

	function add_to_order($line_no,$sale_order_no, $stock_id, $qty, $item_descr, $price, $uom, $req_del_date, $qty_inv, $qty_recd) {
		if (isset($qty) && $qty != 0) {
			$this->line_items[$line_no] = new po_line_details($line_no,$sale_order_no, $stock_id, $item_descr, $qty, $price, $uom, 
				$req_del_date, $qty_inv, $qty_recd);
			$this->lines_on_order++;
			return 1;
		}
		return 0;
	}


	function update_order_item($line_no, $qty, $price, $req_del_date, $description="") {
		if ($description != "")
			$this->line_items[$line_no]->item_description = $description;
		$this->line_items[$line_no]->quantity = $qty;
		$this->line_items[$line_no]->price = $price;
		$this->line_items[$line_no]->req_del_date = $req_del_date;
		$this->line_items[$line_no]->item_description = $description;
	}

	
	

	

	
} 

class plan_line_details {

	var $line_no;
	var $order_no;
    var $style_id;
    var $stock_id;
    var $stk_type;
    var $t_style_qty;
    var $description;
    var $units;
    var $perpc;
    var $stk_extra;
    var $stk_total;
    var $unique_name;
    var $req_date;

	function __construct($line_no, $order_no, $style_id, $stock_id, $stk_type, $t_style_qty, $description, $units, $perpc, $stk_extra, $unique_name, $req_date) {

		$this->line_no = $line_no;
		$this->order_no = $order_no;
		$item_row = get_item($stock_item);
		$this->style_id = $style_id;
		$this->stock_id = $stock_id;
		$this->stk_type = $stk_type;
		$this->t_style_qty = $t_style_qty;
		$this->description = $description;
		$this->units = $units;
		$this->perpc = $perpc;
		$this->stk_extra = $stk_extra;
		$this->stk_total = $stk_total;
		$this->unique_name = $unique_name;
		$this->req_date = $req_date;

		
	}
}