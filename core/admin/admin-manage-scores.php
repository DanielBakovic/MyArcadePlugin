<?php
/**
 * Displays the manage scores page on backend
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 * @copyright (c) 2015, Daniel Bakovic
 * @license http://myarcadeplugin.com
 * @package MyArcadePlugin/Core/Admin
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
 * Manage Scores
 *
 * @version 5.0.0
 * @access  public
 * @return  void
 */
function myarcade_manage_scores() {
  global $wpdb;

  myarcade_header();

  ?>
  <h2><?php _e("Manage Scores", 'myarcadeplugin'); ?></h2>
  <br />
  <?php
  myarcade_premium_message();

  myarcade_footer();
}
?>