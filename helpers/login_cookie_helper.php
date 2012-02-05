<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * Login Cookie Helper
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Andrew Smiley
 * 
 * 
 */

 
 function generate_token() {
 	
	$CI =& get_instance();
    $CI->load->library('encrypt');
 	
	return $CI->encrypt->sha1(substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,40));
 }
