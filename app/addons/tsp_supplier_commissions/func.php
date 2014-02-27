<?php
/*
 * TSP Supplier Commissions CS-Cart Addon
 *
 * @package		TSP Supplier Commissions CS-Cart Addon
 * @filename	func.php
 * @version		2.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/03/01
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Hook implementations for addon
 * 
 */

if ( !defined('BOOTSTRAP') )	{ die('Access denied');	}

use Tygh\Registry;

require_once 'lib/fn.supplier_commissions.php';

//---------------
// HOOKS
//---------------

/***********
 *
 * Modified by LGC - fn_tsp_supplier_commissions_finish_payment
 * Finish payment
 *
 ***********/
function fn_tsp_supplier_commissions_finish_payment($order_id, $pp_response, $force_notification)
{
	$order_info = fn_get_order_info($order_id);
	
	// If the user purchased a supplier membership save the company information
	fn_tspsc_save_supplier($order_info);

	// If the user purchased supplier products store the commissions
	fn_tspsc_save_commissions($order_info);
		
	// Pay supplier commission
	// Determine if automatic payments is enabled
	$pay_automatically = (Registry::get('addons.tsp_supplier_commissions.pay_automatically') == 'Y') ? true : false;

	if (($order_info['payment_info']['order_status'] == 'P') && $pay_automatically) 
	{
		// Get commissions for this order
		list($commissions, $null) = fn_tspsc_get_supplier_commissions(array('order_id' => $order_id));

		fn_tspsc_masspay_commissions($commissions);
	}//endif
}//end fn_tsp_supplier_commissions_finish_payment

/**
 * Since orders are directly related to reminders if an order is deleted, delete
 * the reminder as well
 *
 * @since 1.0.0
 *
 * @param int $order_id Required - The Order ID
 *
 * @return none
 */
function fn_tsp_supplier_commissions_delete_order($order_id)
{
	fn_tspsc_delete_supplier_commissions('order_id', $order_id);
}//end fn_tsp_reorder_reminders_delete_order

/***********
 *
* Delete product metadata
*
***********/
function fn_tsp_supplier_commissions_delete_product_post($product_id)
{
	fn_tspsc_delete_supplier_commissions('product_id', $product_id);
}//end fn_tsp_supplier_commissions_delete_product_post

/***********
 *
* Modified by LGC - fn_tsp_supplier_commissions_order_notification
* Called after all emails sent, send emails to suppliers notifying them of their commissions if any
*
***********/
function fn_tsp_supplier_commissions_order_notification(&$order_info, $order_statuses, &$force_notification)
{
	$suppliers = array();

	foreach ($order_info['products'] as $k => $prod)
	{
		$product_company = $prod['supplier_id'];

		// if the product code is formated correctly and is a supplier
		// product then get the supplier id and start storing the data
		if (!empty($product_company))
		{
			$supplier_id = $product_company;
			$supplier_info = !empty($supplier_id) ? fn_get_supplier_data($supplier_id) : array();
				
			// If the supplier was found continue
			if (!empty($supplier_info))
			{
				$suppliers[$supplier_id] = 0; //shipping cost is set to 0

				$commission_data = db_get_row("SELECT * FROM ?:addon_tsp_supplier_commissions WHERE `supplier_id` = ?i AND `order_id` = ?i AND `product_id` = ?i", $supplier_id, $order_info['order_id'], $prod['product_id']);
				$order_info['items'][$k]['supplier_id'] = $supplier_id;
				$order_info['items'][$k]['commission'] = $commission_data['total'];
				$order_info['items'][$k]['discount'] = $commission_data['discount'];
			}//endif
		}//endif
	}//endforeach

	// If there are suppliers with commissions and the order is marked as paid
	// then it is ok to send out notification
	if (!empty($suppliers) && ($order_info['payment_info']['order_status'] == 'P'))
	{
		if (!empty($order_info['shipping']))
		{
			foreach ($order_info['shipping'] as $shipping_id => $shipping)
			{
				foreach ((array)$shipping['rates'] as $supplier_id => $rate)
				{
					if (isset($suppliers[$supplier_id]))
					{
						$suppliers[$supplier_id] += $rate;
					}//endif
				}//endforeach
			}//endforeach
		}//endif

		Registry::get('view_mail')->assign('order_info', $order_info);
		Registry::get('view_mail')->assign('status_inventory', $order_statuses[$order_info['status']]['inventory']);

		foreach ($suppliers as $supplier_id => $shipping_cost)
		{
			if ($supplier_id != 0)
			{
				$supplier = !empty($supplier_id) ? fn_get_supplier_data($supplier_id) : array();

				Registry::get('view_mail')->assign('shipping_cost', $shipping_cost);
				Registry::get('view_mail')->assign('supplier_id', $supplier_id);
				Registry::get('view_mail')->assign('order_status', fn_get_status_data($order_info['status'], STATUSES_ORDER, $order_info['order_id'], CART_LANGUAGE));
				Registry::get('view_mail')->assign('profile_fields', fn_get_profile_fields('I', '', CART_LANGUAGE));

				// Send a copy to the supplier
				fn_send_mail($supplier['email'], Registry::get('settings.Company.company_orders_department'), 'addons/tsp_supplier_commissions/commission_notification_subj.tpl', 'addons/tsp_supplier_commissions/commission_notification.tpl', '', CART_LANGUAGE, Registry::get('settings.Company.company_orders_department'));

				// Send a copy to the staff
				fn_send_mail(Registry::get('settings.Company.company_orders_department'), Registry::get('settings.Company.company_orders_department'), 'addons/tsp_supplier_commissions/commission_notification_subj.tpl', 'addons/tsp_supplier_commissions/commission_notification.tpl', '', CART_LANGUAGE, Registry::get('settings.Company.company_orders_department'));
			}//endif
		}//endforeach
	}//endif
}//end fn_tsp_supplier_commissions_order_notification
/***********
 *
* Function to update the product metadata
*
***********/
function fn_tsp_supplier_commissions_update_product_post(&$product_data, $product_id, $lang_code, $create){

	if (!empty($product_id) && !empty($product_data))
	{
		$field_names = Registry::get('tspsc_product_data_field_names');

		foreach ($field_names as $field_name => $fdata)
		{
			if (array_key_exists($field_name, $product_data))
			{
				$value = $product_data[$field_name];
				fn_tspsc_update_product_metadata($product_id, $field_name, $value);
			}//endif
		}//endforeach

	}//endif
}//end fn_tsp_supplier_commissions_update_product_post
?>