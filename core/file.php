<?php
/**
 * File handle functions
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 * @copyright (c) 2015, Daniel Bakovic
 * @license http://myarcadeplugin.com
 * @package MyArcadePlugin/Core/File
 */

/*
 * Copyright @ Daniel Bakovic - contact@myarcadeplugin.com
 * Do not modify! Do not sell! Do not distribute! -
 * Check our license Terms!
 */

// No direct access
if( !defined( 'ABSPATH' ) ) {
  die();
}

if ( !function_exists('file_put_contents') ) {
  /**
   * Alternative file_put_contents function
   *
   * @version 5.0.0
   * @access  public
   * @param   string $filename File name
   * @param   string $data Data that should be written into the file
   * @return  int|bool Bytes written or FALSE on error
   */
  function file_put_contents( $filename, $data ) {
    $f = @fopen($filename, 'w');
    if (!$f) {
      return false;
    } else {
      $bytes = fwrite($f, $data);
      fclose($f);
      return $bytes;
    }
  }
}

/**
 * Delete a given file from the hard drive
 *
 * @version 5.0.0
 * @access  public
 * @param   string $file_abs Absolute path to a file
 * @return  void
 */
function myarcade_del_file( $file_abs ) {
  if ( file_exists( $file_abs ) ) {
      @unlink( $file_abs );
  }
}

/**
 * Checks if a game is deleteable
 *
 * @version 5.0.0
 * @access  public
 * @param   string $gametype Game type / distributor ID
 * @return  bool TRUE if game type is deletable
 */
function myarcade_is_game_deleteable( $gametype ) {
  switch ($gametype) {
    case 'mochi':
    case 'heyzap':
    case 'ibparcade':
    case 'kongregate':
    case 'playtomic':
    case 'fgd':
    case 'fog':
    case 'spilgames':
    case 'gamefeed':
    case 'unityfeeds':
    case 'myarcadefeed':
    {
      $result = true;
    } break;

    default:
    {
      $result = false;
    } break;
  }

  return $result;
}

/**
 * Get the abspath of the given URL
 *
 * @version 5.0.0
 * @access  public
 * @param   string $url URL
 * @return  string|bool string if the absolute path has been found. Otherwise FALSE
 */
function myarcade_get_abs_path( $url )  {

  // Get the content URL
  $content_url = content_url();

  // Check if the URL matches with our content URL
  if ( strpos( $url, $content_url ) === FALSE ) {
    // External site
    return false;
  }

  // Remove content URL
  $link_part = str_replace( $content_url, "", $url );

  // Generate the file abs path
  $file_path = WP_CONTENT_DIR . $link_part;

  if ( file_exists( $file_path ) ) {
    return $file_path;
  }

  return false;
}

/**
 * Delete game files when deleting a post
 *
 * @version 5.0.0
 * @access  public
 * @param   int $post_ID Post ID
 * @return  bool
 */
function myarcade_delete_game( $post_ID ) {
  global $wpdb;

  // Get myarcadeplugin settings
  $general = get_option('myarcade_general');

  // Should game files be deleted
  if ( $general['delete'] ) {
    // Delete game thumb if exists
    $thumburl = get_post_meta($post_ID, "mabp_thumbnail_url", true);

    if ($thumburl) {
      $thumb_abs = myarcade_get_abs_path( $thumburl );

      if ($thumb_abs) {
        myarcade_del_file($thumb_abs);
      }
    }

    // Delete game screenshots if exists
    for ($i = 1; $i <= 4; $i++) {
      $screenshot = get_post_meta($post_ID, "mabp_screen".$i."_url", true);

      if ($screenshot) {
        $screen_abs = myarcade_get_abs_path( $screenshot );

        if ($screen_abs) {
          myarcade_del_file($screen_abs);
        }
      }
    } // END for screens

    // Delete game swf if exists
    $gameurl  = get_post_meta($post_ID, "mabp_swf_url", true);
    $gametype = get_post_meta($post_ID, "mabp_game_type", true);

    if ( myarcade_is_game_deleteable($gametype) && $gameurl ) {
      $game_abs = myarcade_get_abs_path( $gameurl);

      if ($game_abs) {
        myarcade_del_file($game_abs);
      }
    }
  } // END if delete files

  // Delete game scores
    // Get game_tag
  $game_tag = $wpdb->get_var("SELECT game_tag FROM `".$wpdb->prefix . 'myarcadegames'."` WHERE `postid` = '$post_ID'");
    // Delete scores
  $wpdb->query("DELETE FROM `".$wpdb->prefix.'myarcadescores'."` WHERE  `game_tag` = '$game_tag'");

  // Set game status to deleted
  $query = "UPDATE `".$wpdb->prefix . 'myarcadegames'."` SET
           `status` = 'deleted',
           `postid` = ''
           WHERE `postid` = '$post_ID'";

  $wpdb->query($query);

  return true;
}
add_action('before_delete_post', 'myarcade_delete_game');

/**
 * Downloads a file using WordPress HTTP function. After the download
 * the file content will be returned. On error the function will return false.
 *
 * @version 5.0.0
 * @access  public
 * @param   string $url URL
 * @return  array
 */
function myarcade_get_file( $url ) {

  $output = array ( 'response' => null, 'error' => null );

  $args = array('timeout' => '300', 'sslverify' => false);
  $response = wp_remote_get($url, $args);

  // Check for error
  if ( is_wp_error($response) ) {
    $output['error'] = $response->get_error_message();
  }
  else {
    // Check if the server sent a 404 code
    if (wp_remote_retrieve_response_code($response) == 404) {
      $output['error'] = __("File not found", 'myarcadeplugin');
    }
  }

  $output['response'] = wp_remote_retrieve_body($response);

  return $output;
}

