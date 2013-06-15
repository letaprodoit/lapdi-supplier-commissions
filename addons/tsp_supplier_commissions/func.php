<?php
/*
 * TSP Supplier Commissions CS-Cart Addon
 *
 * @package		TSP Supplier Commissions CS-Cart Addon
 * @filename	func.php
 * @version		1.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/03/01
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Hook implementations for addon
 * 
 */

if ( !defined('AREA') )	{ die('Access denied');	}

require_once 'lib/fn.supplier_commissions.php';

//---------------
// HOOKS
//---------------

/***********
 *
 * Function to get delete a category based on the supplier
 * don't allow a supplier to delete any other categories but their own
 *
 ***********/
function fn_tsp_supplier_commissions_delete_category_pre(&$category_id)
{
	if (defined('SUPPLIER_ID')) 
	{
		$company_id = db_get_field("SELECT `company_id` FROM ?:categories WHERE `category_id` = ?i", $category_id);
		
		if (SUPPLIER_ID != $company_id)
		{
			$category_id = 0;
		}//endif
	}//endif
}//end fn_tsp_supplier_commissions_delete_category_pre

/***********
 *
 * Function to delete commission data for a supplier	
 *
 ***********/
function fn_tsp_supplier_commissions_delete_company($company_id)
{
	fn_tspsc_delete_supplier_commissions('supplier_id', $company_id);
	fn_tspsc_delete_supplier_profile_data($company_id);
}//end fn_tsp_supplier_commissions_delete_company

/***********
 *
 * Function to get delete a product feature based on the supplier
 * don't allow a supplier to delete any other product feature but their own
 *
 ***********/
function fn_tsp_supplier_commissions_delete_feature_pre(&$feature_id)
{
	if (defined('SUPPLIER_ID')) 
	{
		$company_id = db_get_field("SELECT `company_id` FROM ?:product_features WHERE `feature_id` = ?i", $feature_id);
		
		if (SUPPLIER_ID != $company_id)
		{
			$feature_id = 0;
		}//endif
	}//endif
}//end fn_tsp_supplier_commissions_delete_feature_pre

/***********
 *
 * Function to get delete a category based on the supplier
 * don't allow a supplier to delete any other categories but their own
 *
 ***********/
function fn_tsp_supplier_commissions_delete_product_filter_pre(&$filter_id)
{
	if (defined('SUPPLIER_ID')) 
	{
		$company_id = db_get_field("SELECT `company_id` FROM ?:product_filters WHERE `filter_id` = ?i", $filter_id);
		
		if (SUPPLIER_ID != $company_id)
		{
			$filter_id = 0;
		}//endif
	}//endif
}//end fn_tsp_supplier_commissions_delete_product_filter_pre

/***********
 *
 * Function to delete a product option based on the supplier
 * don't allow a supplier to delete any other product option but their own
 *
 ***********/
function fn_tsp_supplier_commissions_delete_product_option_pre(&$option_id, $pid)
{
	if (defined('SUPPLIER_ID')) 
	{
		$company_id = db_get_field("SELECT `company_id` FROM ?:product_options WHERE `option_id` = ?i", $option_id);
		
		if (SUPPLIER_ID != $company_id)
		{
			$option_id = 0;
		}//endif
	}//endif
}//end fn_tsp_supplier_commissions_delete_product_option_pre

/***********
 *
 * Delete product metadata
 *
 ***********/
function fn_tsp_supplier_commissions_delete_product_post($product_id)
{
	db_query("DELETE FROM ?:addon_tsp_supplier_commissions_product_metadata WHERE `product_id` = ?i", $product_id);
}//end fn_tsp_supplier_commissions_delete_product_post

/***********
 *
 * Function to get delete a category based on the supplier
 * don't allow a supplier to delete any other categories but their own
 *
 ***********/
function fn_tsp_supplier_commissions_delete_product_pre(&$product_id, $status)
{
	if (defined('SUPPLIER_ID')) 
	{
		$company_id = db_get_field("SELECT `company_id` FROM ?:products WHERE `product_id` = ?i", $product_id);
		
		if (SUPPLIER_ID != $company_id)
		{
			$product_id = 0;
		}//endif
	}//endif
}//end fn_tsp_supplier_commissions_delete_product_pre

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

