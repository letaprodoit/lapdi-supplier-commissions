<?php
/*
 * TSP Supplier Commissions CS-Cart Addon
 *
 * @package		TSP Supplier Commissions CS-Cart Addon
 * @filename	config.php
 * @version		2.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/03/01
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Configuraton file for addon
 * 
 */

if ( !defined('BOOTSTRAP') )	{ die('Access denied');	}

use Tygh\Registry;

require_once 'lib/fn.supplier_commissions.php';

Registry::set('tspsc_supplier_commissions_statuses_long', array(
	'O' => array(
		'status_id' 	=> 1,
		'status' 		=> 'O',
		'color_status'	=> 'O',
		'type' 			=> 'A',
		'is_default' 	=> 'Y',
		'description' 	=> 'Open',
		'email_subj' 	=> 'has been created',
		'email_header' 	=> 'Your commission has been created successfully.',
		'lang_code' 	=> 'en',
	),
	'P' => array(
		'status_id' 	=> 2,
		'status' 		=> 'S',
		'color_status'	=> 'B',
		'type' 			=> 'A',
		'is_default' 	=> 'Y',
		'description' 	=> 'Pending',
		'email_subj' 	=> 'is pending',
		'email_header' 	=> 'Your commission is pending.',
		'lang_code' 	=> 'en',
	),
	'S' => array(
		'status_id' 	=> 3,
		'status' 		=> 'C',
		'color_status'	=> 'P',
		'type' 			=> 'A',
		'is_default' 	=> 'Y',
		'description' 	=> 'Completed',
		'email_subj' 	=> 'has been completed',
		'email_header' 	=> 'Your commission has been processed successfully.',
		'lang_code' 	=> 'en',
	),
));

Registry::set('tspsc_supplier_commissions_statuses_short', array(
		'O' => 'Open',
		'P' => 'Pending',
		'S' => 'Successful'
));

Registry::set('tspsc_supplier_commissions_status_params', array(
	'color' => array (
		'type' => 'color',
		'label' => 'color'
	),
	'notify' => array (
		'type' => 'checkbox',
		'label' => 'notify_customer',
		'default_value' => 'Y'
	),
));


Registry::set('tspsc_supplier_section', 'P');

// Field types: 
// admin_only (hidden on customer side), type [S (selectbox), H(selectbox, hash values),T (textarea),I (input),D (date),C (checkbox), U (URL)], 
// options (single dim array), options_func (function name to call at run-time, use with type H or S), 
// title, name (field name), value, icon (used with type U), width (with of field), class (css), hint, readonly (show text only)
Registry::set('tspsc_product_data_field_names', array(
	'tspsc_is_supplier_membership' => array(
		'type' => 'C'
	)
));

// Commission tier information
Registry::set('tspsc_discount_tier_count', 11);
Registry::set('tspsc_discount_tier_start', 0.50);
Registry::set('tspsc_discount_tier_increment_by', -0.05);
Registry::set('tspsc_discount_price_increment_by', 5.00);

// Product tier information
Registry::set('tspsc_quantity_tier_count', 10);
Registry::set('tspsc_quantity_tier_start', 5);
Registry::set('tspsc_quantity_tier_increment_by', 5);
Registry::set('tspsc_quantity_price_increment_by', 5.00);

// Fields necessary for processing supplier commissions
Registry::set('tspsc_payment_credit_id', fn_tspsc_get_paypal_pro_cc_processor(true));
Registry::set('tspsc_payment_credit_test_id', fn_tspsc_get_paypal_pro_cc_processor(false));

// Fields necessary for storing product data
Registry::set('tspsc_product_company_field_id', fn_tspsc_get_product_field_id('tspsc_product_company_field_id'));
Registry::set('tspsc_product_paypal_field_id', fn_tspsc_get_product_field_id('tspsc_product_paypal_field_id'));
Registry::set('tspsc_product_quantity_field_id', fn_tspsc_get_product_field_id('tspsc_product_quantity_field_id'));
Registry::set('tspsc_product_discount_field_id', fn_tspsc_get_product_field_id('tspsc_product_discount_field_id'));

// Fields necessary for storing supplier data
Registry::set('tspsc_supplier_paypal_field_id', fn_tspsc_get_profile_field_id('tspsc_supplier_paypal_field_id'));
Registry::set('tspsc_supplier_quantity_field_id', fn_tspsc_get_profile_field_id('tspsc_supplier_quantity_field_id'));
Registry::set('tspsc_supplier_discount_field_id', fn_tspsc_get_profile_field_id('tspsc_supplier_discount_field_id'));
?>
