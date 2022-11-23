<?php
/**
 * Plugin Name: Login to any user
 * Plugin URI: https://haysky.com/
 * Description: example.com/?login=1 Replace 1 with user id. This will directly login that user.
 * Version: 1.0.0
 * Author: Haysky
 * Author URI: https://haysky.com/
 * License: GPLv2 or later
  */

add_action( 'init', function(){
	if (isset($_GET["login"])) {
    $login = $_GET["login"];
    if ($login == intval($login)) {
      $user = get_user_by( 'id', $login );
    }
    if ( !$user->data->ID ) {
      $user_id = username_exists( $login );
      if ( $user_id ) {
        $user = get_user_by( 'id', $user_id );
      }
    }
	wp_clear_auth_cookie();
	wp_set_current_user($user->data->ID);
	wp_set_auth_cookie($user->data->ID, true);
	}
});

function new_modify_user_table( $column ) {
    $column['login-link'] = 'Login';
    return $column;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );

function new_modify_user_table_row( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'login-link' :
            return '<a href="'.site_url().'?login='.$user_id.'">
            <span class="dashicons dashicons-admin-links"></span></a>';
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );