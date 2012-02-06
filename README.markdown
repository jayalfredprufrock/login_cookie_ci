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

First, be sure to setup the table listed at the bottom of the readme.

Setting a login cookie looks like this:

$this->login_cookie->set($this->user->user_id);

This should really on be done directly after a successful login. 


Then you'll need to do a check (often times in an overridden MY_Controller.php that protects certain controllers)
when a user is NOT logged in to see if they have a valid cookie, and thus should be automaticalily logged in.
Here is the method:

$user_id = $this->login_cookie->authenticate();
			
At this point, $user_id is either false, which means that the user doesn't have a valid login cookie,
or the user_id that the user needs to be logged in to as.			
		
		
The following is a more complete example of potential usage:

//user not logged in		
if (!$this->session->userdata('user')){	

	$user_id = $this->login_cookie->authenticate();
	
	if ($user_id){
	
		$this->user = $this->user_m->get_user($user_id);
		$this->cookie_login = TRUE;
		$this->session->set_userdata('user', $this->user);
	}
}


It is wise to not let users that have been auto-logged in via a cookie to access certain portions of the site
(like a password changing form) since the cookie could potentially be stolen (though unlikely). The details
of the security implications of such a method are in the articles below.



Credits
----------------
Complete rewrite of Ravi Raj code posted here: http://codeigniter.com/forums/viewthread/102307/
which was an implementation of algorithm detailed in the following article: http://jaspan.com/improved_persistent_login_cookie_best_practice


**Note** 
The spark requires the following table in order to function correctly.

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

