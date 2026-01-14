<?php // configuration file
//session_start();
date_default_timezone_set('Canada/Eastern');
error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED & ~E_NOTICE);
@ini_set('display_errors', 'On');
@ini_set('short_open_tag', true);


/**********************************************************************/
/*                                                                    */
/*           general parameters                                       */
/*                                                                    */
/**********************************************************************/

define('DEBUG','true');
define('CHARSET','UTF-8');
$host = $_SERVER["HTTP_HOST"];
//$completeUrl = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
define('SITE_ID',"Php-Blockchain");
define('ADMIN_MAIL',"your@email.com");
define('ADMIN_MAIL2',"your@email.com");
define('ADMIN_MAIL3',"your@email.com");
define('WEBMASTER_MAIL',"your@email.com");
define('SITE_ROOT',dirname(dirname(__FILE__)));

define('ROOT_XML',"/");
define('ROOT_MEDIA',"/var/www/vhosts/yourhostname.com/httpdocs/php-blockchain/");
define('ROOT_SITE_WWW',"");
define('FRONT_END',"http://www.yourhostname.com/php-blockchain");
define('FRONT_END_ROOT',"/");
define('BASE_URL',"http://www.yourhostname.com/php-blockchain");
define('WWW_URL',"http://www.yourhostname.com/php-blockchain");
$site="on";
if ($site=="off") {header("location: ".WWW_URL."/wip.html");}

define('STRIPE_API_KEY', 'sk_test_51MtnWcLwDp85aBvtlIdCTCBbHWUQSDPlA687QkG7ELAb0S0Vqf82oRAmIACGHxv083MkFckXl0RUWAxl8OgAocYH00CSR41LeO'); 
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_51MtnWcLwDp85aBvtP7u3iIr9osrotSz4vMNraOqPBQFxlsefYHAveWObtX4TFlZjxvtA3UOMwx0xem1uxRL3eJ2n00apFJ7FSh'); 
define('STRIPE_CURRENCY', 'USD'); 
$hostName = $_SERVER['HTTP_HOST'];
define('HOST',$hostName);
define('CURR_YEAR',"2018");
define('OWNER',"");
define('LANGUAGES',"en");
define('WEBSITE',"Klabo");
define('MAINLANGUAGE',"_en");

$itemName = "Demo Product"; 
//$itemPrice = 25;  
$currency = "USD";  

define('DB_HOST', 'localhost');
define('DB_USERNAME', ''); 
define('DB_PASSWORD', ''); 
define('DB_NAME', '');

define('L_DB_HOST', 'localhost');
define('L_DB_USERNAME', 'root'); 
define('L_DB_PASSWORD', 'root'); 
define('L_DB_NAME', 'workhouse');

/**********************************************************************/
/*                                                                    */
/*           log parameters                                           */
/*                                                                    */
/**********************************************************************/


/*
$level = 0; // none disabled
$level = 1; // error only
$level = 2; // error and debug
$level = 3; // error, debug and info
*/
define('DEBUGLEVEL',3);
/*
$level = 0; // none disabled
$level = 1; // echo error only
$level = 2; // echo error and debug
$level = 3; // echo error, debug and info
*/
define('DEBUGSCREEN',3);





function uniqid_plus($prefix = "" , $more_entropy = false )
{
				return str_replace('.','_',uniqid($prefix,$more_entropy));
}

function quoteSmart($in) {
	if (is_int($in) || is_double($in)) {
		return $in;
	} elseif (is_bool($in)) {
		return $in ? 1 : 0;
	} elseif (is_null($in)) {
		return 'NULL';
	} else {
		return "'" . str_replace("'", "''", $in) . "'";
	}
}

function validEmail($email)
{
	$mail_pat="^[a-z0-9][_\.a-z0-9-]+@([a-z0-9][0-9a-z-]+\.)+([a-z]{2,4})/";
    if (preg_match('/' . $mail_pat . '/', $email)) {
        return false;
    } else {
        return true;
    }
}
/*
function validEmail($email)
{
	$mail_pat=""
    if (preg_match("/^[a-z0-9][_\.a-z0-9-]+@([a-z0-9][0-9a-z-]+\.)+([a-z]{2,4})/", $email)) {
        return false;
    } else {
        return true;
    }
}
*/



/**********************************************************************/
/*                                                                    */
/*            class autoload configuration                            */
/*                                                                    */
/**********************************************************************/

spl_autoload_register(function ($class) {
    include $class . '.php';
});
/*
function __autoload($class) {
	$load = SITE_ROOT.'/classes/'.$class.'.php';
	if( file_exists( $load ) )
	{
		include_once( $load );
	}
	else
	{
		die( "Can't find a file for class: $class - $load \n" );
	}

}
*/
?>