/**
 * Determinate the file folder depended on the game type, file type and file name
 *
 * @version 5.0.0
 * @access  public
 * @param   string $name File name
 * @param   string $type Game type
 * @return  array        Folder paths
 */
function myarcade_get_folder_path($name = '', $type = '') {
  global $myarcade_feedback;

  $upload_dir = myarcade_upload_dir();

  if ( empty($name) || empty($type) ) {
    $myarcade_feedback->add_message("Missing parameters on the create folder function!");
    return $upload_dir;
  }

  $general = get_option('myarcade_general');
  $general['folder_structure'] = trim($general['folder_structure']);

  // If not folder structure is set then return false. Check if user has entered just a slash ("/")
  if ( empty($general['folder_structure']) || (strlen($general['folder_structure']) <= 1) ) {
    return $upload_dir;
  }

  // Init folder vars
  $folder = false;
  $sub_folder = "0-9";

  // Check the first char of the game name
  if ( ctype_alnum($name[0]) && !is_numeric($name[0]) ) {
    // Use alphabetic folder
    $sub_folder = ucfirst($name[0]);
  }

  // Replace placeholders with the defined folder structure
  $folder = str_replace("%game_type%", $type, $general['folder_structure']);
  $folder = str_replace("%alphabetical%", $sub_folder, $folder);
  // Clean up the folder string
  $folder = str_replace( '//', '/', $folder );
  // append a slash
  $folder = trailingslashit( $folder );
  // add slash at the beginning
  /*if ( strpos( $folder, '/') !== 0 ) {
    $folder = '/' . $folder;
  }*/

  // Check if the folder exists and create if needed
  if ( wp_mkdir_p( $upload_dir['gamesdir'] . $folder ) ) {
    // Folder created or already exists
    // Modify default game paths
    $upload_dir['gamesdir']   = $upload_dir['gamesdir'] . $folder;
    $upload_dir['gamesurl']   = $upload_dir['gamesurl'] . $folder;
    $upload_dir['gamesbase']  = $upload_dir['gamesbase'] . $folder;
  }
  else {
    // Folder creation failed
    $myarcade_feedback->add_message("Can't create folder: " . $upload_dir['gamesdir'] . $folder );
  }

  if ( wp_mkdir_p( $upload_dir['thumbsdir'] . $folder ) ) {
    // Folder created or already exists
    // Modify default game paths
    $upload_dir['thumbsdir']  = $upload_dir['thumbsdir'] . $folder;
    $upload_dir['thumbsurl']  = $upload_dir['thumbsurl'] . $folder;
    $upload_dir['thumbsbase'] = $upload_dir['thumbsbase'] . $folder;
  }
  else {
    // Folder creation failed
    $myarcade_feedback->add_message("Can't create folder: " . $upload_dir['thumbsdir'] . $folder );
  }

  return $upload_dir;
}

/**
 * Display a max post size message
 *
 * @version 5.0.0
 * @access  public
 * @return  void
 */
function myarcade_get_max_post_size_message() {

  $post_max_size = ini_get( 'post_max_size' ) . 'B';

  if ( $post_max_size ) {
    ?>
    <div class="mabp_info mabp_680">
      <?php echo sprintf( __("Your server settings allow you to upload files up to %s.", 'myarcadeplugin'), $post_max_size ); ?>
    </div>
    <?php
  }
}

/**
 * Returns the max post size in bytes
 *
 * @version 5.0.0
 * @access  public
 * @return  int
 */
function myarcade_get_max_post_size_bytes() {

  $post_max_size = ini_get( 'post_max_size' );

  switch (substr ($post_max_size, -1)) {
    case 'M': case 'm': return (int)$post_max_size * 1048576;
    case 'K': case 'k': return (int)$post_max_size * 1024;
    case 'G': case 'g': return (int)$post_max_size * 1073741824;
    default: return $post_max_size;
  }
}

/**
 * List all files available in the uploads/game_type folder
 *
 * @version 5.0.0
 * @access  public
 * @return  void
 */
function myarcade_get_filelist() {

  if ( empty( $_POST['type'] ) ) {
    exit;
  }

  $upload_dir = myarcade_upload_dir();

  $type = $_POST['type'];
  $dir =  $upload_dir['gamesdir'] . 'uploads/' . $type;

  $files_array = array();

  // Open directory
  $handle = opendir( $dir );

  if ( $handle ) {
    while (false !== ($file = readdir($handle))) {
      if ($file != "." && $file != ".." && $file != "Thumbs.db" && $file != ".DS_Store") {
        $files_array[] = $file;
      }
    }
    closedir($handle);

    natcasesort($files_array);
  }

  if ( !empty( $files_array ) ) {
    echo '<select name="fileselect'.$type.'" id="fileselect'.$type.'">';

    foreach ($files_array as $file_name) {
      echo '<option value="'.$file_name.'">'.$file_name.'</option>';
    }

    echo '</select>';

  }
  else {
   echo '<span id="fileselect'.$type.'">'. __("No files found!", 'myarcadeplugin') . '</span>';
  }

  exit;
}
add_action('wp_ajax_myarcade_get_filelist', 'myarcade_get_filelist');
?>