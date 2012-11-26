<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * CodeIgniter Login Cookie
 *
 *
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @author        	Andrew Smiley
 * @license         MIT
 */

class Login_cookie {


	private $_ci; // CodeIgniter instance	
	private $_db; // quick access to login cookie table name


	function __construct(){
		
		$this->_ci = & get_instance();
		
		$this->_ci->load->config('login_cookie');
		
		$this->_db = config_item('login_cookie_table');
			
		log_message('debug', 'Login Cookie Class Initialized');
	}
	
	
	public function authenticate(){
		
		$cookie = $this->get();
		
		//cookie found
		if ($cookie){

			//remove expired cookies
			$this->purge_expired();
			
			//look for user_id,series in database
			$db_cookie = $this->_ci->db->order_by('created','desc')
									   ->get_where($this->_db, array('user_id'=>$cookie['user_id'], 'series'=>$cookie['series']))->row();
			
			if ($db_cookie){
				
				if ($cookie['token'] == $db_cookie->token){
					
					//user is authenticated!
					//create new token for user, updating the database with it and the current series
					$this->set($cookie['user_id'], $cookie['series']);
					
					return $cookie['user_id'];
				}
				else {
					
					// Possible attack detected - invalidate all sessions for this user
					$this->invalidate_user($cookie['user_id']);
					
					log_message('debug','Invalidated all sessions for user ' .$cookie['user_id'] . ' because a valid	series but invalid token was presented which is generally an indication that their login cookie was stolen!');		
				}			
			}			
			
		}

		return FALSE;
	}

	public function get(){
		
		$cookie = $this->_ci->input->cookie(config_item('login_cookie_name'));

		if ($cookie){
			
			if (preg_match("/^(\d+):([a-z0-9]{40})([a-z0-9]{40})$/", $cookie, $m)) {
				
				return array_combine(array('cookie','user_id','series','token'), $m);	
			}
		}
		
		return FALSE;
	}
	

	// Call on successful password-based login to set up cookie
	public function set($user_id, $series = FALSE, $token = FALSE) {
		
		$cookie = array('user_id'=>$user_id, 'series'=>$series, 'token'=>$token, 'user_agent'=> $this->_ci->input->user_agent(), 'ip'=>$this->_ci->input->ip_address(), 'created'=>time());
		
		if (!$cookie['token']){
			
			$cookie['token'] = generate_token();
		}
		
		//not having a series means this is a new computer,
		//so create new cookie in DB
		if (!$cookie['series']){
			
			$cookie['series'] = generate_token();
			
			//insert cookie info into DB
			$this->_ci->db->insert($this->_db, $cookie);
		}
		
		//already has series, so this is an update of the token only
		else {
			
			$this->_ci->db->where(array('user_id'=>$cookie['user_id'], 'series'=>$cookie['series']));
			$this->_ci->db->update($this->_db, $cookie);
		}
		

		//save cookie
		$cookie = array(
		    'name'   => config_item('login_cookie_name'),
		    'value'  => $user_id.':'.$cookie['series'].$cookie['token'],
		    'expire' => config_item('login_cookie_lifetime')
		);
		
		$this->_ci->input->set_cookie($cookie);
	}

	
	
	public function destroy(){
			
		$cookie = $this->get();
		if ($cookie){
			
			$this->_ci->db->delete($this->_db, array('user_id'=>$cookie['user_id'], 'series'=>$cookie['series']));
			$this->_remove();
		}
	}

	
	public function purge_expired(){
		
		$this->_ci->db->delete($this->_db, array('created <'=>time()-config_item('login_cookie_lifetime')));
	}

	
	public function invalidate_user($user_id){
		
		$this->_ci->db->delete($this->_db, array('user_id'=>$user_id));
		$this->_remove();
	}
	
	public function _remove(){
		
		$this->_ci->input->set_cookie(config_item('login_cookie_name'), FALSE);
	}
	

}
/* End of file Persistentpersistentlogincookie.php */
/* Location: ./system/plugins/Persistentpersistentlogincookie.php */