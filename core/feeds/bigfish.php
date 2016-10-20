<?php
/**
 * Big Fish Games
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
function myarcade_settings_bigfish() {
  $bigfish = myarcade_get_settings( 'bigfish' );
  ?>
  <h2 class="trigger"><?php myarcade_premium_img() ?> <?php _e("Big Fish Games", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <?php myarcade_premium_message() ?>
            <br />
            <i>
             <?php printf( __( "%s distributes HTML5 games.", 'myarcadeplugin' ), '<a href="https://affiliates.bigfishgames.com/" target="_blank">Big Fish Games</a>' ); ?>
             <?php _e("Big Fish Games offers an affiliate programm with 70% commisions for each sale you generate.", 'myarcadeplugin'); ?>
            </i>
            <br /><br />
          </td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Username", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="big_username" value="<?php echo $bigfish['username']; ?>" />
          </td>
          <td><i><?php _e("Enter your Big Fish Games affiliate user name.", 'myarcadeplugin'); ?></i></td>
        </tr>
        <tr><td colspan="2"><h3><?php _e("Affiliate Code", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="big_affiliate_code" value="<?php echo $bigfish['affiliate_code']; ?>" />
          </td>
          <td><i><?php _e("Enter your Affiliate Code.", 'myarcadeplugin'); ?></i></td>
        </tr>
        <tr><td colspan="2"><h3><?php _e("Default Game Type", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <select name="big_gametype">
              <option value="pc" <?php myarcade_selected($bigfish['gametype'], "pc"); ?>><?php _e("PC Games", 'myarcadeplugin'); ?></option>
              <option value="mac" <?php myarcade_selected($bigfish['gametype'], "mac"); ?>><?php _e("Mac Games", 'myarcadeplugin'); ?></option>
              <option value="og" <?php myarcade_selected($bigfish['gametype'], "og"); ?>><?php _e("Online Games", 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select the your preferred Game Type.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Language", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <select name="big_locale">
              <option value="en" <?php myarcade_selected($bigfish['locale'], "en"); ?>>English</option>
              <option value="da" <?php myarcade_selected($bigfish['locale'], "da"); ?>>Dansk</option>
              <option value="fr" <?php myarcade_selected($bigfish['locale'], "fr"); ?>>French</option>
              <option value="de" <?php myarcade_selected($bigfish['locale'], "de"); ?>>German</option>
              <option value="it" <?php myarcade_selected($bigfish['locale'], "it"); ?>>Italiano</option>
              <option value="jp" <?php myarcade_selected($bigfish['locale'], "jp"); ?>>Japanese</option>
              <option value="nl" <?php myarcade_selected($bigfish['locale'], "nl"); ?>>Nederlands</option>
              <option value="pt" <?php myarcade_selected($bigfish['locale'], "pt"); ?>>Portugues</option>
              <option value="es" <?php myarcade_selected($bigfish['locale'], "es"); ?>>Spanish</option>
              <option value="sv" <?php myarcade_selected($bigfish['locale'], "sv"); ?>>Svenska</option>
            </select>
          </td>
          <td><i><?php _e("Select the preferred language for Big Fish Games.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Game Categories", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <?php
            // Include Big Fish Games Categories
            foreach ( $bigfish['categories'] as $bigfish_category ) {
              echo '<input type="checkbox" name="big_categories[]" value="'.$bigfish_category['ID'].'" '.$bigfish_category['Status'].' /><label class="opt">&nbsp;'.$bigfish_category['Name'].'</label><br />';
            }
          ?>
          </td>
          <td><i><?php _e("Activate desired Big Fish categories. On Category Mapping you can map these categories to your own category names.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Create Categories", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="big_createcats" value="true" <?php myarcade_checked($bigfish['create_cats'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Check this if you want to create selected Big Fish Games categories.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Game Thumbail Size", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <select name="big_thumbnail">
              <option value="small" <?php myarcade_selected($bigfish['thumbnail'], "small"); ?>>Small (60x40)</option>
              <option value="medium" <?php myarcade_selected($bigfish['thumbnail'], "medium"); ?>>Medium (80x80)</option>
              <option value="feature" <?php myarcade_selected($bigfish['thumbnail'], "feature"); ?>>Feature Image (175x150)</option>
              <option value="subfeature" <?php myarcade_selected($bigfish['thumbnail'], "subfeature"); ?>>Sub-feature Image (175x150)</option>
            </select>
          </td>
          <td><i><?php _e("Select the preferred game thumbnail size. Default: Medium.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Game Description Template", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <textarea name="big_template" cols="40" rows="12"><?php echo $bigfish['template']; ?></textarea>
          </td>
          <td>
            <p><i><?php _e("Set how Big Fish Games description should be generated.", 'myarcadeplugin'); ?></i></p>
            <br />
            <strong><?php _e("Available Placeholders", 'myarcadeplugin'); ?>:</strong><br />
            %DESCRIPTION% - <?php _e("Game description", 'myarcadeplugin'); ?><br />
            %BULLET_POINTS% - <?php _e("Game key feature list", 'myarcadeplugin'); ?><br />
            %SYSREQUIREMENTS% - <?php _e("System requirements for PC and MAC games", 'myarcadeplugin'); ?><br />
            %BUY_URL% - <?php _e("Purchase game link", 'myarcadeplugin'); ?>
          </td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Publishing", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="big_cron_publish" value="true" <?php myarcade_checked($bigfish['cron_publish'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Publish Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="big_cron_publish_limit" value="<?php echo $bigfish['cron_publish_limit']; ?>" />
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
function myarcade_default_settings_bigfish() {
  include( MYARCADE_CORE_DIR . "/feeds/bigfish/categories.php" );

  return array(
    'username'        => '',
    'affiliate_code'  => '',
    'locale'          => 'en',
    'gametype'        => 'og',
    'template'        => '%DESCRIPTION% %BULLET_POINTS% %BUY_GAME% %SYSREQUIREMENTS%',
    'thumbnail'       => 'medium',
    'create_cats'     => true,
    'categories'      => $bigfish_categories,
    'cron_publish'    => false,
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
function myarcade_save_settings_bigfish() {

  myarcade_check_settings_nonce();

  $bigfish = array();
  $bigfish['username'] = (isset($_POST['big_username'])) ? sanitize_text_field($_POST['big_username']) : '';
  $bigfish['affiliate_code'] = (isset($_POST['big_affiliate_code'])) ? sanitize_text_field($_POST['big_affiliate_code']) : '';
  $bigfish['locale'] = (isset($_POST['big_locale'])) ? $_POST['big_locale'] : 'en';
  $bigfish['gametype'] = (isset($_POST['big_gametype'])) ? $_POST['big_gametype'] : 'og';
  $bigfish['template'] = (isset($_POST['big_template'])) ? esc_textarea($_POST['big_template']) : '';
  $bigfish['thumbnail'] = (isset($_POST['big_thumbnail'])) ? $_POST['big_thumbnail'] : 'medium';
  $bigfish['create_cats'] = (isset($_POST['big_createcats']) ) ? true : false;
  //$bigfish['categories'] = isset($_POST['big_categories']) ? $_POST['big_categories'] : array();
  $bigfish['cron_publish']        = (isset($_POST['big_cron_publish']) ) ? true : false;
  $bigfish['cron_publish_limit']  = (isset($_POST['big_cron_publish_limit']) ) ? intval($_POST['big_cron_publish_limit']) : 1;

  $big_categories_post = isset( $_POST['big_categories']) ? $_POST['big_categories'] : array();

  // Get default categories
  require_once( MYARCADE_CORE_DIR . "/feeds/bigfish/categories.php" );

  if ( isset( $bigfish_categories ) ) {
    $bigfish_cat_count = count( $bigfish_categories );
    $bigfish_categories_array = array();

    for ($i = 0; $i < $bigfish_cat_count; $i++) {
      if( in_array( $bigfish_categories[$i]['ID'], $big_categories_post ) ) {
        $bigfish_categories[$i]['Status'] = 'checked';
        $bigfish_categories_array[] = $bigfish_categories[$i]['Name'];
      }
      else {
        $bigfish_categories[$i]['Status'] = '';
      }
    }

    $bigfish['categories'] = $bigfish_categories;
  }

  // Check if Big Fish Games categories schould be created
  if ( $bigfish['create_cats'] && ! empty( $bigfish_categories_array ) ) {
    myarcade_create_categories( $bigfish_categories_array );
  }

  // Update Settings
  update_option('myarcade_bigfish', $bigfish);
}

/**
 * Display distributor fetch games options
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  void
 */
