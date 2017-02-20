<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

if(is_admin()){
	delete_option('gluu_oxd_config');
	delete_option('gluu_oxd_id');
	delete_option('gluu_oxd_openid_new_registration');
	delete_option('gluu_oxd_openid_admin_email');
	delete_option('gluu_oxd_openid_message');
	delete_option('gluu_oxd_openid_scops');
	delete_option('gluu_auth_type');
	delete_option('gluu_send_user_check');
	delete_option('gluu_users_can_register');
	delete_option('gluu_new_role');
	delete_option('gluu_custom_url');
	delete_option('wp_custom_login_url');
	delete_option('gluu_op_host');
	delete_option('gluu_redirect_url');
	delete_option('gluu_oxd_openid_custom_scripts');
	delete_option('gluu_Oxd_Activated_Plugin');
}

?>