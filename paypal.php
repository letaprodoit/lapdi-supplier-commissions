<?php
/*
 * TSP Supplier Commissions CS-Cart Addon
 *
 * @package		TSP Supplier Commissions CS-Cart Addon
 * @filename	paypal.php
 * @version		1.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/02/28
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Configuraton file for addon
 * 
 */

// STEP 1: Read POST data
 
// reading posted data from directly from $_POST causes serialization 
// issues with array data in POST
// reading raw POST data from input stream instead. 
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
  $keyval = explode ('=', $keyval);
  if (count($keyval) == 2)
     $myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
   $get_magic_quotes_exists = true;
} 
foreach ($myPost as $key => $value) {        
   if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
        $value = urlencode(stripslashes($value)); 
   } else {
        $value = urlencode($value);
   }
   $req .= "&$key=$value";
}
  
// STEP 2: Post IPN data back to paypal to validate
 
$ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
 
// In wamp like environments that do not come bundled with root authority certificates,
// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path 
// of the certificate as shown below.
// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
if( !($res = curl_exec($ch)) ) {
    // error_log("Got " . curl_error($ch) . " when processing IPN data");
    curl_close($ch);
    exit;
}
curl_close($ch);
 
 
// STEP 3: Inspect IPN validation result and act accordingly
 
define('AREA', 'C');
define('AREA_NAME', 'customer');
define('ACCOUNT_TYPE', 'customer');
define('DEBUG', false);

require dirname(__FILE__) . '/prepare.php';
require dirname(__FILE__) . '/init.php';

$res = "VERIFIED"; //FIX fiture out why it returns INVALID
$steps = "";

$test_ipn = $_POST['test_ipn']; //if a test IPN was returned
$masspay = "MASSPAY";
if ($test_ipn) $masspay = "MASSPAY-TEST";

if (strcmp ($res, "VERIFIED") == 0) {
		
	$steps = "Prepare to load tsp_supplier_commissions addon.\n";

	fn_load_addon('tsp_supplier_commissions'); //Load addon to get registry data
	
	$steps .= "0. Loaded tsp_supplier_commissions addon.\n";
	
	// Determine if MassPay is using a live or test account
	$use_live_account = (Registry::get('addons.tsp_supplier_commissions.use_live_account') == 'Y') ? true : false;

	// Get the payment processor ID
	$payment_id = Registry::get('tspsc_payment_credit_test_id');
	
	// If a test IPN wasn't returned and we are using a live account set the processor id to live account
	if (!$test_ipn && $use_live_account) $payment_id = Registry::get('tspsc_payment_credit_id');
	
	$steps .= "1. Payment Processor = $payment_id\n";
	$processor_data = fn_get_processor_data($payment_id);
	
	if (!empty($processor_data)):

	    $steps .= "2. Processor data good.\n";
	    $ok_to_process = true;
	    	    
	   	$paypal_username = $processor_data['params']['username'];
	    	
	    // check whether the payment_status is Completed or Processed
	    $payment_status = $_POST['payment_status'];
	    if (!in_array($payment_status, array('Processed','Completed'))) $ok_to_process = false;
	    	    	    
	    // check that this is a masspay transaction
	    $payment_type = $_POST['txn_type'];
	    if ($payment_type != 'masspay') $ok_to_process = false;
	    	    
	    // process payment
	    if ($ok_to_process):
	    	    	
	    	$steps .= "3. OK to process.\n";
	    	$post_data_str = serialize($_POST);
	    	
			$num_commissions = 0;
			
			// get the total number of commissions paid
			if (preg_match_all("/unique_id_(\d+)/", $post_data_str, $matches)):
				$num_commissions = count($matches[1]);
			endif;
	    	
	    	$payment_date = $_POST['payment_date'];	    	
	    	$keys = array ('unique_id_','masspay_txn_id_','status_','payment_gross_');
	    	
	    	$steps .= "4. Number of commissions = $num_commissions\n";
			for ($comm_pos = 1; $comm_pos <= $num_commissions; $comm_pos++):
			
				$comm_id = null; //commision id will get updated from unique ID
				
				$data = array(); //data for commission

				foreach ($keys as $key):
				
					$value = $_POST[$key.$comm_pos];
					
					switch ($key):
					
						case 'unique_id_':
							$comm_id = $value;
							break;
						case 'masspay_txn_id_':
							$data['transaction_id'] = $value;
							break;
						case 'status_':
							if ($value == 'Completed') { $data['status'] = 'S'; }
							elseif ($value == 'Unclaimed') { $data['status'] = 'P'; }
							else { $data['status'] = 'O'; }
							break;
						case 'payment_gross_':
							$data['total_paid'] = floatval($value);
							break;
						default:
							break;
					
					endswitch;
					
				endforeach;
				
				// Update database
				if (!empty($data) && !empty($comm_id) && !$test_ipn):
				
					$data['date_paid'] = strtotime($payment_date);
					
					// if not successful then make sure total paid is reset to zero
					if ($data['status'] != 'S') $data['total_paid'] = floatval(0);
					
					$steps .= "5. Data processed for $comm_id.\n";
					
					//if (!$test_ipn)
						// Update commission based on commission id and only non-successful commissions
						db_query("UPDATE ?:addon_tsp_supplier_commissions SET ?u WHERE `id` = ?i AND `status` != 'S'", $data, $comm_id);
				endif;
			
			endfor;
			
			$steps .= "FINISHED\n\n";
			
	    endif;
	endif;


	$steps_str = "";
	if (DEBUG) $steps_str = "(STEPS) {$steps} ";	
	
	$data = array();
	$data['url'] = "https://www.paypal.com";
	$data['request'] = "http://www.thesoftwarepeople.com/paypal.php";
	$data['response'] = "$masspay SUCCESS: (RES) $res $steps_str(POST) ".serialize($_POST);
	fn_log_event('requests','http', $data);
	
} else if (strcmp ($res, "INVALID") == 0) {

	$steps_str = "";
	if (DEBUG) $steps_str = "(STEPS) {$steps} ";

	$data = array();
	$data['url'] = "https://www.paypal.com";
	$data['request'] = "http://www.thesoftwarepeople.com/paypal.php";
	$data['response'] = "$masspay FAIL: (RES) $res $steps_str(POST) ".serialize($_POST);
	fn_log_event('requests','http', $data);

}
?>