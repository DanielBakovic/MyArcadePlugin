<script type="text/javascript">
function showLoader(id) { jQuery(id).show(); }
function hideLoader(id) { jQuery(id).hide(); }

// wait for the DOM to be loaded
jQuery(document).ready(function() {
    // SWF handler
    jQuery('#uploadFormSWF').submit(function(e) {
      e.preventDefault();

      var options = {
        type: 'POST',
        url: ajaxurl,
        data: { action: "myarcade_import_handler" },
        dataType: 'json',
        beforeSubmit: function() { showLoader('#loadimgswf'); },
        success: showResponseSWF
      };
      jQuery(this).ajaxSubmit(options);
      return false;
    });
    // Thumbnail handler
    jQuery('#uploadFormTHUMB').submit(function(e) {
      e.preventDefault();

      var options = {
      type: 'POST',
      url: ajaxurl,
      data: { action: "myarcade_import_handler" },
      dataType: 'json',
      beforeSubmit: function() { showLoader('#loadimgthumb'); },
      success: showResponseTHUMB
      };
      jQuery(this).ajaxSubmit(options);
      return false;
    });
    // Screenshot handler
    jQuery('#uploadFormSCREEN').submit(function(e) {
      e.preventDefault();

      var options = {
      type: 'POST',
      url: ajaxurl,
      data: { action: "myarcade_import_handler" },
      dataType: 'json',
      beforeSubmit: function() { showLoader('#loadimgscreen'); },
      success: showResponseSCREEN
      };
      jQuery(this).ajaxSubmit(options);
      return false;
    });
    // Embed handler
    jQuery('#uploadFormEMIF').submit(function(e) {
      e.preventDefault();
      var options = {
      type: 'POST',
      url: ajaxurl,
      data: { action: "myarcade_import_handler" },
      dataType: 'json',
      beforeSubmit: function() { showLoader('#loadimgemif'); },
      success: showResponseEMIF
      };
      jQuery(this).ajaxSubmit(options);
      return false;
    });

    // File Size check
    jQuery("#gamefile").change(function ()  {
      myarcade_check_file_size("#gamefile", "#lblgamefile");
    });

    // Folder selection
    jQuery(".fileselection").click( function() {
      var selected = jQuery(this).closest('div').attr('id');
      jQuery("#folder" + selected).hide();
      jQuery("#" + selected + " .loadimg").show();
      showfileSelection( selected );
    });

    jQuery(".cancelselection").click( function() {
      var selected = jQuery(this).closest('div').attr('id');
      jQuery("#fileselect" + selected).remove();
      jQuery("#" + selected + " .cancelselection").hide();
      jQuery("#" + selected + " .fileselection").show();
    });

});

function showfileSelection( selected ) {
  jQuery.ajax({
    type: 'POST',
    url: ajaxurl,
    data: {
      action: "myarcade_get_filelist",
      type: selected
    },
    dataType: "html",
    success: function( response ) {
      jQuery( "#" + selected + " .loadimg" ).hide();
      jQuery( "#" + selected ).prepend(response);
      jQuery( "#" + selected + " .cancelselection" ).show();
    },
    error: function( response ) {}
  });
}

function showResponseSWF(data, statusText, xhr, $form)  {
  hideLoader('#loadimgswf');

  // Check the status
  if (statusText == 'success' && data.error == '') {
    jQuery('#filename').html('<strong>' + data.name + '</strong> - <i>' + data.info_dim + '</i>');
    jQuery('#gamewidth').val(data.width);
    jQuery('#gameheight').val(data.height);
    jQuery('#gamename').val(data.realname);
    jQuery('#importgame').val(data.location_url);
    jQuery('#importtype').val(data.type);
  }
  else {
    if ( statusText != 'success' ) {
      alert('Error: ' + statusText);
    }
    else {
      alert('Error: ' + data.error);
    }
  }
}

function showResponseTHUMB(data, statusText, xhr, $form)  {
  hideLoader('#loadimgthumb');

  // Check the status
  if (statusText == 'success' && data.error == '') {
    jQuery('#filenamethumb').html('<img src="' + data.thumb_url + '" alt=""  />');
    jQuery('#importthumb').val(data.thumb_url);
  }
  else {
    if ( statusText != 'success' ) {
      alert('Error: ' + statusText);
    }
    else {
      alert('Error: ' + data.error);
    }
  }
}

function showResponseSCREEN(data, statusText, xhr, $form)  {
  var output_string = '';

  hideLoader('#loadimgscreen');

  // Check the status
  if (statusText == 'success' && data.error == '') {
    for (var i=0;  i<=3; i++) {
      if (data.screen_name[i] != '') {
        var x = i + 1;
        output_string += '<strong>Screen ' + x + ': ' + data.screen_name[i] + '</strong><br />';
        jQuery('#importscreen' + x).val(data.screen_url[i]);
      }
      else {
        output_string += data.screen_error[i] + '<br />';
      }
    }
    jQuery('#filenamescreen').html(output_string);
  }
  else {
    if ( statusText != 'success' ) {
      alert('Error: ' + statusText);
    }
    else {
      alert('Error: ' + data.error);
    }
  }
}

