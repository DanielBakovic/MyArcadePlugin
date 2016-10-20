<?php
/**
 * Publish Games, Create Game Posts
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
 * Inserts a fetched game to the games table
 *
 * @version 5.15.2
 * @param   object $game Game object
 * @return  void
 */
function myarcade_insert_game($game) {
  global $wpdb;

  $game_data = array(
    "postid"      => NULL,
    "uuid"        => $game->uuid,
    "game_tag"    => $game->game_tag,
    "game_type"   => $game->type,
    "name"        => $game->name,
    "slug"        => $game->slug,
    "categories"  => $game->categs,
    "description" => myarcade_clean_eol( $game->description ),
    "tags"        => isset($game->tags) ? $game->tags : '',
    "instructions"=> isset($game->instructions) ? myarcade_clean_eol( $game->instructions ) : '',
    "controls"    => isset($game->control) ? myarcade_clean_eol( $game->control ) : '',
    "rating"      => isset($game->rating) ? $game->rating : '',
    "height"      => isset($game->height) ? $game->height : '',
    "width"       => isset($game->width) ? $game->width : '',
    "thumbnail_url" => $game->thumbnail_url,
    "swf_url"     => $game->swf_url,
    "screen1_url" => isset($game->screen1_url) ? $game->screen1_url : '',
    "screen2_url" => isset($game->screen2_url) ? $game->screen2_url : '',
    "screen3_url" => isset($game->screen3_url) ? $game->screen3_url : '',
    "screen4_url" => isset($game->screen4_url) ? $game->screen4_url : '',
    "video_url"   => isset($game->video_url) ? $game->video_url : '',
    "created"     => isset($game->created) ? $game->created : date( 'Y-m-d h:i:s', time() ),
    "leaderboard_enabled" => isset($game->leaderboard_enabled) ? $game->leaderboard_enabled : '',
    "highscore_type" => isset($game->highscore_type) ? $game->highscore_type : '',
    "score_bridge"   => isset($game->score_bridge) ? $game->score_bridge : '',
    "coins_enabled"  => isset($game->coins_enabled) ? $game->coins_enabled : '',
    "status"      => "new",
  );

  $wpdb->insert( $wpdb->prefix . 'myarcadegames', $game_data );
}

/**
 * Creates a wordpress post with the given game and returns the post id
 *
 * @version 5.19.0
 * @param   object $game Game object
 * @return  int $post_id
 */
