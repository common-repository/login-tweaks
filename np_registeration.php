<?php

function NP_registration(){
	require_once(ABSPATH . WPINC . '/registration.php');
	global $wpdb, $user_ID;
	//check if user is logged in or not
	if(!$user_ID){
		//check if user registration is allowed or not
		if(get_option('users_can_register')){
			if($_POST['np_register']){
				$username = $wpdb->escape($_REQUEST['np_email']);
				$email	  = $wpdb->escape($_REQUEST['np_email']);
				if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $email)) {  
					echo '<p class="np-error">Please enter a valid email.</p>';   
    			} else{    
			$password 	 = wp_generate_password( 12, false );
			$new_user   = wp_create_user( $username, $password, $email ); 
				if ( is_wp_error($new_user) )  {
					$error	=	'<p class="np-error">Email already exists</p>';  
				}else{  
					$from = get_option('admin_email'); 
					$siteurl	=	get_bloginfo('url') ;
					$headers = 'From: '.$from . "\r\n";  
					$subject = "Registration successful";  
					$msg = "Hi,\nThank you for registering with $siteurl. You can now log in, using the following login information:.\nEmail/Username: $username\nPassword: $password\nyou should change this password to one of your own as soon as you get a chance. Do that here: $siteurl/wp-admin/profile.php\nBest Regards\n $siteurl";  
					wp_mail( $email, $subject, $msg, $headers );  
					$success	=	 '<p class="np-success">Please check your email for login details.</p>';  
				}
				} 
			}
		?>
        	<?php $reg_form	=	$success.$error.'<form action="" method="post" class="np_regform">  
                <input type="email" name="np_email" class="text" value="Email address" /><input type="submit" id="submitbtn" name="np_register" value="SignUp" />  
			</form>';
			
			?>  
        <?php 
		}else{ 
			$reg_form	=	 '<p class="np-error">currently registration is not allowed</p>';	
		}
	}else{
		global $current_user;
		$reg_form	= '<p class="np-success">You are logged in as ' . $current_user->user_login . '</p>';	

	}
return $reg_form;
}
add_shortcode('NP-registration','NP_registration');