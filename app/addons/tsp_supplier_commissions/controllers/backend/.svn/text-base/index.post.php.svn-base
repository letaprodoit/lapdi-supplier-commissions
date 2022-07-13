<?php
/*
 * TSP Supplier Commissions CS-Cart Addon
 *
 * @package		TSP Supplier Commissions CS-Cart Addon
 * @filename	index.post.php
 * @version		1.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @brief		Index post hook for admin area
 * 
 */


if ( !defined('AREA') )	{ die('Access denied');	}

if ($_SERVER['REQUEST_METHOD']	== 'POST') 
{
	return;
}//endif

if ($mode == 'index') 
{
	
	if (defined('SUPPLIER_ID')) 
	{
		$stock = db_get_hash_single_array(
			"SELECT COUNT(product_id) as quantity, IF(amount > 0, 'in', 'out') as c FROM ?:products "
			. "WHERE tracking = 'B' " . fn_get_company_condition('?:products.company_id',true,SUPPLIER_ID) . " GROUP BY c", 
			array('c', 'quantity')
		);
		
		$stock_o = db_get_hash_single_array(
			"SELECT COUNT(DISTINCT(?:product_options_inventory.product_id))  as quantity, "
			. "IF(?:product_options_inventory.amount > 0, 'in', 'out') as c FROM ?:product_options_inventory "
			. "LEFT JOIN ?:products ON ?:products.product_id = ?:product_options_inventory.product_id "
			. "WHERE ?:products.tracking = 'O'" . fn_get_company_condition('?:products.company_id',true,SUPPLIER_ID) . " GROUP BY c", 
			array('c', 'quantity')
		);
	
		$product_stats['in_stock'] = (!empty($stock['in']) ? $stock['in'] : 0) + (!empty($stock_o['in']) ? $stock_o['in'] : 0);
		$product_stats['out_of_stock'] = (!empty($stock['out']) ? $stock['out'] : 0) + (!empty($stock_o['out']) ? $stock_o['out'] : 0);
	
		$category_stats['total'] = db_get_field("SELECT COUNT(*) FROM ?:categories WHERE "
			. fn_get_company_condition('?:categories.company_id',false,SUPPLIER_ID));
		
		$category_stats['status'] =  db_get_hash_single_array("SELECT status, COUNT(*) as amount FROM ?:categories WHERE " 
			. fn_get_company_condition('?:categories.company_id',false,SUPPLIER_ID) . " GROUP BY status", array('status', 'amount'));
	
		$view->assign('product_stats', $product_stats);
		$view->assign('category_stats', $category_stats);
	}//endif
}//endif

?>