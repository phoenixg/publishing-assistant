$(document).ready( function() { 
  // Sends the form to CI via ajax
  $('input[type="submit"]','#rss-form').click(function(evt) { 
    var $form = $(this).parent();
    $.ajax({
      url: $form.attr('action'),
      type: 'POST',
      data: $form.serialize(),
      success: function(data) {
        console.log(data);
        var pass = 0, fail = 0, message="";
        for (item in data) {
          if (data[item] === true) { pass++} else {
            fail++;
            $span = $('[name="'+item+'"]').parent().find('span:nth-child(2)');
            $span.removeClass('label-success').removeClass('label-important').removeClass('label-info').addClass('label-warning');
            $span.append("<span>Could not generate feed!</span>");
          }
        }
        if (fail > 0) { 
          jGrowlNotify(pass +  ' feeds generated.' + ' ' + fail + ' feeds not generated.');
          show_alert('Generated ' + pass + ' feeds. ' + fail + ' feeds could not be generated','alert-warn'); 
        } else {
          jGrowlNotify(pass +  ' feeds generated.' + message);
          show_alert('Generated ' + pass + ' feeds','alert-success'); 
        }
        /*
        jGrowlNotify(data.success.length + ' feeds generated');
        for (var i = 0, len = result.files.length; i < len; i++) {
          $span = $('#' + result.files[i] + '_fieldset').find('span').first();
          $span.removeClass('label-warning').removeClass('label-important').removeClass('label-info').addClass('label-success');
          $span.html("Just Updated!");
        }
        */
      }

    });
    return false;
  });
  function jGrowlNotify(message) {
    $('body').append('<div id="jGrowl" class="bottom-right jGrowl"><div class="jGrowl-notification"></div></div><script type="text/javascript">$.jGrowl("'+message+'");</script>');
  }

});