function myarcade_add_game_post($game) {
  global $wpdb;

  // Get settings
  $general    = get_option('myarcade_general');

  // Single publishing active?
  if ( $general['single'] ) {
    // Clear categories and replace with the single one
    $game->categories = array();
    $game->categories[0] = $general['singlecat'];
  }

  // Generate the content
  if ($general['use_template'] ) {
    $post_content = $general['template'];
    $post_content = str_replace("%THUMB_URL%", $game->thumb, $post_content);
    $post_content = str_replace("%THUMB%", '<img src="' . $game->thumb . '" alt="' . $game->name . '" />', $post_content);
    $post_content = str_replace("%TITLE%", $game->name, $post_content);
    $post_content = str_replace("%DESCRIPTION%", $game->description, $post_content);
    $post_content = str_replace("%INSTRUCTIONS%", $game->instructions, $post_content);
    $post_content = str_replace("%SWF_URL%", $game->file, $post_content);
    $post_content = str_replace("%WIDTH%", $game->width, $post_content);
    $post_content = str_replace("%HEIGHT%", $game->height, $post_content);

    // Prepare tags for the content
    $tags_array   = explode(',', $game->tags);
    $tags_string  = '';

    foreach ($tags_array as $tag) {
     $tags_string .= trim($tag).', ';
    }

    // Remove last ', '
    $tags_string = substr($tags_string, 0, strlen($tags_string) - 2);

    // Insert Tags to the post content
    $post_content = str_replace("%TAGS%", $tags_string, $post_content);

  }
  else {
    $post_content = $game->description;

    if ( ! empty( $game->instructions ) ) {
      $post_content .= "<br />" . $game->instructions;
    }
  }

  //====================================
  // Create a WordPress post
  $post = array();
  $post['post_title']   = $game->name;
  $post['post_content'] = $post_content;
  $post['post_status']  = $game->publish_status;
  $post['post_author']  = apply_filters( 'myarcade_filter_post_author', $game->user, $game);

  if ( $general['post_type'] != 'post' && post_type_exists($general['post_type']) ) {
    $post['post_type'] = $general['post_type'];
  }
  else {
    $post['post_type'] = 'post';
    $post['post_category'] = apply_filters( 'myarcade_filter_category', $game->categories, $game ); // Category IDs - ARRAY

    if ( !isset($general['disable_game_tags']) || $general['disable_game_tags'] == false ) {
      $post['tags_input'] = apply_filters( 'myarcade_filter_tags', $game->tags, $game );
    }
  }

  $post['post_date'] = $game->date;

  $post_id = wp_insert_post($post);

  // Required fields
  add_post_meta($post_id, 'mabp_game_type',     $game->type);
  add_post_meta($post_id, 'mabp_description',   $game->description);

  if ( $game->instructions ) {
    add_post_meta($post_id, 'mabp_instructions',  $game->instructions);
  }

  add_post_meta($post_id, 'mabp_swf_url',       $game->file);
  add_post_meta($post_id, 'mabp_thumbnail_url', $game->thumb);
  add_post_meta($post_id, 'mabp_game_tag',      $game->game_tag);
  add_post_meta($post_id, 'mabp_game_uuid',     $game->uuid);
  add_post_meta($post_id, 'mabp_game_slug',     $game->slug);

  // Optional fields
  if ( $game->height ) {
    add_post_meta($post_id, 'mabp_height', $game->height);
  }
  if ( $game->width ) {
    add_post_meta($post_id, 'mabp_width', $game->width);
  }
  if ( $game->rating ) {
    add_post_meta($post_id, 'mabp_rating', $game->rating);
  }
  if ( $game->screen1_url ) {
    add_post_meta($post_id, 'mabp_screen1_url', $game->screen1_url);
  }
  if ( $game->screen2_url ) {
    add_post_meta($post_id, 'mabp_screen2_url', $game->screen2_url);
  }
  if ( $game->screen3_url ) {
    add_post_meta($post_id, 'mabp_screen3_url', $game->screen3_url);
  }
  if ( $game->screen4_url ) {
    add_post_meta($post_id, 'mabp_screen4_url', $game->screen4_url);
  }
  if ( $game->video_url ) {
    add_post_meta($post_id, 'mabp_video_url', $game->video_url);
  }
  if ( $game->leaderboard_enabled ) {
    add_post_meta($post_id, 'mabp_leaderboard', $game->leaderboard_enabled);
    add_post_meta($post_id, 'mabp_score_order', $game->highscore_type);
  }

  if ( $game->score_bridge ) {
    add_post_meta($post_id, 'mabp_score_bridge', $game->score_bridge);
  }

  // Generate Featured Image id activated
  if ( $general['featured_image'] ) {
    myaracade_set_featured_image( $post_id, $game->thumb );
  }

  // Add custom taxonomies
  if ( $general['post_type'] != 'post' && post_type_exists($general['post_type']) ) {
    if ( !empty($general['custom_category']) && taxonomy_exists($general['custom_category']) ) {
      $categories = apply_filters( 'myarcade_filter_category', $game->categories, $game );
      wp_set_object_terms($post_id, $categories, $general['custom_category']);
    }
    if ( !isset($general['disable_game_tags']) || $general['disable_game_tags'] == false ) {
      if ( !empty($general['custom_tags']) && taxonomy_exists($general['custom_tags']) ) {
        $tags = apply_filters( 'myarcade_filter_tags', $game->tags, $game );
        wp_set_post_terms($post_id, $tags, $general['custom_tags']);
      }
    }
  }


  // Update postID
  $wpdb->query( "UPDATE " . $wpdb->prefix . 'myarcadegames' . " SET postid = '{$post_id}' WHERE id = '{$game->id}'" );

  // Fire an action when the post has been created
  do_action( 'myarcade_post_created', $post_id );

  return $post_id;
}

