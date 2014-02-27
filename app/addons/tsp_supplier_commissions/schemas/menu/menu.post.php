<?php
/*
 * TSP Supplier Commissions CS-Cart Addon
 *
 * @package		TSP Supplier Commissions CS-Cart Addon
 * @filename	menu.post.php
 * @version		1.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Admin post permissions for menus
 * 
 */

$schema['central']['customers']['items']['suppliers']['subitems']['tspsc_supplier_commissions'] = array(
	'href' => 'supplier_commissions.manage',
    'position' => 0,
    'attrs' => array(
        'class'	=>	'tspsc_supplier_commissions is-addon'
    ),
);

return $schema;

?>