<?php
/*
 * TSP Supplier Commissions CS-Cart Addon
 *
 * @package		TSP Supplier Commissions CS-Cart Addon
 * @filename	fn.supplier_commissions.php
 * @version		2.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/03/01
 * @copyright	Copyright ¬© 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Helper functions for addon
 * 
 */

if ( !defined('BOOTSTRAP') )	{ die('Access denied');	}

define('DEBUG', false);

use Tygh\Registry;
use Tygh\Mailer;
use Tygh\Navigation\LastView;

//
// [Functions - Addon.xml Handlers]
//

/***********
 *
 * Function to install product option variants
 *
 ***********/
function fn_tspsc_install_product_option_variants ($option_id, $option_key, $tier, $max_tiers, $price_inc, $tier_inc, $sprintf = '%s')
{
	$mod = 0.00;
	$pos = 0;
	
	for ($i = 0; $i < $max_tiers; $i++) 
	{	
		$var = db_query('INSERT INTO ?:product_option_variants ?e', array('position' => $pos, 'option_id' => $option_id, 'modifier' => $mod));
		
		if (!empty($var)) 
		{
			// Store the global option fields
			db_query("INSERT INTO ?:addon_tsp_supplier_commissions_product_field_metadata (`key`,`option_id`,`variant_id`) VALUES 
			('$option_key',$option_id,$var)");
			
			// Install option variant descriptions
			db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('variant_id' => $var, 'variant_name' => sprintf($sprintf,$tier)));
	
		}//endif

		$pos += 5;
		$mod += $price_inc;
		$tier += $tier_inc;
			
	}//endfor
}//end fn_tspsc_install_product_option_variants

/***********
 *
 * Function to install product fields
 *
 ***********/
function fn_tspsc_install_product_fields () 
{	
	$default_option_fields = array(
		'tspsc_product_company_field_id',
		'tspsc_product_paypal_field_id',
		'tspsc_product_quantity_field_id',
		'tspsc_product_discount_field_id'
	);
	
	foreach ( $default_option_fields as $option_field_key )
	{
		// check to see if the field is already in the table (the global option already added) if it is not
		// then add it
		if ( !fn_tspsc_get_product_field_id($option_field_key) )
		{
			if ($option_field_key == 'tspsc_product_company_field_id')
			{
				// Install the global option fields
				$company_option_id = db_query('INSERT INTO ?:product_options ?e', array('company_id' => 1, 'position' => 70, 'option_type' => 'I', 'inventory' => 'N', 'required' => 'Y', 'status' => 'A', 'regexp' => ''));

				// Store the global option fields
				db_query("INSERT INTO ?:addon_tsp_supplier_commissions_product_field_metadata (`key`,`option_id`) VALUES ('tspsc_product_company_field_id',$company_option_id)");
	
				// Install descriptions
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'en', 'option_id' => $company_option_id, 'option_name' => 'Company Name', 'option_text' => '', 'description' => '', 'comment' => 'Enter in the name of your business.', 'inner_hint' => '', 'incorrect_message' => ''));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'es', 'option_id' => $company_option_id, 'option_name' => 'nombre de compañía', 'option_text' => '', 'description' => '', 'comment' => 'Introduzca el nombre de su negocio.', 'inner_hint' => '', 'incorrect_message' => ''));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'fr', 'option_id' => $company_option_id, 'option_name' => 'Nom de l\'entreprise', 'option_text' => '', 'description' => '', 'comment' => 'Entrez le nom de votre entreprise.', 'inner_hint' => '', 'incorrect_message' => ''));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'el', 'option_id' => $company_option_id, 'option_name' => 'Ónoma etaireías', 'option_text' => '', 'description' => '', 'comment' => 'Eiságete to ónoma ti̱s epicheíri̱sí̱s sas.', 'inner_hint' => '', 'incorrect_message' => ''));
				
			}//endif
			elseif ($option_field_key == 'tspsc_product_paypal_field_id')
			{
				// Install the global option fields
				$paypal_email_option_id = db_query('INSERT INTO ?:product_options ?e', array('company_id' => 1, 'position' => 71, 'option_type' => 'I', 'inventory' => 'N', 'required' => 'Y', 'status' => 'A', 'regexp' => '[a-z0-9!#$%&'."'".'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'."'".'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?'));

				// Store the global option fields
				db_query("INSERT INTO ?:addon_tsp_supplier_commissions_product_field_metadata (`key`,`option_id`) VALUES ('tspsc_product_paypal_field_id',$paypal_email_option_id)");
	
				// Install descriptions
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'en', 'option_id' => $paypal_email_option_id, 'option_name' => 'PayPal Email Address', 'option_text' => '', 'description' => '', 'comment' => 'Enter in the email address where you would like to receive your commission payments.', 'inner_hint' => '', 'incorrect_message' => 'Invalid email address.'));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'es', 'option_id' => $paypal_email_option_id, 'option_name' => 'PayPal E-mail', 'option_text' => '', 'description' => '', 'comment' => 'Introduzca la dirección de correo electrónico donde desea recibir sus pagos de comisiones.', 'inner_hint' => '', 'incorrect_message' => 'Email inválido.'));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'fr', 'option_id' => $paypal_email_option_id, 'option_name' => 'PayPal Email', 'option_text' => '', 'description' => '', 'comment' => 'Entrez l\'adresse email à laquelle vous souhaitez recevoir vos paiements de commissions.', 'inner_hint' => '', 'incorrect_message' => 'E-mail valide.'));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'el', 'option_id' => $paypal_email_option_id, 'option_name' => 'PayPal Email', 'option_text' => '', 'description' => '', 'comment' => 'Eiságete ti̱ diéf̱thynsi̱ i̱lektronikoú tachydromeíou ópou tha thélate na lávete pli̱ro̱més promi̱theió̱n sas.', 'inner_hint' => '', 'incorrect_message' => 'Mi̱ énkyro email.'));
				
			}//end elseif
			elseif ($option_field_key == 'tspsc_product_quantity_field_id')
			{
				// Install the global option fields
				$quantity_option_id = db_query('INSERT INTO ?:product_options ?e', array('company_id' => 1, 'position' => 72, 'option_type' => 'S', 'inventory' => 'N', 'required' => 'Y', 'status' => 'A'));
				
				// Store the global option fields
				db_query("INSERT INTO ?:addon_tsp_supplier_commissions_product_field_metadata (`key`,`option_id`) VALUES ('tspsc_product_quantity_field_id',$quantity_option_id)");
	
				// Install descriptions
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'en', 'option_id' => $quantity_option_id, 'option_name' => 'Maximum Number of Products', 'option_text' => '', 'description' => '', 'comment' => 'Select the maximum number of distinct products you would like to sell at one time in our store. Reminder: Once a product has sold all licenses you can add a new product in its place.', 'inner_hint' => '', 'incorrect_message' => ''));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'es', 'option_id' => $quantity_option_id, 'option_name' => 'Número máximo de productos', 'option_text' => '', 'description' => '', 'comment' => 'Seleccione el número máximo de productos distintos que le gustaría vender a la vez en nuestra tienda. Recordatorio: Una vez que un producto se ha vendido todas las licencias se puede añadir un nuevo producto en su lugar.', 'inner_hint' => '', 'incorrect_message' => ''));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'fr', 'option_id' => $quantity_option_id, 'option_name' => 'Nombre maximum de produits', 'option_text' => '', 'description' => '', 'comment' => 'Sélectionnez le nombre maximum de produits distincts que vous souhaitez vendre à un moment dans notre magasin. Rappel: Une fois qu\'un produit a vendu toutes les licences, vous pouvez ajouter un nouveau produit à sa place.', 'inner_hint' => '', 'incorrect_message' => ''));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'el', 'option_id' => $quantity_option_id, 'option_name' => 'Mégistos arithmós to̱n Proïónto̱n', 'option_text' => '', 'description' => '', 'comment' => 'Epiléxte to mégisto arithmó to̱n diakritó̱n proïónta pou tha thélate na poulí̱sete se éna chróno sto katásti̱má mas . Ypenthýmisi̱ :̱ Ótan éna proïón échei poulí̱sei óles tis ádeies boreíte na prosthésete éna néo proïón sti̱ thési̱ tou.', 'inner_hint' => '', 'incorrect_message' => ''));
				
				// Insert quanity variants
				fn_tspsc_install_product_option_variants($quantity_option_id, 'tspsc_product_quantity_field_vars',
				Registry::get('tspsc_quantity_tier_start'),
				Registry::get('tspsc_quantity_tier_count'),
				Registry::get('tspsc_quantity_price_increment_by'),
				Registry::get('tspsc_quantity_tier_increment_by'),
				'%d Products');
			}//end elseif
			elseif ($option_field_key == 'tspsc_product_discount_field_id')
			{
				// Install the global option fields
				$discount_option_id = db_query('INSERT INTO ?:product_options ?e', array('company_id' => 1, 'position' => 73, 'option_type' => 'S', 'inventory' => 'N', 'required' => 'Y', 'status' => 'A'));
				
				// Store the global option fields
				db_query("INSERT INTO ?:addon_tsp_supplier_commissions_product_field_metadata (`key`,`option_id`) VALUES ('tspsc_product_discount_field_id',$discount_option_id)");
	
				// Install descriptions
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'en', 'option_id' => $discount_option_id, 'option_name' => 'Discount (%)', 'option_text' => '', 'description' => '', 'comment' => 'Select the percentage amount that will be deducted from the price of each license you own that is sold in our store. This amount selected is what The Software People will receive for selling your license in our store.', 'inner_hint' => '', 'incorrect_message' => ''));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'es', 'option_id' => $discount_option_id, 'option_name' => 'descuento (%)', 'option_text' => '', 'description' => '', 'comment' => 'Seleccione la cantidad de porcentaje que se descuenta del precio de cada licencia de su propiedad que se vende en nuestra tienda. Esta cantidad seleccionada es lo que van a recibir el programa del Pueblo para la venta de su licencia en nuestra tienda.', 'inner_hint' => '', 'incorrect_message' => ''));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'fr', 'option_id' => $discount_option_id, 'option_name' => 'réduction (%)', 'option_text' => '', 'description' => '', 'comment' => 'Sélectionner le montant du pourcentage qui sera déduit du prix de chaque licence vous êtes le propriétaire qui est vendu dans notre magasin. Ce montant choisi est ce que le logiciel personnes recevront pour la vente de votre licence dans notre magasin.', 'inner_hint' => '', 'incorrect_message' => ''));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'el', 'option_id' => $discount_option_id, 'option_name' => 'ékpto̱si̱ (%)', 'option_text' => '', 'description' => '', 'comment' => 'Epiléxte to posostó pou tha prépei na afaireítai apó ti̱n timí̱ ti̱s káthe ádeia pou katéchete kai pou po̱leítai sto katásti̱má mas . Af̱tó to epilegméno posó eínai af̱tó pou oi ánthro̱poi logismikó tha lávoun ádeia gia ti̱n pó̱li̱si̱ sas sto katásti̱má mas .', 'inner_hint' => '', 'incorrect_message' => ''));
				
				// Insert discount variants
				fn_tspsc_install_product_option_variants($discount_option_id, 'tspsc_product_discount_field_vars',
				Registry::get('tspsc_discount_tier_start'),
				Registry::get('tspsc_discount_tier_count'),
				Registry::get('tspsc_discount_price_increment_by'),
				Registry::get('tspsc_discount_tier_increment_by'),
				'%0.2f');
			}//end elseif
		}//end if
	}//end foreach
}//end fn_tspsc_install_product_fields


