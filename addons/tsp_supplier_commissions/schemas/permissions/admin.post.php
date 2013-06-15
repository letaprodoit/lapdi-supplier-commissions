<?php
/*
 * TSP Supplier Commissions CS-Cart Addon
 *
 * @package		TSP Supplier Commissions CS-Cart Addon
 * @filename	admin.post.php
 * @version		1.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/03/01
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Admin permissions post for admin area menus
 * 
 */

if ( !defined('AREA') ) { die('Access denied'); }

$schema['supplier_commissions'] = array (
	'permissions' => 'manage_supplier_commissions',
);

$schema['tools']['modes']['update_status']['param_permissions']['table_names']['addon_tsp_supplier_commissions'] = 'manage_supplier_commissions';

$auth = & $_SESSION['auth'];
$supplier_id = $auth['company_id'];

// Only suppliers have these restrictions
if (!empty($supplier_id))
{
	// Tags should be hidden but they arent this is a bug fix
	$schema['tags'] = array (
		'permissions' => 'manage_supplier_commissions',
	);
	
	// Tabs should be hidden but they arent this is a bug fix
	$schema['tabs'] = array (
		'permissions' => 'manage_supplier_commissions',
	);
	
	$schema['tools']['modes']['update_status']['param_permissions']['table_names']['tags'] = 'manage_supplier_commissions';
	$schema['tools']['modes']['update_status']['param_permissions']['table_names']['product_tabs'] = 'manage_supplier_commissions';
}//endif
?>