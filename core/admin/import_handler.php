<?php
/**
 * Import games AJAX Handler
 * Handles file uploads for each game type
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 */

// No direct access
if( !defined( 'ABSPATH' ) ) {
  die();
}

// Check user
if ( function_exists('current_user_can') && !current_user_can('edit_posts') ) {
  die();
}

// Load required WordPress files
require_once(ABSPATH . 'wp-admin/includes/file.php');

// Log game import
myarcade_log_import ( "Import Game POST: " . print_r($_POST, true) . "\n\n" . 'Files: ' . print_r($_FILES, true) );

// Courtesy of php.net, the strings that describe the error indicated in $_FILES[{form field}]['error'].
$upload_error_strings = array( false,
  __( "The uploaded file exceeds the upload_max_filesize directive in php.ini." ),
  __( "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form." ),
  __( "The uploaded file was only partially uploaded." ),
  __( "No file was uploaded." ),
  '',
  __( "Missing a temporary folder." ),
  __( "Failed to write file to disk." ),
  __( "File upload stopped by extension." ));

$upload_dir = myarcade_upload_dir();

$game = new stdClass();
$game->info_dim = '';
$game->error = '';

$result = false;

// Check the submission
switch ( $_POST['upload'] ) {

  // Upload SWF / DCR File
  case 'swf':
  {
    if ( !empty($_FILES['gamefile']['name']) ) {
      // Error check
      if ( !empty($_FILES['gamefile']['error']) ) {
        $game->error = $upload_error_strings[$_FILES['gamefile']['error']];
        myarcade_log_import('Error while uploading SWF File: ' . $game->error);
      }
      else {
        $file_temp = $_FILES['gamefile']['tmp_name'];
        $file_info = pathinfo($_FILES['gamefile']['name']);
        // generate new file name
        $upload_dir_specific = myarcade_get_folder_path($file_info['filename'], 'custom');
        $file_name = wp_unique_filename( $upload_dir_specific['gamesdir'], $file_info['basename'] );
        $result = move_uploaded_file($file_temp, $upload_dir_specific['gamesdir'] . $file_name);
        // Delete temp file
        @unlink($_FILES['gamefile']['tmp_name']);
      }
    }
    elseif ( !empty($_POST['gameurl']) ) {
      // grab from net?
      $file_temp = myarcade_get_file($_POST['gameurl']);

      if ( !empty($file_temp['error']) ) {
        // Get error message
        $game->error = $file_temp['error'];
        myarcade_log_import("Error while downloading SWF file: " . $game->error);
      }
      else {
        $file_info = pathinfo($_POST['gameurl']);
        $upload_dir_specific = myarcade_get_folder_path($file_info['filename'], 'custom');
        $file_name = wp_unique_filename( $upload_dir_specific['gamesdir'], $file_info['basename']);
        $result = file_put_contents(  $upload_dir_specific['gamesdir'] . $file_name, $file_temp['response']);
      }
    }
    elseif ( !empty( $_POST['fileselectswf'] ) ) {
      $full_abs_path = $upload_dir['gamesdir'] . '/uploads/swf/' . $_POST['fileselectswf'];

      if ( !file_exists( $full_abs_path ) ) {
        $game->error = __("Can't find the selected file.", 'myarcadeplugin');
      }
      else {
        $file_info      = pathinfo( $_POST['fileselectswf'] );
        $upload_dir_specific = myarcade_get_folder_path($file_info['filename'], 'custom');
        $file_name      = wp_unique_filename($upload_dir_specific['gamesdir'], $file_info['basename']);
        $result         = rename($full_abs_path, $upload_dir_specific['gamesdir'] . $file_name);
      }
    }
    else {
      $result = false;
    }

    if ( empty($game->error) ) {

      if ($result == true) {
        // Get the file extension
        if ( strtolower( $file_info['extension'] ) == 'dcr') {
          $game->type = 'dcr';
        }
        else {
          $game->type = 'custom';
        }

        $game->name = ucfirst($file_info['filename']);
        $game->location_abs = $upload_dir_specific['gamesdir'] . $file_name;
        $game->location_url = $upload_dir_specific['gamesurl'] . $file_name;

        // try to detect dimensions
        $game_dimensions = @getimagesize($game->location_abs);
        $game->width    = intval($game_dimensions[0]);
        $game->height   = intval($game_dimensions[1]);
        $game->info_dim = 'Game dimensions: '.$game->width.'x'.$game->height;

        if ( empty($game->width) || empty($game->height) ) {
          $game->width  = 0;
          $game->height = 0;
          $game->info_dim = 'Can not detect game dimensions';
        }

        // Try to get the game name
        $name = explode('.', $game->name);
        $game->realname = ucfirst( str_replace('_', ' ', $name[0]) );
      }
      else {
        $game->error = __("Can not upload file!", 'myarcadeplugin');
      }
    }
  }
  break;

  // Upload Game Thumb
  case 'thumb':
  {
    if ( !empty($_FILES['thumbfile']['name']) ) {
      // Error check
      if ( !empty($_FILES['gamefile']['error']) ) {
        $game->error = $upload_error_strings[$_FILES['gamefile']['error']];
        myarcade_log_import('Error while uploading thumbnail: ' . $game->error);
      }
      else {
        $file_temp = $_FILES['thumbfile']['tmp_name'];
        $file_info = pathinfo($_FILES['thumbfile']['name']);
        // generate new file name
        $upload_dir_specific = myarcade_get_folder_path($file_info['filename'], 'custom');
        $file_name = wp_unique_filename( $upload_dir_specific['thumbsdir'], $file_info['basename'] );
        $result = move_uploaded_file($file_temp, $upload_dir_specific['thumbsdir'] . $file_name);
        // Delete temp file
        @unlink($_FILES['thumbfile']['tmp_name']);
      }
    }
    else if  ( !empty($_POST['thumburl']) ) {
      // grab from net?
      $file_temp = myarcade_get_file($_POST['thumburl']);

      if ( !empty($file_temp['error']) ) {
        // Get error message
        $game->error = $file_temp['error'];
        myarcade_log_import("Error while downloading thumbnail: " . $game->error);
      }
      else {
        $file_info = pathinfo($_POST['thumburl']);
        $upload_dir_specific = myarcade_get_folder_path($file_info['filename'], 'custom');
        $file_name = wp_unique_filename( $upload_dir_specific['thumbsdir'], $file_info['basename'] );
        $result = file_put_contents( $upload_dir_specific['thumbsdir'] . $file_name, $file_temp['response'] );
      }
    }

    if ( empty($game->error) ) {
      if ($result == true) {
        $game->thumb_name = $file_name;
        $game->thumb_url  = $upload_dir_specific['thumbsurl'] . $file_name;
        $game->thumb_id   = myarcade_add_attachment( $game->thumb_url, $upload_dir_specific['thumbsdir'] . $file_name );
      }
      else {
        $game->error = 'Can not upload thumbnail!';
      }
    }
  }
  break;

  // Upload Game Screenshots
  case 'screen':
  {
    for ($i = 0; $i <= 3; $i++) {
      $screen = 'screen'.$i;
      $result = false;

      if ( !empty($_FILES[$screen]['name']) ) {
        // Error check
        if ( !empty($_FILES[$screen]['error']) ) {
          $game->error = $upload_error_strings[$_FILES[$screen]['error']];
          myarcade_log_import('Error while uploading screenshot nr. '.$screen.': ' . $game->error);
        }
        else {
          // There is a screen to upload
          $file_temp = $_FILES[$screen]['tmp_name'];
          $file_info = pathinfo($_FILES[$screen]['name']);
          $upload_dir_specific = myarcade_get_folder_path($file_info['filename'], 'custom');
          $file_name = wp_unique_filename( $upload_dir_specific['thumbsdir'], $file_info['basename'] );
          $result = move_uploaded_file($file_temp, $upload_dir_specific['thumbsdir'] . $file_name);
          // Delete temp file
          @unlink($_FILES[$screen]['tmp_name']);
        }
      }
      else if  ( !empty($_POST[$screen.'url']) ) {
         // There is a screen to grab
        $file_temp = myarcade_get_file($_POST[$screen.'url']);

        if ( !empty($file_temp['error']) ) {
          // Get error message
          $game->error = $file_temp['error'];
          myarcade_log_import("Error while downloading screenshot nr. ".$screen.": " . $game->error);
        }
        else {
          $file_info = pathinfo($_POST[$screen.'url']);
          $upload_dir_specific = myarcade_get_folder_path($file_info['filename'], 'custom');
          $file_name = wp_unique_filename( $upload_dir_specific['thumbsdir'], $file_info['basename']);
          $result = file_put_contents( $upload_dir_specific['thumbsdir'] . $file_name, $file_temp['response']);
        }
      }

      if ($result == true) {
        $game->screen_abs[$i] = $upload_dir_specific['thumbsdir'] . $file_name;
        $game->screen_url[$i] = $upload_dir_specific['thumbsurl'] . $file_name;
        $game->screen_name[$i]= $file_name;
        $game->screen_error[$i] = 'OK';
      }
      else {
        $game->screen_error[$i] = 'Upload Failed For Screen No. '.($i+1).' '.$game->error;
        $game->screen_abs[$i] = '';
        $game->screen_url[$i] = '';
        $game->screen_name[$i]= '';
      }
    }
  }
  break;

  // Import Embed / Iframe Code
  case 'emif':
  {
    if ( !empty( $_POST['embedcode'] ) ) {
      $game_code = filter_input( INPUT_POST, 'embedcode' );

      // Check the code
      if( filter_var( $game_code, FILTER_VALIDATE_URL ) ) {
        $game->type = 'iframe';
      }
      else {
        $game->type = 'embed';
      }

      $game->importgame = urlencode( str_replace( '"', '\'', $game_code ) );
      $game->result = 'OK';
    }
    else {
      $game->error = 'No embed code entered!';
    }
  }
  break;

  // What to import??
  default:
  {
    $game->error = 'Unknown Import Method';
  }
  break;

} // end swtich

// Prepare the output
$json = json_encode($game);

myarcade_log_import("Json Return: " . print_r($json, true) );

wp_die( $json );
?>