/**
 * Prepares a game to be added to WordPress
 *
 * - Category mapping
 * - File downloads
 *
 * @version 5.19.0
 * @param   array  $args
 * @return  int|bool Post ID on success or FALSE on error
 */
function myarcade_add_games_to_blog( $args = array() ) {
  global $wpdb, $user_ID, $myarcade_feedback;

  $general = get_option('myarcade_general');

  $defaults = array(
    'game_id'          => false,
    'post_status'      => 'publish',
    'post_date'        => gmdate('Y-m-d H:i:s', ( time() + (get_option('gmt_offset') * 3600 ))),
    'download_games'   => $general['down_games'],
    'download_thumbs'  => $general['down_thumbs'],
    'download_screens' => $general['down_screens'],
    'echo'             => true
  );

  $r = wp_parse_args( $args, $defaults );
  extract($r);

  if ( $echo ) {
    $echo_feedback = "echo";
  }
  else {
    $echo_feedback = "return";
  }

  $myarcade_feedback_args  = array( 'output' => $echo );

  if ( ! $game_id ) {
    $myarcade_feedback->add_error( __("Game ID not provided.", 'myarcadeplugin') );
    $myarcade_feedback->get_errors( $myarcade_feedback_args );
    return false;
  }

  // Create new object
  $game_to_add = new StdClass();

  if ( $echo && function_exists( 'myarcade_header' ) ) {
    myarcade_header($echo);
  }

  myarcade_prepare_environment($echo);

  // Get settings
  $feedcategories = get_option('myarcade_categories');

  // Initialize the var for custom post type
  $use_custom_tax = false;
  if ( ($general['post_type'] != 'post') && post_type_exists($general['post_type']) ) {
    if ( !empty($general['custom_category']) && taxonomy_exists($general['custom_category']) ) {
      $use_custom_tax = true;
    }
  }

  // Get the game
  $game = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . 'myarcadegames' . " WHERE id = '".$game_id."' limit 1");

  if ( !$game ) {
    $myarcade_feedback->add_error( __("Can't find the game in the games database table.", 'myarcadeplugin') );
    $myarcade_feedback->get_errors( $myarcade_feedback_args );
    return false;
  }

  // Check if this is an imported game..
  // If so, then don't download the files again...
  if ( md5($game->name . 'import') == $game->uuid ) {
    $download_games   = false;
    $download_thumbs  = false;
    $download_screens = false;
  }
  elseif ( $download_games == true ) {
    // Get distributor integration
    myarcade_distributor_integration( $game->game_type );

    // Generate download check function name
    $download_check_function = 'myarcade_can_download_' . $game->game_type;

    if ( function_exists( $download_check_function ) ) {
      $download_games = $download_check_function();
    }
    else {
      switch ( $game->game_type ) {
        case 'iframe':
        case 'embed':
          $download_games = false;
        break;

        default:
          // try to download game
          $download_games = true;
        break;
      }
    }
  }

  // Initialise category array
  $cat_id = array();
  // Check game categories..
  $categs = explode(",", $game->categories);

  if ( $general['firstcat'] == true ) {
    $tempcateg = $categs[0];
    unset($categs);
    $categs = array();
    $categs[0] = $tempcateg;
  }

  if ( 'bigfish' == $game->game_type ) {
    $bigfish = get_option( 'myarcade_bigfish' );
    $feedcategories = $bigfish['categories'];
  }

  foreach ($categs as $game_cat) {
    $cat_found = false;
    foreach ($feedcategories as $feedcat) {
      if ($feedcat['Name'] == $game_cat) {
        $cat_found = true;
        // Check for custom taxonomies
        if ($use_custom_tax) {
          $term = get_term_by( 'name', $game_cat, $general['custom_category'] );

          if ( ! empty( $term->term_id ) ) {
            array_push( $cat_id, $term->term_id );
          }
          else {
            // Term doesn't exist!!
           array_push($cat_id, htmlspecialchars( $game_cat ) );
          }
        } else {
          // post_type = post
          array_push( $cat_id, get_cat_id( htmlspecialchars($game_cat ) ) );
        }

        break;
      }
    }

    if ($cat_found == false) {
      if ( $use_custom_tax ) {
        $term = get_term_by( 'name', $game_cat, $general['custom_category'] );
        if ( ! empty( $term->term_id ) ) {
          array_push( $cat_id, $term->term_id );
        }
        else {
          // Term doesn't exist!!
          array_push($cat_id, htmlspecialchars( $game_cat ) );
        }
      }
      else {
        array_push($cat_id, get_cat_id($game_cat));
      }
    }
  }

  $download_message = array(
    'url'       => __("Use URL provided by the game distributor.", 'myarcadeplugin'),
    'thumbnail' => __("Download Thumbnail", 'myarcadeplugin'),
    'screen'    => __("Download Screenshot", 'myarcadeplugin'),
    'game'      => __("Download Game", 'myarcadeplugin'),
    'failed'    => __("FAILED", 'myarcadeplugin'),
    'ok'        => __("OK", 'myarcadeplugin')
  );

  // Get download folders
  $upload_dir = myarcade_get_folder_path($game->slug, $game->game_type);

  // ----------------------------------------------
  // Download Thumbs?
  // ----------------------------------------------
  if ($download_thumbs == true) {

    $file = myarcade_get_file($game->thumbnail_url, true);

    if ( empty($file['error']) ) {
      $path_parts = pathinfo($game->thumbnail_url);
      $extension = $path_parts['extension'];
      $file_name = $game->slug . '.' . $extension;

      // Check, if we got a Error-Page
      if (!strncmp($file['response'], "<!DOCTYPE", 9)) {
        $result = false;
      }
      else {
        // Save the thumbnail to the thumbs folder
        $result = file_put_contents( $upload_dir['thumbsdir'] . $file_name, $file['response']);
      }

      // Error-Check
      if ($result == false) {
        $myarcade_feedback->add_message( $download_message['thumbnail'] . ': ' . $download_message['failed'] . ' - ' . $download_message['url'] );
      }
      else {
        $game->thumbnail_url = $upload_dir['thumbsurl'] . $file_name;
        $myarcade_feedback->add_message( $download_message['thumbnail'] . ': ' . $download_message['ok'] );
      }
    }
    else {
      $myarcade_feedback->add_message( $download_message['thumbnail'] . ': ' . $download_message['failed'] . ' - ' . $file['error'] . ' - ' . $download_message['url'] );
    }
  }

  // ----------------------------------------------
  // Download Screens?
  // ----------------------------------------------
  for ($screenNr = 1; $screenNr <= 4; $screenNr++) {
    $screenshot_url = 'screen' . $screenNr . "_url";

    if (($download_screens == true) && ($game->$screenshot_url)) {
      // Download screenshot
      $file = myarcade_get_file($game->$screenshot_url, true);

      $message_screen = sprintf( __("Downloading Screenshot No. %s", 'myarcadeplugin'), $screenNr);

      if ( empty($file['error']) ) {
        $path_parts = pathinfo($game->$screenshot_url);
        $extension = $path_parts['extension'];
        $file_name = $game->slug . '_img' . $screenNr . '.' . $extension;

        // Check, if we got a Error-Page
        if (!strncmp($file['response'], "<!DOCTYPE", 9)) {
          $result = false;
        }
        else {
          // Save the screenshot to the thumbs folder
          $result = file_put_contents( $upload_dir['thumbsdir'] . $file_name, $file['response']);
        }

        // Error-Check
        if ($result) {
          $game->$screenshot_url = $upload_dir['thumbsurl'] . $file_name;
          $myarcade_feedback->add_message( $message_screen . ': ' . $download_message['ok'] );
        }
        else {
          $myarcade_feedback->add_message( $message_screen . ': ' . $download_message['failed'] . ' - ' . $download_message['url'] );
        }
      } // END - if screens
      else {
        $myarcade_feedback->add_message( $message_screen . ': '  . $download_message['failed'] . ' - ' . $file['error'] . ' - ' . $download_message['url'] );
      }
    } // END - downlaod screens

    // Put the screen urls into the post array
    $game_to_add->$screenshot_url = apply_filters( 'myarcade_filter_screenshot', $game->$screenshot_url, $screenshot_url );
  } // END for - screens


  // ----------------------------------------------
  // Download Games?
  // ----------------------------------------------
  if ($download_games == true) {

    // Clean up the swf url before try to download
    $game->swf_url = strtok($game->swf_url, '?');

    $file = myarcade_get_file($game->swf_url, true);

    // We got a file
    if ( empty($file['error']) ) {
      $path_parts = pathinfo($game->swf_url);
      $extension = $path_parts['extension'];
      $file_name = $game->slug . '.' . $extension;

      // Check, if we got a Error-Page
      if (!strncmp($file['response'], "<!DOCTYPE", 9)) {
        $result = false;
      }
      else {
        // Save the game to the games directory
        $result = file_put_contents( $upload_dir['gamesdir'] . $file_name, $file['response']);
      }

      // Error-Check
      if ($result == false) {
        $myarcade_feedback->add_message( $download_message['game'] . ': ' . $download_message['failed'] . ' - ' . $download_message['url']);
      }
      else {
        $myarcade_feedback->add_message( $download_message['game'] . ': ' . $download_message['ok'] );
        // Overwrite the game url
        $game->swf_url = $upload_dir['gamesurl'] . $file_name;
      }
    }
    else {
      $myarcade_feedback->add_message( $download_message['game'] . ': '  . $download_message['failed'] . ' - ' . $file['error'] . ' - ' . $download_message['url'] );
    }
  } // END - if download games

  // Display messages
  if ($echo) {
    $myarcade_feedback->get_messages( array('output' => 'echo') );
  }

  // ----------------------------------------------
  // Create a WordPress post
  // ----------------------------------------------

  // Get user info's
  get_currentuserinfo();

  $game_to_add->user = ( !empty($user_ID) ) ? $user_ID : 1;

  // Overwrite the post status if user has not sufficient rights
  if ( $user_ID  && !current_user_can('publish_posts') ) {
    $post_status = 'draft';
  }

  if ( $post_date ) {
    $game_to_add->date = $post_date;
  }
  else {
    $game_to_add->date = gmdate('Y-m-d H:i:s', ( time() + (get_option('gmt_offset') * 3600 )));
  }

  $game_to_add->id = $game->id;
  $game_to_add->uuid = $game->uuid;
  $game_to_add->name = $game->name;
  $game_to_add->slug = $game->slug;
  $game_to_add->file = apply_filters( 'myarcade_filter_game_code', $game->swf_url, $game->game_type );
  $game_to_add->width = $game->width;
  $game_to_add->height = $game->height;
  $game_to_add->thumb = apply_filters( 'myarcade_filter_thumbnail', $game->thumbnail_url );
  $game_to_add->description = $game->description;
  $game_to_add->instructions = $game->instructions;
  $game_to_add->video_url = $game->video_url;
  $game_to_add->tags = $game->tags;
  $game_to_add->rating = $game->rating;
  $game_to_add->categories = $cat_id;
  $game_to_add->type = $game->game_type;
  $game_to_add->publish_status = $post_status;
  $game_to_add->leaderboard_enabled = $game->leaderboard_enabled;
  $game_to_add->game_tag = $game->game_tag;
  $game_to_add->highscore_type = $game->highscore_type;
  $game_to_add->score_bridge = $game->score_bridge;

  // Add game as a post
  $post_id = myarcade_add_game_post($game_to_add);

  if ( $post_id ) {
    // Game-Table: Set post status to published
    $wpdb->query( "UPDATE " . $wpdb->prefix . 'myarcadegames' . " SET status = 'published' WHERE id = '".$game->id."'" );
    return $post_id;
  }

  return false;
}

