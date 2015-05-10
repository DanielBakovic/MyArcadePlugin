<?php
/**
 * Displays the fetch games page on backend
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 * @copyright (c) 2015, Daniel Bakovic
 * @license http://myarcadeplugin.com
 * @package MyArcadePlugin/Core/Admin
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
 * Generates the Fetch Games page
 *
 * @version 5.0.0
 * @access  public
 * @return  void
 */
function myarcade_fetch() {
  global $myarcade_distributors;

  myarcade_header();

  ?>
  <div class="icon32" id="icon-plugins"><br/></div>
  <h2><?php _e("Fetch Games", 'myarcadeplugin'); ?></h2>

  <?php
  // Set default distributor
  $distributor = 'gamepix';

  // Load settings dynamically
  foreach ($myarcade_distributors as $key => $name) {
    $$key = get_option( 'myarcade_' . $key );
  }

  $spilgames['search'] = '';
  $spilgames['method'] = 'latest';
  $spilgames['offset'] = 1;

  $famobi['method'] = 'latest';
  $famobi['offset'] = 0;
  $famobi['limit'] = 100;

  $bigfish['echo'] = true;

  $fetch_games = filter_input( INPUT_POST, 'fetch');

  if ( 'start' == $fetch_games ) {
    $distributor = filter_input( INPUT_POST, 'distr' );
    // Famobi
    $famobi['method'] = filter_input( INPUT_POST, 'fetchmethodfamobi' );
    $famobi['offset'] = filter_input( INPUT_POST, 'offsetfamobi', FILTER_VALIDATE_INT );
    $famobi['limit'] = filter_input( INPUT_POST, 'famobi_limit', FILTER_VALIDATE_INT );
    //Spilgames
    $spilgames['search']  = filter_input( INPUT_POST, 'searchspilgames' );
    $spilgames['limit']   = filter_input( INPUT_POST, 'limitspilgames' );
    $spilgames['method']  = filter_input( INPUT_POST, 'fetchmethodspilgames', FILTER_DEFAULT,  array( 'options' => array( 'default' => 'latest' ) ) );
    $spilgames['offset']  = filter_input( INPUT_POST, 'offsetspilgames', FILTER_VALIDATE_IP, array( 'options' => array( 'default' => 1 ) ) );
    //MyArcadeFeed
    $myarcadefeed['feed'] = filter_input( INPUT_POST, 'myarcadefeedselect' );
  }
  ?>

  <script type="text/javascript">
    /* <![CDATA[ */
    function js_myarcade_offset() {
      if (jQuery("input:radio:checked[name='fetchmethod']").val() === 'latest') {
        jQuery("#offs").fadeOut("fast");
      } else if (jQuery("input:radio:checked[name='fetchmethod']").val() === 'offset') {
        jQuery("#offs").fadeIn("fast");
      }

      if (jQuery("input:radio:checked[name='fetchmethodfgd']").val() === 'latest') {
        jQuery("#offsfgd").fadeOut("fast");
      } else if (jQuery("input:radio:checked[name='fetchmethodfgd']").val() === 'offset') {
        jQuery("#offsfgd").fadeIn("fast");
      }

      if (jQuery("input:radio:checked[name='fetchmethodfog']").val() === 'latest') {
        jQuery("#offsfog").fadeOut("fast");
      } else if (jQuery("input:radio:checked[name='fetchmethodfog']").val() === 'offset') {
        jQuery("#offsfog").fadeIn("fast");
      }

      if (jQuery("input:radio:checked[name='fetchmethodspilgames']").val() === 'latest') {
        jQuery("#offsspilgames").fadeOut("fast");
      } else if (jQuery("input:radio:checked[name='fetchmethodspilgames']").val() === 'offset') {
        jQuery("#offsspilgames").fadeIn("fast");
      }

      if (jQuery("input:radio:checked[name='fetchmethodfamobi']").val() === 'latest') {
        jQuery("#offsfamobi").fadeOut("fast");
      } else if (jQuery("input:radio:checked[name='fetchmethodfamobi']").val() === 'offset') {
        jQuery("#offsfamobi").fadeIn("fast");
      }
    }

    jQuery(document).ready(function() {

      <?php
      if ( 'start' == $fetch_games ) : ?>
      jQuery(document).ready(function() {
        js_myarcade_offset();
      });
      <?php endif; ?>


      jQuery(this).find("input:radio[name='fetchmethod']").click(function() {
       js_myarcade_offset();
      });

      jQuery(this).find("input:radio[name='fetchmethodfgd']").click(function() {
        js_myarcade_offset();
      });

      jQuery(this).find("input:radio[name='fetchmethodfog']").click(function() {
        js_myarcade_offset();
      });

     jQuery(this).find("input:radio[name='fetchmethodspilgames']").click(function() {
        js_myarcade_offset();
      });

      jQuery(this).find("input:radio[name='fetchmethodfamobi']").click(function() {
        js_myarcade_offset();
      });
    });

    function js_myarcade_selection() {
      var selected = jQuery("#distr").find(":selected").val();
      jQuery("#"+selected).slideDown("fast");
      jQuery("#distr option").each(function() {
        var val = jQuery(this).val();
        if ( val !== selected ) {
          jQuery("#"+val).slideUp("fast");
        }
      });
    }

    jQuery(document).ready(function(){
      jQuery("#distr").change(function() {
        js_myarcade_selection();
      });

      // Call the function the first time when the site is loaded
      js_myarcade_selection();
    });
    /* ]]> */
  </script>

  <style type="text/css">
  .hide { display:none; }
  </style>

  <br />
  <form method="post" class="myarcade_form">
    <fieldset>
      <div class="myarcade_border grey mabp_680">
        <label for="distr"><?php _e("Select a game distributor", 'myarcadeplugin'); ?>: </label>
        <select name="distr" id="distr">
          <?php foreach ($myarcade_distributors as $slug => $name) : ?>
          <?php
          if ( $slug == 'gamefeed' || $slug == 'mochi' ) {
            continue;
          }
          ?>
          <option value="<?php echo $slug; ?>" <?php myarcade_selected($distributor, $slug); ?>><?php echo $name; ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <?php
      //________________________________________________________________________
      // 2PG
      ?>
      <div class="myarcade_border white hide mabp_680" id="twopg">
        <p class="mabp_info">
        <?php _e("There are no specific settings available. Just Fetch the games :)", 'myarcadeplugin');?>
        </p>
      </div><!-- end 2pg -->

      <?php
      //________________________________________________________________________
      // AGF
      ?>
      <div class="myarcade_border white hide mabp_680" id="agf">
        <?php myarcade_premium_message( "mabp_95" ); ?>
      </div><!-- end agf -->

      <?php
      //________________________________________________________________________
      // Kongregate
      ?>
      <div class="myarcade_border white hide mabp_680" id="kongregate">
        <?php myarcade_premium_message( "mabp_95" ); ?>
      </div><!-- end kongregate -->

      <?php
      //________________________________________________________________________
      // Famobi
      ?>
      <div class="myarcade_border white hide mabp_680" id="famobi">
        <div style="float:left;margin-right:50px;">
          <input type="radio" name="fetchmethodfamobi" value="latest" <?php myarcade_checked($famobi['method'], 'latest');?>>
        <label><?php _e("Latest Games", 'myarcadeplugin'); ?></label>
        <br />
        <input type="radio" name="fetchmethodfamobi" value="offset" <?php myarcade_checked($famobi['method'], 'offset');?>>
        <label><?php _e("Use Offset", 'myarcadeplugin'); ?></label>
        </div>
        <div class="myarcade_border" style="float:left;padding-top: 5px;background-color: #F9F9F9">
        Fetch <input type="text" name="famobi_limit" size="6" value="<?php echo $famobi['limit']; ?>" /> games <span id="offsfamobi" class="hide">from offset <input id="radiooffsfamobi" type="text" name="offsetfamobi" size="4" value="<?php echo $famobi['offset']; ?>" /> </span>
        </div>
        <div class="clear"></div>
      </div><!-- end famobi -->

      <?php
      //________________________________________________________________________
      // FlashGamesDistribution
      ?>
      <div class="myarcade_border white hide mabp_680" id="fgd">
        <?php myarcade_premium_message( "mabp_95" ); ?>
      </div><!-- end fgd -->

      <?php
      //________________________________________________________________________
      // FreeGamesForYourWebsite (FOG)
      ?>
      <div class="myarcade_border white hide mabp_680" id="fog">
        <?php myarcade_premium_message( "mabp_95" ); ?>
      </div><!-- end fog -->

      <?php
      //________________________________________________________________________
      // GamePix
      ?>
      <div class="myarcade_border white hide mabp_680" id="gamepix">
        <p class="mabp_info">
        <?php _e("There are no specific settings available. Just fetch games :)", 'myarcadeplugin');?>
        </p>
      </div><!-- end gamepix -->

      <?php
      //________________________________________________________________________
      // Spilgames
      ?>
      <div class="myarcade_border white hide mabp_680" id="spilgames">
        <label><?php _e("Filter by search query", 'myarcadeplugin'); ?>: </label>
        <input type="text" size="40"  name="searchspilgames" value="<?php echo $spilgames['search']; ?>" />
        <p class="myarcade_hr">&nbsp;</p>
        <div style="float:left;margin-right:50px;">
          <input type="radio" name="fetchmethodspilgames" value="latest" <?php myarcade_checked($spilgames['method'], 'latest');?>>
        <label><?php _e("Latest Games", 'myarcadeplugin'); ?></label>
        <br />
        <input type="radio" name="fetchmethodspilgames" value="offset" <?php myarcade_checked($spilgames['method'], 'offset');?>>
        <label><?php _e("Use Offset", 'myarcadeplugin'); ?></label>
        </div>
        Fetch <input type="text" name="limitspilgames" size="6" value="<?php echo $spilgames['limit']; ?>" /> games <span id="offsspilgames" class="hide">from page <input id="radiooffsspilgames" type="text" name="offsetspilgames" size="4" value="<?php echo $spilgames['offset']; ?>" /> </span>
        <div class="clear"></div>
      </div><!-- end spilgames -->

      <?php
      //________________________________________________________________________
      // MyArcadeFeed
      ?>
      <div class="myarcade_border white hide mabp_680" id="myarcadefeed">
        <?php
        $myarcadefeed_array = array();
        for ($i=1;$i<5;$i++) {
          if ( !empty($myarcadefeed['feed'.$i])) {
            $myarcadefeed_array[$i] = $myarcadefeed['feed'.$i];
          }
        }
        if ( $myarcadefeed_array ) {
          _e("Select a Feed:", 'myarcadeplugin');
          ?>
          <select name="myarcadefeedselect" id="myarcadefeedselect">
            <?php
            foreach ($myarcadefeed_array as $key => $val) {
              echo '<option value="feed'.$key.'"> '.$val.' </option>';
            }
            ?>
          </select>
          <?php
        } else {
            ?>
            <p class="mabp_error">
              <?php _e("No MyArcadeFeed URLs found!", 'myarcadeplugin');?>
            </p>
            <?php
        }
        ?>
      </div><!-- end myarcadefeed -->

      <?php
      //________________________________________________________________________
      // Big Fish Games
      ?>
      <div class="myarcade_border white hide mabp_680" id="bigfish">
        <?php myarcade_premium_message( "mabp_95" ); ?>
      </div><!-- end bigfish -->

      <?php
      //________________________________________________________________________
      // Scirra
      ?>
      <div class="myarcade_border white hide mabp_680" id="scirra">
        <?php myarcade_premium_message( "mabp_95" ); ?>
      </div><!-- end scirra -->

      <?php
      //________________________________________________________________________
      // UnityFeeds
      ?>
      <div class="myarcade_border white hide mabp_680" id="unityfeeds">
        <p class="mabp_info">
        <?php _e("There are no specific settings available. Just fetch games :)", 'myarcadeplugin');?>
        </p>
      </div><!-- end unityfeeds -->

    </fieldset>

    <input type="hidden" name="fetch" value="start" />
    <?php
    $myarcade_categories = get_option('myarcade_categories');
    if ( empty( $myarcade_categories ) ) : ?>
      <?php echo '<p class="mabp_error mabp_680">'.__("You will not be able to fetch games!", 'myarcadeplugin').' '.__('Go to "General Settings" and activate some game categories!', 'myarcadeplugin').'</p>'; ?>
    <?php else: ?>
    <input class="button-primary" id="submit" type="submit" name="submit" value="<?php _e("Fetch Games", 'myarcadeplugin'); ?>" />
    <?php endif; ?>
  </form>
  <br />
  <?php
  if ( 'start' == $fetch_games ) {
    // Start fetching here...
    if ( $distributor ) {

      $distributor_file = MYARCADE_CORE_DIR . '/feeds/' . $distributor . '.php';

      if ( file_exists( $distributor_file ) ) {

        // Load distributor files
        require_once( $distributor_file );

        // Generate fetch games function name
        $func = 'myarcade_feed_'.$distributor;


        if ( function_exists($func) ) {
          myarcade_prepare_environment();

          if ( !isset($$distributor) ) {
            $$distributor = array();
          }

          $args = array( 'echo' => true, 'settings' => $$distributor );

          $func($args);
        }
        else {
          ?>
          <p class="mabp_error mabp_680">
            <?php printf( __("ERROR: Required function can't be found: %s!", 'myarcadeplugin'), $func); ?>
          </p>
          <?php
        }
      }
      else {
        ?>
        <p class="mabp_error mabp_680">
          <?php printf( __("ERROR: Required distributor file can't be found: %s!", 'myarcadeplugin'), $distributor); ?>
        </p>
        <?php
      }
    }
    else {
      ?>
      <p class="mabp_error mabp_680">
        <?php _e("ERROR: Unkwnon game distributor!", 'myarcadeplugin'); ?>
      </p>
      <?php
    }
  }

  myarcade_footer();
}