<?php
/**
 * Arcade Game Feed
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
function myarcade_settings_agf() {

  $agf = myarcade_get_settings( 'agf' );
  ?>
  <h2 class="trigger"><?php myarcade_premium_span(); _e("Arcade Game Feed (AGF)", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <?php myarcade_premium_message(); ?>
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <i>
              <?php printf( __( "%s distributes Flash games.", 'myarcadeplugin' ), '<a href="http://arcadegamefeed.com" target="_blank">Arcade Games Feed</a>' ); ?>
            </i>
            <br /><br />
          </td>
        </tr>
        <tr><td colspan="2"><h3><?php _e("Feed URL", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="agf_url" value="<?php echo $agf['feed']; ?>" />
          </td>
          <td><i><?php _e("Edit this field only if Feed URL has been changed!", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Fetch Games", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="agf_limit" value="<?php echo $agf['limit']; ?>" />
          </td>
          <td><i><?php _e("How many games should be fetched at once.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Thumbnail Size", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="agf_thumbnail" id="agf_thumbnail">
              <option value="lgthumb" <?php myarcade_selected($agf['thumbnail'], 'lgthumb'); ?> ><?php _e("100x100", 'myarcadeplugin'); ?></option>
              <option value="thumbnail" <?php myarcade_selected($agf['thumbnail'], 'thumbnail'); ?> ><?php _e("180x135", 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select a thumbnail size.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Fetching", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="agf_cron_fetch" value="true" <?php myarcade_checked($agf['cron_fetch'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to fetch games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Fetch Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="agf_cron_fetch_limit" value="<?php echo $agf['cron_fetch_limit']; ?>" />
          </td>
          <td><i><?php _e("How many games should be fetched on every cron trigger?", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Publishing", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="agf_cron_publish" value="true" <?php myarcade_checked($agf['cron_publish'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Publish Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="agf_cron_publish_limit" value="<?php echo $agf['cron_publish_limit']; ?>" />
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
function myarcade_default_settings_agf() {
  return array(
    'feed'                => 'http://www.arcadegamefeed.com/feed.php',
    'limit'               => '50',
    'thumbnail'           => 'thumbnail',
    'cron_fetch'          => false,
    'cron_fetch_limit'    => '1',
    'cron_publish'        => false,
    'cron_publish_limit'  => '1',
  );
}

/**
 * Handle distributor settings update
 *
 * @version 5.19.0
 * @access  public
 * @return  void
 */
function myarcade_save_settings_agf() {

  myarcade_check_settings_nonce();

  $agf = array();
  $agf['feed'] = (isset($_POST['agf_url'])) ? esc_sql($_POST['agf_url']) : '';
  $agf['limit'] = (isset($_POST['agf_limit'])) ? intval( $_POST['agf_limit']) : '';
  $agf['thumbnail'] = filter_input( INPUT_POST, 'agf_thumbnail' );
  $agf['cron_fetch'] = (isset($_POST['agf_cron_fetch']) ) ? true : false;
  $agf['cron_fetch_limit'] = (isset($_POST['agf_cron_fetch_limit']) ) ? intval($_POST['agf_cron_fetch_limit']) : 1;
  $agf['cron_publish'] = (isset($_POST['agf_cron_publish']) ) ? true : false;
  $agf['cron_publish_limit'] = (isset($_POST['agf_cron_publish_limit']) ) ? intval($_POST['agf_cron_publish_limit']) : 1;
    // Update settings
    update_option('myarcade_agf', $agf);
}

/**
 * Display distributor fetch games options
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  void
 */
function myarcade_fetch_settings_agf() {

  ?>
  <div class="myarcade_border white hide mabp_680" id="agf">
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
function myarcade_get_fetch_options_agf() {

  // Get distributor settings
  $settings = myarcade_get_settings( 'agf' );

  if ( 'start' == filter_input( INPUT_POST, 'fetch' ) ) {
    // Set submitted fetching options
    $settings['limit'] = filter_input( INPUT_POST, 'agf_limit' );
  }

  return $settings;
}

/**
 * Fetch FlashGameDistribution games
 *
 * @version 5.26.0
 * @access  public
 * @param   array  $args Fetching parameters
 * @return  void
 */
function myarcade_feed_agf( $args = array() ) {
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
function myarcade_embedtype_agf() {
  return 'flash';
}
?>