/**
 * Set featured image on a post
 *
 * @version 5.13.0
 * @param int $post_id Post ID
 * @param string $filename File URL
 * @return int|bool File ID on success or FALSE on error
 */
function myaracade_set_featured_image ($post_id, $filename) {

  $wp_filetype = wp_check_filetype( basename($filename), null );

  // included required WordPress files
  require_once(ABSPATH . 'wp-admin/includes/image.php');
  require_once(ABSPATH . 'wp-admin/includes/file.php');
  require_once(ABSPATH . 'wp-admin/includes/media.php');

  // Download file to temp location
  $tmp = download_url( $filename );

  // Set variables for storage
  // fix file filename for query strings
  preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $filename, $matches);

  //if ( empty($slug) ) $slug = basename($matches[0]);
  $file_array['name'] = basename($filename);
  $file_array['tmp_name'] = $tmp;
  $file_array['type'] = $wp_filetype['type'];

  // If error storing temporarily, unlink
  if ( is_wp_error( $tmp ) ) {
    @unlink($file_array['tmp_name']);
    $file_array['tmp_name'] = '';
    return false;
  }

  // do the validation and storage stuff
  $thumbid = media_handle_sideload($file_array, $post_id);

  // If error storing permanently, unlink
  if ( is_wp_error($thumbid) ) {
    @unlink($file_array['tmp_name']);
    return $thumbid;
  }

  set_post_thumbnail($post_id, $thumbid);
}

