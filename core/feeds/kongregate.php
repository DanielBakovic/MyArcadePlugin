<?php
/**
 * Kongregate
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
function myarcade_settings_kongregate() {
  $kongregate = get_option( 'myarcade_kongregate' );
  ?>
  <h2 class="trigger"><?php myarcade_premium_img() ?> <?php _e("Kongregate", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <?php myarcade_premium_message() ?>
            <br />
            <i>
              <?php _e("Kongegrate provides sponsored game XML feed.", 'myarcadeplugin'); ?> Click <a href="http://www.kongregate.com/games_for_your_site">here</a> to visit the Kongregrate site.
            </i>
            <br /><br />
          </td>
        </tr>
        <tr><td colspan="2"><h3><?php _e("Feed URL", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="kongurl" value="<?php echo $kongregate['feed']; ?>" />
          </td>
          <td><i><?php _e("Edit this field only if Feed URL has been changed!", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Publishing", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="kong_cron_publish" value="true" <?php myarcade_checked($kongregate['cron_publish'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Publish Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="kong_cron_publish_limit" value="<?php echo $kongregate['cron_publish_limit']; ?>" />
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
function myarcade_save_settings_kongregate() {
  // Do a secuirty check before updating the settings
  $myarcade_nonce = filter_input( INPUT_POST, 'myarcade_save_settings_nonce');
  if ( ! $myarcade_nonce || ! wp_verify_nonce( $myarcade_nonce, 'myarcade_save_settings' ) ) {
    // Security check failed .. don't update settings
    return;
  }

  // Kongeregate Settings
  $kongregate = array();

  $kongregate['feed'] = esc_url_raw( filter_input( INPUT_POST, 'kongurl' ) );
  $kongregate['cron_publish'] = (isset($_POST['kong_cron_publish']) ) ? true : false;
  $kongregate['cron_publish_limit']  = (isset($_POST['kong_cron_publish_limit']) ) ? intval($_POST['kong_cron_publish_limit']) : 1;

  // Update Settings
  update_option('myarcade_kongregate', $kongregate);
}

/**
 * Diesplay feed options on the fetch games page
 *
 * @version 5.0.0
 * @access  public
 * @return  void
 */
function myarcade_fetch_options_kongregate() {

}

/**
 * Fetch Kongregate games
 *
 * @version 5.0.0
 * @access  public
 * @param   array  $args Fetching parameters
 * @return  void
 */
function myarcade_feed_kongregate( $args = array() ) {
 myarcade_premium_message();
}
?>