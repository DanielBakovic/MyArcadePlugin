<?php
/**
 * Automated fetching and publishing
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 * @license http://myarcadeplugin.com
 * @package MyArcadePlugin/Core/Admin
 */
/*
 * Copyright @ Daniel Bakovic - contact@myarcadeplugin.com
 * Do not modify! Do not sell! Do not distribute! -
 * Check our license Terms!
 */

// No direct Access
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Automated game fetching
 *
 * @version 5.13.0
 * @return  void
 */
function myarcade_cron_fetching() {
  global $myarcade_distributors;

  if ( myarcade_schluessel() ) {

    // Build the cron array
    $crons = array();

    foreach($myarcade_distributors as $slug => $name) {
      $option = get_option('myarcade_'.$slug);
      if ($option && isset($option['cron_fetch']) && ($option['cron_fetch'] == true) ) {
        $limit = (!empty($option['cron_fetch_limit'])) ? intval($option['cron_fetch_limit']) : 1;
        $crons[$slug] = array( 'echo' => false, 'settings' => array('limit' => $limit) );
      }
    }

    if ( count($crons) > 0 ) {
      foreach($crons as $key => $args) {
        $distributor_file = MYARCADE_CORE_DIR . '/feeds/' . $key . '.php';

        if ( file_exists( $distributor_file ) ) {
          require_once( $distributor_file );
          $fetch = 'myarcade_feed_'.$key;
          if ( function_exists($fetch) ) {
            $fetch($args);
          }
        }
      }
    }
  }
}
add_action('cron_fetching', 'myarcade_cron_fetching');

/**
 * Automated game publishing
 *
 * @version 5.13.0
 * @return  void
 */
function myarcade_cron_publishing() {
  global $wpdb, $myarcade_distributors, $myarcade_game_type_custom;

  if ( myarcade_schluessel() ) {

    // Build the cron array
    $crons = array();

    // Check game distributors
    foreach($myarcade_distributors as $slug => $name) {
      $option = get_option('myarcade_'.$slug);
      if ($option && isset($option['cron_publish']) && ($option['cron_publish'] == true) ) {
        $limit = (!empty($option['cron_publish_limit'])) ? intval($option['cron_publish_limit']) : 1;
        $crons[$slug] = $limit;
      }
    }

    // Check manually imported games
    $general = get_option('myarcade_general');

    if ( $general['automated_publishing'] == true ) {
      foreach ( $myarcade_game_type_custom as $slug => $name) {
        $limit = (!empty( $general['cron_publish_limit'] ) ) ? intval($general['cron_publish_limit']) : 1;
        $crons[$slug] = $limit;
      }
    }

    if ( count($crons) > 0 ) {
      // go trough all distributors
      foreach($crons as $type => $limit) {
        // publish games for each distributor
        for($x=0; $x<$limit; $x++) {
          // Get game id
          $game_id = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix . 'myarcadegames'." WHERE game_type = '".$type."' AND status = 'new' ORDER BY id LIMIT 1");
          if ( $game_id ) {
            myarcade_add_games_to_blog( array('game_id' => $game_id, 'post_status' => 'publish', 'echo' => false) );
          }
        }
      }
    }
  }
}
add_action('cron_publishing', 'myarcade_cron_publishing');
?>