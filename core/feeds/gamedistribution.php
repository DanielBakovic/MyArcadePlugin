<?php
/**
 * GameDistribution - http://www.gamedistribution.com/games/
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 */

/**
 * Save options
 *
 * @return  void
 */
function myarcade_save_settings_gamedistribution() {

  myarcade_check_settings_nonce();

  $settings = array();
  $settings['feed'] = esc_sql( filter_input( INPUT_POST, 'gamedistribution_url' ) );
  $settings['category'] = filter_input( INPUT_POST, 'gamedistribution_category' );
  $settings['collection'] = filter_input( INPUT_POST, 'gamedistribution_collection' );
  $settings['type'] = filter_input( INPUT_POST, 'gamedistribution_type' );

  $settings['cron_fetch'] = filter_input( INPUT_POST, 'gamedistribution_cron_fetch', FILTER_VALIDATE_BOOLEAN );
  $settings['cron_fetch_limit'] = filter_input( INPUT_POST, 'gamedistribution_cron_fetch_limit', FILTER_VALIDATE_INT, array( "options" => array( "default" => 1) ) );

  $settings['cron_publish'] = filter_input( INPUT_POST, 'gamedistribution_cron_publish', FILTER_VALIDATE_BOOLEAN );
  $settings['cron_publish_limit'] = filter_input( INPUT_POST, 'gamedistribution_cron_publish_limit', FILTER_VALIDATE_INT, array( "options" => array( "default" => 1) ) );

  // Update settings
  update_option( 'myarcade_gamedistribution', $settings );
}

/**
 * Display distributor settings on admin page
 *
 * @return  void
 */
