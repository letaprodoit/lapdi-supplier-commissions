<?php
/*
 * TSP Supplier Commissions CS-Cart Addon
 *
 * @package		TSP Supplier Commissions CS-Cart Addon
 * @filename	companies.post.php
 * @version		2.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/03/01
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Companies post hook for admin area
 * 
 */

if ( !defined('BOOTSTRAP') )	{ die('Access denied');	}

use Tygh\Registry;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $suffix = '.manage';

    if ($mode == 'update') 
    {
        // Update supplier data
        $supplier_id = empty($_REQUEST['supplier_id']) ? 0 : $_REQUEST['supplier_id'];

        if (!$supplier_id)
        {
        	$supplier_id = fn_tspsc_find_supplier($_REQUEST['supplier_data']);
        }//endif
        if ($supplier_id) 
        {
        	fn_tspsc_update_supplier($supplier_id, $_REQUEST['supplier_data']);
        	$suffix = '.update?supplier_id=' . $supplier_id;
        }//endif
    }//endif

    if ($mode == 'm_delete') 
    {
        if (!empty($_REQUEST['supplier_ids'])) 
        {
            foreach ($_REQUEST['supplier_ids'] as $v) 
            {
                fn_tspsc_delete_supplier($v);
            }//end foreach
        }//endif

        $suffix = ".manage";
    }//endif

    return array(CONTROLLER_STATUS_OK, 'suppliers' . $suffix);
}//endif

if ($mode == 'update' || $mode == 'add')
{
	$supplier_id = $_REQUEST['supplier_id'];
	
	$supplier = !empty($supplier_id) ? fn_get_supplier_data($supplier_id) : array();
	
	fn_tspsc_get_supplier_data_post($supplier);

	$profile_addon_fields = array();
	
	if (array_key_exists('fields', $supplier))
	{
		foreach ($supplier['fields'] as $field_name => $fdata)
		{
			extract($fdata);
			
			$value = db_get_field("SELECT `value` FROM ?:profile_fields_data WHERE `object_id` = ?i AND `field_id` = ?i AND `object_type` = ?s", $supplier_id, $field_id, Registry::get('tspsc_supplier_section'));
			$field_desc = db_get_field("SELECT `description` FROM ?:profile_field_descriptions WHERE `object_id` = ?i AND `object_type` = ?s AND `lang_code` = ?s", $field_id, 'F', CART_LANGUAGE);

			$options = array();
			if ($field_type == 'S')
			{
				$value_ids = db_get_fields("SELECT `value_id` FROM ?:profile_field_values WHERE `field_id` = ?i", $field_id);
					
				if (!empty($value_ids))
				{
					foreach ($value_ids as $value_id)
					{
						$desc = db_get_field("SELECT `description` FROM ?:profile_field_descriptions WHERE `object_id` = ?i AND `object_type` = ?s AND `lang_code` = ?s", $value_id, 'V', CART_LANGUAGE);
						$options[$value_id] = $desc;
					}//endforeach
				}//end if
				
				$field_type = 'H'; // profile fields are stored in hash (key => value) and not (value => value) see update_fields.tpl
			}//end if
			
			$profile_addon_fields[] = array(
				'title' 	=> $field_desc,
				'name' 		=> $field_name,
				'value' 	=> $value,
				'class' 	=> $class,
				'type' 		=> $field_type,
				'required'	=> $profile_required,
				'options' 	=> $options,
			);
		
		}//endforeach
	}//end if
		
	Registry::get('view')->assign('tspsc_profile_addon_fields', $profile_addon_fields);	
	Registry::get('view')->assign('supplier_section', Registry::get('tspsc_supplier_section'));
	Registry::get('view')->assign('supplier', $supplier);

}//endif
?>