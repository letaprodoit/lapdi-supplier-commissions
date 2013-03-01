<?php
/*
 * TSP Supplier Commissions CS-Cart Addon
 *
 * @package		TSP Supplier Commissions CS-Cart Addon
 * @filename	companies.post.php
 * @version		1.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @brief		Companies post hook for admin area
 * 
 */

if ( !defined('AREA') )	{ die('Access denied');	}


if ($mode == 'update' || $mode == 'add')
{
	$view->assign('supplier_section', Registry::get('tspsc_supplier_section'));

}//endif
?>