/**
 * Inserts a fetched game to the database
 *
 * @version 5.15.0
 * @access  public
 * @param   object  $game Game object
 * @param   boolean $echo TRUE if the game should be displayed
 * @return  void
 */
function myarcade_add_fetched_game( $game , $echo = false ) {
  global $wpdb;

  // Insert  game into the table
  myarcade_insert_game( $game );

  // Show game
  if ( $echo ) {
    $new_game = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . 'myarcadegames' . " WHERE uuid = '$game->uuid' LIMIT 1");
    myarcade_show_game( $new_game );
  }
}

/**
 * Show a message how many games have been fetched
 *
 * @version 5.13.0
 * @access  public
 * @param   int     $count How many games have been added to the database
 * @param   boolean $echo  TRUE if the result should be echoed
 * @return  void
 */
function myarcade_fetched_message( $count, $echo = false ) {
  if ( ! $echo ) {
    return;
  }

  if ( $count > 0 ) {
    echo '<p class="mabp_info mabp_680"><strong>'.sprintf(__("Found %s new game(s).", 'myarcadeplugin'), $count).'</strong></p>';
    echo '<p class="mabp_info mabp_680">'.__("Now, you can publish new games on your site.", 'myarcadeplugin').'</p>';
  }
  else {
    echo '<p class="mabp_error mabp_680">'.__("No new games found!", 'myarcadeplugin').'</p>';
  }
}

