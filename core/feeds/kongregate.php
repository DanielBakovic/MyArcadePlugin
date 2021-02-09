<?php
/**
 * Kongregate - https://www.kongregate.com/games_for_your_site
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 * @package MyArcadePlugin/Feeds
 */

// No direct Access.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Display distributor settings on admin page
 */
function myarcade_settings_kongregate() {
  $kongregate = myarcade_get_settings( 'kongregate' );
  ?>
	<h2 class="trigger"><?php _e( 'Kongregate', 'myarcadeplugin' ); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <i>
							<?php printf( __( '%s distributes Flash games.', 'myarcadeplugin' ), '<a href="http://www.kongregate.com/games_for_your_site" target="_blank">Kongegrate</a>' ); ?>
            </i>
            <br /><br />
          </td>
        </tr>
				<tr><td colspan="2"><h3><?php _e( 'Feed URL', 'myarcadeplugin' ); ?></h3></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="kongurl" value="<?php echo $kongregate['feed']; ?>" />
          </td>
					<td><i><?php _e( 'Edit this field only if Feed URL has been changed!', 'myarcadeplugin' ); ?></i></td>
        </tr>

				<tr><td colspan="2"><h3><?php _e( 'Automated Game Publishing', 'myarcadeplugin' ); ?></h3></td></tr>

        <tr>
          <td>
						<input type="checkbox" name="kong_cron_publish" value="true" <?php myarcade_checked( $kongregate['cron_publish'], true ); ?> /><label class="opt">&nbsp;<?php _e( 'Yes', 'myarcadeplugin' ); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

				<tr><td colspan="2"><h4><?php _e( 'Publish Games', 'myarcadeplugin' ); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="kong_cron_publish_limit" value="<?php echo $kongregate['cron_publish_limit']; ?>" />
          </td>
					<td><i><?php _e( 'How many games should be published on every cron trigger?', 'myarcadeplugin' ); ?></i></td>
        </tr>

      </table>
			<input class="button button-primary" id="submit" type="submit" name="submit" value="<?php _e( 'Save Settings', 'myarcadeplugin' ); ?>" />
    </div>
  </div>
  <?php
}

/**
 * Retrieve distributor's default settings
 *
 * @return  array Default settings
 */
function myarcade_default_settings_kongregate() {
  return array(
    'feed'          => 'http://www.kongregate.com/games_for_your_site.xml',
    'cron_publish'  => false,
    'cron_publish_limit' => '1',
  );
}

/**
 * Handle distributor settings update
 */
function myarcade_save_settings_kongregate() {

  myarcade_check_settings_nonce();

	// Kongeregate Settings.
  $kongregate = array();

  $kongregate['feed'] = esc_url_raw( filter_input( INPUT_POST, 'kongurl' ) );
  $kongregate['cron_publish'] = (isset($_POST['kong_cron_publish']) ) ? true : false;
  $kongregate['cron_publish_limit']  = (isset($_POST['kong_cron_publish_limit']) ) ? intval($_POST['kong_cron_publish_limit']) : 1;

	// Update Settings.
  update_option('myarcade_kongregate', $kongregate);
}

/**
 * Retrieve available distributor's categories mapped to MyArcadePlugin categories
 *
 * @return  array Distributor categories
 */
function myarcade_get_categories_kongregate() {
  return array(
		'Action'      => true,
		'Adventure'   => 'Adventure & RPG',
		'Arcade'      => false,
		'Board Game'  => false,
		'Casino'      => false,
		'Defense'     => false,
		'Customize'   => false,
		'Dress-Up'    => false,
		'Driving'     => false,
		'Education'   => false,
		'Fighting'    => false,
		'Jigsaw'      => false,
		'Multiplayer' => true,
		'Other'       => 'Idle',
		'Puzzles'     => 'Puzzle',
		'Rhythm'      => 'Music & More',
		'Shooting'    => 'Shooter',
		'Sports'      => 'Sports & Racing',
		'Strategy'    => 'Strategy & Defense',
  );
}

/**
 * Fetch Kongregate games
 *
 * @param array $args Fetching parameters.
 */
function myarcade_feed_kongregate( $args = array() ) {

	$defaults = array(
		'echo'     => false,
		'settings' => array(),
	);

	$args = wp_parse_args( $args, $defaults );
	extract( $args );

	$new_games = 0;
	$add_game  = false;

	$kongregate            = myarcade_get_settings( 'kongregate' );
	$kongregate_categories = myarcade_get_categories_kongregate();
	$feedcategories        = get_option( 'myarcade_categories' );

	// Include required fetch functions.
	require_once MYARCADE_CORE_DIR . '/fetch.php';

	$games = myarcade_fetch_games(
		array(
			'url'     => $kongregate['feed'],
			'service' => 'xml',
			'echo'    => $echo,
		)
	);

	if ( ! empty( $games ) ) {
		foreach ( $games as $game_obj ) {

			$game = new stdClass();

			$game->uuid = (string) $game_obj->id;

			// Generate a game tag for this game.
			$game->game_tag = md5( $game_obj->id . $game_obj->title . 'kongregate' );

			$add_game = false;

			// Get game categories into an array.
			$categories = explode( ',', $game_obj->category );

			// Initialize the category name.
			$categories_string = 'Other';

			// Loop trough game categories.
			foreach ( $categories as $gamecat ) {
				// Loop trough MyArcade categories.
				foreach ( $feedcategories as $feedcat ) {
					if ( 'checked' === $feedcat['Status'] ) {
						if ( ! empty( $kongregate_categories[ $feedcat['Name'] ] ) ) {
							// Set category name to check.
							if ( true === $kongregate_categories[ $feedcat['Name'] ] ) {
								$cat_name = $feedcat['Name'];
							} else {
								$cat_name = $kongregate_categories[ $feedcat['Name'] ];
							}
						}

						if ( strpos( $cat_name, $gamecat ) !== false ) {
							$add_game          = true;
							$categories_string = $feedcat['Name'];
							break 2;
						}
					}
				}
			} // END - Category-Check

			// Should we add this game?
			if ( ! $add_game ) {
				continue;
			}

			// Add game.
			$game->type         = 'kongregate';
			$game->slug         = myarcade_make_slug( $game_obj->title );
			$game->name         = esc_sql( $game_obj->title );
			$game->description  = esc_sql( wp_strip_all_tags( $game_obj->description ) );
			$game->instructions = esc_sql( wp_strip_all_tags( $game_obj->instructions ) );
			$game->rating       = esc_sql( $game_obj->rating );
			$game->categs       = esc_sql( $categories_string );
			$game->swf_url      = esc_sql( $game_obj->flash_file );
			$game->width        = esc_sql( $game_obj->width );
			$game->height       = esc_sql( $game_obj->height );

			// remove ? from  the thumbnail url.
			$thumb_array         = explode( '?', $game_obj->thumbnail );
			$game->thumbnail_url = esc_sql( $thumb_array[0] );

			// Add game to the database.
			if ( myarcade_add_fetched_game( $game, $args ) ) {
				$new_games++;
			}
		}
}

	// Show, how many games have been fetched.
	myarcade_fetched_message( $new_games, $echo );
}

/**
 * Return game embed method
 *
 * @return  string Embed Method
 */
function myarcade_embedtype_kongregate() {
  return 'flash';
}
