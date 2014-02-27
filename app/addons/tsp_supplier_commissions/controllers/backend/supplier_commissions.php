<?php
/*
 * TSP Supplier Commissions CS-Cart Addon
 *
 * @package		TSP Supplier Commissions CS-Cart Addon
 * @filename	supplier_comissions.post.php
 * @version		2.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/03/01
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Dispatch for addon
 * 
 */

if ( !defined('BOOTSTRAP') )	{ die('Access denied');	}

define('DEBUG', false);

use Tygh\Registry;

//
// Handle posts to dispatch
//

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	fn_trusted_vars('supplier_commissions_data', 'supplier_commissions', 'delete');
	$suffix = '';
	if ($mode == 'do_delete') 
	{
		if (!empty($_REQUEST['commission_ids'])) 
		{
			foreach ($_REQUEST['commission_ids'] as $id) 
			{
				fn_tspsc_delete_supplier_commissions('id',$id);
			}//endforeach
		}//endif

		$suffix = '.manage';
	}//endif

	if ($mode == 'process_commissions') 
	{
		if (!empty($_REQUEST['commission_ids'])) 
		{
			$commissions = $_REQUEST['commission_ids'];

			if (!fn_is_empty($commissions)) 
			{
				$list_commissions = array();
				foreach ($commissions as $com_id) 
				{
					$list_commissions[$com_id] = array (
							'commission_id' => $com_id,
							'type' => 'C'
					);
				}//endforeach

				$cache_file = 'supplier_commissions_' . md5(uniqid(rand()));

				fn_mkdir(DIR_CACHE_MISC . 'tsp_supplier_commissions/');
				if (fn_put_contents(DIR_CACHE_MISC . 'tsp_supplier_commissions/' . $cache_file, serialize($list_commissions))) 
				{
					return array(CONTROLLER_STATUS_OK, "supplier_commissions.batch_process_commissions?cache_file=$cache_file");
				}//endif
				else 
				{
					$msg = __('cannot_write_file');
					$msg = str_replace('[file]', DIR_CACHE_MISC . 'tsp_supplier_commissions/' . $cache_file, $msg);
					fn_set_notification('E', __('error'), $msg);
				}//endelse
			}//endif
		}//endif

		$suffix = '.manage';
	}//endif

	return array(CONTROLLER_STATUS_OK, "supplier_commissions$suffix");
}//endif


//
// Handle views for dispatch
//
if ($mode == 'manage') 
{

	list($supplier_commissions, $search) = fn_tspsc_get_supplier_commissions($_REQUEST);

	Registry::get('view')->assign('supplier_commissions', $supplier_commissions);
	Registry::get('view')->assign('search', $search);

	list($company_list) = fn_get_companies(array('status' => 'A'), $auth);

	$_company_list = array();
	foreach ($company_list as $item) 
	{
		$_company_list[$item['company_id']] = $item['company'];
	}//endforeach

	Registry::get('view')->assign('company_list', $_company_list);

}//endif
elseif ($mode == 'update' && !empty($_REQUEST['commission_id'])) 
{
	$commission = db_get_row("SELECT * FROM ?:addon_tsp_supplier_commissions WHERE id = ?i", $_REQUEST['commission_id']);
	
	if (empty($commission)) 
	{
		return array(CONTROLLER_STATUS_NO_PAGE);	
	}//endif
	
	if (!empty($commission['supplier_id'])) 
	{
		$commission['supplier'] = fn_tspsc_get_supplier_data($commission['supplier_id']);
	}//endif
		
	if (!empty($commission['product_id'])) 
	{
		$commission['product'] = fn_get_product_data($commission['product_id'],$auth,CART_LANGUAGE,'product,product_code');
	}//endif
		
	if (!empty($commission['order_id'])) 
	{
		$commission['order'] = fn_get_order_info($commission['order_id']);
	}//endif

	$commission['earned'] = floatval($commission['product_price'] * $commission['product_quantity'] * $commission['discount']);
	$commission['product_total'] = floatval($commission['product_price'] * $commission['product_quantity']);
	
	// [Breadcrumbs]
	fn_add_breadcrumb(__('tspsc_supplier_commissions'), "supplier_commissions.manage.reset_view");
	fn_add_breadcrumb(__('search_results'), "supplier_commissions.manage.last_view");
	// [/Breadcrumbs]

	Registry::get('view')->assign('commission', $commission);
	
}//endelseif
elseif ($mode == 'delete') 
{
	if (!empty($_REQUEST['commission_id'])) 
	{
		fn_tspsc_delete_supplier_commissions('id',$_REQUEST['commission_id']);
	}//endif

	return array(CONTROLLER_STATUS_REDIRECT, "supplier_commissions.manage");
	
}//endelseif
elseif ($mode == 'batch_process_commissions' && !empty($_REQUEST['cache_file'])) 
{
	$data = fn_get_contents(DIR_CACHE_MISC . 'tsp_supplier_commissions/' . $_REQUEST['cache_file']);
	if (!empty($data)) 
	{
		$data = @unserialize($data);
	}//endif

	if (is_array($data)) 
	{	
		list($complete, $msg) = fn_tspsc_masspay_commissions($data);
		fn_rm(DIR_CACHE_MISC . 'tsp_supplier_commissions/' . $_REQUEST['cache_file']);

		if (!$complete) 
		{
			fn_set_notification('E', __('error'), __('tspsc_commission_not_processed')."<br><b>".__('reason').":</b> ".$msg);
		}//endif
		else 
		{
			fn_set_notification('N', __('notice'), __('tspsc_commission_processed'));
		}//endelse
	}//endif 
	else 
	{
		fn_set_notification('W', __('warning'), __('tspsc_no_commissions_to_process'));
	}//endelse

	return array(CONTROLLER_STATUS_OK, "supplier_commissions.manage");

}//endelseif
elseif ($mode == 'charge') 
{
	if (!empty($_REQUEST['commission_id'])) 
	{
		$data[$_REQUEST['commission_id']] = array (
				'commission_id' => $_REQUEST['commission_id'],
				'type' => 'C'
		);
		
		fn_tspsc_masspay_commissions($data);
		
		fn_set_notification('N', __('notice'), __('tspsc_commission_charged'));
	}//endif

	return array(CONTROLLER_STATUS_REDIRECT, "subscriptions.manage");

}//endelseif
elseif ($mode == 'process_commission')
{

	if (!empty($_REQUEST['commission_id'])) 
	{

		$list_commissions[$_REQUEST['commission_id']] = array (
				'commission_id' => $_REQUEST['commission_id'],
				'type' => 'C'
		);

		$cache_file = 'supplier_commissions_' . md5(uniqid(rand()));

		fn_mkdir(DIR_CACHE_MISC . 'tsp_supplier_commissions/');
		if (fn_put_contents(DIR_CACHE_MISC . 'tsp_supplier_commissions/' . $cache_file, serialize($list_commissions))) 
		{
			return array(CONTROLLER_STATUS_OK, "supplier_commissions.batch_process_commissions?cache_file=$cache_file");
		}//endif
		else 
		{
			$msg = __('cannot_write_file');
			$msg = str_replace('[file]', DIR_CACHE_MISC . 'tsp_supplier_commissions/' . $cache_file, $msg);
			fn_set_notification('E', __('error'), $msg);
		}//endelse
	}//endif

	return array(CONTROLLER_STATUS_REDIRECT, "supplier_commissions.manage");
}//endelseif
?>