/***********
 *
 * Function to get categories based on the supplier
 * allow supplier to see only their categories
 *
 ***********/
function fn_tsp_supplier_commissions_get_categories($params, $join, &$condition, $fields, $group_by, $sortings)
{
	if (defined('SUPPLIER_ID')) 
	{
		$condition .= db_quote(' AND ?:categories.company_id = ?i', SUPPLIER_ID);
	}//endif
}//end fn_tsp_supplier_commissions_get_categories

/***********
 *
 * Function to get category data based on the supplier
 * don't allow a supplier to see any other categories but their own
 *
 ***********/
function fn_tsp_supplier_commissions_get_category_data(&$category_id, $field_list, $join, $lang_code)
{
	if (defined('SUPPLIER_ID')) 
	{
		$company_id = db_get_field("SELECT `company_id` FROM ?:categories WHERE `category_id` = ?i", $category_id);
		
		if (SUPPLIER_ID != $company_id)
		{
			$category_id = 0;
		}//endif
	}//endif
}//end fn_tsp_supplier_commissions_get_category_data

/***********
 *
 * Function to get the field data for supplier
 *
 ***********/
function fn_tsp_supplier_commissions_get_company_data_post(&$company_data)
{
	$company_id = $company_data['company_id'];
	
	$supplier_fields = db_get_fields("SELECT `field_id` FROM ?:profile_fields WHERE `section` = ?s", Registry::get('tspsc_supplier_section'));
	
	foreach ($supplier_fields as $field_id) 
	{	
		$value = db_get_field("SELECT `value` FROM ?:profile_fields_data WHERE `object_id` = ?i AND `field_id` = ?i AND `object_type` = ?s", $company_id, $field_id, Registry::get('tspsc_supplier_section'));
		
		$company_data['fields'][$field_id] = $value;
		
	}//endforeach
}//end fn_tsp_supplier_commissions_get_company_data_post

/***********
 *
 * Function to get product data based on the supplier
 * don't allow a supplier to see any other products but their own
 *
 ***********/
function fn_tsp_supplier_commissions_get_product_data($product_id, $field_list, $join, $auth, $lang_code, &$condition)
{
	if (defined('SUPPLIER_ID')) 
	{
		$condition .= " AND ?:products.company_id = ".SUPPLIER_ID;
	}//endif
}//end fn_tsp_supplier_commissions_get_product_data

/***********
 *
 * Function to get product features based on the supplier
 * don't allow a supplier to see any other product features but their own
 *
 ***********/
function fn_tsp_supplier_commissions_get_product_features($fields, $join, &$condition, $params)
{
	if (defined('SUPPLIER_ID')) 
	{
		$condition .= " AND pf.company_id = ".SUPPLIER_ID;
	}//endif
}//end fn_tsp_supplier_commissions_get_product_features

/***********
 *
 * Function to get product features based on the supplier
 * don't allow a supplier to see any other product features but their own
 *
 ***********/
function fn_tsp_supplier_commissions_get_product_features_post(&$data, $params, $has_ungroupped)
{
	// Double check to make sure only the suppliers features are shown
	if (defined('SUPPLIER_ID')) 
	{
		foreach ($data as $k => $v) 
		{		
			$company_id = db_get_field("SELECT `company_id` FROM ?:product_features WHERE `feature_id` = ?i", $k);
			
			if (SUPPLIER_ID != $company_id) 
			{
				unset($data[$k]);
			}//endif
		
		}//endforeach
	}//endif
}//end fn_tsp_supplier_commissions_get_product_features_post


/***********
 *
 * Function to get product filters based on the supplier
 * allow supplier to see only their product filters
 *
 ***********/
