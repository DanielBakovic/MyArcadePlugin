<?php
/**
 * FreeGamesForYourWebsite
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
 * @version 5.15.0
 * @access  public
 * @return  void
 */
function myarcade_settings_fog() {
  $fog = myarcade_get_settings( 'fog' );
  ?>
  <h2 class="trigger"><?php myarcade_premium_span(); _e("FreeGamesForYourWebsite (FOG)", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <?php myarcade_premium_message(); ?>
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <p>
              <i>
                <?php printf( __( "%s distributes Flash and Unity3D games.", 'myarcadeplugin' ), '<a href="http://www.freegamesforyourwebsite.com" target="_blank">FreeGamesForYourWebsite</a>' ); ?>
              </i>
            </p>
          </td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Feed URL", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="fogurl" value="<?php echo $fog['feed']; ?>" />
          </td>
          <td><i><?php _e("Edit this field only if Feed URL has been changed!", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Fetch Games", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="foglimit" value="<?php echo $fog['limit']; ?>" />
          </td>
          <td><i><?php _e("How many games should be fetched at once. Enter 'all' (without quotes) if you want to fetch all games. Otherwise enter an integer.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Thumbnail Size", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="fogthumbsize" id="fogthumbsize">
              <option value="small" <?php myarcade_selected($fog['thumbsize'], 'small'); ?> ><?php _e("Small (100x100)", 'myarcadeplugin'); ?></option>
              <option value="medium" <?php myarcade_selected($fog['thumbsize'], 'medium'); ?> ><?php _e("Medium (180x135)", 'myarcadeplugin'); ?></option>
              <option value="large" <?php myarcade_selected($fog['thumbsize'], 'large'); ?> ><?php _e("Large (300x300)", 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select the size of the thumbnails that should be used for games from FreeGamesForYourWebsite. Default size is small (100x100).", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Use Large Thumbnails as Screenshots", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="fogscreen" value="true" <?php myarcade_checked($fog['screenshot'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Check this if you want to use large thumbnails (300x300px) from the feed as game screenshots", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Game Categories", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="fogtag" id="fogtag">
              <option value="all" <?php myarcade_selected($fog['tag'], 'all'); ?>>All Categories</option>
              <option value="3D" <?php myarcade_selected($fog['tag'], '3D'); ?>><?php _e('3D Games', 'myarcadeplugin'); ?></option>
              <option value="Adventure" <?php myarcade_selected($fog['tag'], 'Adventure'); ?>><?php _e('Adventure Games', 'myarcadeplugin'); ?></option>
              <option value="Defense" <?php myarcade_selected($fog['tag'], 'Defense'); ?>><?php _e('Defense Games', 'myarcadeplugin'); ?></option>
              <option value="Driving" <?php myarcade_selected($fog['tag'], 'Driving'); ?>><?php _e('Driving Games', 'myarcadeplugin'); ?></option>
              <option value="Flying" <?php myarcade_selected($fog['tag'], 'Flying'); ?>><?php _e('Flying Games', 'myarcadeplugin'); ?></option>
              <option value="Multiplayer" <?php myarcade_selected($fog['tag'], 'Multiplayer'); ?>><?php _e('Multiplayer Games', 'myarcadeplugin'); ?></option>
              <option value="Puzzle" <?php myarcade_selected($fog['tag'], 'Puzzle'); ?>><?php _e('Puzzle Games', 'myarcadeplugin'); ?></option>
              <option value="Shooting" <?php myarcade_selected($fog['tag'], 'Shooting'); ?>><?php _e('Shooting Games', 'myarcadeplugin'); ?></option>
              <option value="Sports" <?php myarcade_selected($fog['tag'], 'Sports'); ?>><?php _e('Sports Games', 'myarcadeplugin'); ?></option>
              <option value="unity-games" <?php myarcade_selected($fog['tag'], 'unity-games'); ?>><?php _e('Unity Games', 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select a game category that you would like to fetch from FreeGamesForYourWebsite.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Language", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="foglanguage" id="foglanguage">
              <option value="ar" <?php myarcade_selected($fog['language'], 'ar'); ?>><?php _e('Arabic', 'myarcadeplugin'); ?></option>
              <option value="en" <?php myarcade_selected($fog['language'], 'en'); ?>><?php _e('English', 'myarcadeplugin'); ?></option>
              <option value="fr" <?php myarcade_selected($fog['language'], 'fr'); ?>><?php _e('French', 'myarcadeplugin'); ?></option>
              <option value="de" <?php myarcade_selected($fog['language'], 'de'); ?>><?php _e('German', 'myarcadeplugin'); ?></option>
              <option value="el" <?php myarcade_selected($fog['language'], 'el'); ?>><?php _e('Greek', 'myarcadeplugin'); ?></option>
              <option value="ro" <?php myarcade_selected($fog['language'], 'ro'); ?>><?php _e('Romanian', 'myarcadeplugin'); ?></option>
              <option value="es" <?php myarcade_selected($fog['language'], 'es'); ?>><?php _e('Spanish', 'myarcadeplugin'); ?></option>
              <option value="ur" <?php myarcade_selected($fog['language'], 'ur'); ?>><?php _e('Urdu', 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select a game language.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Fetching", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="fog_cron_fetch" value="true" <?php myarcade_checked($fog['cron_fetch'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to fetch games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Fetch Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="fog_cron_fetch_limit" value="<?php echo $fog['cron_fetch_limit']; ?>" />
          </td>
          <td><i><?php _e("How many games should be fetched on every cron trigger?", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Publishing", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="fog_cron_publish" value="true" <?php myarcade_checked($fog['cron_publish'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Publish Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="fog_cron_publish_limit" value="<?php echo $fog['cron_publish_limit']; ?>" />
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
 * @access  public
 * @return  array Default settings
 */
function myarcade_default_settings_fog() {
  return array(
    'feed'          => 'http://www.freegamesforyourwebsite.com/feeds/games/',
    'limit'         => '20',
    'thumbsize'     => 'medium',
    'screenshot'    => true,
    'tag'           => 'all',
    'language'      => 'en',
    'cron_fetch'    => false,
    'cron_fetch_limit' => '1',
    'cron_publish'  => false,
    'cron_publish_limit' => '1',
    'status'        => 'publish',
  );
}

/**
 * Handle distributor settings update
 *
 * @version 5.19.0
 * @access  public
 * @return  void
 */
function myarcade_save_settings_fog() {

  myarcade_check_settings_nonce();

  // FreeGamesForYourSite Settings
  $fog = array();
  if ( isset($_POST['fogurl'])) $fog['feed'] = esc_url_raw($_POST['fogurl']); else $fog['feed'] = '';
  if ( isset($_POST['foglimit'])) $fog['limit'] = sanitize_text_field($_POST['foglimit']); else $fog['limit'] = '20';
  if ( isset($_POST['fogthumbsize'])) $fog['thumbsize'] = trim($_POST['fogthumbsize']); else $fog['thumbsize'] = 'small';
  if ( isset($_POST['fogscreen'])) $fog['screenshot'] = true; else $fog['screenshot'] = false;
  if ( isset($_POST['fogtag'])) $fog['tag'] = sanitize_text_field($_POST['fogtag']); else $fog['tag'] = 'all';
  if ( isset($_POST['foglanguage'])) $fog['language'] = sanitize_text_field($_POST['foglanguage']); else $fog['language'] = 'en';

  $fog['cron_fetch']          = (isset($_POST['fog_cron_fetch'])) ? true : false;
  $fog['cron_fetch_limit']    = (isset($_POST['fog_cron_fetch_limit']) ) ? intval($_POST['fog_cron_fetch_limit']) : 1;
  $fog['cron_publish']        = (isset($_POST['fog_cron_publish']) ) ? true : false;
  $fog['cron_publish_limit']  = (isset($_POST['fog_cron_publish_limit']) ) ? intval($_POST['fog_cron_publish_limit']) : 1;

  // Update Settings
  update_option('myarcade_fog', $fog);
}

/**
 * Display distributor fetch games options
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  void
 */
function myarcade_fetch_settings_fog() {

  ?>
  <div class="myarcade_border white hide mabp_680" id="fog">
    <?php myarcade_premium_message(); ?>
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
function myarcade_get_fetch_options_fog() {

  // Get distributor settings
  $settings = myarcade_get_settings( 'fog' );

  // Set default fetching options
  $settings['method']     = 'latest';

  if ( 'start' == filter_input( INPUT_POST, 'fetch' ) ) {
    // Set submitted fetching options
    $settings['method']  = filter_input( INPUT_POST, 'fetchmethodfog' );
    $settings['limit']   = filter_input( INPUT_POST, 'limitfog' );
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
function myarcade_get_categories_fog() {
  return array(
    "Action"      => false,
    "Adventure"   => true,
    "Arcade"      => false,
    "Board Game"  => false,
    "Casino"      => false,
    "Defense"     => true,
    "Customize"   => false,
    "Dress-Up"    => false,
    "Driving"     => true,
    "Education"   => false,
    "Fighting"    => false,
    "Jigsaw"      => false,
    "Multiplayer" => true,
    "Other"       => true,
    "Puzzles"     => true,
    "Rhythm"      => false,
    "Shooting"    => true,
    "Sports"      => true,
    "Strategy"    => false,
  );
}

/**
 * Fetch FreeGamesForYourWebsite games
 *
 * @access  public
 * @param   array  $args Fetching parameters
 * @return  void
 */
function myarcade_feed_fog( $args = array() ) {
  ?>
  <div class="myarcade_border white mabp_680">
  <?php myarcade_premium_message(); ?>
  </div>
  <?php
}

/**
 * Return game embed method
 *
 * @version 5.18.0
 * @since   5.18.0
 * @access  public
 * @return  string Embed Method
 */
function myarcade_embedtype_fog() {
  return 'flash';
}
?>