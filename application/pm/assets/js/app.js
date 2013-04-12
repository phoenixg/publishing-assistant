var Science = Science || {};

function show_alert(msg, mstyle) {
  msgStr = '<div class="alert ' + mstyle  +'">';
  msgStr +=  '<button class="close" data-dismiss="alert">×</button>';
  msgStr +=  msg + '</div>';
  $(msgStr).insertAfter('h1').fadeIn("slow");
}
