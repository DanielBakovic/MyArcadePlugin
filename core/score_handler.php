<?php
/**
 * Game output functions
 *
 * @package MyArcadePlugin/Scores
 */

// No direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Handle submitted scores.
 *
 * @param array $score Score array.
 */
function myarcade_handle_score( $score ) {
	global $wpdb;

	// Get needed settings.
	$general = get_option( 'myarcade_general' );

	// Save scores only if enabled!
	if ( ! $general['scores'] ) {
		return;
	}

	if ( is_array( $score ) ) {

		myarcade_log_score( 'Score: ' . print_r( $score, true ) );

		// Get user info.
		$current_user = wp_get_current_user();

		// Check if the logged in user submittes scores.
		if ( $current_user->ID && ( $current_user->ID === $score['user_id'] ) ) {

			myarcade_log_score( 'Score submitted by logged in user with userID: ' . $current_user->ID );

			do_action( 'myarcade_new_score', $score );

			// Should we save only highest scores of a user.
			if ( true === $general['highscores'] ) {
				// Save only the highest score.
				myarcade_log_score( 'Save only the highest score' );

				// Get the best score of the user.
				$last_score = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}myarcadescores WHERE game_tag = %s AND user_id = %d ORDER BY score+0 {$score['sortorder']} LIMIT 1", $score['game_tag'], $score['user_id'] ) );

				if ( ! empty( $last_score ) ) {

					switch ( $score['sortorder'] ) {
						case 'DESC':
							if ( intval( $score['score'] ) > intval( $last_score->score ) ) {
								myarcade_update_score( $score, $last_score->id );
							}
							break;

						case 'ASC':
							if ( intval( $score['score'] ) < intval( $last_score->score ) ) {
								myarcade_update_score( $score, $last_score->id );
							}
							break;
					}
				} else {
					// No high score available.
					myarcade_log_score( 'Insert initial score' );

					// Insert initial score.
					myarcade_insert_score( $score );
				}
			} else {
				// Save all user scores.
				myarcade_log_score( 'Save all user scores' );

				// Insert new score into the database.
				myarcade_insert_score( $score );
			}
		} else {
			myarcade_log_score( 'Score submitted by anonymous.' );
		}
	} else {
		// Wrong parameter submitted.
		myarcade_log_score( 'Submitted score data are not valid!' );
	}
}

/**
 * Inserts new scores into the database.
 *
 * @param  array $score Score array.
 * @return void
 */
function myarcade_insert_score( $score ) {
	global $wpdb;

	if ( is_array( $score ) ) {

		// Do some custom actions.
		do_action( 'myarcade_insert_score', $score );

		$wpdb->insert( $wpdb->prefix . 'myarcadescores', $score );

		myarcade_log_score( 'New Score Added: ' . print_r( $score, true ) );

		// Check if this is a new game highscore.
		myarcade_handle_highscore( $score );
	} else {
		myarcade_log_score( 'Insert Score: Wrong parameter submitted' . "\n" . 'Score: ' . print_r( $score, true ) );
	}
}

/**
 * Updates scores of a user.
 *
 * @param  array $score    Score array.
 * @param  int   $score_id Scorde ID which shcoul be updated.
 */
function myarcade_update_score( $score, $score_id ) {
	global $wpdb;

	if ( isset( $score_id ) && is_array( $score ) ) {

		myarcade_log_score( 'Update highscore' );

		// Do some custom actions.
		do_action( 'myarcade_update_score', $score, $score_id );

		$values = array(
			'session' => $score['session'],
			'date'    => $score['date'],
			'score'   => $score['score'],
		);

		$where = array( 'id' => $score_id );

		$wpdb->update( $wpdb->prefix . 'myarcadescores', $values, $where );

		myarcade_log_score( 'Score updated: ' . print_r( $score, true ) );

		// Check if this is a new game highscore.
		myarcade_handle_highscore( $score );
	} else {
		myarcade_log_score( 'Update Score: Wrong parameter submitted' . "\n" . 'Score: ' . print_r( $score, true ) . "\n" . 'Score ID: ' . $score_id );
	}
}

/**
 * Inserts new scores into the database.
 *
 * @param array $score Score array.
 */
function myarcade_insert_highscore( $score ) {
	global $wpdb;

	if ( is_array( $score ) ) {

		$highscore = array(
			'game_tag' => $score['game_tag'],
			'user_id'  => $score['user_id'],
			'score'    => $score['score'],
		);

		$wpdb->insert( $wpdb->prefix . 'myarcadehighscores', $highscore );

		myarcade_log_score( 'New Highscore Added: ' . print_r( $score, true ) );
	} else {
		myarcade_log_score( 'Insert Highscore: Wrong parameter submitted' . "\n" . 'Score: ' . print_r( $score, true ) );
	}
}

