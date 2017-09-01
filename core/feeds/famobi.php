<?php
/**
 * Famobi Feed
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 */

// No direct Access
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Display distributor settings on admin page
 *
 * @version 5.16.2
 * @access  public
 * @return  void
 */
function myarcade_settings_famobi() {

  $famobi = myarcade_get_settings( 'famobi' );
  ?>
  <h2 class="trigger"><?php _e("Famobi", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <i>
              <?php printf( __( "%s distributes HTML5 games.", 'myarcadeplugin' ), '<a href="http://famobi.com" target="_blank">Famobi</a>' ); ?>
            </i>
            <br /><br />
          </td>
        </tr>
        <tr><td colspan="2"><h3><?php _e("Feed URL", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="famobi_url" value="<?php echo $famobi['feed']; ?>" />
          </td>
          <td><i><?php _e("Edit this field only if Feed URL has been changed!", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Category", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="famobi_category" id="famobi_category">
              <option value="all" <?php myarcade_selected($famobi['category'], 'all'); ?> ><?php _e("All Games", 'myarcadeplugin'); ?></option>
              <option value="action" <?php myarcade_selected($famobi['category'], 'action'); ?> ><?php _e("Action", 'myarcadeplugin'); ?></option>
              <option value="arcade" <?php myarcade_selected($famobi['category'], 'arcade'); ?> ><?php _e("Arcade", 'myarcadeplugin'); ?></option>
              <option value="best" <?php myarcade_selected($famobi['category'], 'best'); ?> ><?php _e("Best", 'myarcadeplugin'); ?></option>
              <option value="breakout" <?php myarcade_selected($famobi['category'], 'breakout'); ?> ><?php _e("Breakout", 'myarcadeplugin'); ?></option>
              <option value="bubble-shooter" <?php myarcade_selected($famobi['category'], 'bubble-shooter'); ?> ><?php _e("Bubble Shooter", 'myarcadeplugin'); ?></option>
              <option value="cards" <?php myarcade_selected($famobi['category'], 'cards'); ?> ><?php _e("Cards", 'myarcadeplugin'); ?></option>
              <option value="cars" <?php myarcade_selected($famobi['category'], 'cars'); ?> ><?php _e("Cars", 'myarcadeplugin'); ?></option>
              <option value="cooking" <?php myarcade_selected($famobi['category'], 'cooking'); ?> ><?php _e("Cooking", 'myarcadeplugin'); ?></option>
              <option value="dress-up" <?php myarcade_selected($famobi['category'], 'dress-up'); ?> ><?php _e("Dress-up", 'myarcadeplugin'); ?></option>
              <option value="girls" <?php myarcade_selected($famobi['category'], 'girls'); ?> ><?php _e("Girls", 'myarcadeplugin'); ?></option>
              <option value="jump-and-run" <?php myarcade_selected($famobi['category'], 'jump-and-run'); ?> ><?php _e("Jump & Run", 'myarcadeplugin'); ?></option>
              <option value="make-up" <?php myarcade_selected($famobi['category'], 'make-up'); ?> ><?php _e("Make-up", 'myarcadeplugin'); ?></option>
              <option value="match-3" <?php myarcade_selected($famobi['category'], 'match-3'); ?> ><?php _e("Match-3", 'myarcadeplugin'); ?></option>
              <option value="multiplayer" <?php myarcade_selected($famobi['category'], 'multiplayer'); ?> ><?php _e("Multiplayer", 'myarcadeplugin'); ?></option>
              <option value="new" <?php myarcade_selected($famobi['category'], 'new'); ?> ><?php _e("New", 'myarcadeplugin'); ?></option>
              <option value="puzzle" <?php myarcade_selected($famobi['category'], 'puzzle'); ?> ><?php _e("Puzzle", 'myarcadeplugin'); ?></option>
              <option value="quiz" <?php myarcade_selected($famobi['category'], 'quiz'); ?> ><?php _e("Quiz", 'myarcadeplugin'); ?></option>
              <option value="racing" <?php myarcade_selected($famobi['category'], 'racing'); ?> ><?php _e("Racing", 'myarcadeplugin'); ?></option>
              <option value="sports" <?php myarcade_selected($famobi['category'], 'sports'); ?> ><?php _e("Sports", 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select which games you would like to fetch.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Thumbnail Size", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="famobi_thumbsize" id="famobi_thumbsize">
              <option value="thumb_60" <?php myarcade_selected($famobi['thumbsize'], 'thumb_60'); ?> ><?php _e("Small (60x60)", 'myarcadeplugin'); ?></option>
              <option value="thumb_120" <?php myarcade_selected($famobi['thumbsize'], 'thumb_120'); ?> ><?php _e("Medium (120x120)", 'myarcadeplugin'); ?></option>
              <option value="thumb_180" <?php myarcade_selected($famobi['thumbsize'], 'thumb_180'); ?> ><?php _e("Large (180x180)", 'myarcadeplugin'); ?></option>
              <option value="thumb" <?php myarcade_selected($famobi['thumbsize'], 'thumb'); ?> ><?php _e("Extra Large (360x360)", 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select a thumbnail size (Default 120x120).", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Language", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="famobi_language" id="famobi_language">
              <option value="en_EN" <?php myarcade_selected($famobi['language'], 'en_EN'); ?> ><?php _e("English", 'myarcadeplugin'); ?></option>
              <option value="de_DE" <?php myarcade_selected($famobi['language'], 'de_DE'); ?> ><?php _e("German", 'myarcadeplugin'); ?></option>
              <option value="tr_TR" <?php myarcade_selected($famobi['language'], 'tr_TR'); ?> ><?php _e("Turkish", 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select a game language.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Fetching", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="famobi_cron_fetch" value="true" <?php myarcade_checked($famobi['cron_fetch'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to fetch games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Fetch Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="famobi_cron_fetch_limit" value="<?php echo $famobi['cron_fetch_limit']; ?>" />
          </td>
          <td><i><?php _e("How many games should be fetched on every cron trigger?", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Publishing", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="famobi_cron_publish" value="true" <?php myarcade_checked($famobi['cron_publish'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Publish Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="famobi_cron_publish_limit" value="<?php echo $famobi['cron_publish_limit']; ?>" />
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
function myarcade_default_settings_famobi() {
  return array(
    'feed'          => 'http://api.famobi.com/feed',
    'affiliate_id'  => 'A-MYARCADEPLUGIN',
    'thumbsize'     => 'thumb_120',
    'category'      => 'all',
    'language'      => 'en_EN',
    'cron_fetch'    => false,
    'cron_fetch_limit' => '1',
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
function myarcade_save_settings_famobi() {

  myarcade_check_settings_nonce();

  $settings = array();
  $settings['affiliate_id'] = 'A-MYARCADEPLUGIN';
  $settings['feed'] = (isset($_POST['famobi_url'])) ? esc_sql($_POST['famobi_url']) : '';
  $settings['category'] = (isset($_POST['famobi_category'])) ? $_POST['famobi_category'] : 'all';
  $settings['thumbsize'] = (isset($_POST['famobi_thumbsize'])) ? $_POST['famobi_thumbsize'] : 'thumb_120';
  $settings['language'] = (isset($_POST['famobi_language'])) ? $_POST['famobi_language'] : 'en_EN';
  $settings['cron_fetch'] = (isset($_POST['famobi_cron_fetch'])) ? true : false;
  $settings['cron_fetch_limit']    = (isset($_POST['famobi_cron_fetch_limit']) ) ? intval($_POST['famobi_cron_fetch_limit']) : 1;
  $settings['cron_publish'] = (isset($_POST['famobi_cron_publish']) ) ? true : false;
  $settings['cron_publish_limit'] = (isset($_POST['famobi_cron_publish_limit']) ) ? intval($_POST['famobi_cron_publish_limit']) : 1;

  // Update settings
  update_option('myarcade_famobi', $settings);
}

/**
 * Display distributor fetch games options
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  void
 */
function myarcade_fetch_settings_famobi() {

  $famobi = myarcade_get_fetch_options_famobi();
  ?>
  <div class="myarcade_border white hide mabp_680" id="famobi">
    <div style="float:left;width:150px">
      <input type="radio" name="fetchmethodfamobi" value="latest" <?php myarcade_checked($famobi['method'], 'latest');?>>
    <label><?php _e("Latest Games", 'myarcadeplugin'); ?></label>
    <br />
    <input type="radio" name="fetchmethodfamobi" value="offset" <?php myarcade_checked($famobi['method'], 'offset');?>>
    <label><?php _e("Use Offset", 'myarcadeplugin'); ?></label>
    </div>
    <div class="myarcade_border" style="float:left;padding-top: 5px;background-color: #F9F9F9">
    Fetch <input type="number" name="famobi_limit" value="<?php echo $famobi['limit']; ?>" /> games <span id="offsfamobi" class="hide">from offset <input id="radiooffsfamobi" type="number" name="offsetfamobi" value="<?php echo $famobi['offset']; ?>" /> </span>
    </div>
    <div class="clear"></div>
  </div>
  <?php
}

/**
 * Generate an options array with submitted fetching parameters
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  array Fetching options
 */
function myarcade_get_fetch_options_famobi() {

  // Get distributor settings
  $settings = myarcade_get_settings( 'famobi' );

  if ( 'start' == filter_input( INPUT_POST, 'fetch' ) ) {
    $settings['method'] = filter_input( INPUT_POST, 'fetchmethodfamobi' );
    $settings['offset'] = intval( filter_input( INPUT_POST, 'offsetfamobi' ) );
    $settings['limit'] = intval( filter_input( INPUT_POST, 'famobi_limit' ) );
  }
  else {
    $settings['method'] = 'latest';
    $settings['offset'] = 0;
    $settings['limit'] = 100;
  }

  return $settings;
}

/**
 * Retrieve available distributor's categories mapped to MyArcadePlugin categories
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  array Distributor categories
 */
function myarcade_get_categories_famobi() {
  return array(
    "Action"      => "action,jump-and-run",
    "Adventure"   => false,
    "Arcade"      => true,
    "Board Game"  => "cards,match-3",
    "Casino"      => false,
    "Defense"     => false,
    "Customize"   => "make-up",
    "Dress-Up"    => "dress-up,girls",
    "Driving"     => "cars,racing",
    "Education"   => false,
    "Fighting"    => false,
    "Jigsaw"      => false,
    "Multiplayer" => true,
    "Other"       => "quiz,cooking",
    "Puzzles"     => true,
    "Rhythm"      => false,
    "Shooting"    => false,
    "Sports"      => true,
    "Strategy"    => "breakout,bubble-shooter",
  );
}

/**
 * Fetch games
 *
 * @version 5.27.0
 * @since   5.16.0
 * @access  public
 * @param   array  $args Fetching parameters
 * @return  void
 */
function myarcade_feed_famobi( $args = array() ) {
  global $wpdb;

  $defaults = array(
    'echo'     => false,
    'settings' => array(),
    'method'   => 'latest'
  );

  $args = wp_parse_args( $args, $defaults );
  extract($args);

  $new_games = 0;
  $add_game = false;

  $famobi = myarcade_get_fetch_options_famobi();
  $famobi_categories = myarcade_get_categories_famobi();
  $feedcategories = get_option('myarcade_categories');
  $general = get_option('myarcade_general');

  // Init settings vars
  if ( !empty($settings) ) {
    $settings = array_merge($famobi, $settings);
  }
  else {
    $settings = $famobi;
  }

  if ( empty( $settings['affiliate_id']) ) {
    $settings['affiliate_id'] = 'A-MYARCADEPLUGIN';
  }

  // Generate Feed URL
  $settings['feed'] = add_query_arg( array("a" => $settings['affiliate_id'] ), trim( $settings['feed'] ) );

  if ( $settings['limit'] > 0 ) {
    $settings['feed'] = add_query_arg( array("n" => intval( $settings['limit'] ) ), $settings['feed'] );
  }

  if ( $settings['method'] == 'offset' && isset( $settings['offset'] ) ) {
    $settings['feed'] = add_query_arg( array( 'skip' => intval( $settings['offset'] ) ), $settings['feed'] );
  }

  if ( $settings['category'] !== 'all' ) {
    $settings['feed'] = add_query_arg( array("channel" => $settings['category'] ), $settings['feed'] );
  }

  if ( $settings['language'] != 'en_EN' ) {
    $settings['feed'] = add_query_arg( array( 'locale' => $settings['language'] ), $settings['feed'] );
  }

  // Include required fetch functions
  require_once( MYARCADE_CORE_DIR . '/fetch.php' );

  // Fetch games
  $json_games = myarcade_fetch_games( array( 'url' => $settings['feed'], 'service' => 'json', 'echo' => $echo) );

  //====================================
  if ( !empty($json_games->games) ) {
    foreach ($json_games->games as $game_obj) {

      $game = new stdClass();
      $game->uuid     = crc32( $game_obj->package_id ) . '_famobi';
      // Generate a game tag for this game
      $game->game_tag = md5( $game_obj->package_id . 'famobi' );

      $add_game = false;

      // Clean categories
      $game_obj->categories = array_diff( $game_obj->categories,  array( 'new', 'best' ) );
      $game_obj->categories = array_map( 'trim', $game_obj->categories );

      $categories_string = 'Other';

      foreach( $game_obj->categories as $gamecat ) {

        // Transform some feed categories
        switch ( strtolower($gamecat) ) {
          case 'puzzle': {
            $gamecat = 'Puzzles';
          } break;
        }

        foreach ( $feedcategories as $feedcat ) {
          if ( $feedcat['Status'] == 'checked' ) {
            if ( ! empty( $famobi_categories[ $feedcat['Name'] ] ) ) {
              // Set category name to check
              if ( $famobi_categories[ $feedcat['Name'] ] === true ) {
                $cat_name = $feedcat['Name'];
              }
              else {
                $cat_name = $famobi_categories[ $feedcat['Name'] ];
              }

              if ( strpos( strtolower($cat_name), strtolower($gamecat) ) !== false ) {
                $add_game = true;
                $categories_string = $feedcat['Name'];
                break 2;
              }
            }
          }
        }
      } // END - Category-Check

      if ( ! $add_game ) {
        continue;
      }

      $thumb_size = $settings['thumbsize'];
      $thumbnail = $game_obj->$thumb_size;

      // Fallback
      if ( empty( $thumbnail ) ) {
        $thumbnail = $game_obj->thumb;
      }

      $game->type          = 'famobi';
      $game->name          = esc_sql($game_obj->name);
      $game->slug          = myarcade_make_slug($game_obj->name);
      $game->description   = esc_sql($game_obj->description);
      $game->categs        = $categories_string;
      $game->thumbnail_url = myarcade_maybe_ssl( strtok( $thumbnail, '?' ) );
      $game->swf_url       = myarcade_maybe_ssl( $game_obj->link );

      // Calculate game width
      if ( intval( $general['max_width'] ) > 0 ) {
        $max_width = $general['max_width'];
      }
      else {
        $max_width = 800;
      }

      if ( !isset( $game_obj->orientation ) ) {
        // Orientation is missing. We need to determinate it manually
        if ( $game_obj->aspect_ratio > 0 ) {
          $game_obj->orientation = 'landscape';
        }
        else {
          $game_obj->orientation = 'portrait';
        }
      }

      if ( $game_obj->orientation == 'landscape' ) {
        $game->width = $max_width;
        $game->height = round($max_width / $game_obj->aspect_ratio, 2);
      }
      else {
        $game->height = 700;
        $game->width = round( $game->height * $game_obj->aspect_ratio, 2);
      }

      // Add game to the database
      if ( myarcade_add_fetched_game( $game, $args ) ) {
        $new_games++;
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
function myarcade_embedtype_famobi() {
  return 'iframe';
}

/**
 * Return if games can be downloaded by this distirbutor
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  bool True if games can be downloaded
 */
function myarcade_can_download_famobi() {
  return false;
}
?>