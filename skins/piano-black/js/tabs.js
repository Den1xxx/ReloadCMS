$(document).ready(function() {

  //Get all the LI from the #tabMenu UL
  $('#tabMenu > li').click(function(){

    //perform the actions when it's not selected
    if (!$(this).hasClass('selected')) {

    //remove the selected class from all LI    
    $('#tabMenu > li').removeClass('selected');

    //Reassign the LI
    $(this).addClass('selected');

    //Hide all the DIV in .boxBody
    $('.boxBody div').slideUp('500');

    //Look for the right DIV in boxBody according to the Navigation UL index, therefore, the arrangement is very important.
    $('.boxBody div:eq(' + $('#tabMenu > li').index(this) + ')').slideDown('500');

  }

  }).mouseover(function() {

    //Add and remove class, Personally I dont think this is the right way to do it, anyone please suggest    
    $(this).addClass('mouseover');
    $(this).removeClass('mouseout');

  }).mouseout(function() {

    //Add and remove class
    $(this).addClass('mouseout');
    $(this).removeClass('mouseover');

  });

	//Mouseover with animate Effect for Category menu list
  $('.boxBody #category li').mouseover(function() {

    //Change background color and animate the padding
    $(this).children().animate({paddingLeft:"20px"}, {queue:false, duration:300});
  }).mouseout(function() {
    
    //Change background color and animate the padding
    $(this).children().animate({paddingLeft:"0"}, {queue:false, duration:300});
  });

});