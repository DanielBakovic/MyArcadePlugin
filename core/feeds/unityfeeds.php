<?php
/**
 * UnityFeeds
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

// No direct Access
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Display distributor settings on admin page
 *
 * @version 5.17.0
 * @access  public
 * @return  void
 */
function myarcade_settings_unityfeeds() {
  $unityfeeds = myarcade_get_settings( 'unityfeeds' );
  ?>
  <h2 class="trigger"><?php _e("UnityFeeds Games", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <i>
              <?php printf( __( "%s distributes Unity3D games.", 'myarcadeplugin' ), '<a href="http://unityfeeds.com/" target="_blank">UnityFeeds</a>' ); ?>
            </i>
          </td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Feed URL", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="unityfeeds_url" value="<?php echo $unityfeeds['feed']; ?>" />
          </td>
          <td><i><?php _e("Edit this field only if Feed URL has been changed!", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Category", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="unityfeeds_category" id="unityfeeds_category">
              <option value="all" <?php myarcade_selected($unityfeeds['category'], 'all'); ?> ><?php _e("All Games", 'myarcadeplugin'); ?></option>
              <option value="8" <?php myarcade_selected($unityfeeds['category'], '8'); ?> ><?php _e("Action Games", 'myarcadeplugin'); ?></option>
              <option value="9" <?php myarcade_selected($unityfeeds['category'], '9'); ?> ><?php _e("Arcade Games", 'myarcadeplugin'); ?></option>
              <option value="7" <?php myarcade_selected($unityfeeds['category'], '7'); ?> ><?php _e("Driving Games", 'myarcadeplugin'); ?></option>
              <option value="11" <?php myarcade_selected($unityfeeds['category'], '11'); ?> ><?php _e("Flying Games", 'myarcadeplugin'); ?></option>
              <option value="6" <?php myarcade_selected($unityfeeds['category'], '6'); ?> ><?php _e("Girls Games", 'myarcadeplugin'); ?></option>
              <option value="10" <?php myarcade_selected($unityfeeds['category'], '10'); ?> ><?php _e("Puzzle Games", 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select which games you would like to fetch.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Thumbnail Size", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="unityfeeds_thumbnail" id="unityfeeds_thumbnail">
              <option value="100x100" <?php myarcade_selected($unityfeeds['thumbnail'], '100x100'); ?> ><?php _e("100x100", 'myarcadeplugin'); ?></option>
              <option value="120x90" <?php myarcade_selected($unityfeeds['thumbnail'], '120x90'); ?> ><?php _e("120x90", 'myarcadeplugin'); ?></option>
              <option value="160x160" <?php myarcade_selected($unityfeeds['thumbnail'], '160x160'); ?> ><?php _e("160x160", 'myarcadeplugin'); ?></option>
              <option value="180x135" <?php myarcade_selected($unityfeeds['thumbnail'], '180x135'); ?> ><?php _e("180x135", 'myarcadeplugin'); ?></option>
              <option value="300x250" <?php myarcade_selected($unityfeeds['thumbnail'], '300x250'); ?> ><?php _e("300x250", 'myarcadeplugin'); ?></option>
              <option value="300x300" <?php myarcade_selected($unityfeeds['thumbnail'], '300x300'); ?> ><?php _e("300x300", 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select a thumbnail size.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Screenshot Size", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="unityfeeds_screenshot" id="unityfeeds_screenshot">
              <option value="100x100" <?php myarcade_selected($unityfeeds['screenshot'], '100x100'); ?> ><?php _e("100x100", 'myarcadeplugin'); ?></option>
              <option value="120x90" <?php myarcade_selected($unityfeeds['screenshot'], '120x90'); ?> ><?php _e("120x90", 'myarcadeplugin'); ?></option>
              <option value="160x160" <?php myarcade_selected($unityfeeds['screenshot'], '160x160'); ?> ><?php _e("160x160", 'myarcadeplugin'); ?></option>
              <option value="180x135" <?php myarcade_selected($unityfeeds['screenshot'], '180x135'); ?> ><?php _e("180x135", 'myarcadeplugin'); ?></option>
              <option value="300x250" <?php myarcade_selected($unityfeeds['screenshot'], '300x250'); ?> ><?php _e("300x250", 'myarcadeplugin'); ?></option>
              <option value="300x300" <?php myarcade_selected($unityfeeds['screenshot'], '300x300'); ?> ><?php _e("300x300", 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select a screenshot size.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Publishing", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="unityfeeds_cron_publish" value="true" <?php myarcade_checked($unityfeeds['cron_publish'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Publish Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="unityfeeds_cron_publish_limit" value="<?php echo $unityfeeds['cron_publish_limit']; ?>" />
          </td>
          <td><i><?php _e("How many games should be published on every cron trigger?", 'myarcadeplugin'); ?></i></td>
        </tr>

      </table>
      <input class="button button-primary" id="submit" type="submit" name="submit" value="<?php _e("Save Settings", 'myarcadeplugin'); ?>" />
    </div>
  </div>
  <?php
}

/**
 * Retrieve distributor's default settings
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  array Default settings
 */
function myarcade_default_settings_unityfeeds() {
  return array(
    'feed'          => 'http://unityfeeds.com/feed/',
    'category'      => 'all',
    'thumbnail'     => '100x100',
    'screenshot'    => '300x300',
    'cron_publish'  => false,
    'cron_publish_limit' => '1',
  );
}

/**
 * Handle distributor settings update
 *
 * @version 5.19.0
 * @access  public
 * @return  void
 */
function myarcade_save_settings_unityfeeds() {

  myarcade_check_settings_nonce();

  // UnityFeeds
  $unityfeeds = array();
  $unityfeeds['feed'] = (isset($_POST['unityfeeds_url'])) ? esc_sql($_POST['unityfeeds_url']) : '';
  $unityfeeds['category'] = (isset($_POST['unityfeeds_category'])) ? esc_sql($_POST['unityfeeds_category']) : 'all';
  $unityfeeds['thumbnail'] = (isset($_POST['unityfeeds_thumbnail'])) ? esc_sql($_POST['unityfeeds_thumbnail']) : '100x100';
  $unityfeeds['screenshot'] = (isset($_POST['unityfeeds_screenshot'])) ? esc_sql($_POST['unityfeeds_screenshot']) : '300x300';
  $unityfeeds['cron_publish']        = (isset($_POST['unityfeeds_cron_publish']) ) ? true : false;
  $unityfeeds['cron_publish_limit']  = (isset($_POST['unityfeeds_cron_publish_limit']) ) ? intval($_POST['unityfeeds_cron_publish_limit']) : 1;
    // Update Settings
    update_option('myarcade_unityfeeds', $unityfeeds);
}

/**
 * Diesplay feed options on the fetch games page
 *
 * @version 5.15.0
 * @access  public
 * @return  void
 */
function myarcade_fetch_options_unityfeeds() {

}

/**
 * Fetch UnityFeeds games
 *
 * @version 5.15.0
 * @access  public
 * @param   array  $args Fetching parameters
 * @return  void
 */
function myarcade_feed_unityfeeds( $args = array() ) {
  global $wpdb;

  $defaults = array(
    'echo'     => false,
    'settings' => array()
  );

  $r = wp_parse_args( $args, $defaults );

  extract($r);

  $new_games = 0;
  $add_game = false;

  $unityfeeds      = myarcade_get_settings( 'unityfeeds' );
  $feedcategories = get_option('myarcade_categories');

  // Init settings var's
  if ( !empty($settings) ) {
    $settings = array_merge($unityfeeds, $settings);
  }
  else {
    $settings = $unityfeeds;
  }

  /**
   * Generate Feed URL
  */

  $feed_format ='?format=json';
  $category = ( $unityfeeds['category'] ) ? $unityfeeds['category'] : 'all';

  // Generate the Mochi Feed URL
  $feed = trim( $unityfeeds['feed'] ) . $feed_format . '&limit=all&category=' . $category;

  // Include required fetch functions
  require_once( MYARCADE_CORE_DIR . '/fetch.php' );

  // Fetch Spilgames games
  $json_games = myarcade_fetch_games( array('url' => $feed, 'service' => 'json', 'echo' => $echo) );

  //====================================
  if ( !empty($json_games) ) {

    foreach ($json_games as $game_obj) {

      // Check the keyword filter before we do anything else
      if ( ! empty( $settings['keyword_filter'] ) ) {
        if ( ! preg_match( $settings['keyword_filter'], strtolower( $game_obj->name ) ) && ! preg_match( $settings['keyword_filter'], strtolower( $game_obj->description ) ) ) {
          // Filter failed. Skip game
          continue;
        }
      }

      $game = new stdClass();

      $game->uuid     = $game_obj->id . '_unityfeeds';
      // Generate a game tag for this game
      $game->game_tag = md5($game_obj->id.'unityfeeds');

      // Check, if this game is present in the games table
      $duplicate_game = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix . 'myarcadegames'." WHERE uuid = '".$game->uuid."' OR game_tag = '".$game->game_tag."' OR name = '".esc_sql( $game_obj->name )."'");

      if ( !$duplicate_game ) {
        // Check game categories and add game if it's category has been selected

        $add_game   = false;

        // Map UnityFeeds category names to our own names
        switch ($game_obj->category) {
          case 'Action Games':    $category = 'Action'; break;
          case 'Arcade Games':    $category = 'Arcade'; break;
          case 'Driving Games':   $category = 'Driving'; break;
          case 'Flying Games':    $category = 'Other'; break;
          case 'Girls Games':     $category = 'Dress-Up'; break;
          case 'Puzzle Games':    $category = 'Puzzles'; break;
          default: $category = 'Other'; break;
        }

        // Category-Check
        foreach ($feedcategories as $feedcat) {
          if ( ($feedcat['Name'] == $category) && ($feedcat['Status'] == 'checked') ) {
            $add_game = true;
            break;
          }
        }

        if (!$add_game) {
          continue;
        }

        $thumbnail_size = ( $unityfeeds['thumbnail'] ) ? $unityfeeds['thumbnail'] : '100x100';
        if ( ! empty( $game_obj->thumbnails->$thumbnail_size ) ) {
          $thumbnail_url = $game_obj->thumbnails->$thumbnail_size;
        }
        else {
          $thumbnail_url = MYARCADE_URL . "/images/noimage.png";
        }

        $screenshot_size = ( $unityfeeds['screenshot'] ) ? $unityfeeds['screenshot'] : '300x300';
        if ( !empty( $game_obj->thumbnails->$screenshot_size ) ) {
          $screenshot_url = $game_obj->thumbnails->$screenshot_size;
        }
        else {
          $screenshot_url = '';
        }

        $tags_string = '';
        $tags = (array) $game_obj->tags;
        if ( ! empty( $tags ) ) {
          foreach ( $tags as $key => $tag) {
            $tags_string .= $tag . ',';
          }

          $tags_string = rtrim( $tags_string, ',');
        }

        $game->type          = 'unityfeeds';
        $game->name          = esc_sql($game_obj->name);
        $game->slug          = myarcade_make_slug($game_obj->name);
        $game->created       = date('Y-m-d h:i:s',$game_obj->added);
        $game->description   = esc_sql($game_obj->description);
        $game->instructions  = esc_sql($game_obj->instructions);
        $game->categs        = esc_sql($category);
        $game->swf_url       = esc_sql($game_obj->file);
        $game->thumbnail_url = esc_sql($thumbnail_url);
        $game->screen1_url   = esc_sql($screenshot_url);;
        $game->tags          = esc_sql( $tags_string );
        $game->width         = esc_sql($game_obj->width);
        $game->height        = esc_sql($game_obj->height);

        $new_games++;

        // Add game to the database
        myarcade_add_fetched_game( $game, $echo );
      }
    }
  }

  // Show, how many games have been fetched
  myarcade_fetched_message( $new_games, $echo );
}

/**
 * Return game embed method
 *
 * @version 5.18.0
 * @since   5.18.0
 * @access  public
 * @return  string Embed Method
 */
function myarcade_embedtype_unityfeeds() {
  return 'unity';
}
?>