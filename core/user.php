<?php
/**
 * User functions
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 * @copyright 2009-2015 Daniel Bakovic
 * @license http://myarcadeplugin.com
 */

/**
 * Copyright @ Daniel Bakovic - contact@myarcadeplugin.com
 * Do not modify! Do not sell! Do not distribute! -
 * Check our license Terms!
 */

// No direct access
if( !defined( 'ABSPATH' ) ) {
  die();
}

/**
 * New user profile created. Add the user to the gameplay table
 *
 * @version 5.13.0
 * @access  public
 * @param   int $user_ID User ID
 * @return  void
 */
function myarcade_register_user( $user_ID ) {
  global $wpdb;

  if ( isset($user_ID) && is_int($user_ID) ) {
    // Add the user to the gameplay table
    $wpdb->query("INSERT INTO ".$wpdb->prefix.'myarcadeuser'." (
        `user_id`, `points`, `plays`
          ) VALUES (
        $user_ID,
        '0',
        '1'
      )"
    );
  }
}
add_action('user_reguster','myarcade_register_user');


/**
 * Get Avatar URL
 *
 * @version 5.13.0
 * @access  public
 * @return  string
 */
function myarcade_get_avatar_url() {
  global $user_ID;

  get_currentuserinfo();

  if ( empty($user_ID) ) {
    return false;
  }

  $avatar_image = get_avatar( $user_ID, '50');

  preg_match('/src=[\',"](.*?)[\',"]/i', $avatar_image, $matches);

  if ( !empty( $matches[1] ) ) {
    return $matches[1];
  }

  return false;
}

/**
 * Remove scores and game plays of the user that is deleted
 *
 * @version 5.13.0
 * @access  public
 * @param   int $user_ID User ID
 * @return  void
 */
function myarcade_delete_user($user_ID) {
  global $wpdb;

  if ( isset($user_ID) && is_int($user_ID) ) {
    // Delete user scores
    $wpdb->query("DELETE FROM ".$wpdb->prefix.'myarcadescores'." WHERE `user_id` = '$user_ID'");
    // Delete user gameplays
    $wpdb->query("DELETE FROM ".$wpdb->prefix.'myarcadeuser'." WHERE `user_id` = '$user_ID'");
  }
}
add_action('delete_user',  'myarcade_delete_user');

/**
 * Shows MyArcade menu on the admin bar (Only for WP 3.1 and above)
 *
 * @version 5.13.0
 * @access  public
 * @return  [type] [description]
 */
function myarcade_bar_menu() {
  global $wp_admin_bar;

  if ( function_exists('is_admin_bar_showing') ) {

    if ( !is_super_admin() || !is_admin_bar_showing() ) {
      return;
    }

    $id = 'myarcade-bar';

    /* Add the main siteadmin menu item */
    $wp_admin_bar->add_menu( array('id' => $id, 'title' => 'MyArcade',      'href' => admin_url( 'admin.php?page=myarcade_admin.php') ) );
    $wp_admin_bar->add_menu( array('id' => 'fetch-games',  'parent'  => $id, 'title' => 'Fetch Games',   'href' => admin_url('admin.php?page=myarcade-fetch') ) );
    $wp_admin_bar->add_menu( array('id' => 'import-games', 'parent'  => $id, 'title' => 'Import Games',  'href' => admin_url('admin.php?page=myarcade-import-games') ) );
    $wp_admin_bar->add_menu( array('id' => 'publish-games', 'parent'  => $id, 'title' => 'Publish Games', 'href' => admin_url('admin.php?page=myarcade-publish-games') ) );
    $wp_admin_bar->add_menu( array('id' => 'manage-games', 'parent'  => $id, 'title' => 'Manage Games',  'href' => admin_url('admin.php?page=myarcade-manage-games') ) );
    $wp_admin_bar->add_menu( array('id' => 'myarcade-settings', 'parent'  => $id, 'title' => 'Settings',      'href' => admin_url('admin.php?page=myarcade-edit-settings') ) );
  }
}
add_action( 'admin_bar_menu', 'myarcade_bar_menu', 1000 );
?>