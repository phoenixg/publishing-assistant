Date.firstDayOfWeek = 7;
Date.format = 'mm/dd/yy';

// START JQUERY CODE

$(function() {
	
	function updateImportCount(){
		$b = $('input[type=checkbox]');
		$('#importButton').attr('value','Import ' + $b.filter(':checked').length + ' Items');
	}

  //import button behavior
  $('#importButton').click(function() {
    var $b = $('input[type=checkbox]');
    if ($b.filter(':checked').length===0) {
      alert("You haven\'t chosen any files to import.");
      return false;
    }
  });

  $('.section').click(function() {
    $(this).next().slideToggle().removeClass("hidden");
  });	

  // show all sections
  $('#allTemplates').on('click', function selectAll(evt) { 
    evt.preventDefault();
    var $div = $('.section').next();
    $div.find('input').attr('checked',true).promise().done(function () {
      $div.slideDown().removeClass("hidden"); 
    });
    updateImportCount();
  });
  // hide all sections
  $('#noTemplates').on('click', function unselectAll(evt) { 
    evt.preventDefault();
    var $div = $('.section').next();
    $div.slideUp().promise().done(function() {
      $div.find('input').attr('checked',false);
    });
    updateImportCount();
  });
  //default all checkboxes to on - deals with caching problem
  $(':checkbox').each(function() {
    this.checked = true;
    updateImportCount();
  });

  $(':checkbox').click(function() {
    updateImportCount();
  });



});

// END OF JQUERY CODE

// REGULAR FUNCTIONS

function increment(field,val) {
  // adjust the value of a numeric text field

  var fieldVal  = document.forms[0][field].value;
  if (isNaN(fieldVal)) {
    return;
  } else {
    fieldVal = (fieldVal == "")? 0 : fieldVal
      document.forms[0][field].value = parseInt(fieldVal) + val;	
  }

}

function setPath(path, file, process) {

  //reset the form to clear any previous entries
  document.forms['upload-form'].reset();

  path = (path=="")? "You must specify a destination for this file" : path;
  document.forms['upload-form']['file_target'].value = path;

  //if we have a new process path - set the new target for the form
  if (process != "") {
    document.forms['upload-form'].action = process;
    document.getElementById("processPath").innerHTML = process;
  }

  document.forms['publish-form']['label'].value = path;
  document.forms['publish-form']['file_list'].value = file.substr(file.lastIndexOf("/")+1, file.length);
  //replace "/" with "\" for windows file path
  document.getElementById('file-ref').innerHTML = file.replace(/\//g, "\\");
}

function setDefaultURL(target) {

  //read volume and issue references from the page
  ref = document.getElementById("VOLUME_NUM").value + "/" +  document.getElementById("ISSUE_NUM").value

    //populate the input field with the default value
    var obj = document.getElementById(target);
  if (obj.value.substring(0,7)!="http://"){
    obj.value = "http://www.sciencemag.org/cgi/content/short/" + ref + "/" + obj.value;
  }

  return false;

}


function parseVals(text) {

  //replace tabs with delimiters - in case this has come from EXCEL
  text = text.replace(/\t/g, "|");

  var lines = text.split("\n");
  for (var i in lines) {
    var parts = lines[i].split("|");
    if (document.forms["data"][parts[0]]) {
      document.forms["data"][parts[0]].value = parts[1];
      document.getElementById('bcount').innerHTML = parseInt(i)+1;	
    }
  }

}