function myarcade_fetch_settings_bigfish() {

  $bigfish = myarcade_get_fetch_options_bigfish();
  ?>
  <div class="myarcade_border white hide mabp_680" id="bigfish">
    <?php _e("Select a game type", 'myarcadeplugin'); ?>
    <select name="big_gametype" id="big_gametype">
      <option value="pc" <?php myarcade_selected($bigfish['gametype'], "pc"); ?>><?php _e("PC Games", 'myarcadeplugin'); ?></option>
      <option value="mac" <?php myarcade_selected($bigfish['gametype'], "mac"); ?>><?php _e("Mac Games", 'myarcadeplugin'); ?></option>
      <option value="og" <?php myarcade_selected($bigfish['gametype'], "og"); ?>><?php _e("Online Games", 'myarcadeplugin'); ?></option>
    </select>
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
function myarcade_get_fetch_options_bigfish() {

  // Get distributor settings
  $settings = myarcade_get_settings( 'bigfish' );

  // Set default fetching options
  $settings['echo'] = true;

  if ( 'start' == filter_input( INPUT_POST, 'fetch' ) ) {
    // Set submitted fetching options
    $settings['gametype'] = filter_input( INPUT_POST, 'big_gametype' );
  }

  return $settings;
}

/**
 * Fetch Big Fish Games
 *
 * @version 5.19.0
 * @param   array $args Feed settings
 * @return  void
 */
function myarcade_feed_bigfish( $args =array() ) {
  myarcade_premium_message();
}

/**
 * Return game embed method
 *
 * @version 5.18.0
 * @since   5.18.0
 * @access  public
 * @return  string Embed Method
 */
function myarcade_embedtype_bigfish() {
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
function myarcade_can_download_bigfish() {
  return false;
}
?>