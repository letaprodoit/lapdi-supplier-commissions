<?php
/*
 * TSP Supplier Commissions CS-Cart Addon
 *
 * @package		TSP Supplier Commissions CS-Cart Addon
 * @filename	products.post.php
 * @version		1.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @brief		Products post hook for admin area
 * 
 */


if ( !defined('AREA') )	{ die('Access denied');	}

if ($_SERVER['REQUEST_METHOD']	== 'POST') 
{
	return;
}//endif

$commission_id = $_REQUEST['commission_id'];
$supplier_id = $_REQUEST['supplier_id'];
$product_id = $_REQUEST['product_id'];
$params = $_REQUEST;

// View Supplier Products: Get products from the commissions talbe
if ($mode == 'manage' && !empty($commission_id)) 
{
	$supplier_id = db_get_field("SELECT `supplier_id` FROM ?:addon_tsp_supplier_commissions WHERE `id` = ?i", $commission_id);

	$product_ids = db_get_fields("SELECT DISTINCT `product_id` FROM ?:products WHERE `company_id` = ?i", $supplier_id);
	
	if (empty($product_ids))
	{
		$product_ids[] = null;
	}//endif

	$params['pid'] = $product_ids;
	
	list($products, $search, $product_count) = fn_get_products($params, Registry::get('settings.Appearance.admin_products_per_page'), DESCR_SL);
	fn_gather_additional_products_data($products, array('get_icon' => true, 'get_detailed' => true, 'get_options' => false, 'get_discounts' => false));

	$view->assign('products', $products);
	$view->assign('search', $search);
	$view->assign('product_count', $product_count);
}//endif
// View Supplier Products: Get products by supplier id
elseif ($mode == 'manage' && !empty($supplier_id)) 
{
	$product_ids = db_get_fields("SELECT DISTINCT `product_id` FROM ?:products WHERE `company_id` = ?i", $supplier_id);
	
	if (empty($product_ids))
	{
		$product_ids[] = null;
	}//endif
	
	$params['pid'] = $product_ids;
	
	list($products, $search, $product_count) = fn_get_products($params, Registry::get('settings.Appearance.admin_products_per_page'), DESCR_SL);
	fn_gather_additional_products_data($products, array('get_icon' => true, 'get_detailed' => true, 'get_options' => false, 'get_discounts' => false));

	$view->assign('products', $products);
	$view->assign('search', $search);
	$view->assign('product_count', $product_count);
}//endelseif
// View Supplier Products: Restore product addon settings
elseif ($mode == 'update' && !empty($product_id)) 
{
	// Only show these options to the primary administrator
	if (!defined('SUPPLIER_ID')) 
	{
		// Get current product data
		$product_data = fn_get_product_data($product_id, $auth, DESCR_SL, '', true, true, true, true, false, true, false);
		$product_metadata = db_get_hash_array("SELECT * FROM ?:addon_tsp_supplier_commissions_product_metadata WHERE `product_id` = $product_id", 'field_name');
		
		if (!empty($product_data)) 
		{		
			$field_names = Registry::get('tspsc_product_data_field_names');
			$product_addon_fields = array();
			
			foreach ($field_names as $field_name => $fdata) 
			{			
				$value = "";
				
				// if the value is not set in the metadata it is ok
				// to display an empty value
				if (array_key_exists($field_name, $product_metadata)) 
				{
					$value = $product_metadata[$field_name]['value'];
				}//endif
				
				if (!empty($fdata['options_func']))
				{
					$fdata['options'] = call_user_func($fdata['options_func']);
				}//endif

				$product_addon_fields[] = array(
					'title' => fn_get_lang_var($field_name),
					'name' => $field_name,
					'value' => $value,
					'icon' => $fdata['icon'],
					'width' => $fdata['width'],
					'class' => $fdata['class'],
					'type' => $fdata['type'],
					'hint' => $fdata['hint'],
					'options' => $fdata['options'],
					'readonly' => $fdata['readonly']
				);
			
			}//endforeach
			
			$view->assign('tspsc_product_addon_fields', $product_addon_fields);				
			
		}//endif
	}//endif
	else
	{		
		// Hide tabs that should not be updated by suppliers
		$tabs = Registry::get('navigation.tabs');
		unset($tabs['product_tabs']);
		unset($tabs['reward_points']);
		unset($tabs['subscribers']);
	
		Registry::set('navigation.tabs', $tabs);
		
	}//endelse
	
}//endelseif	
// When displaying the form to add a new product make sure that the addon fields
// get added values will be null
elseif ($mode == 'add') 
{	
	// Only show these options to the primary administrator
	if (!defined('SUPPLIER_ID')) 
	{	
		$field_names = Registry::get('tspsc_product_data_field_names');
		$product_addon_fields = array();
		
		foreach ($field_names as $field_name => $fdata)
		{
		
			$value = "";
			
			if (!empty($fdata['options_func'])) 
			{
				$fdata['options'] = call_user_func($fdata['options_func']);
			}//endif

			$product_addon_fields[] = array(
				'title' => fn_get_lang_var($field_name),
				'name' => $field_name,
				'value' => $value,
				'icon' => $fdata['icon'],
				'width' => $fdata['width'],
				'class' => $fdata['class'],
				'type' => $fdata['type'],
				'hint' => $fdata['hint'],
				'options' => $fdata['options'],
				'readonly' => $fdata['readonly']
			);
		
		}//endforeach
		
		$view->assign('tspsc_product_addon_fields', $product_addon_fields);
	}//endif
	else
	{
		// Set the supplier ID
		$product_data['company_id'] = SUPPLIER_ID;
		$view->assign('product_data', $product_data);
					
	}//endelse
	
}//endelseif
?>