/**
 * Generate slug for a given string
 *
 * @version 5.15.0
 * @param string $string
 * @return string
 */
function myarcade_make_slug( $string ) {
  $slug = sanitize_title($string);
  $slug = strtolower( str_replace(" ", "-", $string ) );
  $slug = preg_replace('/-+/', '-', $slug);
  $slug = preg_replace("/[^\dA-Za-z0-9-]/i", "", $slug);
  return $slug;
}

/**
 * Remove line breaks from a string
 *
 * @version 5.15.2
 * @since   5.15.2
 * @param   string $string
 * @return  string
 */
function myarcade_clean_eol( $string ) {
  return str_replace( array( '\r\n', '\r', '\n'), '', $string );
}

/**
 * Prepares the environment for MyArcadePlugin
 *
 * @version 5.13.0
 * @access  public
 * @param   boolean $echo
 * @return  void
 */
function myarcade_prepare_environment($echo = true) {

  $max_execution_time_l = 600;    // 10 min
  $memory_limit_l       = "128";  // Should be enough
  $set_time_limit_l     = 600;    // 10 min

  // Check for safe mode
  if( !ini_get('safe_mode') ) {
    // Check max_execution_time
    @ini_set("max_execution_time", $max_execution_time_l);
    // Check memory limit
    $limit = ini_get("memory_limit");
    $limit = substr( $limit, 0, 1 );
    if ( $limit < $memory_limit_l ) {
      @ini_set("memory_limit", $memory_limit_l."M");
    }

    @set_time_limit($set_time_limit_l);
  }
  else {
    // save mode is set
    if ($echo) {
      echo '<p class="mabp_error"><strong>'.__("WARNING!", 'myarcadeplugin').'</strong> '.__("Can't make needed settins, because you have Safe Mode active.", 'myarcadeplugin').'</p>';
    }
  }
}

