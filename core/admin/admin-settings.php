<?php
/**
 * Displays the settings page on backend
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 */

// No direct access
if( !defined( 'ABSPATH' ) ) {
  die();
}

/**
 * Settings page
 *
 * @version 5.3.2
 * @access  public
 * @return  void
 */
function myarcade_settings() {
  global $myarcade_distributors;

  myarcade_header();

  ?>
  <div id="icon-tools" class="icon32"><br /></div>
  <h2><?php _e("Settings"); ?></h2>
  <?php

  $action = filter_input( INPUT_POST, 'feedaction' );

  if ( 'save' ==  $action ) {

    myarcade_check_settings_nonce();

    // Remove the settings update notice if set
    if ( get_transient('myarcade_settings_update_notice') == true  ) {
      delete_transient('myarcade_settings_update_notice');
    }

    $general = array();
    $general['scores'] = filter_input( INPUT_POST, 'leaderboardenable', FILTER_VALIDATE_BOOLEAN );
    $general['highscores'] = filter_input( INPUT_POST, 'onlyhighscores', FILTER_VALIDATE_BOOLEAN );
    $general['posts'] = intval( filter_input( INPUT_POST, 'game_count' ) );
    $general['status'] = filter_input( INPUT_POST, 'publishstatus', FILTER_SANITIZE_STRING, array( "options" => array( "default" => 'publish' ) ) );
    $general['schedule'] = intval( filter_input( INPUT_POST, 'schedtime' ) );
    $general['down_thumbs'] = filter_input( INPUT_POST, 'downloadthumbs', FILTER_VALIDATE_BOOLEAN );
    $general['down_games'] = filter_input( INPUT_POST, 'downloadgames', FILTER_VALIDATE_BOOLEAN );
    $general['down_screens'] = filter_input( INPUT_POST, 'downscreens', FILTER_VALIDATE_BOOLEAN );
    $general['delete'] = filter_input( INPUT_POST, 'deletefiles', FILTER_VALIDATE_BOOLEAN );
    $general['folder_structure'] = sanitize_text_field( filter_input( INPUT_POST 'folder_structure' ) );
    $general['swfobject'] = filter_input( INPUT_POST, 'swfobject', FILTER_VALIDATE_BOOLEAN );
    $general['create_cats'] = filter_input( INPUT_POST, 'createcats', FILTER_VALIDATE_BOOLEAN );
    $general['parent'] = intval( filter_input( INPUT_POST 'parentcatid' ) );
    $general['firstcat'] = filter_input( INPUT_POST, 'firstcat', FILTER_VALIDATE_BOOLEAN );
    $general['maxwidth'] = sanitize_text_field( filter_input( INPUT_POST 'maxwidth' ) );
    $general['single'] = filter_input( INPUT_POST, 'singlecat', FILTER_VALIDATE_BOOLEAN );
    $general['singlecat'] = intval( filter_input( INPUT_POST 'singlecatid' ) );
    $general['embed'] = sanitize_text_field( filter_input( INPUT_POST 'embedflashcode', array( "options" => array( "default" => 'manually' ) ) ) );
    $general['use_template'] = filter_input( INPUT_POST, 'usetemplate', FILTER_VALIDATE_BOOLEAN );
    $general['template'] = esc_textarea( filter_input( INPUT_POST 'post_template' ) );
    $general['allow_user'] = filter_input( INPUT_POST, 'allow_user', FILTER_VALIDATE_BOOLEAN );
    $general['limit_plays'] = intval( filter_input( INPUT_POST 'limitplays' ) );
    $general['limit_message'] = esc_textarea( filter_input( INPUT_POST 'limitmessage' ) );
    $general['post_type'] = sanitize_text_field( filter_input( INPUT_POST 'posttype', array( "options" => array( "default" => 'post' ) ) ) );
    $general['featured_image'] = filter_input( INPUT_POST, 'featured_image', FILTER_VALIDATE_BOOLEAN );
    $general['custom_category'] = sanitize_text_field( filter_input( INPUT_POST 'customtaxcat' ) );
    $general['custom_tags'] = sanitize_text_field( filter_input( INPUT_POST 'customtaxtag' ) );
    $general['disable_game_tags'] = filter_input( INPUT_POST, 'disable_game_tags', FILTER_VALIDATE_BOOLEAN );

    // Update Settings
    update_option( 'myarcade_general', $general );

    // Update distributor settings dynamically
    foreach ($myarcade_distributors as $key => $name) {
      // Generate save settings function name
      $settings_update_function = 'myarcade_save_settings_' . $key;

      // Get distributor integration file
      myarcade_distributor_integration( $key );

      // Check if function exists
      if ( function_exists( $settings_update_function ) ) {
        // Update settings
        $settings_update_function();
      }
    } // end foreach

    // END Settings Updates
    //_________________________________________________________________________

    //
    // Create Game Categories
    //
    $categories_post =  isset( $_POST['gamecats'] ) ) ? $_POST['gamecats'] : array();

    if ( ! is_array( $categories_post ) ) {
      // Something went wrong.
      $categories_post = array();
    }

    // Get current settings
    $feedcategories = get_option('myarcade_categories');

    // count checked categories
    $cat_count = 0;
    $feedcat_count = count($feedcategories);
    $categories_array = array();

    for ($i = 0; $i < $feedcat_count; $i++) {
      if( in_array( $feedcategories[$i]['Slug'], $categories_post ) ) {
        $cat_count++;
        $feedcategories[$i]['Status'] = 'checked';
        $categories_array[] = $feedcategories[$i]['Name'];
      }
      else {
        $feedcategories[$i]['Status'] = '';
      }
    }

    // Update categories
    update_option('myarcade_categories', $feedcategories);

    if ( $cat_count ) {
      // Create categories if activated
      if ( true == $general['create_cats'] && ! empty( $categories_array ) ) {
        myarcade_create_categories( $categories_array );
      }
    }

    echo '<p class="mabp_info mabp_800">'.__("Your settings have been updated!", 'myarcadeplugin').'</p>';
  } // END - if action

  // Check if we should reset settings to default
  $load_defaults = filter_input( INPUT_POST, 'loaddefaults' );
  $confirmation = filter_input( INPUT_POST, 'checkdefaults' );

  if ( $load_default && 'yes' == $confirmation ) {
    myarcade_load_default_settings();
    echo '<p class="mabp_info mabp_800">'.__("Default settings have been restored!", 'myarcadeplugin').'</p>';
  }

  // Get settings
  $general    = get_option('myarcade_general');
  $categories = get_option('myarcade_categories');

  if ( empty( $categories ) ) {
    echo '<p class="mabp_error mabp_800">'.__("You will not be able to fetch games!", 'myarcadeplugin').' '.__('Go to "General Settings" and activate some game categories!', 'myarcadeplugin').'</p>';
  }

  // Load settings dynamically
  foreach ($myarcade_distributors as $key => $name) {
    $$key = myarcade_get_settings( $key );
  }

  // Create directories
  myarcade_create_directories();

  $upload_dir = myarcade_upload_dir();

  if ( $general['down_thumbs'] ) {
    if ( !is_writable( $upload_dir['thumbsdir'] ) ) {
      echo '<p class="mabp_error mabp_800">'.sprintf(__("The thumbails directory '%s' must be writable (chmod 777) in order to download thumbnails.", 'myarcadeplugin'), $upload_dir['thumbsdir'] ).'</p>';
    }
  }

  if ( $general['down_screens'] ) {
    if ( !is_writable( $upload_dir['thumbsdir'] ) ) {
      echo '<p class="mabp_error mabp_800">'.sprintf(__("The thumbails directory '%s' must be writable (chmod 777) in order to download game screenshots.", 'myarcadeplugin'), $upload_dir['thumbsdir'] ).'</p>';
    }
  }

  // Check Application ID for Bing Translator
  if ( ($general['translation'] == 'bing') && empty( $general['bingid'] ) ) {
    echo '<p class="mabp_error mabp_800">'.__("You have activated the Bing Translator but not entered your Application ID. In this case the translator will not work!", 'myarcadeplugin').'</p>';
  }
  if ( ($general['translation'] == 'google') && empty( $general['google_id'] ) ) {
    echo '<p class="mabp_error mabp_800">'.__("You have activated the Google Translator but not entered your Google API Key. In this case the translator will not work!", 'myarcadeplugin').'</p>';
  }

  // Get all categories
  if ( $general['post_type'] == 'post') {
    $categs_ids_tmp = get_terms( 'category', array('fields' => 'ids', 'get' => 'all') );
    $categs_tmp = array();

    foreach ($categs_ids_tmp as $categ_id_tmp) {
      $categs_tmp[$categ_id_tmp] = get_cat_name($categ_id_tmp);
    }
  }
  else {
    $categs_tmp = array();

    if (taxonomy_exists($general['custom_category']) ) {
      $taxonomies = get_terms($general['custom_category'], array('hide_empty' => false));

      foreach ($taxonomies as $taxonomy) {
        $categs_tmp[$taxonomy->term_id] = $taxonomy->name;
      }
    }
  }

  // Create an array with all available cron intervals
  $myarcade_cron_intervals = myarcade_get_cron_intervals();

  $default_crones = array(
    'hourly'    => array('display' => __('Hourly')),
    'twicedaily'=> array('display' => __('Twice Daily')),
    'daily'     => array('display' => __('Daily')),
  );

  $crons =array_merge($myarcade_cron_intervals,$default_crones);
  ?>
    <br />

    <div id="myarcade_settings">
      <form method="post" name="editsettings">
        <?php wp_nonce_field( 'myarcade_save_settings', 'myarcade_save_settings_nonce' ); ?>
        <input type="hidden" name="feedaction" value="save">

        <?php
        //----------------------------------------------------------------------
        // General Settings
        //----------------------------------------------------------------------
        ?>
        <h2 class="trigger"><?php _e("General Settings", 'myarcadeplugin'); ?></h2>
        <div class="toggle_container">
          <div class="block">
            <table class="optiontable" width="100%">

              <tr><td colspan="2"><h3><?php _e("Publish Games", 'myarcadeplugin'); ?></h3></td></tr>
              <tr>
                <td>
                  <input type="text" size="40"  name="game_count" value="<?php echo $general['posts']; ?>" />
                </td>
                <td><i><?php _e('How many games should be published when clicking on "Publish Games"?', 'myarcadeplugin'); ?></i></td>
              </tr>

              <tr><td colspan="2"><h3><?php _e("Post Status", 'myarcadeplugin'); ?></h3></td></tr>

              <tr>
                <td>
                  <input type="radio" name="publishstatus" value="publish" <?php myarcade_checked($general['status'], 'publish'); ?> /><label class="opt">&nbsp;<?php _e("Publish", 'myarcadeplugin'); ?></label>
                  <input type="radio" name="publishstatus" value="future" <?php myarcade_checked($general['status'], 'future'); ?> /><label class="opt">&nbsp;<?php _e("Scheduled", 'myarcadeplugin'); ?></label>
                  <input type="radio" name="publishstatus" value="draft" <?php myarcade_checked($general['status'], 'draft'); ?> /><label class="opt">&nbsp;<?php _e("Draft", 'myarcadeplugin'); ?></label>
                  <br /><br />
                  <?php _e("Schedule Time", 'myarcadeplugin'); ?>: <input type="text" size="10" name="schedtime" value="<?php echo $general['schedule']; ?>">
                </td>
                <td><i><?php _e("Choose the post status for new game publication. If you whish to schedule new game publication, indicate an interval between publications in minutes.", 'myarcadeplugin'); ?></i></td>
              </tr>

              <tr><td colspan="2"><h3><?php _e("Download Thumbnails", 'myarcadeplugin'); ?></h3></td></tr>

              <tr>
                <td>
                  <input type="checkbox" name="downloadthumbs" value="true" <?php myarcade_checked($general['down_thumbs'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
                </td>
                <td><i><?php _e("Should the game thumbnails be imported and saved on your web server? For this to work properly, the thumb directory (wp-content/thumbs/) must be writable.", 'myarcadeplugin'); ?></i></td>
              </tr>

              <tr><td colspan="2"><h3><?php _e("Download Screenshots", 'myarcadeplugin'); ?></h3></td></tr>

              <tr>
                <td>
                  <input type="checkbox" name="downscreens" value="true"  <?php myarcade_checked($general['down_screens'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
                </td>
                <td><i><?php _e("Should the game screenshots be imported and stored on your web server? For this to work properly, the thumb directory (wp-content/thumbs/) must be  writable.", 'myarcadeplugin'); ?></i></td>
              </tr>

              <tr><td colspan="2"><h3><?php _e("Delete Game Files", 'myarcadeplugin'); ?></h3></td></tr>

              <tr>
                <td>
                  <input type="checkbox" name="deletefiles" value="true" <?php myarcade_checked($general['delete'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
                </td>
                <td><i><?php _e("This option will delete the associated game files from your server after deleting the post from your blog. Warning - deleted games cannot be re-published! For this to work properly, the games and thumbs directories must be writable.", 'myarcadeplugin'); ?></i></td>
              </tr>

              <tr><td colspan="2"><h3><?php _e("Folder Organization Structure", 'myarcadeplugin'); ?></h3></td></tr>

              <tr>
                <td>
                  <input type="text" size="40"  name="folder_structure" value="<?php echo $general['folder_structure']; ?>" />
                </td>
                <td><i><?php _e('Define the folder structure for file downloads. Available variables are %game_type% and %alphabetical%. You can combine those variables like this: %game_type%/%alphabetical%/.', 'myarcadeplugin'); ?><br />
                    <?php _e('That means, for each game type a new folder will be created and files will be organized in sub folders. Example: "/games/fog/A/awesome_game.swf.', 'myarcadeplugin'); ?><br />
                    <?php _e('Leave blank if you want to save all files in a single folder."', 'myarcadeplugin'); ?></i></td>
              </tr>

              <tr><td colspan="2"><h3><?php _e("Game Categories", 'myarcadeplugin'); ?></h3></td></tr>

              <tr>
                <td>
                <?php
                  foreach ($categories as $feedcat) {
                    echo '<input type="checkbox" name="gamecats[]" value="'.$feedcat['Slug'].'" '.$feedcat['Status'].' /><label class="opt">&nbsp;'.$feedcat['Name'].'</label><br />';
                  }
                ?>
                </td>
                <td><i><?php _e("Select game categories that should be fetched.", 'myarcadeplugin'); ?></i></td>
              </tr>

              <tr><td colspan="2"><h3><?php _e("Create Categories", 'myarcadeplugin'); ?></h3></td></tr>

              <tr>
                <td>
                  <input type="checkbox" name="createcats" value="true" <?php myarcade_checked($general['create_cats'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
                </td>
                <td><i><?php _e("Check this if you want to create selected categories on your blog.", 'myarcadeplugin'); ?></i></td>
              </tr>


              <tr><td colspan="2"><h3><?php _e("Parent Category", 'myarcadeplugin'); ?></h3></td></tr>
                <tr>
                  <td>
                    <select size="1" name="parentcatid" id="parentcatid">
                    <option value=''>--- <?php _e("No Parent Category", 'myarcadeplugin'); ?> ---</option>
                    <?php
                      // Get selected category
                      foreach ($categs_tmp as $cat_tmp_id => $cat_tmp_name) {
                        if ($cat_tmp_id == $general['parent']) { $cat_selected = 'selected'; } else { $cat_selected = ''; }
                        echo '<option value="'.$cat_tmp_id.'" '.$cat_selected.'>'.$cat_tmp_name.'</option>';
                      }
                    ?>
                    </select>
                  </td>
                  <td><i><?php _e("This option will create game categories as subcategories in the selected category.", 'myarcadeplugin'); ?> <?php _e(" This option is useful if you have a mixed site and not only a pure arcade site.", 'myarcadeplugin'); ?></i></td>
                </tr>

                <?php // Use only the first category ?>
                <tr>
                  <td colspan="2">
                    <h3><?php _e("Use Only The First Category", 'myarcadeplugin'); ?></h3>
                  </td>
                </tr>
                <tr>
                  <td>
                    <input type="checkbox" name="firstcat" value="true" <?php myarcade_checked($general['firstcat'], true); ?> />&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?>
                  </td>
                  <td><i><?php _e("Many game developers tag their games to more than one category to get more downloads. Thereby the gamess will be added to several categories. Activate this option to avoid game publishing in more than one category.", 'myarcadeplugin'); ?></i></td>
                </tr>

              <tr><td colspan="2"><h3><?php _e("Max. Game Width (optional)", 'myarcadeplugin'); ?></h3></td></tr>

              <tr>
                <td>
                  <input type="text" size="40" name="maxwidth" value="<?php echo $general['max_width']; ?>" />
                </td>
                <td><i><?php _e("Maximum allowed game width in px. If set, the get_game function will create output code with adjusted game dimensions.", 'myarcadeplugin'); ?></i></td>
              </tr>

              <tr><td colspan="2"><h3><?php _e("Publish In A Single Category", 'myarcadeplugin'); ?></h3></td></tr>

              <tr>
                <td>
                  <input type="checkbox" name="singlecat" value="true" <?php myarcade_checked($general['single'], true); ?> />&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?>
                  <select size="1" name="singlecatid" id="singlecatid">
                  <?php
                    // Get selected category
                    foreach ($categs_tmp as $cat_tmp_id => $cat_tmp_name) {
                      if ($cat_tmp_id == $general['singlecat']) { $cat_selected = 'selected'; } else { $cat_selected = ''; }
                      echo '<option value="'.$cat_tmp_id.'" '.$cat_selected.'/>'.$cat_tmp_name.'</option>';
                    }
                  ?>
                  </select>
                </td>
                <td><i><?php _e("This option will publish all games only in the selected category.", 'myarcadeplugin'); ?> <?php _e("This option is useful if you have a mixed site and not only a pure arcade site.", 'myarcadeplugin'); ?></i></td>
              </tr>

              <tr><td colspan="2"><h3><?php _e("Embed Game Code", 'myarcadeplugin'); ?></h3></td></tr>

              <tr>
                <td>
                <select size="1" name="embedflashcode" id="embedflashcode">
                  <option value="manually" <?php myarcade_selected($general['embed'], 'manually'); ?> ><?php _e("Manually", 'myarcadeplugin'); ?></option>
                  <option value="top" <?php myarcade_selected($general['embed'], 'top'); ?> ><?php _e("At The Top Of A Game Post", 'myarcadeplugin'); ?></option>
                  <option value="bottom" <?php myarcade_selected($general['embed'], 'bottom'); ?> ><?php _e("At The Bottom Of A Game Post", 'myarcadeplugin'); ?></option>
                </select>
                </td>
                <td><i><?php _e("Select if MyArcadePlugin should auto embed the game code in your game posts (only if you don't use FunGames theme).", 'myarcadeplugin'); ?></i></td>
              </tr>

              <tr><td colspan="2"><h3><?php _e("Use SWFObject", 'myarcadeplugin'); ?></h3></td></tr>

              <tr>
                <td>
                  <input type="checkbox" name="swfobject" value="true" <?php myarcade_checked( $general['swfobject'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
                </td>
                <td><i><?php _e("Activate this if you want to use SWFObject to embed Flash games.", 'myarcadeplugin'); ?></i></td>
              </tr>

              <tr><td colspan="2"><h3><?php _e("Game Post Template", 'myarcadeplugin'); ?></h3></td></tr>

              <tr>
                <td>
                  <input type="checkbox" name="usetemplate" value="true" <?php myarcade_checked($general['use_template'], true); ?> /><label class="opt">&nbsp;<?php _e("Activate Post Template", 'myarcadeplugin'); ?></label>
                  <br /><br />
                  <textarea rows="12" cols="40" id="post_template" name="post_template"><?php echo htmlspecialchars(stripslashes($general['template'])); ?></textarea>
                </td>
                <td><i>
                    <?php _e("Use this template to style the output of MyArcadePlugin when creating game posts.", 'myarcadeplugin'); ?>
                    <br />
                     <strong><?php _e("Available Variables", 'myarcadeplugin'); ?>:</strong><br />
                    %TITLE% - <?php _e("Show the game title", 'myarcadeplugin'); ?><br />
                    %DESCRIPTION% - <?php _e("Show game description", 'myarcadeplugin'); ?><br />
                    %INSTRUCTIONS% - <?php _e("Show game instructions if available", 'myarcadeplugin'); ?><br />
                    %TAGS% - <?php _e("Show all game tags", 'myarcadeplugin'); ?><br />
                    %THUMB% - <?php _e("Show the game thumbnail", 'myarcadeplugin'); ?><br />
                    %THUMB_URL% - <?php _e("Show game thumbnail URL", 'myarcadeplugin'); ?><br />
                    %SWF_URL% - <?php _e("Show game SWF URL / Embed Code", 'myarcadeplugin'); ?><br />
                    %WIDTH% - <?php _e("Show game width", 'myarcadeplugin'); ?><br />
                    %HEIGHT% - <?php _e("Show game height", 'myarcadeplugin'); ?><br />
                  </i></td>
              </tr>

              <?php // Disable game tags ?>
              <tr>
                <td colspan="2">
                  <h3><?php _e("Disable Game Tags", 'myarcadeplugin'); ?></h3>
                </td>
              </tr>
              <tr>
                <td>
                  <input type="checkbox" name="disable_game_tags" value="true" <?php myarcade_checked($general['disable_game_tags'], true); ?> />&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?>
                </td>
                <td><i><?php _e("Check this if you want to prevent MyArcadePlugin from adding tags to WordPress posts (not recommended).", 'myarcadeplugin'); ?></i></td>
              </tr>

              <?php // Post Type ?>
              <tr>
                <td colspan="2">
                  <h3><?php _e("Post Type", 'myarcadeplugin'); ?></h3>
                </td>
              </tr>
              <tr>
                <td>
                  <?php
                  $types = get_post_types();
                  $exclude = array('attachment', 'revision', 'nav_menu_item', 'page');
                  $types = array_diff($types, $exclude);
                  ?>
                  <select size="1" name="posttype" id="posttype">
                    <?php
                    foreach($types as $type) {
                      ?>
                      <option value="<?php echo $type; ?>" <?php myarcade_selected($general['post_type'], $type); ?>>
                        <?php echo $type; ?>
                      </option>
                    <?php } ?>
                  </select>
                </td>
                <td><i><?php _e("Select a post type you want to use with MyArcadePlugin. If you want to use a custom post type then you will need to create it before you can make a selection. The easiest way to create a custom post type is to use a plugin like 'Custom Post Type UI'.", 'myarcadeplugin'); ?></i></td>
              </tr>

              <tr>
                <td colspan="2">
                  <h3><?php _e("Custom Taxonomies", 'myarcadeplugin'); ?></h3>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <?php
                  $custom_taxonomies = get_taxonomies(array('public'   => true,'_builtin' => false));
                  if ( !is_array($custom_taxonomies) || empty($custom_taxonomies)) {
                    ?>
                    <i><?php _e('No custom taxonomies found..', 'myarcadeplugin'); ?></i>
                    <?php
                  } else {
                    ?>
                    <table>
                      <tr>
                      <td>Game Categories:</td>
                      <td>
                    <?php if ( is_array($custom_taxonomies) && !empty($custom_taxonomies)) : ?>
                      <select size="1" name="customtaxcat" id="customtaxcat">
                        <option value="">-- select a taxonomy --</option>
                        <?php foreach( $custom_taxonomies as $taxonomy) : ?>
                        <option value="<?php echo $taxonomy; ?>" <?php myarcade_selected($taxonomy , $general['custom_category']); ?>><?php echo $taxonomy; ?></option>
                        <?php endforeach; ?>
                      </select>
                    <?php endif; ?>
                      </td>
                      <td><?php _e("Select a custom taxonomy that should be used for game categories.", 'myarcadeplugin'); ?></td>
                      </tr>
                      <tr>
                      <td>Game Tags:</td>
                      <td>
                    <?php if ( is_array($custom_taxonomies) && !empty($custom_taxonomies)) : ?>
                      <select size="1" name="customtaxtag" id="customtaxtag">
                        <option value="">-- select a taxonomy --</option>
                        <?php foreach( $custom_taxonomies as $taxonomy) : ?>
                        <option value="<?php echo $taxonomy; ?>" <?php myarcade_selected($taxonomy , $general['custom_tags']); ?>><?php echo $taxonomy; ?></option>
                        <?php endforeach; ?>
                      </select>
                    <?php endif; ?>
                      </td>
                      <td><?php _e("Select a custom taxonomy that should be used for game tags.", 'myarcadeplugin'); ?></td>
                      </tr>
                    </table>
                    <?php
                  }
                  ?>
                </td>
              </tr>


              <?php // Featured Image ?>
              <tr>
                <td colspan="2">
                  <h3><?php _e("Featured Image", 'myarcadeplugin'); ?></h3>
                </td>
              </tr>
              <tr>
                <td>
                  <input type="checkbox" name="featured_image" value="true" <?php myarcade_checked($general['featured_image'], true); ?> />&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?>
                </td>
                <td><i><?php _e("Activate this option if you want MyArcadePlugin to attach game thumbnails to the created post as featured images. Use this only if you don't use a pure Arcade Theme.", 'myarcadeplugin'); ?></i></td>
              </tr>

            </table>
            <input class="button button-primary" id="submit" type="submit" name="submit" value="<?php _e("Save Settings", 'myarcadeplugin'); ?>" />
          </div>
        </div>

        <?php
        //----------------------------------------------------------------------
        // Advanced Features
        //----------------------------------------------------------------------
        ?>

        <script type="text/javascript">
          /* <![CDATA[ */
          function confirmDeleteGames() {
            if ( confirm("Are you sure you want to delete all fetched games?") ) {
              jQuery('#del_response').html('<div class=\'gload\'> </div>');
              jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', {action:'myarcade_handler',func:'delgames'},function(data){jQuery('#del_response').html(data);});
            }
          }
          function confirmDeleteScores() {
            if ( confirm("Are you sure you want to delete all scores?") ) {
              jQuery('#score_response').html('<div class=\'gload\'> </div>');
              jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', {action:'myarcade_handler',func:'delscores'},function(data){jQuery('#score_response').html(data);});
            }
          }
          /* ]]> */
        </script>

        <h2 class="trigger"><?php _e("Advanced Features", 'myarcadeplugin'); ?></h2>
        <div class="toggle_container" id="advanced_settings">
          <div class="block">
            <table class="optiontable" width="100%">
              <tr>
                <td colspan="3">
                  <p class="mabp_error" style="padding:10px">
                    <?php _e("Please, use this only if you know what you do!", 'myarcadeplugin'); ?>
                  </p>
                  <br />
                </td>
              </tr>

              <tr><td colspan="3"><h3><?php _e("Delete All Feeded Games", 'myarcadeplugin'); ?></h3></td></tr>

              <tr>
                <td width="160">
                  <div class="button-secondary" style="float:left;text-align:center;" onclick="return confirmDeleteGames();">
                    <?php _e("Reset Feeded Games", 'myarcadeplugin'); ?>
                  </div>
                </td>
                <td width="30"><div id="del_response"></div></td>
                <td><i><?php _e("Attention! All feeded or imported games will be deleted from the games database! Published posts will not be touched. After this score submitting of publiished games will stop working!", 'myarcadeplugin'); ?></i></td>
              </tr>

              <tr><td colspan="3"><h3><?php _e("Remove Games Marked as 'deleted'", 'myarcadeplugin'); ?></h3></td></tr>

              <tr>
                <td>
                  <div class="button-secondary" style="float:left;text-align:center;" onclick="jQuery('#remove_response').html('<div class=\'gload\'> </div>');jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', {action:'myarcade_handler',func:'remgames'},function(data){jQuery('#remove_response').html(data);});">
                    <?php _e("Remove 'deleted' Games", 'myarcadeplugin'); ?>
                  </div>
                </td>
                <td width="30"><div id="remove_response"></div></td>
                <td><i><?php _e("Attention! All games marked as 'deleted' will be removed from the database!", 'myarcadeplugin'); ?></i></td>
              </tr>


              <tr><td colspan="3"><h3><?php _e("Delete Blank / Zero Scores", 'myarcadeplugin'); ?></h3></td></tr>

              <tr>
                <td>
                  <div class="button-secondary" style="float:left;text-align:center;" onclick="jQuery('#zero_response').html('<div class=\'gload\'> </div>');jQuery.post('<?php echo admin_url('admin-ajax.php');  ?>', {action:'myarcade_handler',func:'zeroscores'},function(data){jQuery('#zero_response').html(data);});">
                    <?php _e("Delete Zero Scores", 'myarcadeplugin'); ?>
                  </div>
                </td>
                <td width="30"><div id="zero_response"></div></td>
                <td><i><?php _e("Clean your scores table. This will delete all zero and empty scores if present in your database.", 'myarcadeplugin'); ?></i></td>
              </tr>

              <tr><td colspan="3"><h3><?php _e("Delete All Scores", 'myarcadeplugin'); ?></h3></td></tr>

              <tr>
                <td>
                  <div class="button-secondary" style="float:left;text-align:center;" onclick="return confirmDeleteScores();">
                    <?php _e("Delete All Scores", 'myarcadeplugin'); ?>
                  </div>
                </td>
                <td width="30"><div id="score_response"></div></td>
                <td><i><?php _e("Attention! All saved scores will be deleted!", 'myarcadeplugin'); ?></i></td>
              </tr>

              <tr><td colspan="3"><h3><?php _e("Load Default Settings", 'myarcadeplugin'); ?></h3></td></tr>

              <tr>
                <td colspan="3">
                  <p class="mabp_info" style="padding:10px"><?php _e("Attention! All MyArcadePlugin settings will be reset to default!", 'myarcadeplugin'); ?></p>
                  <form method="post" name="defaultsettings">
                    <input type="hidden" name="loaddefaults" id="loaddefaults" value="yes" />
                    <input type="checkbox" name="checkdefaults" id="checkdefaults" value="yes" /> Yes, I want to load default settings <input id="submitdefaults" type="submit" name="submitdefaults" class="button-secondary" value="<?php _e("Load Default Settings", 'myarcadeplugin'); ?>" disabled />
                  </form>
                  <script type="text/javascript">
                    /* <![CDATA[ */
                    jQuery("#checkdefaults").click(function() {
                      var checked_status = this.checked;
                      if (checked_status === true) {
                        jQuery("#submitdefaults").removeAttr("disabled");
                      } else {
                        jQuery("#submitdefaults").attr("disabled", "disabled");
                      }
                    });
                    /* ]]> */
                  </script>
                  <br />
                </td>
              </tr>

            </table>
          </div>
        </div>

        <?php
        //----------------------------------------------------------------------
        //----------------------------------------------------------------------
        // GAME FEEDS
        //----------------------------------------------------------------------
        ?>
        <div class="clear"></div>
        <hr />
        <h2><?php _e("Game Feeds", 'myarcadeplugin'); ?> <span style="float:right;font-size:16px;"><i><?php _e("ordered alphabetically", 'myarcadeplugin'); ?></i></span></h2>
        <hr />

        <?php
        // Load settings page dynamically for each distributor
        foreach ($myarcade_distributors as $key => $name) {
          $settings_function = 'myarcade_settings_' . $key;

          // Get distributor integration file
          myarcade_distributor_integration( $key );

          // Check if settings function exists
          if ( function_exists( $settings_function ) ) {
            $settings_function();
          }
          else {
            // Settings function doesn't exist. Maybe there are no settings available for this distributor.
          }
        }
        ?>
      </form>

      <div style="clear:both"></div>
    </div><?php // end id myarcade_settings ?>

   <div class="clear"></div>
  <?php

  myarcade_footer();
}

/**
 * Create categories from the given array
 *
 * @version 5.13.0
 * @access  public
 * @param   array $categories Categories to create
 * @return  void
 */
function myarcade_create_categories( $categories ) {

  if ( ! is_array( $categories ) ) {
    echo '<p class="mabp_error mabp_800">'.__("Can't create categories - Category array expected!", 'myarcadeplugin').'</p>';
    return;
  }

  $general = get_option( 'myarcade_general' );

  if ( $general['post_type'] == 'post' ) {
    foreach ($categories as $key => $name ) {
      // Get Cat ID
      $cat_id = get_cat_ID( htmlspecialchars( $name ) );

      if ( 0 == $cat_id ) {
        // Create Category
        $args = array(
          'cat_name'              => $name,
          'category_description'  => $name,
          'category_nicename'     => myarcade_make_slug( $name ),
          'category_parent'       => $general['parent']
        );

        if ( ! wp_insert_category( $args ) ) {
          echo '<p class="mabp_error mabp_800">'.__("Failed to create category:", 'myarcadeplugin').' '. $name .'</p>';
        }
      }
    }
  }
  else {
    // We have a custom post type... Check if custom taxonomy is selected
    if ( post_type_exists( $general['post_type'] ) ) {
      if ( !empty($general['custom_category']) && taxonomy_exists($general['custom_category']) ) {
        foreach ($categories as $key => $name ) {
          if ( !term_exists( $name, $general['custom_category'] ) ) {
            // Add custom taxonomy
            $result = wp_insert_term ( $name, $general['custom_category'], array( 'description' => $name, 'slug' => myarcade_make_slug( $name ) ) );

            if ( is_wp_error($result) ) {
              echo '<p class="mabp_error mabp_800">'.__("Failed to create category:", 'myarcadeplugin').' '.$result->get_error_message().'</p>';
            }
          }
        }
      }
    }
  }
}

/**
 * Load default MyArcadePlugin settings
 *
 * @version 5.19.0
 * @access  public
 * @return  void
 */
function myarcade_load_default_settings() {
  global $myarcade_distributors;

  if ( empty( $myarcade_distributors ) ) {
    myarcade_set_distributors();
  }

  $default_settings = MYARCADE_CORE_DIR.'/settings.php';

  if ( file_exists($default_settings) ) {
    @include_once($default_settings);
  }
  else {
    wp_die('Required configuration file not found!', 'Error: MyArcadePlugin Activation');
  }

  update_option('myarcade_general', $myarcade_general_default);

  foreach ($myarcade_distributors as $key => $name) {
    // Generate save settings function name
    $settings_default_function = 'myarcade_default_settings_' . $key;

    // Get distributor integration file
    myarcade_distributor_integration( $key );

    // Check if function exists
    if ( function_exists( $settings_default_function ) ) {
      // Update settings
      $settings = $settings_default_function();

      update_option( 'myarcade_' . $key, $settings );
    }
  } // end foreach

  // Include the feed game categories
  $catfile = MYARCADE_CORE_DIR.'/feedcats.php';

  if ( file_exists($catfile) ) {
    @include_once($catfile);
  }
  else {
    wp_die('Required configuration file not found!', 'Error: MyArcadePlugin Activation');
  }

  update_option('myarcade_categories', $feedcategories);
}

/**
 * Check settings nonce
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  [type] [description]
 */
function myarcade_check_settings_nonce() {
  $myarcade_nonce = filter_input( INPUT_POST, 'myarcade_save_settings_nonce');

  if ( ! $myarcade_nonce || ! wp_verify_nonce( $myarcade_nonce, 'myarcade_save_settings' ) ) {
    // Security check failed .. don't update settings
    wp_die( __('Security check failed. Please refresh the page and retry to submit settings again.', 'myarcadeplugin') );
  }
}
?>