function myarcade_settings_gamedistribution() {
  $gamedistribution = myarcade_get_settings( 'gamedistribution' );

  /**
   * since 5.33.0
   * Update distributor URL
   */
  if ( strpos( $gamedistribution['feed'], 'games.gamedistribution.com/All/' ) !== FALSE ) {
    $default_settings = myarcade_default_settings_gamedistribution();
    $gamedistribution['feed'] = $default_settings['feed'];
  }
  ?>
  <h2 class="trigger"><?php _e( "GameDistribution", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <i>
               <?php printf( __( "%s distributes Flash and HTML5 games. In order to earn money with GameDistribution's games you will need to join the partner program %shere%s", 'myarcadeplugin' ), '<a href="http://www.gamedistribution.com/" target="_blank">GameDistribution</a>', '<a href="http://www.gamedistribution.com/joinus" target="_blank">', '</a>' ); ?>
            </i>
            <br /><br />
          </td>
        </tr>
        <tr><td colspan="2"><h3><?php _e("Feed URL", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="gamedistribution_url" value="<?php echo $gamedistribution['feed']; ?>" />
          </td>
          <td><i><?php _e("Edit this field only if Feed URL has been changed!", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Collection", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="gamedistribution_collection" id="gamedistribution_collection">
              <option value="all" <?php myarcade_selected( $gamedistribution['collection'], 'all' ); ?>><?php _e( 'All games', 'myarcadeplugin' ); ?></option>
              <option value="exclusive" <?php myarcade_selected( $gamedistribution['collection'], 'exclusive' ); ?>><?php _e( 'Exclusive games', 'myarcadeplugin' ); ?></option>
              <option value="best" <?php myarcade_selected( $gamedistribution['collection'], 'best' ); ?>><?php _e( 'Best new games', 'myarcadeplugin' ); ?></option>
              <option value="featured" <?php myarcade_selected( $gamedistribution['collection'], 'featured' ); ?>><?php _e( 'Hot Games', 'myarcadeplugin' ); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select game collections.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Category", 'myarcadeplugin'); ?></h3></td></tr>

        <?php
        $gamedistribution_categories = array(
          'All' => __( "All games", 'myarcadeplugin' ),
          "1 Player" => "1 Player",
          "2 Player" => "2 Player",
          "3D" => "3D",
          "Action" => "Action",
          "Addicting" => "Addicting",
          "Adventure" => "Adventure",
          "Android" => "Android",
          "Animal" => "Animal",
          "Arcade" => "Arcade",
          "Basketball" => "Basketball",
          "Bubble Shooter" => "Bubble Shooter",
          "Car" => "Car",
          "Card" => "Card",
          "Cartoon" => "Cartoon",
          "Catching" => "Catching",
          "Celebrity" => "Celebrity",
          "Clicker" => "Clicker",
          "Cooking" => "Cooking",
          "Decoration" => "Decoration",
          "Drawing" => "Drawing",
          "Dress Up" => "Dress Up",
          "Educational" => "Educational",
          "Escape" => "Escape",
          "Flying" => "Flying",
          "Football" => "Football",
          "Fun" => "Fun",
          "Funny" => "Funny",
          "Girls" => "Girls",
          "Halloween" => "Halloween",
          "Hidden Objects" => "Hidden Objects",
          "HTML5" => "HTML5",
          "Kids" => "Kids",
          "Mahjong" => "Mahjong",
          "Make Over" => "Make Over",
          "Make up" => "Make up",
          "Match-3" => "Match-3",
          "Mathematical" => "Mathematical",
          "Memory" => "Memory",
          "Motorbike" => "Motorbike",
          "Multiplayer" => "Multiplayer",
          "Music" => "Music",
          "Parking" => "Parking",
          "Physics" => "Physics",
          "Platform" => "Platform",
          "Puzzle" => "Puzzle",
          "Quiz" => "Quiz",
          "Racing" => "Racing",
          "Rpg" => "Rpg",
          "Running" => "Running",
          "Shoot 'Em Up" => "Shoot 'Em Up",
          "Shooter" => "Shooter",
          "Shooting" => "Shooting",
          "Simulation" => "Simulation",
          "Skill" => "Skill",
          "Soccer" => "Soccer",
          "Sports" => "Sports",
          "Tower Defense" => "Tower Defense",
        );
        ?>
        <tr>
          <td>
            <select size="1" name="gamedistribution_category" id="gamedistribution_category">
              <?php foreach ( $gamedistribution_categories as $key => $value ) : ?>
               <option value="<?php echo $key; ?>" <?php myarcade_selected( $gamedistribution['category'], $key ); ?>><?php echo $value; ?></option>
              <?php endforeach; ?>
            </select>
          </td>
          <td><i><?php _e("Select which games you would like to fetch.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Fetching", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="checkbox" name="gamedistribution_cron_fetch" value="true" <?php myarcade_checked( $gamedistribution['cron_fetch'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to fetch games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Fetch Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="gamedistribution_cron_fetch_limit" value="<?php echo $gamedistribution['cron_fetch_limit']; ?>" />
          </td>
          <td><i><?php _e("How many games should be fetched on every cron trigger?", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Publishing", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="gamedistribution_cron_publish" value="true" <?php myarcade_checked($gamedistribution['cron_publish'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Publish Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="gamedistribution_cron_publish_limit" value="<?php echo $gamedistribution['cron_publish_limit']; ?>" />
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
 * @return  array Default settings
 */
function myarcade_default_settings_gamedistribution() {
  return array(
    'feed'          => 'https://catalog.api.gamedistribution.com/api/v1.0/rss/All/',
    'limit'         => '40',
    'type'          => 'all',
    'collection'    => 'all',
    'category'      => 'All',
    'cron_fetch'    => false,
    'cron_fetch_limit' => '1',
    'cron_publish'  => false,
    'cron_publish_limit' => '1',
  );
}

/**
 * Generate an options array with submitted fetching parameters
 *
 * @return  array Fetching options
 */
function myarcade_get_fetch_options_gamedistribution() {

  // Get distributor settings
  $settings = myarcade_get_settings( 'gamedistribution' );
  $defaults = myarcade_default_settings_gamedistribution();
  $settings = wp_parse_args( $settings, $defaults );

  $settings['method'] = 'latest';
  $settings['offset'] = 1;

  if ( 'start' == filter_input( INPUT_POST, 'fetch' ) ) {
    $settings['limit']   = filter_input( INPUT_POST, 'limitgamedistribution', FILTER_VALIDATE_INT, array( "options" => array( "default" => 40 ) ) );
    $settings['method']  = filter_input( INPUT_POST, 'fetchmethodgamedistribution', FILTER_SANITIZE_STRING, array( "options" => array( "default" => 'latest') ) );
    $settings['offset']  = filter_input( INPUT_POST, 'offsetgamedistribution', FILTER_SANITIZE_STRING, array( "options" => array( "default" => '1') ) );
  }

  return $settings;
}

/**
 * Display distributor fetch games options
 *
 * @return  void
 */
function myarcade_fetch_settings_gamedistribution() {

  $gamedistribution = myarcade_get_fetch_options_gamedistribution();
  ?>

  <div class="myarcade_border white hide mabp_680" id="gamedistribution">
    <div style="float:left;width:150px;">
      <input type="radio" name="fetchmethodgamedistribution" value="latest" <?php myarcade_checked($gamedistribution['method'], 'latest');?>>
    <label><?php _e("Latest Games", 'myarcadeplugin'); ?></label>
    <br />
    <input type="radio" name="fetchmethodgamedistribution" value="offset" <?php myarcade_checked($gamedistribution['method'], 'offset');?>>
    <label><?php _e("Use Offset", 'myarcadeplugin'); ?></label>
    </div>
    <div class="myarcade_border" style="float:left;padding-top: 5px;background-color: #F9F9F9">
    Fetch <input type="number" name="limitgamedistribution" value="<?php echo $gamedistribution['limit']; ?>" /> games <span id="offsgamedistribution" class="hide">from page <input id="radiooffsgamedistribution" type="number" name="offsetgamedistribution" value="<?php echo $gamedistribution['offset']; ?>" /> </span>
    </div>
    <div class="clear"></div>
  </div>
  <?php
}

/**
 * Retrieve available distributor's categories mapped to MyArcadePlugin categories
 *
 * @return  array Distributor categories
 */
function myarcade_get_categories_gamedistribution() {
  return array(
    "Action"      => true,
    "Adventure"   => true,
    "Arcade"      => true,
    "Board Game"  => "Card,Clicker,Mahjong",
    "Casino"      => false,
    "Defense"     => "Defense,Tower Defense",
    "Customize"   => "Make Over,Make up",
    "Dress-Up"    => "Celebrity,Dress Up,Girls",
    "Driving"     => "Car,Motorbike,Parking,Racing",
    "Education"   => "Educational,Kids,Mathematical",
    "Fighting"    => false,
    "Jigsaw"      => false,
    "Multiplayer" => "2 Player,Multiplayer",
    "Other"       => "3D,Addicting,Android,Animal,Cartoon,Catching,Cooking,Decoration,1 Player,Drawing,Fun,Funny,Halloween,HTML5,Running,Skill",
    "Puzzles"     => "Hidden Objects,Match-3,Memory,Puzzle",
    "Rhythm"      => "Music",
    "Shooting"    => "Shoot 'Em Up,Shooter,Shooting",
    "Sports"      => "Basketball,Football,Soccer,Sports",
    "Strategy"    => "Bubble Shooter,Escape,Flying,Physics,Platform,Quiz,Rpg,Simulation",
  );
}

/**
 * Fetch games
 *
 * @param   array  $args Fetching parameters
 * @return  void
 */
function myarcade_feed_gamedistribution( $args = array() ) {

  $defaults = array(
    'echo'     => false,
    'settings' => array(),
  );

  $args = wp_parse_args( $args, $defaults );
  extract($args);

  $new_games = 0;
  $add_game = false;

  $gamedistribution = myarcade_get_fetch_options_gamedistribution();
  $gamedistribution_categories = myarcade_get_categories_gamedistribution();
  $feedcategories = myarcade_get_settings( 'categories' );
  $general = myarcade_get_settings( 'general' );

  // Init settings var's
  if ( ! empty($settings) ) {
    $settings = array_merge( $gamedistribution, $settings );
  }
  else {
    $settings = $gamedistribution;
  }

  if ( ! isset($settings['method']) ) {
    $settings['method'] = 'latest';
  }

  // Generate the feed URL
  $feed = add_query_arg( array( "format" => "json", "collection" => $settings['collection'] ), trim( $settings['feed'] ) );

  // Check if there is a feed limit. If not, feed all games
  if ( ! empty( $settings['limit'] ) ) {
    $feed = add_query_arg( array( "amount" => $settings['limit'] ), $feed );
  }

  $feed = add_query_arg( array("page" => $settings['offset'] ), $feed );

  if ( $settings['category'] != 'all' ) {
    $feed = add_query_arg( array("category" => rawurlencode( $settings['category'] ) ), $feed );
  }

  if ( isset( $general['types'] ) && 'mobile' == $general['types'] ) {
    $feed = add_query_arg( array( "type"  => "html5", "mobile" => 1 ), $feed );
  }
  else {
    $feed = add_query_arg( array( "type"  => "all" ), $feed );
  }

  // Include required fetch functions
  require_once( MYARCADE_CORE_DIR . '/fetch.php' );

  // Fetch games
  $json_games = myarcade_fetch_games( array( 'url' => trim( $feed ), 'service' => 'json', 'echo' => $echo ) );

  //====================================
  if ( !empty($json_games ) ) {
    foreach ( $json_games as $game_obj ) {

      $game = new stdClass();
      $game->uuid     = crc32( $game_obj->title ) . '_gamedistribution';
      // Generate a game tag for this game
      $game->game_tag = md5( $game_obj->title . 'gamedistribution' );

      $add_game   = false;

      $categories = array();

      // Map categories
      if ( is_array( $game_obj->categoryList ) ) {
        foreach ( $game_obj->categoryList as $category ) {
          if ( isset( $category->name ) ) {
            $categories[] = $category->name;
          }
        }
      }
      else {
        $categories[] = 'Other';
      }


      $categories_string = 'Other';

      foreach( $categories as $gamecat ) {
        $gamecat = htmlspecialchars_decode( $gamecat );

        foreach ( $feedcategories as $feedcat ) {
          if ( $feedcat['Status'] == 'checked' ) {
            if ( ! empty( $gamedistribution_categories[ $feedcat['Name'] ] ) ) {
              // Set category name to check
              if ( $gamedistribution_categories[ $feedcat['Name'] ] === true ) {
                $cat_name = $feedcat['Name'];
              }
              else {
                $cat_name = $gamedistribution_categories[ $feedcat['Name'] ];
              }
            }

            if ( strpos( $cat_name, $gamecat ) !== false ) {
              $add_game = true;
              $categories_string = $feedcat['Name'];
              break 2;
            }
          }
        }
      } // END - Category-Check


      if ( ! $add_game ) {
        continue;
      }

      if ( "html5" == $game_obj->gameType ) {
        $game->type = 'gamedistribution';
      }
      else {
        $game->type = "custom";
      }

      $game->name           = esc_sql( $game_obj->title );
      $game->slug           = myarcade_make_slug( $game_obj->title );
      $game->description    = esc_sql( $game_obj->description );
      $game->instructions   = esc_sql( $game_obj->instructions );
      $game->categs         = $categories_string;
      $game->width          = intval( $game_obj->width );
      $game->height         = intval( $game_obj->height );
      $game->swf_url        = esc_sql( $game_obj->url );

      // Get the thumbnail
      if ( is_array( $game_obj->assetList ) ) {
        $assets = array();

        foreach ( $game_obj->assetList as $asset ) {
          if ( isset( $asset->name ) ) {
            $assets[] = $asset->name;
          }
        }

        // Try to use 512x512 image as featured image. If not available use the last image (usually the last image is the smallest one)
        $game->thumbnail_url = end ( $assets );

        $needle = '512x512';
        $results = array_keys( array_filter( $assets, function($var) use ($needle) {
          return strpos($var, $needle) !== false;
        }));

        if ( ! empty( $results[0] ) ) {
          // We found the 512x512 image. Overwrite the thumbnail url
          $game->thumbnail_url = $assets[ $results[0] ];
        }
      }

      // Get game tags
      if ( is_array( $game_obj->tagList ) ) {
        $game->tags = '';

        foreach ( $game_obj->tagList as $tag ) {
          if ( isset( $tag->name ) ) {
            $game->tags .= $tag->name . ',';
          }
        }

        $game->tags = rtrim( $game->tags, ',' );
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
 * @return  string Embed Method
 */
function myarcade_embedtype_gamedistribution() {
  return 'iframe';
}

/**
 * Return if games can be downloaded by this distirbutor
 *
 * @return  bool True if games can be downloaded
 */
function myarcade_can_download_gamedistribution() {
  return false;
}
?>