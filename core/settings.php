<?php
/**
 * MyArcadePlugin default settings
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

$myarcade_general_default = array (
  'scores'        => true,
  'highscores'    => false,
  'posts'         => '20',
  'status'        => 'publish',
  'schedule'      => '60',
  'down_thumbs'   => false,
  'down_games'    => false,
  'down_screens'  => false,
  'delete'        => true,
  'folder_structure' => '%game_type%/%alphabetical%/',
  'automated_fetching' => false,
  'interval_fetching' => 'hourly',
  'automated_publishing' => false,
  'interval_publishing' => 'daily',
  'cron_publish_limit' => 1,
  'create_cats'   => true,
  'parent'        => '',
  'firstcat'      => true,
  'single'        => false,
  'singlecat'     => '',
  'max_width'     => '',
  'embed'         => 'manually',
  'template'      => "%DESCRIPTION% %INSTRUCTIONS%",
  'use_template'  => false,
  'allow_user'    => false,
  'limit_plays'   => '0',
  'limit_message' => 'Please register to play more games!',
  'play_delay'    => '30',
  'post_type'     => 'post',
  'custom_category' => '',
  'custom_tags'   => '',
  'featured_image' => true,
  'disable_game_tags' => false,
  'swfobject' => false,
  /* Translation Settings */
  'translation'   => 'none',
  'bingid'        => '',
  'bingsecret'    => '',
  'translate_to'  => 'en',
  'google_id'    => '',
  'google_translate_to' => 'en',
  'translate_fields' => array('name', 'description', 'instructions', 'tags'),
  'translate_games'  => array(),
  'yandex_key' => '',
  'yandex_translate_to' => 'de',
);
?>