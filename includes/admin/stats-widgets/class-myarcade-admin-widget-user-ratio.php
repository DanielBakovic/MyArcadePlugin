<?php
/**
 * Display the user, guest ratio for last 30 days
 */

class MyArcade_Admin_Widget_User_Ratio {

  public static function output() {
    global $wpdb;

    $all = (float) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}myarcade_plays WHERE DATE_FORMAT( `date`, '%Y-%m-%d' ) >= '".MyArcade_Stats::get_date( '-30')."'");
    $visitors = (float) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}myarcade_plays WHERE DATE_FORMAT( `date`, '%Y-%m-%d' ) >= '".MyArcade_Stats::get_date( '-30')."' AND `user_id` IS NULL");

    $percent_visitor = ( $all ) ? round( ( $visitors * 100 ) / $all ) : 0;
    $percent_users   = 100 - $percent_visitor;

    $data = "['" . __('Registered Users') . "'," . $percent_users . "],";
    $data .= "['" . __('Unregistered Visitors') . "'," . $percent_visitor . "],";
   // Generate the graph
   ?>
   <div id="ratio-graph"></div>

   <script type="text/javascript">
      var ratio_chart;

      jQuery(document).ready(function($) {
        var ratio_data = [<?php echo $data; ?>];

        ratio_chart = $.jqplot( 'ratio-graph', [ratio_data], {
          grid: {
            drawBorder: false,
            shadow: false,
            background: '#ffffff',
          },
          seriesColors: ["rgb(247, 163, 92)", "rgb(124, 181, 236)"],
          seriesDefaults: {
            renderer: $.jqplot.DonutRenderer,
            rendererOptions: {
              sliceMargin: 3,
              showDataLabels: true,
              shadow: false,

            },
          },
          legend: { show:true, location: 's' }
        });

        $(window).resize(function () {
          ratio_chart.replot({resetAxes: true});
        });
     });
   </script>
   <?php
  }
}

MyArcade_Admin_Widget_User_Ratio::output();