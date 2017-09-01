<?php
/**
 * MyArcadeFeed
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
 * @version 5.15.0
 * @access  public
 * @return  void
 */
function myarcade_settings_myarcadefeed() {
  $myarcadefeed = myarcade_get_settings( 'myarcadefeed' );
  ?>
  <h2 class="trigger"><?php _e("MyArcadeFeed", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <i>
              <?php _e("Add up to five Feeds generated with MyArcadeFeed Plugin.", 'myarcadeplugin'); ?> Click <a href="http://exells.com/shop/products/myarcadefeed">here</a> to learn more about MyArcadeFeed.
            </i>
            <br /><br />
          </td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("MyArcadeFeed URL 1", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="myarcadefeed1" value="<?php echo $myarcadefeed['feed1']; ?>" />
          </td>
          <td><i><?php _e("Paste your MyArcadeFeed URL No. 1 here.", 'myarcadeplugin'); ?></i></td>
        </tr>
        <tr><td colspan="2"><h3><?php _e("MyArcadeFeed URL 2", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="myarcadefeed2" value="<?php echo $myarcadefeed['feed2']; ?>" />
          </td>
          <td><i><?php _e("Paste your MyArcadeFeed URL No. 2 here.", 'myarcadeplugin'); ?></i></td>
        </tr>
        <tr><td colspan="2"><h3><?php _e("MyArcadeFeed URL 3", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="myarcadefeed3" value="<?php echo $myarcadefeed['feed3']; ?>" />
          </td>
          <td><i><?php _e("Paste your MyArcadeFeed URL No. 3 here.", 'myarcadeplugin'); ?></i></td>
        </tr>
        <tr><td colspan="2"><h3><?php _e("MyArcadeFeed URL 4", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="myarcadefeed4" value="<?php echo $myarcadefeed['feed4']; ?>" />
          </td>
          <td><i><?php _e("Paste your MyArcadeFeed URL No. 4 here.", 'myarcadeplugin'); ?></i></td>
        </tr>
        <tr><td colspan="2"><h3><?php _e("MyArcadeFeed URL 5", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="myarcadefeed5" value="<?php echo $myarcadefeed['feed5']; ?>" />
          </td>
          <td><i><?php _e("Paste your MyArcadeFeed URL No. 5 here.", 'myarcadeplugin'); ?></i></td>
        </tr>
        <tr><td colspan="2"><h3><?php _e("Fetch All Categories", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="checkbox" name="myarcadefeed_all_categories" value="true" <?php myarcade_checked($myarcadefeed['all_categories'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Activate this if you want to fetch all games independent of your activated categories.", 'myarcadeplugin'); ?></i></td>
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
function myarcade_default_settings_myarcadefeed() {
  return array(
    'feed1'          => 'http://games.myarcadeplugin.com/game_feed.xml',
    'feed2'          => '',
    'feed3'          => '',
    'feed4'          => '',
    'feed5'          => '',
    'all_categories' => false,
  );
}

/**
 * Handle distributor settings update
 *
 * @version 5.19.0
 * @access  public
 * @return  void
 */
function myarcade_save_settings_myarcadefeed() {

  myarcade_check_settings_nonce();

  // MyArcadeFeed
  $myarcadefeed = array();
  $myarcadefeed['feed1'] = (isset($_POST['myarcadefeed1'])) ? esc_url_raw($_POST['myarcadefeed1']) : '';
  $myarcadefeed['feed2'] = (isset($_POST['myarcadefeed2'])) ? esc_url_raw($_POST['myarcadefeed2']) : '';
  $myarcadefeed['feed3'] = (isset($_POST['myarcadefeed3'])) ? esc_url_raw($_POST['myarcadefeed3']) : '';
  $myarcadefeed['feed4'] = (isset($_POST['myarcadefeed4'])) ? esc_url_raw($_POST['myarcadefeed4']) : '';
  $myarcadefeed['feed5'] = (isset($_POST['myarcadefeed5'])) ? esc_url_raw($_POST['myarcadefeed5']) : '';
  $myarcadefeed['all_categories'] = (isset($_POST['myarcadefeed_all_categories'])) ? true : false;

  // Update Settings
  update_option('myarcade_myarcadefeed', $myarcadefeed);
}

/**
 * Display distributor fetch games options
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  void
 */
function myarcade_fetch_settings_myarcadefeed() {

  $myarcadefeed = myarcade_get_fetch_options_myarcadefeed();
  ?>

  <div class="myarcade_border white hide mabp_680" id="myarcadefeed">
    <?php
    $myarcadefeed_array = array();
    for ($i=1;$i<5;$i++) {
      if ( !empty($myarcadefeed['feed'.$i])) {
        $myarcadefeed_array[$i] = $myarcadefeed['feed'.$i];
      }
    }
    if ( $myarcadefeed_array ) {
      _e("Select a Feed:", 'myarcadeplugin');
      ?>
      <select name="myarcadefeedselect" id="myarcadefeedselect">
        <?php
        foreach ($myarcadefeed_array as $key => $val) {
          echo '<option value="feed'.$key.'"> '.$val.' </option>';
        }
        ?>
      </select>
      <?php
    } else {
        ?>
        <p class="mabp_error">
          <?php _e("No MyArcadeFeed URLs found!", 'myarcadeplugin');?>
        </p>
        <?php
    }
    ?>
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
function myarcade_get_fetch_options_myarcadefeed() {

  // Get distributor settings
  $settings = myarcade_get_settings( 'myarcadefeed' );

  if ( 'start' == filter_input( INPUT_POST, 'fetch' ) ) {
    // Set submitted fetching options
    $settings['feed'] = filter_input( INPUT_POST, 'myarcadefeedselect' );
  }

  return $settings;
}

/**
 * Fetch MyArcadeFeed games
 *
 * @version 5.26.0
 * @access  public
 * @param   array  $args Fetching parameters
 * @return  void
 */
function myarcade_feed_myarcadefeed($args) {
 global $wpdb;

  $defaults = array(
    'echo'     => false,
    'settings' => array()
  );

  $args = wp_parse_args( $args, $defaults );
  extract($args);

  $new_games = 0;
  $add_game = false;

  $myarcadefeed   = myarcade_get_fetch_options_myarcadefeed();
  $feedcategories = get_option('myarcade_categories');
  $feed           = $myarcadefeed['feed'];

  // Include required fetch functions
  require_once( MYARCADE_CORE_DIR . '/fetch.php' );

  $games = myarcade_fetch_games( array(
      'url'     => $myarcadefeed[$feed],
      'service' => 'xml',
      'echo'    => true
    )
  );

  if ( !empty($games) && isset($games->gameset) ) {
    foreach ($games->gameset->game as $game) {

      $game->uuid     = $game->id;
      // Generate a game tag for this game
      $game->game_tag = md5($game->id.$game->name.'myarcadefeed');

      $categories = explode( ',', $game->category );

      if ( ! $myarcadefeed['all_categories'] ) {
        $add_game = false;
        // Category-Check
        foreach ($feedcategories as $feedcat) {
          foreach ( $categories as $category ) {
            if ( ($feedcat['Name'] == $category) && ($feedcat['Status'] == 'checked') ) {
              $add_game = true;
              break;
            }
          }
          if ( $add_game ) {
            break;
          }
        }

        // Should we add this game?
        if ($add_game == false) { continue; }
      }

      // Decode URL
      $game->gamecode = urldecode($game->gamecode);

      // Check for file extension or embed code
      if ( strpos( $game->gamecode, 'src=') !== FALSE ) {
        // This is an embed code game
        $game->type = 'embed';
      }
      else {
        $extension = pathinfo( $game->gamecode , PATHINFO_EXTENSION );

        switch ( $extension ) {
          case 'dcr' : {
            $game->type = 'dcr';
          } break;

          case 'unity3d' : {
            $game->type = 'unity';
          }

          case 'html' : {
            $game->type = 'iframe';
          } break;

          default : {
            $game->type = 'myarcadefeed';
          } break;
        }
      }

      $game->name           = esc_sql( $game->name );
      $game->slug           = myarcade_make_slug($game->name);
      $game->description    = esc_sql($game->description);
      $game->instructions    = esc_sql($game->instructions);
      $game->categs         = esc_sql($game->category);
      $game->thumbnail_url  = esc_sql($game->thumbnail);
      $game->swf_url        = esc_sql($game->gamecode);
      $game->screen1_url    = !empty($game->screenshot_1) ? $game->screenshot_1 : '';
      $game->screen2_url    = !empty($game->screenshot_2) ? $game->screenshot_2 : '';
      $game->screen3_url    = !empty($game->screenshot_3) ? $game->screenshot_3 : '';
      $game->screen4_url    = !empty($game->screenshot_4) ? $game->screenshot_4 : '';
      $game->tags           = ( !empty($game->tags) ) ? esc_sql($game->tags) : '';

      // Add game to the database
      if ( myarcade_add_fetched_game( $game, $args ) ) {
        $new_games++;
      }
    }
  }

  // Show, how many games have been fetched
  myarcade_fetched_message( $new_games, $echo );
}
?>