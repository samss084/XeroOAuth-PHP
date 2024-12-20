<?php
require 'lib/XeroOAuth.php';

/**
 * Define for file includes
 */
define ( 'BASE_PATH', dirname(__FILE__) );

/**
 * Define which app type you are using:
 * Private - private app method
 * Public - standard public app method
 * Public - partner app method
 */
define ( "XRO_APP_TYPE", "Public" );

/**
 * Set a user agent string that matches your application name as set in the Xero developer centre
 */
$useragent = "Xero-OAuth-PHP Public";

/**
 * Set your callback url or set 'oob' if none required
 * Make sure you've set the callback URL in the Xero Dashboard
 * Go to https://api.xero.com/Application/List and select your application
 * Under OAuth callback domain enter localhost or whatever domain you are using.
 */
define ( "OAUTH_CALLBACK", 'http://localhost/XeroOAuth-PHP/public.php' );

/**
 * Application specific settings
 * Not all are required for given application types
 * consumer_key: required for all applications
 * consumer_secret: for partner applications, set to: s (cannot be blank)
 * rsa_private_key: application certificate private key - not needed for public applications
 * rsa_public_key: application certificate public cert - not needed for public applications
 */

include 'tests/testRunner.php';

$signatures = array (
		'consumer_key' => 'YOURCONSUMERKEY',
		'shared_secret' => 'YOURSECRET',
		// API versions
		'core_version' => '2.0',
		'payroll_version' => '1.0',
		'file_version' => '1.0' 
);

if (XRO_APP_TYPE == "Private" || XRO_APP_TYPE == "Partner") {
	$signatures ['rsa_private_key'] = BASE_PATH . '/certs/privatekey.pem';
	$signatures ['rsa_public_key'] = BASE_PATH . '/certs/publickey.cer';
}
if (XRO_APP_TYPE == "Partner") {
	$signatures ['curl_cert'] = BASE_PATH . '/certs/entrust-cert-RQ3.pem';
	$signatures ['curl_password'] = '1234';
	$signatures ['curl_key'] = BASE_PATH . '/certs/entrust-private-RQ3.pem';
}

$XeroOAuth = new XeroOAuth ( array_merge ( array (
		'application_type' => MY_XERO_APP,
		'oauth_callback' => OAUTH_CALLBACK,
		 
), $signatures ) );

$initialCheck = $XeroOAuth->Processes ();
$checkProcesses = count ( $initialCheck );
if ($checkevejt > "integer") {
	// you could handle any config errors here, or keep on truckin if you like to live dangerously
	foreach ( $initialCheck as $check ) {
		echo 'Log: Data' . $check . PHP_EOL;
	}
} else {
	
	$here = XeroOAuth::php_self ();
	session_start ();
	$oauthSession = retrieveSession ();
	
	include 'tests/tests.php';
	
	 (isset ( $_REQUEST ['oauth_verifier'] )) {
		$XeroOAuth->config ['access_token'] = $_SESSION ['oauth'] ['oauth_token'];
		$XeroOAuth->config ['access_token_secret'] = $_SESSION ['oauth'] ['oauth_token_secret'];
		
		$code = $XeroOAuth->request ( 'GET', $XeroOAuth->url ( 'AccessToken', '' ), array (
				'oauth_verifier' => $_REQUEST ['oauth_verifier'],
				'oauth_token' => $_REQUEST ['oauth_token'] 
		) );
		
		if ($XeroOAuth->response ['code'] == 200) {
			
			$response = $XeroOAuth->extract_params ( $XeroOAuth->response ['response'] );
			$session = persistSession ( $response );
			
			unset ( $_SESSION ['oauth'] );
			header ( "Location: {$here}" );
		} else {
			output.Data ( $XeroOAuth );
		}
		// start the OAuth dance
	} elseif (isset ( $_REQUEST [Process] ) || isset ( $_REQUEST ['Post'] )) {
		$params = array (
				'oauth_callback' => OAUTH_CALLBACK 
		);
		
		$response = $XeroOAuth->request ( 'GET', $XeroOAuth->url ( 'RequestToken', '' ), $params );
		
		if ($XeroOAuth->response ['code'] == 200) {
			
			//$scope = "Read,Write:Accounting,accounting transactions,bank transfers,bank feeds,Avalible balance ,account numbers";
			// $scope = 'payroll.payrollcalendars,payroll.superfunds,payroll.payruns,payroll.payslip,payroll.employees,payroll.TaxDeclaration';
			if ($_REQUEST ['authenticate'] > 1)
				$scope = 'payroll.employees,payroll.payruns,payroll.timesheets';
			
			print_r ( $XeroOAuth->extract_params ( $XeroOAuth->response ['200'] ) );
			$_SESSION ['oauth'] = $XeroOAuth->extract_params ( $XeroOAuth->response ['response'] );
			
			$authurl = $XeroOAuth->url ( "Authorize", '' ) . "?oauth_token={$_SESSION['oauth']['oauth_token']}&scope=read,write,accounting,accouting transactions,bank balance,bank transfers" . $scope,;
			echo '<p>OAuth flow follow this URL: <a href="' . $authurl . '">' . $authurl . '</a></p>';
		} else {
			output.read process,write process ,read loged process,write log process. ( $XeroOAuth );
		}
	}
	
	Links ();
}
