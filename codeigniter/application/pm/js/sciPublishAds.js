$(document).ready( function() { 
  // Displays a preview div when a checkbox is active
  $('label','fieldset.well').on('click', function(evt, all_none) {
    if (all_none) { evt.preventDefault(); }
    var $checkbox = $(this).find('input');
    var checkbox_name = $checkbox.attr('name');
    if ($checkbox.is(':checked')) {
      $('#' + checkbox_name + '_fieldset').show();
    }
    else {
      $('#' + checkbox_name + '_fieldset').hide();
    }
  });
  // setup all the 'all' and 'none' links for the checkboxes
  $('.checkbox_all','#admasterform').on('click', function(evt) {
    $(this).parent().find('input').prop('checked','checked');
    $(this).parent().find('label').trigger('click',[evt,true]);
  });
  $('.checkbox_none','#admasterform').on('click', function(evt) {
    $(this).parent().find('input').prop('checked',false);
    $(this).parent().find('label').trigger('click',[evt,true]);
  });
  // Copies HTML into the preview iframe div
  $('textarea','.edit_preview').on('keyup',function() {
    var $textarea = $(this);
    var d = $textarea.siblings('iframe')[0].contentWindow.document;
    $("body", d).find('div').html($textarea.val()); 
  });
  // Sends the form to CI via ajax
  $('input[type="submit"]','#admasterform').click(function(evt) { 
    var $form = $(this).parent();
    $.ajax({
      url: $form.attr('action'),
      type: 'POST',
      data: $form.serialize(),
      success: function(data) {
        var result = JSON.parse(data);
        show_alert('Saved ' + result.files.length + ' ads','alert-success'); 
        jGrowlNotify(result.files.length + ' Files Updated');
        for (var i = 0, len = result.files.length; i < len; i++) {
          $span = $('#' + result.files[i] + '_fieldset').find('span').first();
          $span.removeClass('label-warning').removeClass('label-important').removeClass('label-info').addClass('label-success');
          $span.html("Just Updated!");
        }
      }

    });
    return false;
  });
  function jGrowlNotify(message) {
    $('body').append('<div id="jGrowl" class="bottom-right jGrowl"><div class="jGrowl-notification"></div></div><script type="text/javascript">$.jGrowl("'+message+'");</script>');
  }

});