function showResponseEMIF(data, statusText, xhr, $form)  {
  hideLoader('#loadimgemif');

  // Check the status
  if (statusText == 'success' && data.error == '') {
    jQuery('#importtype').val(data.type);
    jQuery('#importgame').val(data.importgame);
    jQuery('#filenameemif').html('<strong>' + data.result + '</strong>');
  }
  else {
    if ( statusText != 'success' ) {
      alert('Error: ' + statusText);
    }
    else {
      alert('Error: ' + data.error);
    }
  }
}
/** Ende Upload Test **/


function myarcade_chkImportCustom() {
  if (document.FormCustomGame.importgame.value == "") {
    alert("<?php _e("No game file added!", 'myarcadeplugin'); ?>");
    return false;
  }
  if (document.FormCustomGame.importthumb.value == "") {
    alert("<?php _e("No thumbnail added!", 'myarcadeplugin'); ?>");
    return false;
  }
  if (document.FormCustomGame.gamename.value == "") {
    alert("<?php _e("Game name not set!", 'myarcadeplugin'); ?>");
    document.FormCustomGame.gamename.focus();
    return false;
  }
  if (document.FormCustomGame.gamedescr.value == "") {
    alert("<?php _e("There is no game description!", 'myarcadeplugin'); ?>");
    document.FormCustomGame.gamedescr.focus();
    return false;
  }

  var categs = false;
  for(var i = 0; i < document.FormCustomGame.elements.length - 1; i++) {
    if( (document.FormCustomGame.elements[i].type == "checkbox") && (document.FormCustomGame.elements[i].checked == true)) {
      categs = true;
      break;
    }
  }

  if (categs == false) {
    alert("<?php _e("Select at least one category!", 'myarcadeplugin');?>");
    return false;
  }

  return true;
} // END - myarcade_chkImportCustom

function myarcade_check_file_size( fileID, targetID ) {

  var iSizeBit  = jQuery(fileID)[0].files[0].size;
  var iSize     = ( iSizeBit / 1024 );
  var allowedSize = <?php echo myarcade_get_max_post_size_bytes(); ?>;
  var unit      = "kB";

  if (iSize / 1024 > 1) {
    if (((iSize / 1024) / 1024) > 1) {
      iSize = (Math.round(((iSize / 1024) / 1024) * 100) / 100);
      unit = "GB";
    }
    else {
      iSize = (Math.round((iSize / 1024) * 100) / 100)
      unit = "MB";
    }
  }
  else {
    iSize = (Math.round(iSize * 100) / 100)
  }

  jQuery(targetID).html("File Size: " + iSize  + unit);

  if ( iSizeBit >allowedSize ) {
    alert( '<?php _e("ERROR: Max allowed file size exceeded!", 'myarcadeplugin'); ?>' );
  }
}

/* Import method selection */
jQuery(document).ready(function() {
  jQuery('#importibparcade').hide();
  jQuery('#importphpbb').hide();
  jQuery('#importunity').hide();
  jQuery('#importembedif').hide();
  jQuery('#importmethod').change( function() {
    jQuery('#filename').html('');
    jQuery('#filenametar').html('');
    jQuery('#gamewidth').val('');
    jQuery('#gameheight').val('');
    jQuery('#gamename').val('');
    jQuery('#importgame').val('');
    jQuery('#importtype').val('');
    jQuery('#importscreen1').val('');
    jQuery('#importscreen2').val('');
    jQuery('#importscreen3').val('');
    jQuery('#importscreen4').val('');
    //jQuery('#lbenabled').val('');
    //jQuery('#highscoretype').val('');
    jQuery('#slug').val('');

    switch (this.value) {
      case 'importibparcade': {
        jQuery('#importswfdcr').hide();
        jQuery('#importembedif').hide();
        jQuery('#thumbform').hide();
        jQuery('#importphpbb').hide();
        jQuery('#importunity').hide();
        jQuery('#importibparcade').fadeIn('slow');
      }
      break;

      case 'importphpbb': {
        jQuery('#importswfdcr').hide();
        jQuery('#importembedif').hide();
        jQuery('#thumbform').hide();
        jQuery('#importphpbb').fadeIn('slow');
        jQuery('#importunity').hide();
        jQuery('#importibparcade').hide();
      }
      break;

      case 'importswfdcr': {
        jQuery('#importibparcade').hide();
        jQuery('#importembedif').hide();
        jQuery('#importphpbb').hide();
        jQuery('#importunity').hide();
        jQuery('#importswfdcr').fadeIn('slow');
        jQuery('#thumbform').fadeIn('slow');
      }
      break;

      case 'importembedif': {
        jQuery('#importibparcade').hide();
        jQuery('#importswfdcr').hide();
        jQuery('#importphpbb').hide();
        jQuery('#importunity').hide();
        jQuery('#importembedif').fadeIn('slow');
        jQuery('#thumbform').fadeIn('slow');
      }
      break;

      case 'importunity': {
        jQuery('#importibparcade').hide();
        jQuery('#importswfdcr').hide();
        jQuery('#importphpbb').hide();
        jQuery('#importunity').fadeIn('slow');
        jQuery('#importembedif').hide();
        jQuery('#thumbform').fadeIn('slow');
      }
      break;
    }
  });
  jQuery('#importmethod').change();
});
</script>