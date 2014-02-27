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
 * @brief		Init post hook for admin area
 * 
 */

if ( !defined('BOOTSTRAP') )	{ die('Access denied');	}

use Tygh\Registry;

// Define supplier id
if (!empty($_SESSION['auth']['supplier_id']))
{
	fn_define('SUPPLIER_ID', $_SESSION['auth']['supplier_id']);
}//endif

?>