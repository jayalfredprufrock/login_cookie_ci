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