function fn_tsp_supplier_commissions_get_product_filters_before_select($fields, $join, &$condition, $group_by, $sorting, &$limit, $params, $lang_code)
{
	if (defined('SUPPLIER_ID')) 
	{
		$condition .= db_quote(' AND ?:product_filters.company_id = ?i', SUPPLIER_ID);

		$total = db_get_field("SELECT COUNT(*) FROM ?:product_filters LEFT JOIN ?:product_filter_descriptions ON ?:product_filter_descriptions.lang_code = ?s AND ?:product_filter_descriptions.filter_id = ?:product_filters.filter_id LEFT JOIN ?:product_features_descriptions ON ?:product_features_descriptions.feature_id = ?:product_filters.feature_id AND ?:product_features_descriptions.lang_code = ?s LEFT JOIN ?:product_features ON ?:product_features.feature_id = ?:product_filters.feature_id WHERE 1 ?p", $lang_code, $lang_code, $condition);
		$limit = fn_paginate($params['page'], $total, Registry::get('settings.Appearance.admin_elements_per_page'), false, 'filters');
		
		// if the total is null then do not display pagination
		if (empty($total)) 
		{
			Registry::get('view')->assign('pagination', '');
			Registry::get('view')->assign('pagination_objects', '');
		}//endif
	}//endif
}//end fn_tsp_supplier_commissions_get_product_filters_before_select

/***********
 *
 * Function to get product filters based on the supplier
 * allow supplier to see only their product filters
 *
 ***********/
function fn_tsp_supplier_commissions_get_product_filters_post(&$filters, $params, $lang_code)
{
	if (defined('SUPPLIER_ID')) 
	{
		foreach ($filters as $k => $v) 
		{		
			$company_id = db_get_field("SELECT `company_id` FROM ?:product_filters WHERE `filter_id` = ?i", $k);
			
			if (SUPPLIER_ID != $company_id) 
			{
				unset($filters[$k]);
			}//endif
		
		}//endforeach
	}//endif
}//end fn_tsp_supplier_commissions_get_product_filters_post

/***********
 *
 * Function to get product options based on the supplier
 * allow supplier to see only their products options
 *
 ***********/
function fn_tsp_supplier_commissions_get_product_global_options_before_select($params, $fields, &$condition, $join)
{
	if (defined('SUPPLIER_ID')) 
	{
		$condition .= db_quote(' AND ?:product_options.company_id = ?i', SUPPLIER_ID);
	}//endif
}//end fn_tsp_supplier_commissions_get_product_global_options_before_select

/***********
 *
 * Function to get product options based on the supplier
 * allow supplier to see only their products options
 *
 ***********/
function fn_tsp_supplier_commissions_get_product_global_options_post(&$data, $params, &$total)
{
	if (defined('SUPPLIER_ID')) 
	{
		foreach ($data as $k => $v) 
		{		
			$company_id = db_get_field("SELECT `company_id` FROM ?:product_options WHERE `option_id` = ?i", $v['option_id']);
			
			if (SUPPLIER_ID != $company_id) 
			{
				unset($data[$k]);
			}//endif
		
		}//endforeach

		$total = db_get_field("SELECT COUNT(*) FROM ?:product_options WHERE company_id = ?i", SUPPLIER_ID);
	}//endif
}//end fn_tsp_supplier_commissions_get_product_global_options_post

/***********
 *
 * Function to get categories based on the supplier
 * allow supplier to see only their products
 *
 ***********/
function fn_tsp_supplier_commissions_get_products($params, $fields, $sortings, &$condition, $join, $sorting, $group_by, $lang_code)
{
	if (defined('SUPPLIER_ID')) 
	{
		$condition .= db_quote(' AND products.company_id = ?i', SUPPLIER_ID);
	}//endif
}//end fn_tsp_supplier_commissions_get_products

/***********
 *
 * Modified by LGC - fn_tsp_supplier_commissions_order_notification
 * Called after all emails sent, send emails to suppliers notifying them of their commissions if any
 *
 ***********/
