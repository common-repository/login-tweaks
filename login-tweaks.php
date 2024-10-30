<?php

/*

Plugin Name: Login Tweaks by Next Page
Plugin URI: http://nextpage.no/login-tweaks
Description: This plugin will allow you to login into admin using username/email. You can use both username and email to login to worpdress dashboard
Author: Next Page
Version: 1.0
Author URI: http://nextpage.no/login-tweaks
*/
//Change the login form text to tell the user to login username/password





function NP_login_form(){ ?>
<script type="text/javascript">

		if(document.getElementById('loginform') ){

			var labels	=	document.getElementById('loginform').getElementsByTagName('label');

			var firstlabel	=	labels[0].firstChild.nodeValue='Email or Username';

			

		

		}

		if(document.getElementById('login_error') ){

			var inner	=	document.getElementById('login_error').innerHTML=document.getElementById('login_error').innerHTML.replace( 'username', 'Username or Email' );

		}

	

	</script>
<?php }

add_action( 'login_form', 'NP_login_form' );



//	this function adds the Instructions page

$plugin_short_prefix	=	'NP';

$plugin_long_prefix	=	'NextPage';
add_action('admin_menu', 'NP_login_menu');
function NP_login_menu(){
	global $plugin_long_prefix;
	global $plugin_short_prefix;
	add_submenu_page( 'options-general.php','Login Tweaks', 'Login Tweaks', 'manage_options', $plugin_short_prefix.'_login', $plugin_short_prefix.'_login_instructions' );	
}

//	add "instructions" link to plugin page

add_filter('plugin_action_links_' . plugin_basename(__FILE__) , 'NP_login_instructions_link');

function NP_login_instructions_link($links) {

	$NP_login_instructions_link = sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=NP_login' ), __('Instructions') );

	array_unshift($links, $NP_login_instructions_link);

	return $links;

}

//Callback function for instructions page



function NP_login_instructions(){
	require 'instructions.php';	
}



function NP_login_authentication($email, $username, $password){

	if ( !empty( $username ) ) {

		$email = get_user_by( 'email', $username );

		if ( isset( $email, $email->user_login, $email->user_status ) && 0 == (int) $email->user_status )

			$username = $email->user_login;

		}



	return wp_authenticate_username_password( null, $username, $password );

}

remove_filter( 'authenticate', 'wp_authenticate_username_password', 20, 3 );

add_filter( 'authenticate', 'NP_login_authentication', 20, 3 );


require 'np_registeration.php';


add_action('register_form','NP_register_form');


function load_jquery(){
	wp_enqueue_script('jquery');
}
add_action( 'login_enqueue_scripts', 'load_jquery', 1 );
function NP_register_form(){ 

?>
<script type="text/javascript"> 

jQuery(document).ready(function(){

	//alert('working');
	jQuery("#registerform p:first")	.css('display','none');
	 jQuery('#user_email').keyup(function(){
			var username	=	jQuery(this).val();
			jQuery('#user_login').val(username);
		});
		
		
		
	});


</script>
<?php }