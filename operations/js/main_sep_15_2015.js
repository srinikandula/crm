  $( window ).load(function() {
		var InH=window.innerHeight;
		
		var divheight=$(".top_box_fixed").height();
		var divheight1=$(".navbar-inner").height();
		var divheight2=$(".container").height();
		var divheight3=$(".table-bordered thead").height();
		var divheight4=$(".navbar-fixed-bottom").height();
		
		$("#content").css("min-height",(InH-divheight1-divheight4)); 
		//$(".items_main_div").css("max-height",(InH-divheight-divheight1-divheight2-divheight3-42)); 
            checkScroll();
    });
	
	
			$(function(){ // document ready

		  if (!!$('.top_box_fixed, .table-bordered thead, .top_box_margin .summary, .fixed_top_buttons').offset()) { // make sure ".sticky" element exists

		    var stickyTop = $('.top_box_fixed, .table-bordered thead, .top_box_margin .summary, .fixed_top_buttons').offset().top; // returns number 

		  $(window).scroll(function(){ // scroll event

		      var windowTop = $(window).scrollTop(); // returns number 

		      if (stickyTop < windowTop){

				
				//$('.fixed_top_buttons').css({ position: 'fixed', top: '148px', position: 'fixed'  });
			}
		      else {
			
		    }

		    });

		  }

		});
function checkScroll()
{
    
    var InH=window.innerHeight;
    var divHeight = parseInt($(".navbare_left_bg").css("height").replace('px',''));
    $(".navbare_left_bg").css("max-height",(InH-65));
    if(InH>divHeight&&InH-divHeight<100)
        divHeight = divHeight+100;
    if(InH>divHeight)
        $(".navbare_left_bg").removeClass("scr");
    else
        $(".navbare_left_bg").addClass("scr");  
//    console.log(InH+'--'+divHeight);
//    
}
function waitForCheckScroll(th)
{
    window.setTimeout(checkScroll,1000);
    if($(th).find('ul').css('display')=='block')
    {
        $(th).find('ul').css('display','none');
        $('.dropdown-menu-left').toggle();
        return true;
    }
    $('.dropdown-menu-left').toggle();
    $(th).find('ul').css('display','block');
}
function createToggle( i ){
  return function(){
    $("#hide_box_line"+i).toggle();
  };
}
 $(document).ready(function(){
  $('.dropdown.item-test.open.item-test ul').css('display','block');					   
  var countEle;
  countEle = $('#count_div').val();
  for(var i = 0; i < countEle; i++) {
      $("#hide_box_btn"+i).click(createToggle( i ));
  }

}); 
$(document).ready(function(){
/*$(".arrow_main").click(function(){
    $("#hide_box_btn2").toggleClass("arrow-minus");
  });*/
$(".portlet-decoration").click(function(){
    $(this).toggleClass("arrow-minus");
  });
  
	  });
	
	
			

	
function validateGridCheckbox(field)
{
      //var atLeastOneIsChecked = $('input[name=\"id[]\"]:checked').length > 0;
        var atLeastOneIsChecked = $('input[name=\"'+field+'\"]:checked').length > 0;
	if (!atLeastOneIsChecked)
	{
			//js:bootbox.alert('No rows selected!!');
                        alert('No rows selected!!');
	}/*else{
        js:bootbox.confirm("Are you sure?", function(confirmed){ console.log(confirmed);return confirmed;{alert("Confirmed: "+confirmed);}
            if(confirmed){flag=true}else{ flag= false}})
    alert(console.log.value)
    }*/
  
	else if(confirm('Are you sure you want to perform selected action?'))
	{
			return true;
	}else
	{
		return false;
	}
}

$(document).ready(function () {
  $('input:checkbox').change(function () {
       if ($(this).attr('checked')) {
        $(this).closest('tr').addClass('checked');
    } else {
        $(this).closest('tr').removeClass('checked');
    }
   });
  
  
   $(".image-name-display").hover(function(){
    $(".uploading-img-main .logo-img ").toggle();
  });
  
  
  $(".open-and-close").click(function(){
	$(".design_fixed_top").toggleClass("hidi-div-main");
	
  });


  
});	
