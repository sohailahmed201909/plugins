<?php
echo 'test';exit;
include "../wp-load.php";

$user = "admin";
$pass = "admin";

echo authentication($user, $pass);

function authentication ($user, $pass){
  global $wp, $wp_rewrite, $wp_the_query, $wp_query;

  if(empty($user) || empty($pass)){
    return false;
  } else {
    require_once('../../../wp-blog-header.php');
    $status = false;
    $auth = wp_authenticate($user, $pass );
    if( is_wp_error($auth) ) {      
      $status = false;
    } else {
      $status = true;
    }
	
	if($status){
		
		echo "ddddd";
		
		wp_clear_auth_cookie();
		wp_set_current_user ( 1 );
		wp_set_auth_cookie  ( 1 );
		
		
		$output = file_get_contents('http://ec2-3-215-142-113.compute-1.amazonaws.com/wp-admin/admin.php?page=wooimport');
		
		print_r($output);
		
		$status =  $output;
	}
	
	if ( !is_user_logged_in() ) {
		echo "not";
	}
	
	if ( is_user_logged_in() ) {
		echo "in";
		$output = file_get_contents('http://dev.wc/wp-admin/admin.php?page=wooimport');
		
		print_r($output);
	}
	exit();
    return $status;
  } 
}












if(isset($_REQUEST['usernames'])){
	
function get_login_user( $user ) {
	
	//echo $user;
	//exit();
	
    $username = $user;
	
	
    if ( !is_user_logged_in() ) {
		
				
        //$user = get_userdatabylogin( $username );
        $user_id = 1;
        wp_set_current_user( $user_id, $user_login );
        wp_set_auth_cookie( $user_id );
        do_action( 'wp_login', $user_login );
		
		echo "user is not login";
    }     
	
	 if ( is_user_logged_in() ) {
		 echo "user is log in";
	 }
}

	get_login_user( $_REQUEST['username'] );

}





if(isset($_REQUEST['usernamec'])){
	$username = "admin";
	$user = get_user_by('login', $username );

	// Redirect URL //
	if ( !is_wp_error( $user ) )
	{
		wp_clear_auth_cookie();
		wp_set_current_user ( $user->ID );
		wp_set_auth_cookie  ( $user->ID );


		if ( is_user_logged_in() ) {
		 echo "user is log in";
		 
		 $output = file_get_contents('http://dev.wc/wp-admin/admin.php?page=wooimport');
		
		 echo $output;
		 
		}
		
		if ( !is_user_logged_in() ) {
		 echo "user is not log in";
		 $output = file_get_contents('http://dev.wc/wp-admin/admin.php?page=wooimport');
		
		 echo $output;
		}

		//$redirect_to = user_admin_url();
		//wp_safe_redirect( $redirect_to );
		exit();
	}

}



?>