/**
 * Updates highscore of a game.
 *
 * @param array $score    Score array.
 * @param int   $score_id ID of the score that should be updated.
 */
function myarcade_update_highscore( $score, $score_id ) {
	global $wpdb;

	if ( isset( $score_id ) && is_array( $score ) ) {

		$values = array (
			'user_id' => $score['user_id'],
			'score'   => $score['score'],
		);

		$where = array( 'id' => $score_id );

		$wpdb->update( $wpdb->prefix . 'myarcadehighscores', $values, $where );

		myarcade_log_score( 'Highscore updated: ' . print_r( $score, true ) );
	} else {
		myarcade_log_score( 'Update Score: Wrong parameter submitted' . "\n" . 'Score: ' . print_r( $score, true ) . "\n" . 'Score ID: ' . $score_id );
	}
}

/**
 *  Updates the user highscore count and inserts the new score into the highscore table.
 *
 * @param array $score Score array.
 */
function myarcade_handle_highscore( $score ) {
	global $wpdb;

	$highscore     = false;
	$new_highscore = false;

	if ( ! empty( $score ) ) {

		// Get current highscore of this game.
		$highscore = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}myarcadehighscores WHERE game_tag = %s LIMIT 1", $score['game_tag'] ) );

		myarcade_log_score( 'Current Highscore: ' . print_r( $highscore, true ) );

		if ( empty( $highscore ) ) {
			// Insert new highscore.
			myarcade_log_score( 'Insert New Highscore' );
			myarcade_insert_highscore( $score );

			// Populate the highscore object.
			$highscore     = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}myarcadehighscores WHERE game_tag = %s LIMIT 1", $score['game_tag'] ) );
			$new_highscore = true;
		} else {
			// Update highscore.
			myarcade_log_score( 'Should we update new highscore?' );

			switch ( $score['sortorder'] ) {
				case 'DESC':
					if ( $score['score'] > $highscore->score ) {
						myarcade_update_highscore( $score, $highscore->id );
						$new_highscore = true;
					}
					break;

				case 'ASC':
					if ( $score['score'] < $highscore->score ) {
						myarcade_update_highscore( $score, $highscore->id );
						$new_highscore = true;
					}
					break;
			}
		}

		// Do some custom actions.
		if ( $new_highscore ) {
			myarcade_log_import( 'Action myarcade_new_highscore - winner: ' . $score['user_id'] . ' looser: ' . $highscore->user_id );

			do_action( 'myarcade_new_highscore', $score, $highscore );
		} else {
			myarcade_log_import( 'Not doing action myarcade_new_highscore.' );
		}
	}
}

/**
 * Handles Achievement Submissions.
 *
 * @param array $achievement Achievements array.
 */
function myarcade_handle_achievement( $achievement ) {
	global $wpdb;

	if ( ! is_array( $achievement ) ) {
		return;
	}

	// Check if the user has already achieved this medal.
	$medal_id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}myarcademedals WHERE game_tag = %s AND user_id = %d AND name = %s LIMIT 1", $achievement['game_tag'], $achievement['user_id'], $achievement['name'] ) );

	if ( empty( $medal_id ) ) {
		myarcade_log_score( 'New Medal: ' . print_r( $achievement, true ) );

		// Do some custom actions.
		do_action( 'myarcade_new_medal', $achievement );

		$wpdb->insert( $wpdb->prefix . 'myarcademedals', $achievement );
	}
}

/**
 * Handles score submitting by IBPArcade games.
 */
function myarcade_ibp_handle() {

	// REDIRECT FOR IBPARCADE GAMES.
	if ( isset( $_SERVER["REQUEST_URI"] ) && preg_match( '@(.*)arcade/(gamedata/.*)@', $_SERVER["REQUEST_URI"], $match ) ) {

		$upload_dir = MyArcade()->upload_dir();

		if ( file_exists( $upload_dir['gamesdir'] . $match[2] ) ) {
			$gamedata_path = $upload_dir['gamesurl'] . $match[2];
		} else {
			$redirect      = '/wp-content/games/';
			$gamedata_path = site_url() . $redirect . $match[2];
		}

		// Redirect only if this isn't alreaddy correct access.
		if ( strpos( esc_url( $_SERVER["REQUEST_URI"] ), $gamedata_path ) === false ) {
			// Redirect.
			header( 'Location: ' . $gamedata_path );
  die();
}
	}

	// Include the IBPArcade score handler.
	require_once MyArcade()->plugin_path() . '/modules/ibparcade_scores.php';
}