/***********
 *
 * Function to install profile field values
 *
 ***********/
function fn_tspsc_install_profile_field_values ($option_id, $option_key, $tier, $max_tiers, $tier_inc, $sprintf = '%s')
{
	$pos = 0;
	
	for ($i = 0; $i < $max_tiers; $i++) 
	{	
		$object_id = db_query("INSERT INTO ?:profile_field_values ?e", array('field_id' => $option_id, 'position' => $pos));
		
		if (!empty($object_id)) 
		{
			// Store the profile option field
			db_query("INSERT INTO ?:addon_tsp_supplier_commissions_profile_field_metadata (`key`,`option_id`,`variant_id`) VALUES ('$option_key',$option_id,$object_id)");

			db_query("INSERT INTO ?:profile_field_descriptions ?e", array('object_id' => $object_id, 'description' => sprintf($sprintf,$tier), 'object_type' => 'V', 'lang_code' => 'en'));
			db_query("INSERT INTO ?:profile_field_descriptions ?e", array('object_id' => $object_id, 'description' => sprintf($sprintf,$tier), 'object_type' => 'V', 'lang_code' => 'es'));
			db_query("INSERT INTO ?:profile_field_descriptions ?e", array('object_id' => $object_id, 'description' => sprintf($sprintf,$tier), 'object_type' => 'V', 'lang_code' => 'fr'));
		}//endif

		$pos += 5;
		$tier += $tier_inc;
		
	}//endfor
}//end fn_tspsc_install_profile_field_values

/***********
 *
 * Function to install profile fields
 *
 ***********/
