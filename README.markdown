CodeIgniter - Login Cookie
================

Author
----------------
Andrew Smiley <jayalfredprufrock>


Requirements
----------------

1. PHP 5.1+
2. CodeIgniter 2.0+


Usage
----------------
First, setup the table listed at the bottom of the readme.

Setting a login cookie looks like this:

$this->login_cookie->set($this->user->user_id);


And then you'll need to do a check (often times in an overridden MY_Controller.php that protects certain controllers)
that looks like this:

$user_id = $this->login_cookie->authenticate();
			
if ($user_id){
	$this->user = $this->user_m->get_user($user_id);
	$this->user->cookie_login = TRUE;
	$this->session->set_userdata('user', $this->user);
}


In the above example, the variable "cookie_login" can be used to prevent access to certain portions of the site
(like a password changing form) since the cookie could potentially be stolen



Credits
----------------
Complete rewrite of Ravi Raj code posted here: http://codeigniter.com/forums/viewthread/102307/
which was an implementation of algorithm detailed in the following article: http://jaspan.com/improved_persistent_login_cookie_best_practice


Note, you'll need to create the following database in order for this spark to function correctly.

Database
----------------

CREATE  TABLE `login_cookies` (

  `user_id` INT NOT NULL ,

  `series` VARCHAR(40) NOT NULL ,

  `token` VARCHAR(40) NOT NULL ,

  `created` INT NOT NULL ,

  `user_agent` VARCHAR(255) NULL ,

  `ip` VARCHAR(16) NULL ,

  PRIMARY KEY (`user_id`, `series`) );

