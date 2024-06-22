<?php
/**
 * Mochi score module for MyArcadePlugin Pro
 * Collects submitted Mochi Media scores and medals.
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 */


// Locate WordPress root folder
$root = dirname( dirname( dirname( dirname( dirname(__FILE__)))));

if ( file_exists($root . '/wp-load.php') ) {
  define('MYARCADE_DOING_ACTION', true);
  require_once($root . '/wp-load.php');
}
else {
  // WordPress not found
  die();
}

// Description from Mochimdia
/*
 * In order to use this for authentication, create a string of all the POST vars
 * and their values, EXCEPT signature. The POST vars and values should be a URL
 * encoded string, sorted alphabetically by param name.
 *
 * 1 Populate an array of all parameter names as keys and their values.

 * 2 Remove out the signature parameter.

 * 3 Sort the array alphabetically by the key name.

 * 4 Turn the array into a url encoded string that looks like this:
boardID=1e113c7239048b3f&datatype=number&description=This%20is%20the%20MochiScores&gameID=84993a1de4031cd8&name=rockstar&score=11519&scoreLabel=score&sessionID=sf908uw098urerjw3948&sortOrder=desc&title=My%20Top%20Scores&userID=29281&username=rockstar

 * 5 Append your secret key. For example, if your query string before was ...
    &username=rockstar

    then it would look like

    &username=rockstarsdcb66e8d7676deb7e6db787cbd98d

 * 6 Compute the MD5 hash with the string, and compare your MD5 hash with the
 *   signature parameter sent by the Bridge
 */


/*
  -- SCORES --
submission - "score" will be passed here. This is used to filter score submissions.
userID - Unique ID of the logged-in player. This will be what you supplied in the embed parameters.
name - Username the user input for the score.
username - Username you supplied in the embed parameters.
sessionID - Returned ID provided through the embed parameters to identify the unique user playing the game.
score - Integer indicating the score the player is submitting
gameID - Unique ID of the game the leaderboard belongs to.
boardID - Unique ID of the leaderboard.
title - Title of the leaderboard
description - Text description of the leaderboard (optionally supplied)
datatype - Value indicating the type of score. Values are either 'number' or 'time'. Note: time is supplied in milliseconds
sortOrder - Value indicating the sort direction of the scores. Values are either 'asc' or 'desc'.
scoreLabel - Label to indicate what the score represents. (ex: 'Track time', 'Gems', or 'Points')

 -- MEDALS --
submission - "medal" will be passed here. This is used to filter medal submissions.
name - Plain test name of the medal achieved.
description - Plain text description of the medal achieved.
thumbnail - Fully qualified URL to a 64x64 pixel image of the medal.
gameID - Unique ID of the game that this medal is associated with.
userID - Unique ID of the logged-in player. This will be what you supplied in the embed parameters.
username - User name of the player. This will be what you supplied in the embed parameters.
sessionID - Returned ID provided through the embed parameters to identify the unique user playing the game.
*/

if ( !isset($_POST['signature']) ) {
  die();
}

$mochi = get_option('myarcade_mochi');

// Create a string
$submitted_data = "";
// Sort the array alphabetically by the key name
ksort($_POST);

// Turn the array into a url encoded string
foreach ($_POST as $name => $value) {
  if ($name != 'signature') {
     $submitted_data .= $name."=".rawurlencode($value)."&";
  }
}

$submitted_data = substr($submitted_data, 0, strlen($submitted_data) - 1);

// Apped the secret key
$submitted_data .= $mochi['secret_key'];

if ( defined( 'MYARCADE_DEBUG' ) && MYARCADE_DEBUG ) {
  myarcade_log_score ( 'Mochi Game Submitted Data: ' . $submitted_data);
}

// Compare the MD5 with signature
if( md5($submitted_data) == $_POST['signature']) {

  // We have received valid submission
  if ( defined( 'MYARCADE_DEBUG' ) && MYARCADE_DEBUG ) {
    myarcade_log_score('Mochi Signature OK!');
  }

  //
  // SCORE SUBMISSION
  //
  if ( isset($_POST['submission']) && $_POST['submission'] == "score" ) {
    if ( defined( 'MYARCADE_DEBUG' ) && MYARCADE_DEBUG ) {
      myarcade_log_score ('Mochi Score submission.');
    }

    // Get the score sort order
    if (strtolower($_POST['sortOrder']) == 'desc' ) {
      $order = 'DESC';
    }
    else {
      $order = 'ASC';
    }

    // Collect needed information
    $score = array(
      'session'   => $_POST['sessionID'],
      'date'      => date('Y-m-d'),
      'datatype'  => $_POST['datatype'],
      'game_tag'  => $_POST['gameID'],
      'user_id'    => $_POST['userID'],
      'score'     => $_POST['score'],
      'sortorder' => $order
    );

    myarcade_handle_score( $score );
  } // END Score submission

  //
  // MEDAL SUBMISSION
  //
  if( isset($_POST['submission']) && $_POST['submission'] == "medal" ) {

    // Collect needed information
    $medaldata = array(
      'date'      => date('Y-m-d'),
      'game_tag'  => $_POST['gameID'],
      'user_id'   => $_POST['userID'],
      'score'     => '',
      'name'      => $_POST['name'],
      'description'  => $_POST['description'],
      'thumbnail'   => $_POST['thumbnail']
    );

    myarcade_handle_mochi_medal($medaldata, $_POST['sessionID']);

  }
} // END signature check
else {
  if ( defined( 'MYARCADE_DEBUG' ) && MYARCADE_DEBUG ) {
    myarcade_log_score('Mochi Signature NOK!');
  }
}
?>