/**
 * Ajax publish handler. Publishes a given game by ID
 *
 * @version 5.13.0
 * @return  void
 */
function myarcade_ajax_publish() {
  global $myarcade_feedback;

  // Don't break the JSON result
  @error_reporting( 0 );

  header( 'Content-type: application/json' );

  $id       = (int) $_REQUEST['id'];
  $status   = $_REQUEST['status'];
  $schedule = (int) $_REQUEST['schedule'];
  $count    = (int) $_REQUEST['count'];
  $download_thumbs = ($_REQUEST['download_thumbs'] == '1') ? true : false;
  $download_screens = ($_REQUEST['download_screens'] == '1') ? true : false;
  $download_games = ($_REQUEST['download_games'] == '1') ? true : false;

  if ( $status == 'future') {
    $post_interval = ($count - 1) * $schedule;
  }
  else {
    $post_interval = 0;
  }

  $args = array(
    'game_id'          => $id,
    'post_status'      => $status,
    'post_date'        => gmdate('Y-m-d H:i:s', ( time() + ($post_interval * 60) + (get_option('gmt_offset') * 3600 ))),
    'download_games'   => $download_games,
    'download_thumbs'  => $download_thumbs,
    'download_screens' => $download_screens,
    'echo'             => false
  );

  $post_id = myarcade_add_games_to_blog($args);

  $errors = '';
  $messages = '';

  if ( is_myarcade_feedback($myarcade_feedback) ) {
    if ( $myarcade_feedback->has_errors() ) {
      $errors = $myarcade_feedback->get_errors(array('output' => 'string'));
    }
    if ( $myarcade_feedback->has_messages() ) {
      $messages = $myarcade_feedback->get_messages(array('output' => 'string'));
    }
  }

  if ( $post_id ) {
    if ( $status == 'publish' ) {
      $post_link = '<a href="'.get_permalink($post_id).'" class="button-secondary" target="_blank">View Post</a>';
    }
    else {
      $post_link = '<a href="'.add_query_arg( 'preview', 'true', get_permalink($post_id) ).'" class="button-secondary" target="_blank">Preview Post</a>';
    }

    $categories = get_the_category($post_id);
    $cat_string = '';

    if ( !empty($categories) ) {
      $count = count($categories);

      for($i=0; $i<$count; $i++) {
        if ( ($count - $i) > 1) {
          $cat_string .= $categories[$i]->cat_name . ', ';
        }
        else {
          $cat_string .= $categories[$i]->cat_name;
        }
      }
    }

    // The game has been published successfully
    wp_die(
      json_encode(
        array( 'success' => '<strong>'.esc_html( get_the_title($post_id) ).'</strong><br />
          <div>
            <div style="float:left;margin-right:5px">
              <img src="'. get_post_meta($post_id, 'mabp_thumbnail_url', true).'" width="80" height="80" alt="">
            </div>
            <div style="float:left">
            <table border="0">
            <tr valign="top">
              <td width="200"><strong>Categories:</strong> '.$cat_string.'<br />'.$errors.'</td>
              <td width="350">'.$messages.'</td>
            </tr>
            </table>
             <p><a href="'.get_edit_post_link( $post_id ).'" class="button-secondary" target="_blank">Edit Post</a> '.$post_link.'</p>
            </div>
          </div>
          <div style="clear:both;"></div>'
        )
      )
    );
  }
  else {
    // Error while creating game post
    die(json_encode(array('error' => __("Error: Post can not be created!", 'myarcadeplugin') .' - ' . $messages )));
  }
}
add_action('wp_ajax_myarcade_ajax_publish', 'myarcade_ajax_publish');
?>