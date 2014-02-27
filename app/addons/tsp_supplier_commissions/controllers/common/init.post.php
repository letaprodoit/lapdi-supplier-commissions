<?php
/*
 * TSP Supplier Commissions CS-Cart Addon
 *
 * @package		TSP Supplier Commissions CS-Cart Addon
 * @filename	init.post.php
 * @version		2.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/03/01
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Init post hook for both customer and admin area
 * 
 */

if ( !defined('BOOTSTRAP') )	{ die('Access denied');	}

set_include_path(DIR_ROOT . '/app/addons/tsp_supplier_commissions/lib' . PATH_SEPARATOR . get_include_path());

require_once 'PayPal.php';
require_once 'PayPal/Profile/Handler/Array.php';
require_once 'PayPal/Profile/API.php';
require_once 'PayPal/Type/MassPayRequestType.php';
require_once 'PayPal/Type/MassPayRequestItemType.php';
require_once 'PayPal/Type/MassPayResponseType.php';
require_once 'Multi.php';
require_once 'fn.supplier_commissions.php';

?>