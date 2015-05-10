<?php
/**
 * User functions
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 * @copyright (c) 2015, Daniel Bakovic
 * @license http://myarcadeplugin.com
 * @package MyArcadePlugin/Core/Game
 */

/*
 * Copyright @ Daniel Bakovic - contact@myarcadeplugin.com
 * Do not modify! Do not sell! Do not distribute! -
 * Check our license Terms!
 */

// No direct access
if( !defined( 'ABSPATH' ) ) {
  die();
}

/**
 * Shows MyArcade menu on the admin bar (Only for WP 3.1 and above)
 *
 * @version 5.0.0
 * @access  public
 * @return  void
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