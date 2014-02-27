<?php
/*
 * TSP Supplier Commissions CS-Cart Addon
 *
 * @package		TSP Supplier Commissions CS-Cart Addon
 * @filename	orders.post.php
 * @version		2.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/03/01
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Orders post hook for admin area
 * 
 */


if ( !defined('BOOTSTRAP') )	{ die('Access denied');	}

use Tygh\Registry;

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

	Registry::get('view')->assign('orders', $orders);
	Registry::get('view')->assign('search', $search);

	Registry::get('view')->assign('totals', $totals);
	Registry::get('view')->assign('display_totals', fn_display_order_totals($orders));

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

	Registry::get('view')->assign('orders', $orders);
	Registry::get('view')->assign('search', $search);

	Registry::get('view')->assign('totals', $totals);
	Registry::get('view')->assign('display_totals', fn_display_order_totals($orders));

}//endelseif
?>