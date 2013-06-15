<?php
/*
 * TSP Supplier Commissions CS-Cart Addon
 *
 * @package		TSP Supplier Commissions CS-Cart Addon
 * @filename	init.post.php
 * @version		1.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/03/01
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Init post hook for admin area
 * 
 */

if ( !defined('AREA') )	{ die('Access denied');	}

// Define supplier id
if (!empty($_SESSION['auth']['company_id']))
{
	fn_define('SUPPLIER_ID', $_SESSION['auth']['company_id']);
}//endif

?>