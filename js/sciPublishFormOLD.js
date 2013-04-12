Date.firstDayOfWeek = 7;
Date.format = 'mm/dd/yy';

$(function()
{
	$('.date-pick').datePicker();

	$(':checkbox').click(function() {
		var target = "#set_" + (this.id.charAt(this.id.length -1));
		if ($(target).is(":hidden")) {
		  $(target).fadeIn("slow");
		} else {
		  $(target).hide();
		}
	});
	
	$(".editable").each(function() {
		$(this).ref = $(this).text();
		$(this).editable(updateOutput, { 
		    tooltip   : "Click to edit...",
		    submit  :  "OK",
		    style  : "inherit"
		 });
	});
});

function updateOutput(value, settings) {
	console.log(this);
	console.log(value);
	console.log(settings);
	
	//get the form reference from the classname 
	//it's always the item after the space - so we can split the string to an array
	var tmpField = "#" + (this.className.split(" ")[1]);
	
	//set the form field to the new value
	$(tmpField).attr("value", value);

	return(value);
	
}
