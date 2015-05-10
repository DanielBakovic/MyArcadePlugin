<?php
/**
 * Scirra
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
function myarcade_settings_scirra() {
  $scirra = get_option( 'myarcade_scirra' );
  ?>
  <h2 class="trigger"><?php myarcade_premium_img() ?> <?php _e("Scirra", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <?php myarcade_premium_message() ?>
            <br />
            <i>
              <?php _e("Scirra provides sponsored game XML feed.", 'myarcadeplugin'); ?> Click <a href="http://www.scirra.com/arcade/free-games-for-your-website" target="_blank">here</a> to visit the Scirra site.
            </i>
            <br /><br />
          </td>
        </tr>
        <tr><td colspan="2"><h3><?php _e("Feed URL", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="scirra_url" value="<?php echo $scirra['feed']; ?>" />
          </td>
          <td><i><?php _e("Edit this field only if Feed URL has been changed!", 'myarcadeplugin'); ?></i></td>
        </tr>
        <tr><td colspan="2"><h3><?php _e("Thumbail Size", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <select name="scirra_thumbnail">
              <option value="small" <?php myarcade_selected($scirra['thumbnail'], "small"); ?>>Small (72x60)</option>
              <option value="medium" <?php myarcade_selected($scirra['thumbnail'], "medium"); ?>>Medium (120x100)</option>
              <option value="big" <?php myarcade_selected($scirra['thumbnail'], "big"); ?>>Big (280x233)</option>
            </select>
          </td>
          <td><i><?php _e("Select the preferred game thumbnail size. Default: Medium.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Publishing", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="scirra_cron_publish" value="true" <?php myarcade_checked($scirra['cron_publish'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Publish Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="scirra_cron_publish_limit" value="<?php echo $scirra['cron_publish_limit']; ?>" />
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
function myarcade_save_settings_scirra() {

  // Do a secuirty check before updating the settings
  $myarcade_nonce = filter_input( INPUT_POST, 'myarcade_save_settings_nonce');
  if ( ! $myarcade_nonce || ! wp_verify_nonce( $myarcade_nonce, 'myarcade_save_settings' ) ) {
    // Security check failed .. don't update settings
    return;
  }

    // Scirra Settings
  $scirra = array();
  if ( isset($_POST['scirra_url'])) $scirra['feed'] = esc_url_raw($_POST['scirra_url']); else $scirra['feed'] = '';
  if ( isset($_POST['scirra_thumbnail'])) $scirra['thumbnail'] = trim($_POST['scirra_thumbnail']); else $scirra['thumbnail'] = 'medium';

  $scirra['cron_publish']        = (isset($_POST['scirra_cron_publish']) ) ? true : false;
  $scirra['cron_publish_limit']  = (isset($_POST['scirra_cron_publish_limit']) ) ? intval($_POST['scirra_cron_publish_limit']) : 1;
    // Update Settings
    update_option('myarcade_scirra', $scirra);
}

/**
 * Diesplay feed options on the fetch games page
 *
 * @version 5.0.0
 * @access  public
 * @return  void
 */
function myarcade_fetch_options_scirra() {

}

/**
 * Fetch Scirra games
 *
 * @version 5.0.0
 * @access  public
 * @param   array  $args Fetching parameters
 * @return  void
 */
function myarcade_feed_scirra( $args = array() ) {
  myarcade_premium_message();
}
?>