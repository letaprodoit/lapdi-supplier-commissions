<?php
/*
 * TSP Supplier Commissions CS-Cart Addon
 *
 * @package		TSP Supplier Commissions CS-Cart Addon
 * @filename	orders.post.php
 * @version		1.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @brief		Orders post hook for admin area
 * 
 */


if ( !defined('AREA') )	{ die('Access denied');	}

$commission_id = $_REQUEST['commission_id'];
$supplier_id = $_REQUEST['supplier_id'];
$params = $_REQUEST;

if ($mode == 'manage' && !empty($commission_id))
{

	$supplier_id = db_get_field("SELECT `supplier_id` FROM ?:addon_tsp_supplier_commissions WHERE `id` = ?i", $commission_id);

	$order_ids = db_get_fields("SELECT DISTINCT `order_id` FROM ?:addon_tsp_supplier_commissions WHERE `supplier_id` = ?i", $supplier_id);
	
	if (empty($order_ids))
	{
		$order_ids[] = null;
	}//endif

	$params['order_id'] = $order_ids;
	
	list($orders, $search, $totals) = fn_get_orders($params, Registry::get('settings.Appearance.admin_orders_per_page'), true);

	$view->assign('orders', $orders);
	$view->assign('search', $search);

	$view->assign('totals', $totals);
	$view->assign('display_totals', fn_display_order_totals($orders));

}//endif
elseif ($mode == 'manage' && !empty($supplier_id))
{
	$order_ids = db_get_fields("SELECT DISTINCT `order_id` FROM ?:addon_tsp_supplier_commissions WHERE `supplier_id` = ?i", $supplier_id);
	
	if (empty($order_ids))
	{
		$order_ids[] = null;
	}//endif

	$params['order_id'] = $order_ids;
	
	list($orders, $search, $totals) = fn_get_orders($params, Registry::get('settings.Appearance.admin_orders_per_page'), true);

	$view->assign('orders', $orders);
	$view->assign('search', $search);

	$view->assign('totals', $totals);
	$view->assign('display_totals', fn_display_order_totals($orders));

}//endelseif
?>