<?php
/**
 * CoolGames
 */

/**
 * Save options function
 *
 * @version 5.24.0
 * @since   5.24.0
 * @access  public
 * @return  void
 */
function myarcade_save_settings_coolgames() {

  myarcade_check_settings_nonce();

  $settings = array();
  $settings['feed'] = esc_sql( filter_input( INPUT_POST, 'coolgames_url' ) );
  $settings['category'] = filter_input( INPUT_POST, 'coolgames_category' );
  $settings['thumbnail'] = filter_input( INPUT_POST, 'coolgames_thumbnail' );
  $settings['cron_publish'] = filter_input( INPUT_POST, 'coolgames_cron_publish', FILTER_VALIDATE_BOOLEAN );
  $settings['cron_publish_limit'] = filter_input( INPUT_POST, 'coolgames_cron_publish_limit', FILTER_VALIDATE_INT, array( "options" => array( "default" => 1) ) );

  // Update settings
  update_option( 'myarcade_coolgames', $settings );
}

/**
 * Display distributor settings on admin page
 *
 * @version 5.24.0
 * @since   5.24.0
 * @access  public
 * @return  void
 */
function myarcade_settings_coolgames() {
  $coolgames = myarcade_get_settings( 'coolgames' );
  ?>
  <h2 class="trigger"><?php _e("CoolGames", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <i>
              <?php printf( __( "%s distributes HTML5 games.", 'myarcadeplugin' ), '<a href="http://coolgames.com" target="_blank">CoolGames</a>' ); ?>
            </i>
            <br /><br />
          </td>
        </tr>
        <tr><td colspan="2"><h3><?php _e("Feed URL", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="coolgames_url" value="<?php echo $coolgames['feed']; ?>" />
          </td>
          <td><i><?php _e("Edit this field only if Feed URL has been changed!", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Category", 'myarcadeplugin'); ?></h3></td></tr>

        <?php
        $coolgames_categories = array(
          'all' => __("All games", 'myarcadeplugin' ),
          "arcade" => "Arcade",
          "board" => "Board Games",
          "bubble" => "Bubble Games",
          "cooking" => "Cooking Games",
          "girl" => "Girl",
          "puzzle" => "Puzzles",
          "sports" => "Sports",
        );
        ?>
        <tr>
          <td>
            <select size="1" name="coolgames_category" id="coolgames_category">
              <?php foreach ( $coolgames_categories as $key => $value ) : ?>
               <option value="<?php echo $key; ?>" <?php myarcade_selected( $coolgames['category'], $key ); ?>><?php echo $value; ?></option>
              <?php endforeach; ?>
            </select>
          </td>
          <td><i><?php _e("Select which games you would like to fetch.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Thumbnail Size", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="coolgames_thumbnail" id="coolgames_thumbnail">
              <option value="s" <?php myarcade_selected($coolgames['thumbnail'], 's'); ?> ><?php _e("160x160", 'myarcadeplugin'); ?></option>
              <option value="m" <?php myarcade_selected($coolgames['thumbnail'], 'm'); ?> ><?php _e("480x480", 'myarcadeplugin'); ?></option>
              <option value="l" <?php myarcade_selected($coolgames['thumbnail'], 'l'); ?> ><?php _e("800x800", 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select a thumbnail size.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Publishing", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="coolgames_cron_publish" value="true" <?php myarcade_checked($coolgames['cron_publish'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Publish Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="coolgames_cron_publish_limit" value="<?php echo $coolgames['cron_publish_limit']; ?>" />
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
 * Load default distributor settings
 *
 * @version 5.24.0
 * @since   5.24.0
 * @access  public
 * @return  array Default settings
 */
function myarcade_default_settings_coolgames() {
  return array(
    'feed'          => 'http://download.coolgames.com/MyArcadeFeed2.json',
    'category'      => 'all',
    'thumbnail'     => 's',
    'cron_publish'  => false,
    'cron_publish_limit' => '1',
  );
}

/**
 * Fetch games
 *
 * @version 5.26.0
 * @since   5.24.0
 * @access  public
 * @param   array  $args Fetching parameters
 * @return  void
 */
function myarcade_feed_coolgames( $args = array() ) {
  global $wpdb;

  $defaults = array(
    'echo'     => false,
    'settings' => array(),
  );

  $args = wp_parse_args( $args, $defaults );
  extract($args);

  $new_games = 0;
  $add_game = false;

  $coolgames = myarcade_get_settings( 'coolgames' );

  $feedcategories = get_option( 'myarcade_categories' );

  // Init settings var's
  if ( ! empty($settings) ) {
    $settings = array_merge( $coolgames, $settings );
  }
  else {
    $settings = $coolgames;
  }

  // Include required fetch functions
  require_once( MYARCADE_CORE_DIR . '/fetch.php' );

  // Fetch games
  $json_games = myarcade_fetch_games( array( 'url' => trim( $settings['feed'] ), 'service' => 'json', 'echo' => $echo ) );

  //====================================
  if ( !empty($json_games ) ) {
    foreach ( $json_games as $game_obj ) {

      $game = new stdClass();
      $game->uuid     = crc32( $game_obj->name ) . '_coolgames';
      // Generate a game tag for this game
      $game->game_tag = md5( $game_obj->name . 'coolgames' );

      // Check game categories and add game if it's category has been selected
      $add_game   = false;

      switch ( $game_obj->category ) {
        case 'acade':
        case 'sports' : {
          $categories_string = ucfirst( $game_obj->category );
        } break;

        case 'board': {
          $categories_string = 'Board Game';
        } break;

        case 'girl': {
          $categories_string = 'Customize';
        } break;

        case 'puzzle' : {
          $categories_string = 'Puzzles';
        }

        default : {
          $categories_string = 'Other';
        } break;
      }

      foreach ( $feedcategories as $feedcat ) {
        if ( ($feedcat['Name'] == $categories_string) && ($feedcat['Status'] == 'checked') ) {
          $add_game = true;
          break;
        }
      }

      if ( ! $add_game ) {
        continue;
      }

      $game->type        = 'coolgames';
      $game->name        = esc_sql( $game_obj->name );
      $game->slug        = myarcade_make_slug( $game_obj->name );
      $game->description = esc_sql( $game_obj->description );
      $game->categs      = $categories_string;
      $game->swf_url     = esc_sql( $game_obj->url );

      if ( isset( $game_obj->orientation ) && 'p' == $game_obj->orientation ) {
        $game->width = 600;
        $game->height = 800;
      }
      else {
        $game->width = 800;
        $game->height = 600;
      }

      $thumb_size = $settings['thumbnail'];

      if ( ! empty( $game_obj->square->{$thumb_size}[0] ) ) {
        $game->thumbnail_url = esc_sql( $game_obj->square->{$thumb_size}[0] );
      }

      for( $i = 0; $i <= 3; $i++ ) {
        if ( ! empty( $game_obj->screenshot->m[ $i ] ) ) {
          $nr = $i + 1;
          $screen_nr = 'screen'. $nr . '_url';
          $game->$screen_nr = $game_obj->screenshot->m[ $i ];
        }
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
 * @version 5.24.0
 * @since   5.24.0
 * @access  public
 * @return  string Embed Method
 */
function myarcade_embedtype_coolgames() {
  return 'iframe';
}

/**
 * Return if games can be downloaded by this distirbutor
 *
 * @version 5.24.0
 * @since   5.24.0
 * @access  public
 * @return  bool True if games can be downloaded
 */
function myarcade_can_download_coolgames() {
  return false;
}
?>