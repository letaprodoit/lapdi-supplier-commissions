<?php
/*
 * TSP Supplier Commissions CS-Cart Addon
 *
 * @package		TSP Supplier Commissions CS-Cart Addon
 * @filename	init.php
 * @version		2.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/03/01
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Hook initializer for addon
 * 
 */

if ( !defined('BOOTSTRAP') )	{ die('Access denied');	}

fn_register_hooks(
	'finish_payment',
	'delete_order',
	'delete_product_post',
	'order_notification',
	'update_product_post'
);

?>