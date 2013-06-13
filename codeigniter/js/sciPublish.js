Date.firstDayOfWeek = 7;
Date.format = 'mm/dd/yy';

// START JQUERY CODE

$(function()
{
	
	//import button behavior
	$('#importButton').click(function() {
		var $b = $('input[type=checkbox]');
        if ($b.filter(':checked').length==0) {
			alert("You haven\'t chosen any files to import.");
			return false;
		};
	});

	//default all checkboxes to on - deals with caching problem
	$(':checkbox').each(function() {
		this.checked = true;
		updateImportCount();
	});

	//select all templates control
	$('#allTemplates').click(function() {
		$(':checkbox').attr('checked','checked');
		$('.fieldBlock:hidden').fadeIn("slow");
		updateImportCount();
		return false;
	});
	
	//select no templates control
	$('#noTemplates').click(function() {
		$(':checkbox').attr('checked','');
		$('.fieldBlock:visible').fadeOut("slow");
		updateImportCount();
		return false;

	});	

	$(':checkbox').click(function() {
		updateImportCount();
	});

		//default all checkboxes to on - deals with caching problem
	function updateImportCount(){
		$b = $('input[type=checkbox]');
		$('#importButton').attr('value','Import ' + $b.filter(':checked').length + ' Items');
	}
	
/*	
	
	//assign checkbox behaviors
	$(':checkbox').click(function() {
		var target = "#set_" + (this.id.substr(4, this.id.length));
		if ($(this).is(":checked")) {
		  $(target).fadeIn("slow");
		} else {
		  $(target).fadeOut("slow");
		}
	});
	
	//set up all the date pickers
	$('.date-pick').datePicker();


	//set up all numeric field behaviors
	$('.text-num').each(function() {
		numControl = " <a href='#' onclick='increment(\"" + this.id + "\",-1);return false;' class='small-button'>-</a> ";
		numControl += "<a href='#' onclick='increment(\"" + this.id + "\",1) ;return false;' class='small-button'>+</a>";
		$( this ).after(numControl);
	});
	
	//show|hide results link
	$('.result').each(function(){
	
		$(this).click(function() {
			var target = "#" + this.id + "_panel";
			if ($(target).is(":hidden")) {
			  $(target).slideDown("slow");
			} else {
			  $(target).slideUp("slow");
			}
			return false;
		});
	});

	//publish button behavior
	$('#publishButton').click(function() {
		
		var checked = 0;
		var hasEmpty = false;
		var msg = '';
		var canSubmit;
		var dbg ='';

		$(':checkbox').each(function() {
		
			dbg += this.id + " : \n";
		
			if ($(this).is(":checked")){
				//verify that all the input fields have values
				$('#set_'+ this.id.substr(4) + ' input').each(function(){
					if ($(this).attr("value") == "") {
						hasEmpty = true;
						msg += "- " + this.id + "\n";
					}
				});
				checked++;
			}
		});

		//alert(dbg);
		
		if (checked == 0 ){
			alert("You haven\'t chosen any articles.");
			canSubmit = false;
		} else if (hasEmpty) {
			var submit = confirm("The following fields have not been filled in. Is it ok to proceed?\n\n" + msg);
			canSubmit = submit;
		} else {
			canSubmit = true;
		}
		
		if (canSubmit) {

			$(':checkbox').each(function() {

				//calculate which form field we're looking at.
				var out = "#out_" + this.id.substr(4);

				if (!$(this).is(":checked")){

					//$(this).siblings("input").attr('value', 'null');
					$(out).attr('value', 'null');

				} else {
				
					//capture the current value of the output file form the field
					var tmpOutfile = $(out).attr('value');

					//define the regex pattern
					var pattern = /{!{[A-Z0-9a-z_:\/]*}!}/;
					
					//execute the regex against the filename
					var matches = tmpOutfile.match(/{!{[A-Z0-9a-z_:\/]*}!}/g);
					
					if (matches != null) {
						
						for (var i=0; i<matches.length; i++) {

							//remove the delimiters from the field name
							var field = matches[i].replace("{!{","").replace("}!}","");

							//lookup the value from the page;
							//alert(document.forms["data"][field].value);
							var fieldVal =  document.forms["data"][field].value;

							//replace in the filename
							tmpOutfile = tmpOutfile.replace(matches[i], fieldVal);

						}						

						//update the output filename with the 'replaced' version
						$(out).attr('value', tmpOutfile);
						
					}
				
				}
			});
		}
		
		return canSubmit;
		
	});
	
	//publishbutton
	$('#publish-button').click(function() {
	
		$('#publish-form').fadeOut("slow");
		return true;
	
	});
	
	//modal window operation
	$('#upload').jqm();
	
	//uploadbutton
	$('#upload-button').click(function() {
	
		//$('#publish-form').fadeIn("slow");
		return true;
	
	});
	
	//show | hide extended common properties
	$('#options-control').click(function() {
	
			if ($('#extended-options').is(":hidden")) {
			  $('#extended-options').slideDown("fast");
			  $(this).html("less");
			} else {
			  $('#extended-options').slideUp("fast");
			  $(this).html("more");
			}
			
			$(this).toggleClass("contract");
			
			return false;
	
	});
	

	//show | hide variables
	$('#dictionary-control').click(function() {
	
			if ($('#dictionary-list').is(":hidden")) {
			  $('#dictionary-list').slideDown("fast");
			} else {
			  $('#dictionary-list').slideUp("fast");
			}
			
			$(this).toggleClass("contract");
			
			return false;
	
	});
	
	//show | hide builder
	$('#builderHead').click(function() {
	
			if ($('#builderPanel').is(":hidden")) {
			  $('#builderPanel').slideDown("fast");
			} else {
			  $('#builderPanel').slideUp("fast");
			}
			
			$(this).toggleClass("builderOpen");
			
			return false;
	
	});
	
	
	$('#dictionary-list label').click(function(){
	
		$('#dictionary-list').slideUp("fast");
		$('#dictionary-control').toggleClass("contract");	
	
	});
	
	$('.swatch').blur( function() {
	
		var tmp = '#sw_' + $(this).attr('name');
		$(tmp).css('background-color', "#" + this.value)
		
	
	});
	
	$('.swatch').each( function() {
	
		if (this.value != "") {
			var tmp = '#sw_' + $(this).attr('name');
			$(tmp).css('background-color', "#" + this.value)
		}		
	
	});
*/

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
