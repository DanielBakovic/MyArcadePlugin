<?php
/**
 * PlayToMax
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 * @copyright 2009-2016 Daniel Bakovic
 * @license http://myarcadeplugin.com
 */

/**
 * Copyright @ Daniel Bakovic - contact@myarcadeplugin.com
 * Do not modify! Do not sell! Do not distribute! -
 * Check our license Terms!
 */

/**
 * Save options function
 *
 * @version 5.23.0
 * @since   5.23.0
 * @access  public
 * @return  void
 */
function myarcade_save_settings_playtomax() {

  myarcade_check_settings_nonce();

  $settings = array();
  $settings['feed'] = esc_sql( filter_input( INPUT_POST, 'playtomax_url' ) );
  $settings['category'] = filter_input( INPUT_POST, 'playtomax_category' );
  $settings['cron_publish'] = filter_input( INPUT_POST, 'playtomax_cron_publish', FILTER_VALIDATE_BOOLEAN );
  $settings['cron_publish_limit'] = filter_input( INPUT_POST, 'playtomax_cron_publish_limit', FILTER_VALIDATE_INT, array( "options" => array( "default" => 1) ) );

  // Update settings
  update_option( 'myarcade_playtomax', $settings );
}

/**
 * Display distributor settings on admin page
 *
 * @version 5.23.0
 * @since   5.23.0
 * @access  public
 * @return  void
 */
function myarcade_settings_playtomax() {
  $playtomax = myarcade_get_settings( 'playtomax' );
  ?>
  <h2 class="trigger"><?php myarcade_premium_span(); _e("PlayToMax", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <?php myarcade_premium_message(); ?>
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <i>
              <?php printf( __( "%s distributes HTML5 games.", 'myarcadeplugin' ), '<a href="http://playtomax.com" target="_blank">PlayToMax</a>' ); ?>
            </i>
            <br /><br />
          </td>
        </tr>
        <tr><td colspan="2"><h3><?php _e("Feed URL", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="playtomax_url" value="<?php echo $playtomax['feed']; ?>" />
          </td>
          <td><i><?php _e("Edit this field only if Feed URL has been changed!", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Category", 'myarcadeplugin'); ?></h3></td></tr>

        <?php
        $playtomax_categories = array(
          'all' => __("All games", 'myarcadeplugin' ),
          "Arcade" => "Arcade",
          "Girls" => "Girls",
          "Match3" => "Match3",
          "Physics" => "Physics",
          "Puzzle" => "Puzzle",
          "Ragdoll" => "Ragdoll",
          "Remover" => "Remover",
          "Shooter" => "Shooter",
        );
        ?>
        <tr>
          <td>
            <select size="1" name="playtomax_category" id="playtomax_category">
              <?php foreach ( $playtomax_categories as $key => $value ) : ?>
               <option value="<?php echo $key; ?>" <?php myarcade_selected( $playtomax['category'], $key ); ?>><?php echo $value; ?></option>
              <?php endforeach; ?>
            </select>
          </td>
          <td><i><?php _e("Select which games you would like to fetch.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Publishing", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="playtomax_cron_publish" value="true" <?php myarcade_checked($playtomax['cron_publish'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Publish Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="playtomax_cron_publish_limit" value="<?php echo $playtomax['cron_publish_limit']; ?>" />
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
 * @version 5.23.0
 * @since   5.23.0
 * @access  public
 * @return  array Default settings
 */
function myarcade_default_settings_playtomax() {
  return array(
    'feed'          => 'http://playtomax.com/api/json/embed',
    'category'      => 'all',
    'cron_publish'  => false,
    'cron_publish_limit' => '1',
  );
}

/**
 * Display distributor fetch games options
 *
 * @version 5.4.0
 * @since   5.4.0
 * @access  public
 * @return  void
 */
function myarcade_fetch_settings_playtomax() {

  ?>
  <div class="myarcade_border white hide mabp_680" id="playtomax">
    <?php myarcade_premium_message(); ?>
  </div>
  <?php
}

/**
 * Fetch games
 *
 * @version 5.26.0
 * @since   5.23.0
 * @access  public
 * @param   array  $args Fetching parameters
 * @return  void
 */
function myarcade_feed_playtomax( $args = array() ) {

  ?>
  <div class="myarcade_border white mabp_680">
    <?php myarcade_premium_message(); ?>
  </div>
  <?php
}

/**
 * Return game embed method
 *
 * @version 5.26.0
 * @since   5.23.0
 * @access  public
 * @return  string Embed Method
 */
function myarcade_embedtype_playtomax() {
  return 'embed';
}

/**
 * Return if games can be downloaded by this distirbutor
 *
 * @version 5.23.0
 * @since   5.23.0
 * @access  public
 * @return  bool True if games can be downloaded
 */
function myarcade_can_download_playtomax() {
  return false;
}
?>