<?php
/**
 * SpilGames
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 * @copyright (c) 2015, Daniel Bakovic
 * @license http://myarcadeplugin.com
 * @package MyArcadePlugin/Core/Fetch
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
 * @version 5.0.0
 * @access  public
 * @return  void
 */
function myarcade_settings_spilgames() {
  $spilgames = get_option( 'myarcade_spilgames' );
  ?>
  <h2 class="trigger"><?php _e("Spil Games", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <i>
              <?php _e("Spil Games provides a game feed with over 1500 games.", 'myarcadeplugin'); ?> Click <a href="http://publishers.spilgames.com/">here</a> to visit the Spil Games site.
            </i>
            <br /><br />
            <p class="mabp_info" style="padding:10px">
              <?php _e("Some 'Spil Games' games have a domain lock. That means that they will not work if you host game files on your server. Therby it is recommended to deactivate Game Download Feature when publishing these games.", 'myarcadeplugin'); ?>
            </p>
          </td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Feed URL", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="spilgamesurl" value="<?php echo $spilgames['feed']; ?>" />
          </td>
          <td><i><?php _e("Edit this field only if Feed URL has been changed!", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Fetch Games", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="spilgameslimit" value="<?php echo $spilgames['limit']; ?>" />
          </td>
          <td><i><?php _e("How many games should be fetched at once. Enter 'all' (without quotes) if you want to fetch all games. Otherwise enter an integer.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Thumbnail Size", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="spilgamesthumbsize" id="spilgamesthumbsize">
              <option value="1" <?php myarcade_selected($spilgames['thumbsize'], '1'); ?> ><?php _e("Small (100x75)", 'myarcadeplugin'); ?></option>
              <option value="2" <?php myarcade_selected($spilgames['thumbsize'], '2'); ?> ><?php _e("Medium (120x90)", 'myarcadeplugin'); ?></option>
              <option value="3" <?php myarcade_selected($spilgames['thumbsize'], '3'); ?> ><?php _e("Large (200x120)", 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select the size of the thumbnails that should be used for games from Spil Games. Default size is small (100x75).", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Gamer Player API", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="checkbox" name="spilgames_player_api" value="true" <?php myarcade_checked($spilgames['player_api'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to use the Spilgames Game Player API to embed games. Spilgames will add revenue sharing trough the API in the future.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Fetching", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="checkbox" name="spilgames_cron_fetch" value="true" <?php myarcade_checked($spilgames['cron_fetch'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to fetch games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Fetch Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="spilgames_cron_fetch_limit" value="<?php echo $spilgames['cron_fetch_limit']; ?>" />
          </td>
          <td><i><?php _e("How many games should be fetched on every cron trigger?", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Publishing", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="spilgames_cron_publish" value="true" <?php myarcade_checked($spilgames['cron_publish'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Publish Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="spilgames_cron_publish_limit" value="<?php echo $spilgames['cron_publish_limit']; ?>" />
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
 * Handle distributor settings update
 *
 * @version 5.0.0
 * @access  public
 * @return  void
 */
function myarcade_save_settings_spilgames() {

  // Do a secuirty check before updating the settings
  $myarcade_nonce = filter_input( INPUT_POST, 'myarcade_save_settings_nonce');
  if ( ! $myarcade_nonce || ! wp_verify_nonce( $myarcade_nonce, 'myarcade_save_settings' ) ) {
    // Security check failed .. don't update settings
    return;
  }

  // Spil Games Settings
  $spilgames = array();
  if ( isset($_POST['spilgamesurl'])) $spilgames['feed'] = esc_url_raw($_POST['spilgamesurl']); else $spilgames['feed'] = '';
  if ( isset($_POST['spilgameslimit'])) $spilgames['limit'] = sanitize_text_field($_POST['spilgameslimit']); else $spilgames['limit'] = '20';
  if ( isset($_POST['spilgamesthumbsize'])) $spilgames['thumbsize'] = trim($_POST['spilgamesthumbsize']); else $spilgames['thumbsize'] = 'small';

  $spilgames['cron_fetch']          = (isset($_POST['spilgames_cron_fetch'])) ? true : false;
  $spilgames['cron_fetch_limit']    = (isset($_POST['spilgames_cron_fetch_limit']) ) ? intval($_POST['spilgames_cron_fetch_limit']) : 1;
  $spilgames['cron_publish']        = (isset($_POST['spilgames_cron_publish']) ) ? true : false;
  $spilgames['cron_publish_limit']  = (isset($_POST['spilgames_cron_publish_limit']) ) ? intval($_POST['spilgames_cron_publish_limit']) : 1;
  $spilgames['player_api'] = (isset($_POST['spilgames_player_api'])) ? true : false;

  // Update Settings
  update_option('myarcade_spilgames', $spilgames);
}

/**
 * Diesplay feed options on the fetch games page
 *
 * @version 5.0.0
 * @access  public
 * @return  void
 */
function myarcade_fetch_options_spilgames() {

}

/**
 * Fetch SpilGames games
 *
 * @version 5.0.0
 * @access  public
 * @param   array  $args Fetching parameters
 * @return  void
 */
function myarcade_feed_spilgames( $args = array() ) {
  global $wpdb;

  $defaults = array(
    'echo'     => false,
    'settings' => array()
  );

  $r = wp_parse_args( $args, $defaults );

  extract($r);

  $new_games = 0;
  $add_game = false;

  $spilgames      = get_option('myarcade_spilgames');
  $feedcategories = get_option('myarcade_categories');

  // Init settings var's
  if ( ! empty( $settings ) ) {
    $settings = array_merge($spilgames, $settings);
  }
  else {
    $settings = $spilgames;
  }

  if ( !isset($settings['method']) ) {
    $settings['method'] = 'latest';
  }

  // Generate Feed URL
  $feed = add_query_arg( array("format" => "json"), trim( $settings['feed'] ) );

  // Check if there is a feed limit. If not, feed all games
  if ( ! empty( $settings['limit'] ) ) {
    $feed = add_query_arg( array("limit" => $settings['limit'] ), $feed );
  }

  if ( $settings['method'] == 'offset' ) {
    $feed = add_query_arg( array("page" => $settings['offset'] ), $feed );
  }

  // Add search query
  if ( ! empty( $settings['search'] ) ) {
    $feed = add_query_arg( array( "q" => $settings['search'] ), $feed );
  }

  // Add source attribute
  $feed = add_query_arg( array( "source" => 'MyArcadePlugin' ), $feed );

  // Include required fetch functions
  require_once( MYARCADE_CORE_DIR . '/fetch.php' );

  // Fetch Spilgames games
  $json_games = myarcade_fetch_games( array('url' => $feed, 'service' => 'json', 'echo' => $echo) );

  //====================================
  if ( ! empty($json_games->entries ) ) {

    $images = array('png', 'jpg', 'jpeg', 'gif', 'bmp');

    foreach ($json_games->entries as $game_obj) {

      $game = new stdClass();

      $game->uuid = $game_obj->id . '_spilgames';
      // Generate a game tag for this game
      $game->game_tag = md5($game_obj->id . 'spilgames');

      // Check, if this game is present in the games table
      $duplicate_game = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix . 'myarcadegames'." WHERE uuid = '".$game->uuid."' OR game_tag = '".$game->game_tag."' OR name = '".esc_sql( $game_obj->title )."'");

      if ( !$duplicate_game ) {
        // Check game categories and add game if it's category has been selected

        $add_game   = false;

        // Map ategories
        if ( ! empty($game_obj->category) ) {
          $categories = explode(',', $game_obj->category);
          $categories = array_map( 'trim', $categories );
        }
        else {
          $categories = array( 'Other' );
        }

        // Initialize the category string
        $categories_string = 'Other';

        foreach($categories as $gamecat) {
          $gamecat = htmlspecialchars_decode ( trim($gamecat) );

          foreach ( $feedcategories as $feedcat ) {
            if ( $feedcat['Status'] == 'checked' ) {
              // Name to check
              if ( $feedcat['Spilgames'] === true ) {
                $cat_name = $feedcat['Name'];
              }
              else {
                $cat_name = $feedcat['Spilgames'];
              }

              if ( strpos( $cat_name, $gamecat ) !== false ) {
                $add_game = true;
                $categories_string = $feedcat['Name'];
                break 2;
              }
            }
          }
          //if ($add_game == true) break;
        } // END - Category-Check

        if (!$add_game) {
          continue;
        }

        switch ( $spilgames['thumbsize'] ) {
          case '1': {
            $thumbnail_url = $game_obj->thumbnails->small;
            $ext = pathinfo( $thumbnail_url, PATHINFO_EXTENSION);
            if ( in_array( $ext, $images ) ) {
              break;
            }
          }
          case '2': {
            $thumbnail_url = $game_obj->thumbnails->medium;
            $ext = pathinfo( $thumbnail_url, PATHINFO_EXTENSION);
            if ( in_array( $ext, $images ) ) {
              break;
            }
          }
          case '3': {
            $thumbnail_url = $game_obj->thumbnails->large;
            $ext = pathinfo( $thumbnail_url, PATHINFO_EXTENSION);
            if ( in_array( $ext, $images ) ) {
              break;
            }
          }
          default : {
            // We did not find a valid thumbnail image
            // Use default image
            $thumbnail_url = MYARCADE_URL . "/images/noimage.png";
          }
        }

        // Check if this is a HTML5 game. If so, then change game type and generate an iframe code
        if ( "iframe" == $game_obj->technology ) {
          $game->type          = 'iframe';
        }
        else {
          $game->type          = 'spilgames';
        }

        $game->name          = esc_sql($game_obj->title);
        $game->slug          = myarcade_make_slug($game_obj->title);
        $game->created       = date( 'Y-m-d h:i:s', time() );
        $game->description   = esc_sql($game_obj->description);
        $game->categs        = esc_sql($categories_string);
        $game->swf_url       = esc_sql($game_obj->gameUrl);
        $game->thumbnail_url = esc_sql($thumbnail_url);
        $game->leaderboard_enabled =  esc_sql( $game_obj->properties->highscore );
        $game->width         = $game_obj->width;
        $game->height        = $game_obj->height;

        $new_games++;

        // Add game to the database
        myarcade_add_fetched_game( $game, $echo );
      }
    }
  }

  // Show, how many games have been fetched
  myarcade_fetched_message( $new_games, $echo );
}
?>