function fn_tsp_supplier_commissions_order_notification(&$order_info, $order_statuses, &$force_notification)
{	
	$suppliers = array();

	foreach ($order_info['items'] as $k => $prod) 
	{
		$product_company = $prod['company_id'];
		
		// if the product code is formated correctly and is a supplier
		// product then get the supplier id and start storing the data
		if (!empty($product_company)) 
		{
			$supplier_id = $product_company;			
			$company_info = fn_get_company_data($supplier_id, 'EN', false);
			
			// If the supplier was found continue
			if (!empty($company_info)) 
			{
				$suppliers[$supplier_id] = 0; //shipping cost is set to 0
				
				$commission_data = db_get_row("SELECT * FROM ?:addon_tsp_supplier_commissions WHERE `supplier_id` = ?i AND `order_id` = ?i AND `product_id` = ?i", $supplier_id, $order_info['order_id'], $prod['product_id']);
				$order_info['items'][$k]['company_id'] = $supplier_id;
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
				$supplier = fn_get_company_data($supplier_id, 'EN', false);
				
				Registry::get('view_mail')->assign('shipping_cost', $shipping_cost);
				Registry::get('view_mail')->assign('supplier_id', $supplier_id);
				Registry::get('view_mail')->assign('order_status', fn_get_status_data($order_info['status'], STATUSES_ORDER, $order_info['order_id'], $supplier['lang_code']));
				Registry::get('view_mail')->assign('profile_fields', fn_get_profile_fields('I', '', $supplier['lang_code']));

				// Send a copy to the supplier
				fn_send_mail($supplier['email'], Registry::get('settings.Company.company_orders_department'), 'addons/tsp_supplier_commissions/commission_notification_subj.tpl', 'addons/tsp_supplier_commissions/commission_notification.tpl', '', $supplier['lang_code'], Registry::get('settings.Company.company_orders_department'));

				// Send a copy to the staff
				fn_send_mail(Registry::get('settings.Company.company_orders_department'), Registry::get('settings.Company.company_orders_department'), 'addons/tsp_supplier_commissions/commission_notification_subj.tpl', 'addons/tsp_supplier_commissions/commission_notification.tpl', '', $supplier['lang_code'], Registry::get('settings.Company.company_orders_department'));
			}//endif
		}//endforeach
	}//endif
}//end fn_tsp_supplier_commissions_order_notification

/***********
 *
 * Function to create a category for a supplier
 * the parent id is always set to the SLicEx category if no parent selected
 *
 ***********/
function fn_tsp_supplier_commissions_update_category_pre(&$category_data, &$category_id, $lang_code)
{
	if (defined('SUPPLIER_ID')) 
	{	
		//Check to make sure the user has permissions to update this category
		// if they do not have permission set the category id to null so no featue gets updated
		if (!empty($category_id)) 
		{
			$company_id = db_get_field("SELECT `company_id` FROM ?:categories WHERE `category_id` = ?i", $category_id);
			
			if (SUPPLIER_ID != $company_id)
				$category_id = 0;
		}//endif
	
		foreach ($category_data as $cat) 
		{		
			if (empty($category_data['parent_id']))
			{
				$category_data['parent_id'] = 257;
			}//endif

			$category_data['usergroup_ids'] = 0;
			$category_data['company_id'] = SUPPLIER_ID;
		
		}//endforeach
	}//endif
}//end fn_tsp_supplier_commissions_update_category_pre

/***********
 *
 * Function to save/update the field data for supplier
 *
 ***********/
function fn_tsp_supplier_commissions_update_company($company_data, $company_id, $lang_code) {

	if (!empty($_REQUEST['company_data']['fields'])) 
	{			
		$company_exists = db_get_field("SELECT `value` FROM ?:profile_fields_data WHERE `object_id` = ?i AND `object_type` = ?s", $company_id, Registry::get('tspsc_supplier_section'));
		
		$sql_opt = "INSERT";
		
		if ($company_exists) 
		{
			$sql_opt = "REPLACE";
		}//endif
		
		$fields = $_REQUEST['company_data']['fields'];
		foreach ($fields as $id => $value)
		{
			$data = array (
				'object_type' => Registry::get('tspsc_supplier_section'),
				'object_id' => $company_id,
				'field_id' => $id,
				'value' => $value
			);
			db_query("$sql_opt INTO ?:profile_fields_data ?e", $data);
		}//endforeach
	}//endif
	
}//end fn_tsp_supplier_commissions_update_company

