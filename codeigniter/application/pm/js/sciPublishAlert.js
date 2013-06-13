var Science = Science || {};

Science.AlertsBehaviors = (function() { $(function() {

  //Bootstrap Set-up
  $('.dropdown-toggle').dropdown();

  //UBF demo of password functionality

  var pass = "notprod"; //DEVELOPMENT

  var environment = $("meta[name='environment']").attr("content");
  if (typeof environment !== "undefined" && environment === "production") {
    pass = "password";
  }

  $('.pass-field').on("keyup input paste", function() {
    if ($(this).val() == pass) {
      unlock();
    }
  });

  function hidePassword() {
      $('.pass-field').hide('fast', function(){
        $(this).siblings('.add-on').addClass('btn-success').children('.icon-lock').addClass('icon-white');
        $(".pass-field").css('display','none');
      });
  }
  
  function lock() {

    //deactivate the controls
    $(".btn[value='create_email']").attr('disabled','disabled');


  }
  function unlock() {

    //hide password fields and activate the 'unlocked' icon
    hidePassword();

    //activate the controls
    $(".btn").removeAttr('disabled');

    //as a precaution, disable the SEND controls
    $(".btn-info").attr('disabled','disabled');

    //set a cookie
    $.cookie('auth', 'valid');      

  }

  if ($.cookie('auth') == 'valid') {
    hidePassword();
  }
  $("input:checkbox").on('click', function() {
    var cookieValid = ($.cookie('auth') === 'valid');
  //unlock if the cookie is set
    if (cookieValid && $("input:checkbox").is(":checked")) {
      unlock();
    }
    else {
      lock();
    }
  });


});
return { unlocked: false };
}());

$(document).ready( function() {
  // Intercept preview clicks
  $('a.btn:contains("Preview")').on('click',function(evt) {
    evt.preventDefault();
    $this = $(this);
    $this.after(' <em class="spinner"></em>');
    $.ajax({
      url: $this.attr('href'),
      dataType: 'json',
      type: 'post',
      success: function(data) {
        if (typeof data.redirect != "undefined") {
          window.open(data.redirect,"_blank");
        }
        $this.siblings("em").remove();
      }
    });
  });
  
  // Sends the form to CI via ajax
  $('button[value="create_email"]', '.controls').on('click', function(evt) {
    var $button = $(this);
    evt.preventDefault();
    if ($button.html().match.toString('/Put/')) {
      $button.attr('disabled','disabled').html('Pending');
    }
    $spinner = $(this).closest('div');
    $spinner.after(' <em class="spinner"></em>');

    // disable the submit button

    var $form = $(this).closest('form');
    //REMOVE
    $.ajax({
      url: 'alertMaster/compileSummary',
      type: 'POST',
      data: $form.serialize(),
      success: function(data) {
        var result = JSON.parse(data);
        show_alert('Email alerts created: '+result.files.length+'<br/> Pending emails can be reviewed at <a href="http://www.eloqua.com" target="_blank">Eloqua</a>.', 'alert-success');
        $button.html('Put').removeAttr('disabled'); // restore submit ability
        $spinner.siblings("em").remove();
        //
        //for (var i = 0, len = result.files.length; i < len; i++) {
        //  $span = $('#' + result.files[i] ).find('span');
        //  $span.css('background','#99ff99');
        //  $span.html("Just Updated!");
        // }
        //
      }

    });
    return false;
  });
});

