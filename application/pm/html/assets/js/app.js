    $(function() {
    
      //Bootstrap Set-up
      $('.dropdown-toggle').dropdown();
      
      //UBF demo of password functionality
      var pass = "password";
      $('.pass-field').on("keyup input paste", function() {
        if ($(this).val() == pass) {
          $(this).hide('fast').siblings('.add-on').addClass('btn-success').children('.icon-lock').addClass('icon-white');
        };
      });
      
    });