/***********
 *
 * Function to create/update a product feature for a supplier
 * the supplier id is always set to the current supplier
 *
 ***********/
function fn_tsp_supplier_commissions_update_product_feature_pre(&$feature_data, &$feature_id, $lang_code)
{
	if (defined('SUPPLIER_ID')) 
	{	
		//Check to make sure the user has permissions to update this feature
		// if they do not have permission set the feature id to null so no featue gets updated
		if (!empty($feature_id)) 
		{
			$company_id = db_get_field("SELECT `company_id` FROM ?:product_features WHERE `feature_id` = ?i", $feature_id);
			
			if (SUPPLIER_ID != $company_id)
			{
				$feature_id = 0;
			}//endif
		}//endif
	
		foreach ($feature_data as $fd) 
		{		
			$feature_data['company_id'] = SUPPLIER_ID;
		
		}//endforeach
	}//endif
}//end fn_tsp_supplier_commissions_update_product_feature_pre

/***********
 *
 * Function to create a product feature for a supplier
 * the supplier id is always set to the current supplier
 *
 ***********/
function fn_tsp_supplier_commissions_update_product_filter(&$filter_data, &$filter_id, $lang_code)
{
	if (defined('SUPPLIER_ID')) 
	{	
		//Check to make sure the user has permissions to update this filter
		// if they do not have permission set the filter id to null so no filter gets updated
		if (!empty($filter_id) && !empty($_REQUEST['filter_id'])) 
		{
			$company_id = db_get_field("SELECT `company_id` FROM ?:product_filters WHERE `filter_id` = ?i", $filter_id);
			
			if (SUPPLIER_ID != $company_id)
			{
				$filter_id = 0;
			}//endif
		}//endif
	
		foreach ($filter_data as $fd) 
		{		
			$filter_data['company_id'] = SUPPLIER_ID;
		
		}//endforeach
		
		// BUG: Product filters is not apart of fn.catalog.php set of functions with pre and post hooks
		// the filter has to be updated manually with the supplier id
		db_query("UPDATE ?:product_filters SET `company_id` = ?i WHERE `filter_id` = ?i", SUPPLIER_ID, $filter_id);
	}//endif
}//end fn_tsp_supplier_commissions_update_product_filter

/***********
 *
 * Function to create/upate a product feature for a supplier
 * the supplier id is always set to the current supplier
 *
 ***********/
function fn_tsp_supplier_commissions_update_product_option_pre(&$option_data, &$option_id, $lang_code)
{
	if (defined('SUPPLIER_ID')) 
	{	
		//Check to make sure the user has permissions to update this feature
		// if they do not have permission set the feature id to null so no featue gets updated
		if (!empty($option_id)) 
		{
			$company_id = db_get_field("SELECT `company_id` FROM ?:product_optons WHERE `option_id` = ?i", $option_id);
			
			if (SUPPLIER_ID != $company_id)
			{
				$option_id = 0;
			}
		}//endif
	
		foreach ($option_data as $od) 
		{		
			$option_data['company_id'] = SUPPLIER_ID;
		
		}//endforeach
	}//endif
}//end fn_tsp_supplier_commissions_update_product_option_pre

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

/***********
 *
 * Function to create/update a product for a supplier
 * the supplier id is always set to the current supplier
 *
 ***********/
function fn_tsp_supplier_commissions_update_product_pre(&$product_data, &$product_id, $lang_code)
{
	if (defined('SUPPLIER_ID')) 
	{
		//Check to make sure the user has permissions to update this product
		// if they do not have permission set the product id to null so no product gets updated
		if (!empty($product_id)) 
		{
			$company_id = db_get_field("SELECT `company_id` FROM ?:products WHERE `product_id` = ?i", $product_id);
			
			if (SUPPLIER_ID != $company_id)
			{
				$product_id = 0;
			}//endif
		}//endif

		foreach ($product_data as $pro) 
		{		
			$product_data['company_id'] = SUPPLIER_ID;
		
		}//endforeach
	}//endif
}//end fn_tsp_supplier_commissions_update_product_pre

?>