function fn_tspsc_install_profile_fields ()
{	
	$default_option_fields = array(
		'tspsc_supplier_paypal_field_id',
		'tspsc_supplier_quantity_field_id',
		'tspsc_supplier_discount_field_id',
	);
	
	foreach ( $default_option_fields as $option_field_key )
	{
		// check to see if the field is already in the table (the global option already added) if it is not
		// then add it
		if ( !fn_tspsc_get_profile_field_id($option_field_key) )
		{
			if ($option_field_key == 'tspsc_supplier_paypal_field_id')
			{
				// Install the global option fields
				$supplier_paypal_email_id = db_query('INSERT INTO ?:profile_fields ?e', array('field_name' => 'paypal_email', 'position' => 97, 'profile_show' => 'Y', 'field_type' => 'I', 'profile_required' => 'Y', 'section' => Registry::get('tspsc_supplier_section')));
				
				// Store the profile fields
				db_query("INSERT INTO ?:addon_tsp_supplier_commissions_profile_field_metadata (`key`,`option_id`) VALUES 
					('tspsc_supplier_paypal_field_id',$supplier_paypal_email_id)");
				
				// Install field descriptions
				db_query("INSERT INTO ?:profile_field_descriptions (`object_id`,`description`,`object_type`,`lang_code`) VALUES 
					($supplier_paypal_email_id,'PayPal Email','F','en'),
					($supplier_paypal_email_id,'PayPal Email','F','es'),
					($supplier_paypal_email_id,'PayPal Email Address','F','fr')");
			}//end if
			elseif ($option_field_key == 'tspsc_supplier_quantity_field_id')
			{
				// Install the global option fields
				$supplier_quantity_id = db_query('INSERT INTO ?:profile_fields ?e', array('field_name' => 'product_quantity', 'position' => 98, 'profile_show' => 'Y', 'field_type' => 'S', 'profile_required' => 'Y', 'section' => Registry::get('tspsc_supplier_section')));
				
				// Store the profile fields
				db_query("INSERT INTO ?:addon_tsp_supplier_commissions_profile_field_metadata (`key`,`option_id`) VALUES 
					('tspsc_supplier_quantity_field_id',$supplier_quantity_id)");
				
				// Install field descriptions
				db_query("INSERT INTO ?:profile_field_descriptions (`object_id`,`description`,`object_type`,`lang_code`) VALUES 
					($supplier_quantity_id,'Maximum Number of Products','F','en'),
					($supplier_quantity_id,'N√∫mero m√°ximo de productos','F','es'),
					($supplier_quantity_id,'Nombre maximum de produits','F','fr')");
				
				// Install field values & descriptions for tiers
				fn_tspsc_install_profile_field_values($supplier_quantity_id, 'tspsc_supplier_quantity_field_vars',
					Registry::get('tspsc_quantity_tier_start'), 
					Registry::get('tspsc_quantity_tier_count'), 
					Registry::get('tspsc_quantity_tier_increment_by'),
					'%d Products');
			}//end elseif
			elseif ($option_field_key == 'tspsc_supplier_discount_field_id')
			{
				// Install the global option fields
				$supplier_discount_id = db_query('INSERT INTO ?:profile_fields ?e', array('field_name' => 'product_discount', 'position' => 99, 'profile_show' => 'Y', 'field_type' => 'S', 'profile_required' => 'Y', 'section' => Registry::get('tspsc_supplier_section')));
				
				// Store the profile fields
				db_query("INSERT INTO ?:addon_tsp_supplier_commissions_profile_field_metadata (`key`,`option_id`) VALUES 
					('tspsc_supplier_discount_field_id',$supplier_discount_id)");

				// Install field descriptions
				db_query("INSERT INTO ?:profile_field_descriptions (`object_id`,`description`,`object_type`,`lang_code`) VALUES 
					($supplier_discount_id,'Discount (%)','F','en'),
					($supplier_discount_id,'Descuento (%)','F','es'),
					($supplier_discount_id,'Fournisseur (%)','F','fr')");
			
				// Install field values & descriptions for product quantity
				fn_tspsc_install_profile_field_values($supplier_discount_id, 'tspsc_supplier_discount_field_vars',
					Registry::get('tspsc_discount_tier_start'), 
					Registry::get('tspsc_discount_tier_count'), 
					Registry::get('tspsc_discount_tier_increment_by'),
					'%0.2f');
			}//end elseif
		}//end if
	}//end foreach
}//end fn_tspsc_install_profile_fields

/***********
 *
 * Function to uninstall profile field metadata
 *
 ***********/
function fn_tspsc_uninstall_profile_field_metadata () 
{
	if (Registry::get('addons.tsp_supplier_commissions.delete_commission_data') == 'Y') 
	{
		// Get the profile fields
		$profile_fields = db_get_fields("SELECT `option_id` FROM ?:addon_tsp_supplier_commissions_profile_field_metadata");
		$profile_section = Registry::get('tspsc_supplier_section');
		
		if (!empty($profile_fields) && is_array($profile_fields)) 
		{
			// Delete the profile fields from all tables
			foreach ($profile_fields as $val)
			{
				db_query("DELETE FROM ?:profile_fields WHERE `field_id` = ?i AND `section` = ?s", $val, $profile_section);
				db_query("DELETE FROM ?:profile_field_values WHERE `field_id` = ?i", $val);
				db_query("DELETE FROM ?:profile_field_descriptions WHERE `object_type` = 'F' AND `object_id` = ?i", $val);
				db_query("DELETE FROM ?:profile_fields_data WHERE `field_id` = ?i AND `object_type` = ?s", $val, $profile_section);
			}//endforeach
		}//endif
	
		// Get the profile field values
		$profile_field_options = db_get_fields("SELECT `variant_id` FROM ?:addon_tsp_supplier_commissions_profile_field_metadata");
	
		if (!empty($profile_field_options) && is_array($profile_field_options)) 
		{
			// Delete the profile field values from all tables
			foreach ($profile_field_options as $val)
			{
				db_query("DELETE FROM ?:profile_field_values WHERE `value_id` = ?i", $val); // just to be sure to get it all
				db_query("DELETE FROM ?:profile_field_descriptions WHERE `object_type` = 'V' AND `object_id` = ?i", $val);
			}//endforeach
		}//endif
		
		// After all data removed drop the storage table
		db_query("DROP TABLE IF EXISTS `?:addon_tsp_supplier_commissions_profile_field_metadata`");
	}
}//end fn_tspsc_uninstall_profile_field_metadata

/***********
 *
 * Function to uninstall languages
 *
 ***********/
function fn_tspsc_uninstall_languages ()
{
	if (Registry::get('addons.tsp_supplier_commissions.delete_commission_data') == 'Y') 
	{
		$names = array(
			'tsp_supplier_commissions',	
			'tspsc_commission',
			'tspsc_commission_charged',
			'tspsc_commission_info',
			'tspsc_commission_not_charged',
			'tspsc_commission_not_processed',
			'tspsc_commission_processed',
			'tspsc_editing_supplier_commission',
			'tspsc_is_supplier_membership',
			'tspsc_no_commissions_to_process',
			'tspsc_pay_selected_commissions',
			'tspsc_store_earned',
			'tspsc_store_earnings',
			'tspsc_total_earned',
			'tspsc_total_supplier_commissions',
			'tspsc_supplier',
			'tspsc_suppliers',
			'tspsc_suppliers_menu_description',
			'tspsc_supplier_commissions',
			'tspsc_supplier_commissions_menu_description',
			'tspsc_supplier_commission',
			'tspsc_view_supplier_products',
			'tspsc_view_supplier_orders'
		);
		
		if (!empty($names)) 
		{
			db_query("DELETE FROM ?:language_values WHERE name IN (?a)", $names);
		}//endif
	}//endif
}//end fn_tspsc_uninstall_languages

/***********
 *
 * Function to uninstall product metadata
 *
 ***********/
function fn_tspsc_uninstall_product_metadata()
{
	if (Registry::get('addons.tsp_supplier_commissions.delete_commission_data') == 'Y') 
	{
		db_query("DROP TABLE IF EXISTS `?:addon_tsp_supplier_commissions_product_metadata`");
	}//endif
}//end fn_tspsc_uninstall_product_metadata

/***********
 *
 * Function to uninstall product fields metadata
 *
 ***********/
function fn_tspsc_uninstall_product_field_metadata ()
{
	if (Registry::get('addons.tsp_supplier_commissions.delete_commission_data') == 'Y') 
	{
		// Get the product options
		$product_options = db_get_fields("SELECT `option_id` FROM ?:addon_tsp_supplier_commissions_product_field_metadata");
		
		if (!empty($product_options) && is_array($product_options))
		{
			// Delete the product options from all tables
			foreach ($product_options as $val)
			{
				db_query("DELETE FROM ?:product_options WHERE `option_id` = ?i", $val);
				db_query("DELETE FROM ?:product_options_descriptions WHERE `option_id` = ?i", $val);
			}//endforeach
		}//endif
		
		// Get the Product options variants
		$product_option_variants = db_get_fields("SELECT `variant_id` FROM ?:addon_tsp_supplier_commissions_product_field_metadata");
		
		if (!empty($product_option_variants) && is_array($product_option_variants))
		{
			// Delete the product options variants from all tables
			foreach ($product_option_variants as $val)
			{
				db_query("DELETE FROM ?:product_option_variants WHERE `variant_id` = ?i", $val);
				db_query("DELETE FROM ?:product_option_variants_descriptions WHERE `variant_id` = ?i", $val);
			}//endforeach
		}//endif
	
		// After all data removed drop the storage table
		db_query("DROP TABLE IF EXISTS `?:addon_tsp_supplier_commissions_product_field_metadata`");
	}//end if
}//end fn_tspsc_uninstall_product_field_metadata

/***********
 *
 * Function to uninstall commission data
 *
 ***********/
function fn_tspsc_uninstall_commission_data ()
{
	if (Registry::get('addons.tsp_supplier_commissions.delete_commission_data') == 'Y') 
	{
		db_query("DROP TABLE IF EXISTS `?:addon_tsp_supplier_commissions`");
	}//endif
}//end fn_tspsc_uninstall_commission_data

//
// [Functions - General]
//

/***********
 *
 * Function to delete commission data for a supplier	
 *
 ***********/
function fn_tspsc_delete_supplier($supplier_id)
{
	fn_tspsc_delete_supplier_commissions('supplier_id', $supplier_id);
	fn_tspsc_delete_supplier_profile_data($supplier_id);
}//end fn_tsp_supplier_commissions_delete_company

/***********
 *
 * Function to delete the supplier's profile field data
 *
 ***********/
function fn_tspsc_delete_supplier_profile_data($supplier_id)
{
	db_query("DELETE FROM ?:profile_fields_data WHERE `object_id` = ?i AND `object_type` = ?s", $supplier_id, Registry::get('tspsc_supplier_section'));
}//end fn_tspsc_delete_supplier_profile_data

/***********
 *
 * Function to delete a supplier commission
 *
 ***********/
function fn_tspsc_delete_supplier_commissions($key, $value)
{
	db_query("DELETE FROM ?:addon_tsp_supplier_commissions WHERE $key = ?s", $value);
}//end fn_tspsc_delete_supplier_commissions

/***********
 *
 * Function to update product metadata
 *
 ***********/
// Function to display a mass pay error
function fn_tspsc_display_masspay_errors($errObj)
{
	return "{$errObj->ShortMessage} ({$errObj->LongMessage})";
}//end fn_tspsc_display_masspay_errors

function fn_tspsc_find_supplier(&$supplier_data) 
{
	return db_get_field("SELECT `supplier_id` FROM ?:suppliers WHERE `company_id` = ?i AND `name` = ?s AND `email` = ?s AND `phone` = ?s", $supplier_data['company_id'], $supplier_data['name'], $supplier_data['email'], $supplier_data['phone']);
}//end fn_tspsc_find_supplier

/***********
 *
 * There is one (1) product field that is required for any supplier to receive a commission
 * When signing up to be a supplier they must purchase a membership that has a supplier
 * paypal email address
 *
 ***********/
function fn_tspsc_get_product_field_id($key)
{
	$field_id = null;
	
	$table = '?:addon_tsp_supplier_commissions_product_field_metadata';
	$table_exists = db_get_row("SHOW TABLES LIKE '$table'");

	if ($table_exists) 
	{
		$id = db_get_field("SELECT `option_id` FROM `?:addon_tsp_supplier_commissions_product_field_metadata` WHERE `key` = '$key'");
		
		if (!empty($id))
		{
			$field_id = $id;
		}//endif
	}//endif
	
	return $field_id;
}//end fn_tspsc_get_product_field_id

/***********
 *
 * There are two (2) product fields that are required for any supplier to receive a commission
 * They must have a valid paypal email and they must have a discount selected
 *
 ***********/
function fn_tspsc_get_profile_field_id($key)
{
	$field_id = null;
	
	$table = '?:addon_tsp_supplier_commissions_profile_field_metadata';
	$table_exists = db_get_row("SHOW TABLES LIKE '$table'");

	if ($table_exists) 
	{
		$id = db_get_field("SELECT `option_id` FROM `?:addon_tsp_supplier_commissions_profile_field_metadata` WHERE `key` = '$key'");
		
		if (!empty($id)) 
		{
			$field_id = $id;
		}//endif
	}//endif
	
	return $field_id;
}//end fn_tspsc_get_profile_field_id

/***********
 *
 * Get the appropriate paypal processor id given a live or test
 * status
 *
 ***********/
function fn_tspsc_get_paypal_pro_cc_processor($live)
{
	$payment_id = null;
	
	//get the payment processor for PayPal Pro
	$processor_id = db_get_field("SELECT `processor_id` FROM ?:payment_processors WHERE `processor` LIKE '%PayPal Pro%' AND `processor_template` LIKE '%cc.tpl%'");
	
	if (!empty($processor_id)) 
	{
	
		$pdata = db_get_row("SELECT * FROM ?:payments WHERE `processor_id` = $processor_id AND `status` = 'A'");
		$param_data = unserialize($pdata['params']);
		
		if ($live && $param_data['mode'] == 'live') 
		{
			$payment_id = $pdata['payment_id'];
		}//endif
		elseif (!$live && $param_data['mode'] == 'test') 
		{
			$payment_id = $pdata['payment_id'];
		}//endelseif
		
	}//endif
	
	return $payment_id;
}//end fn_tspsc_get_paypal_pro_cc_processor

/***********
 *
 * Get any supplier commission field given the commission id and field name
 *
 ***********/
function fn_tspsc_get_supplier_commission_field($id,$field)
{
	return db_get_field("SELECT `$field` FROM ?:addon_tsp_supplier_commissions WHERE `id` = ?i",$id);
}//end fn_tspsc_get_supplier_commission_field

/***********
 *
 * Primary function for getting supplier commissions given paramaters for searching
 * or an empty param for all commissons
 *
 ***********/
function fn_tspsc_get_supplier_commissions($params, $items_per_page = 0)
{
	// Init filter
	$params = LastView::instance()->update('supplier_commissions', $params);

	$default_params = array (
			'page' => 1,
			'items_per_page' => $items_per_page,
			'company' => Registry::get('runtime.company_id'),
	);
	
	$params = array_merge($default_params, $params);

	// Define fields that should be retrieved
	$fields = array (
		'?:addon_tsp_supplier_commissions.*',
		'?:suppliers.company_id',
		'?:suppliers.name',
		'?:suppliers.email'
	);

	// Define sort fields
	$sortings = array (
		'company_id' 	=> "?:suppliers.supplier_id",
		'email' 		=> '?:suppliers.email',
		'name' 			=> "?:suppliers.name",
		'discount' 		=> "?:addon_tsp_supplier_commissions.discount",
		'total' 		=> "?:addon_tsp_supplier_commissions.total",
		'total_paid' 	=> "?:addon_tsp_supplier_commissions.total_paid",
		'date_created' 	=> "?:addon_tsp_supplier_commissions.date_created",
		'status' 		=> "?:addon_tsp_supplier_commissions.status",
	);

	$directions = array (
		'asc' => 'asc',
		'desc' => 'desc'
	);

	if (empty($params['sort_order']) || empty($directions[$params['sort_order']])) 
	{
		$params['sort_order'] = 'desc';
	}//endif

	if (empty($params['sort_by']) || empty($sortings[$params['sort_by']])) 
	{
		$params['sort_by'] = 'date_created';
	}//endif

	$sorting = (is_array($sortings[$params['sort_by']]) ? implode(' ' . $directions[$params['sort_order']] . ', ', $sortings[$params['sort_by']]) : $sortings[$params['sort_by']]) . " " . $directions[$params['sort_order']];

	// Reverse sorting (for usage in view)
	$params['sort_order'] = $params['sort_order'] == 'asc' ? 'desc' : 'asc';

	$join = $condition = '';

	if (!empty($params['company'])) 
	{
		$condition .= db_quote(" AND ?:addon_tsp_supplier_commissions.company_id = ?i", $params['company']);
	}//endif
	
	if (!empty($params['email'])) 
	{
		$condition .= db_quote(" AND ?:suppliers.email LIKE ?l", "%{$params['email']}%");
	}//endif

	if (!empty($params['id'])) 
	{
		$condition .= db_quote(" AND ?:addon_tsp_supplier_commissions.id = ?i", $params['id']);
	}//endif
	elseif (!empty($params['supplier_id'])) 
	{
		$condition .= db_quote(" AND ?:addon_tsp_supplier_commissions.id = ?i", $params['supplier_id']);
	}//endif
	
	if (!empty($params['commission_id'])) 
	{
		$condition .= db_quote(" AND ?:addon_tsp_supplier_commissions.id = ?i", $params['commission_id']);
	}//endif

	if (!empty($params['period']) && $params['period'] != 'A') 
	{
		list($params['time_from'], $params['time_to']) = fn_create_periods($params);

		$condition .= db_quote(" AND (?:addon_tsp_supplier_commissions.date_created >= ?i AND ?:addon_tsp_supplier_commissions.date_created <= ?i)", $params['time_from'], $params['time_to']);
	}//endif

	if (!empty($params['status'])) 
	{
		$condition .= db_quote(" AND ?:addon_tsp_supplier_commissions.status = ?s", $params['status']);
	}//endif

	if (!empty($params['order_id'])) 
	{
		$condition .= db_quote(" AND ?:addon_tsp_supplier_commissions.order_id = ?i", $params['order_id']);
	}//endif

	if (isset($params['amount_from']) && fn_is_numeric($params['amount_from'])) 
	{
		$condition .= db_quote(" AND ?:addon_tsp_supplier_commissions.total >= ?d", trim($params['amount_from']));
	}//endif

	if (isset($params['amount_to']) && fn_is_numeric($params['amount_to'])) 
	{
		$condition .= db_quote(" AND ?:addon_tsp_supplier_commissions.total <= ?d", trim($params['amount_to']));
	}//endif

	if (empty($items_per_page)) 
	{
		$items_per_page = Registry::get('settings.Appearance.admin_elements_per_page');
	}//endif

	$total = db_get_field("SELECT COUNT(*) FROM ?:addon_tsp_supplier_commissions LEFT JOIN ?:suppliers ON ?:addon_tsp_supplier_commissions.supplier_id = ?:suppliers.supplier_id WHERE 1 $condition");
	$limit = db_paginate($params['page'], $total, $items_per_page);

	$supplier_commissions = db_get_hash_array("SELECT " . implode(', ', $fields) . " FROM ?:addon_tsp_supplier_commissions LEFT JOIN ?:suppliers ON ?:addon_tsp_supplier_commissions.supplier_id = ?:suppliers.supplier_id WHERE 1 $condition ORDER BY $sorting $limit", 'id');
	
	// Always get the paypal_email
	foreach ($supplier_commissions as $k => $v) 
	{
		$supplier_commissions[$k]['paypal_email'] = fn_tspsc_get_supplier_paypal_email($v['supplier_id']);
	}//endforeach
	
	LastView::instance()->processResults('supplier_commissions', $supplier_commissions, $params, $items_per_page);

	return array($supplier_commissions, $params);
}//end fn_tspsc_get_supplier_commissions

/***********
 *
 * Get supplier data given the supplier id
 *
 ***********/
function fn_tspsc_get_supplier_data($supplier_id)
{
	if (empty($supplier_id)) 
	{
		return false;
	}//endif

	$supplier = db_get_row("SELECT * FROM ?:suppliers WHERE `supplier_id` = ?i AND `status` = 'A'", $supplier_id);

	// if a supplier was found lets add the paypal email address to the array
	// if a paypal email is not found then make it null to alert the store
	// that the supplier needs a paypal email instead of using the wrong one
	if (!empty($supplier)) 
	{
		$supplier['paypal_email'] = fn_tspsc_get_supplier_paypal_email($supplier_id);
		$supplier['discount'] = fn_tspsc_get_supplier_discount($supplier_id);
	}//endif
		
	return empty($supplier) ? false : $supplier;
}//end fn_tspsc_get_supplier_data

/***********
 *
* Function to get the field data for supplier
*
***********/
function fn_tspsc_get_supplier_data_post(&$supplier_data)
{
	$supplier_id = $supplier_data['supplier_id'];

	$supplier_fields = db_get_hash_array("SELECT * FROM ?:profile_fields WHERE `section` = '".Registry::get('tspsc_supplier_section')."'", 'field_name');
	
	foreach ($supplier_fields as $field_name => $field )
	{
		extract($field);
		
		$supplier_data['fields'][$field_name] = $supplier_fields[$field_name];

	}//endforeach
}//end fn_tspsc_get_supplier_data_post


/***********
 *
 * Get supplier commission given the supplier id
 *
 ***********/
function fn_tspsc_get_commission_discount($commission_id)
{
	$discount = 0;
	
	if (empty($commission_id)) 
	{
		return floatval($discount);
	}//endif

	$discount = db_get_field("SELECT `discount` FROM ?:addon_tsp_supplier_commissions WHERE `id` = ?i", $commission_id);
	
	return floatval($discount);
}//end fn_tspsc_get_commission_discount


/***********
 *
 * Get commission discount captured at the time of sale
 *
 ***********/
function fn_tspsc_get_supplier_discount($supplier_id)
{
	$discount = 0;
	
	if (empty($supplier_id)) 
	{
		return floatval($discount);
	}//endif
	
	$discount_option = db_get_field("SELECT `value` FROM ?:profile_fields_data WHERE `object_id` = ?i AND `object_type` = ?s AND `field_id` = ?i", $supplier_id, Registry::get('tspsc_supplier_section'), Registry::get('tspsc_supplier_discount_field_id'));
	
	$discount = db_get_field("SELECT `description` FROM ?:profile_field_descriptions WHERE `object_id` = ?i AND `object_type` = 'V' and `lang_code` = ?s", $discount_option, CART_LANGUAGE);
	
	return floatval($discount);
}//end fn_tspsc_get_supplier_discount

/***********
 *
 * Get supplier email given the supplier id
 *
 ***********/
function fn_tspsc_get_supplier_paypal_email($supplier_id)
{
	$paypal_email = '';
	
	if (empty($supplier_id)) 
	{
		return $paypal_email;
	}//endif
	
	$paypal_email = db_get_field("SELECT `value` FROM ?:profile_fields_data WHERE `object_id` = ?i AND `object_type` = ?s AND `field_id` = ?i", $supplier_id, Registry::get('tspsc_supplier_section'), Registry::get('tspsc_supplier_paypal_field_id'));	
	
	return $paypal_email;
}//end fn_tspsc_get_supplier_paypal_email

/***********
 *
 * Function to masspay commissions using paypal
 *
 ***********/
function fn_tspsc_masspay_commissions($commissions)
{	
	$return_masspay_complete = false;
	$return_msg = "";

	$date = fn_date_format(time());
		
	$payment_id = Registry::get('tspsc_payment_credit_test_id');
	
	// Determine if MassPay is using a live or test account
	$use_live_account = (Registry::get('addons.tsp_supplier_commissions.use_live_account') == 'Y') ? true : false;

	// If we are using a live account use the live credit id
	if ($use_live_account) $payment_id = Registry::get('tspsc_payment_credit_id');

	// Determine if MassPay is enabled
	$masspay_enabled = (Registry::get('addons.tsp_supplier_commissions.masspay_enabled') == 'Y') ? true : false;
	
	// Get processor data
	$processor_data = fn_get_processor_data($payment_id);
	
	if (!empty($commissions) && !empty($processor_data) && $masspay_enabled) 
	{	
		$subject = Registry::get('settings.Company.company_name').": Commission Payments for $date";
		$sandbox = ($processor_data['params']['mode'] == 'test') ? '.sandbox' : '';
		$environment = ($processor_data['params']['mode'] == 'test') ? 'sandbox' : 'live';
		
		$paypal_username 	= $processor_data['params']['username'];
		$paypal_password 	= $processor_data['params']['password'];
		$paypal_signature 	= $processor_data['params']['signature'];
		$paypal_currency 	= $processor_data['params']['currency'];
	
		$handler = ProfileHandler_Array::getInstance(array(
		            'username' => $paypal_username,
		            'certificateFile' => null,
		            'subject' => $subject,
		            'environment' => $environment));
		
		$pid = ProfileHandler::generateID();
		
		$profile = new APIProfile($pid, $handler);
		
		// Set up your API credentials, PayPal end point, and API version.
		
		$profile->setEnvironment($environment);
		$profile->setAPIUsername($paypal_username);
		$profile->setAPIPassword($paypal_password);
		$profile->setSignature($paypal_signature);
	    //$profile->setCertificateFile('my_cert_file_path');		
		//--------------------------------------------------
		
		$masspay_request = PayPal::getType('MassPayRequestType');
		$masspay_request->setVersion("51.0");
		
		// Set request-specific fields.
		$emailSubject =urlencode($subject);
		$receiverType = urlencode('EmailAddress'); // EmailAddress (ReceiverEmail in MassPayItem), PhoneNumber (ReceiverPhone in MassPayItem), or by UserID (ReceiverID in MassPayItem).
		$currencyID = urlencode($paypal_currency);							// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
		
		$receiversArray = array();
		
		foreach ($commissions as $comm_id => $comm) 
		{					
			// We want to make sure the data coming in is complete
			// so regardless of what is being sent to us get the data
			// again based on the id
			list($data, $null) = fn_tspsc_get_supplier_commissions(array('commission_id' => $comm_id));
			
			if (!empty ($data)) 
			{
				$commission_data = $data[$comm_id];
				
				$status = $commission_data['status'];
				$pp_email = $commission_data['paypal_email'];
				$total = $commission_data['total'];
				$product_id = $commission_data['product_id'];
				
				// Don't reprocess commissions, only process a commission if it is open
				if ($status == 'O' && !empty($pp_email) && !empty($total)) 
				{
					$commissions[$comm_id]['processed'] = true;

					$receiversArray[] = array(
						'receiverEmail' => $pp_email,
						'amount' => $total,
						'uniqueID' => $comm_id,
						'note' => "Commission Payment for Product #{$product_id}"
					);
				}//endif
				else
				{
					$commissions[$comm_id]['processed'] = false;
				}//endelse
				
			}//endif				
			
		}//endforeach
		
		$massPayItems = array();
		
		foreach($receiversArray as $i => $receiverData) 
		{
			$massPayItems[$i] = PayPal::getType('MassPayRequestItemType');
			$massPayItems[$i]->setReceiverEmail($receiverData["receiverEmail"]);
			$massPayItems[$i]->setNote($receiverData["note"]);
			$massPayItems[$i]->setUniqueId($receiverData["uniqueID"]);
		
			$amtType = PayPal::getType('BasicAmountType');
			$amtType->setattr('currencyID',$currencyID);
			$amtType->setval($receiverData["amount"],'iso-8859-1');
			$massPayItems[$i]->setAmount($amtType);
		}//endforeach
		
		$masspay_request->setEmailSubject($emailSubject);
		$masspay_request->setReceiverType($receiverType);
		
		// Create multiple occurences of MassPayRequestItem. as necessary.
		$multiItems = new MultiOccurs($masspay_request, 'MassPayRequestItem');
		$multiItems->setChildren($massPayItems);
		$masspay_request->setMassPayItem($multiItems);
		
		$caller = PayPal::getCallerServices($profile);
		
		// Execute SOAP request.
		$response = $caller->MassPay($masspay_request);
				
		switch($response->getAck()) 
		{
			case 'Success':
			case 'SuccessWithWarning':
		        $return_masspay_complete = true;
				if (DEBUG) print "DoMassPay Completed Successfully: " . print_r($response, true);
				break;
			case '':
				$return_msg = "Could not connect to the PayPal Server.";
				break;
			default:
				$return_msg = fn_tspsc_display_masspay_errors($response->getErrors());
				if (DEBUG) print "DoMassPay failed: ($return_msg)" . print_r($response, true);
				if (DEBUG) sleep(60);
				break;
		}//endswitch
		
		// Mark Commissions as Pending
		// Since Paypal needs to send a seperate process to us
		if ($return_masspay_complete) 
		{
			foreach ($commissions as $comm_id => $comm) 
			{
				if ($comm['processed'])
				{
					fn_tspsc_update_commission($comm_id, array('status' => 'P'));
				}//endif
			}//endforeach
		}//endif
		
	}//endif
	
	if (!$masspay_enabled)
	{
		$return_msg .= "<br>MassPay not enabled.";
	}//endif
	
	if (empty($commissions))
	{
		$return_msg .= "<br>No commissions selected.";
	}//endif
	
	if (empty($processor_data))
	{
		$return_msg .= "<br>Payment processor not valid.";
	}//endif
		
	return array($return_masspay_complete, $return_msg);
	
}//end fn_tspsc_masspay_commissions

/***********
 *
 * Funciton to mark a commission as paid when it comes back from paypal
 *
 ***********/
function fn_tspsc_mark_commission_paid($id,$transaction_id)
{
	$data = array(
		'status' => 'S',
		'transaction_id' => $transaction_id,
		'date_paid' => time()
	);
	
	fn_tspsc_update_commission($id, $data);
}//end fn_tspsc_mark_commission_paid

/***********
 *
 * Function to save a commission when a product is purchased
 *
 ***********/
function fn_tspsc_save_commissions(&$order_info)
{
	$commissions_enabled = (Registry::get('addons.tsp_supplier_commissions.commissions_enabled') == 'Y') ? true : false;
	
	if (!empty($order_info) && $commissions_enabled)
	{	
		$products = $order_info['products'];
	
		// Only pay commission if the order has been successfully processed
		if ($order_info['payment_info']['order_status'] == 'P') 
		{	
			foreach ($products as $prod) 
			{			
				// get product supplier info
				$supplier_id = $prod['supplier_id'];
				
				// if the product code is formated correctly and is a supplier
				// product then get the supplier id and start storing the data
				if (!empty($supplier_id)) 
				{				
					$supplier = !empty($supplier_id) ? fn_get_supplier_data($supplier_id) : array();
					
					// If the supplier was found continue
					if (!empty($supplier)) 
					{					
						$quantity = intval($prod['amount']);
						$price = floatval($prod['price']);
						$total = floatval($quantity * $price);
						
						$discount = fn_tspsc_get_supplier_discount($supplier_id);
						$commission = floatval($total - ($total * $discount));
						$company_id = db_get_field("SELECT company_id FROM ?:products WHERE product_id = ?i", $prod['product_id']);
						
						$data = array (
							'status' 			=> 'O',
							'company_id' 		=> $company_id,
							'order_id' 			=> $prod['order_id'],
							'product_id' 		=> $prod['product_id'],
							'supplier_id' 		=> $supplier_id,
							'product_price' 	=> $price,
							'product_quantity' 	=> $quantity,
							'discount' 			=> $discount,
							'total' 			=> $commission,
							'date_created' 		=> time()
						);
						
						$comm_id = db_get_field("SELECT `id` FROM ?:addon_tsp_supplier_commissions WHERE `order_id` = ?i AND `product_id` = ?i AND supplier_id = ?i", $prod['order_id'], $prod['product_id'], $supplier_id);
						
						// Prevent duplicate submissions if a commission is in the database with the same order, product and supplier
						// then don't add it again
						if (empty($comm_id))
						{
							db_query("INSERT INTO ?:addon_tsp_supplier_commissions ?e", $data);
						}//endif
					
					}//endif
				}//endif			
			}//endforeach
		}//endif
	}//endif
}//end fn_tspsc_save_commissions

/***********
 *
 * If a supplier membership is purchased copy the paypal email address from the product
 * and insert a new company into the database
 *
 ***********/
function fn_tspsc_save_supplier(&$order_info)
{
	if (!empty($order_info))
	{			
		// Only update company and the paypal email address if the order is processed
		if ($order_info['payment_info']['order_status'] == 'P') 
		{
			$product_paypal_email = null;
			$product_company_name = null;
			$product_discount_tier = null;
			$product_quantity_tier = null;
			
			$product_paypal_field_id = Registry::get('tspsc_product_paypal_field_id'); // associated with a supplier membership
			$product_company_field_id = Registry::get('tspsc_product_company_field_id'); // associated with a supplier membership
			$product_quantity_field_id = Registry::get('tspsc_product_quantity_field_id'); // associated with a supplier membership
			$product_discount_field_id = Registry::get('tspsc_product_discount_field_id'); // associated with a supplier membership
			
			foreach ($order_info['products'] as $null => $prod) 
			{			
				$product_id = $prod['product_id'];
				
				$is_supplier_membership = db_get_field("SELECT `value` FROM ?:addon_tsp_supplier_commissions_product_metadata WHERE `product_id` = ?i AND `field_name` = 'tspsc_is_supplier_membership'", $product_id);
				
				// if the product code is a supplier membership
				if ($is_supplier_membership == 'Y') 
				{									
					foreach ($prod['extra']['product_options_value'] as $k => $option) 
					{					
						if ($option['option_id'] == $product_paypal_field_id) 
						{
							$product_paypal_email = $option['value'];
						}//endif
						elseif ($option['option_id'] == $product_company_field_id)
						{
							$product_company_name = $option['value'];
						}//endelseif
						elseif ($option['option_id'] == $product_quantity_field_id) 
						{
							$product_quantity_tier = $option['value'];
						}//endelseif
						elseif ($option['option_id'] == $product_discount_field_id) 
						{
							$product_discount_tier = $option['value'];
						}//endelseif
						
					}//endforeach					
				}//endif
				
			}//endforeach
			
			// if this order contains a paypal adddress, discount and company name
			if (!empty($product_paypal_email) & !empty($product_company_name) & !empty($product_discount_tier)) 
			{		
				//insert user information into company record
				// company is stored in a table
				$data = array(
					'company_id' => $order_info['company_id'],
					'name' => $product_company_name,
					'address' => $order_info['b_address'],
					'city' => $order_info['b_city'],
					'state' => $order_info['b_state'],
					'zipcode' => $order_info['b_zipcode'],
					'country' => $order_info['b_country'],
					'email' => $order_info['email'],
					'phone' => $order_info['phone'],
					'fax' => $order_info['fax'],
					'timestamp' => time()
				);
				
				$supplier_id = $order_info['supplier_id'];
				$supplier_exists = db_get_field("SELECT `supplier_id` FROM ?:suppliers WHERE `supplier_id` = ?i", $supplier_id);
				
				if ($supplier_exists) 
				{
					db_query("REPLACE INTO ?:suppliers ?e WHERE `supplier_id` = ?i", $data, $supplier_id);
				}//endif
				else
				{
					$supplier_id = db_query('INSERT INTO ?:suppliers ?e', $data);
					$order_info['supplier_id'] = $supplier_ids; // Update order info with company_id
				}//endelse
				
				// paypal and discount or stored in profile fields
				if (!empty($supplier_id)) 
				{
					$supplier_paypal_field_id = Registry::get('tspsc_supplier_paypal_field_id');
					$supplier_quantity_field_id = Registry::get('tspsc_supplier_quantity_field_id');
					$supplier_discount_field_id = Registry::get('tspsc_supplier_discount_field_id');
					
					// Insert paypal email address into profile field
					fn_tspsc_insert_profile_field_data($supplier_id,$supplier_paypal_field_id,$product_paypal_email);
					
					// For select box fields: Since the product field id is different than the profile field ID then we will need to
					// convert the product field option id to the supplier profile field option id

					// [Product Quantity Tiers]
					$product_option_value = db_get_field("SELECT `variant_name` FROM ?:product_option_variants_descriptions WHERE `variant_id` = ?i", $product_quantity_tier);
					$supplier_quantity_tier = db_get_field("SELECT `object_id` FROM ?:profile_field_descriptions WHERE `object_type` = 'V' AND `description` = ?s AND `lang_code` = ?s", $product_option_value, CART_LANGUAGE);
					fn_tspsc_insert_profile_field_data($supplier_id,$supplier_quantity_field_id,$supplier_quantity_tier);
					
					// [Discount Tiers]
					$product_option_value = db_get_field("SELECT `variant_name` FROM ?:product_option_variants_descriptions WHERE `variant_id` = ?i", $product_discount_tier);
					$supplier_discount_tier = db_get_field("SELECT `object_id` FROM ?:profile_field_descriptions WHERE `object_type` = 'V' AND `description` = ?s AND `lang_code` = ?s", $product_option_value, CART_LANGUAGE);
					fn_tspsc_insert_profile_field_data($supplier_id,$supplier_discount_field_id,$supplier_discount_tier);
				}//endif
			}//endif
			
			
		}//endif
		
	}//endif
}//end fn_tspsc_save_supplier

/***********
 *
 * Function to update a commission given data and a key
 *
 ***********/
function fn_tspsc_update_commission($id,$data)
{
	if (!empty($data))
	{
		db_query("UPDATE ?:addon_tsp_supplier_commissions SET ?u WHERE `id` = ?i", $data, $id);
	}//endif
}//end fn_tspsc_update_commission

/***********
 *
 * Function to update product metadata
 *
 ***********/
function fn_tspsc_update_product_metadata($product_id, $field_name, $value) {
			
	if (!empty($value)) 
	{
		$data = array(
			'product_id' => $product_id, 
			'field_name' => $field_name,
			'value' => $value
		);
		db_query("REPLACE INTO ?:addon_tsp_supplier_commissions_product_metadata ?e", $data);
	}//endif
	else
	{
		// Don't store a bunch of null values in the database, if a field has no value
		// simply delete it from the table
		db_query("DELETE FROM ?:addon_tsp_supplier_commissions_product_metadata WHERE `product_id` = ?i AND `field_name` = ?s", $product_id, $field_name);
	}//endelse
}//end fn_tspsc_update_product_metadata

/***********
 *
 * Function to save/update the field data for supplier
 *
 ***********/
function fn_tspsc_update_supplier($supplier_id, &$supplier_data, $lang_code = CART_LANGUAGE) {

	if (!empty($supplier_data) && !empty($supplier_id)) 
	{			
		$supplier_exists = db_get_field("SELECT `supplier_id` FROM ?:suppliers WHERE `supplier_id` = ?i", $supplier_id);
		
		$sql_opt = "INSERT";
		
		if ($supplier_exists) 
		{
			$sql_opt = "REPLACE";
		}//endif
		
		$supplier_fields = db_get_hash_array("SELECT * FROM ?:profile_fields WHERE `section` = '".Registry::get('tspsc_supplier_section')."'", 'field_name');
		
		foreach ($supplier_data as $field_name => $value)
		{
			if (array_key_exists($field_name, $supplier_fields))
			{
				$data = array (
						'object_type' => Registry::get('tspsc_supplier_section'),
						'object_id' => $supplier_id,
						'field_id' => $supplier_fields[$field_name]['field_id'],
						'value' => $value
				);
				
				if ($sql_opt == "INSERT")
				{
					$id = db_query("$sql_opt INTO ?:profile_fields_data ?e", $data);
				}//end if
				else
				{
					db_query("$sql_opt INTO ?:profile_fields_data ?e", $data);
				}//end else
			}//end if
		}//endforeach
	}//endif
}//end fn_tspsc_update_supplier

/***********
 *
 * Function to insert profile data into table
 *
 ***********/
function fn_tspsc_insert_profile_field_data($company_id,$field_id,$value)
{
	$data = array(
		'object_id' => $company_id,
		'object_type' => Registry::get('tspsc_supplier_section'),
		'field_id' => $field_id,
		'value' => trim($value)
	);
	db_query('INSERT INTO ?:profile_fields_data ?e', $data);
}//end fn_tspsc_insert_profile_field_data
?>