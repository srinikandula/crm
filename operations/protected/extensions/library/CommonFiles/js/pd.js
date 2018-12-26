		// Initialize the plugin with no custom options
		$(document).ready(function () {
			// None of the options are set
			$("div#makeMeScrollable").smoothDivScroll({
				autoScrollingMode: "onStart",
                               // touchScrolling: true,
		                //hotSpotScrolling: false,
                                mousewheelScrolling: "horizontal",
                                visibleHotSpotBackgrounds: "hover"
			});
		});
	   $('.thumbnail').click(function(){
//	                  alert("yes");
			  $('.topup1').attr('data-scroll',$(this).attr('data-scroll'));
                          $('.topup1').attr('data-scroll-width',$(this).attr('data-scroll-width'));
			  $('.topup1').attr('data-value',$(this).attr('data-value'));
                          $('.topup1').attr('data-zoom-image',$(this).attr('data-zoom-image'));
                          $('.topup1').attr('src',$(this).attr('data-image'));
                         // alert($(this).attr('data-image'));
                        //  alert($('.topup1').attr("src"));
		   });
		   
     
    
	  $(".topup1").click(function() {
			//alert($('.innerdiv:first').attr('data-scroll'));
			//alert($(this).attr('data-scroll-width'));
			$('.zoomLens').css('display','none');
			$('.zoomWindow').css('display','none');
			$('#toPopup').css('background-image','url("'+$(this).attr('data-scroll')+'")');
                        $('#toPopup').attr('data-scroll-width',$(this).attr('data-scroll-width'));
                        if($(this).attr('data-scroll-width')<650){
                            $('#toPopup').css('background-position','center center');
                        };
                       // alert($('#toPopup').css('background-position'));
			$("#right").attr('name',$(this).attr('data-value'));
			
			loading(); // loading
			setTimeout(function(){ // then show popup, deley in .5 second
				loadPopup(); // function show popup 
			}, 500); // .5 second
	return false;
	});
	
	/* event for close the popup */
	$("div.close").hover(
					function() {
						$('span.ecs_tooltip').show();
					},
					function () {
    					$('span.ecs_tooltip').hide();
  					}
				);
	
	$("div.close").click(function() {
		disablePopup();  // function close pop up
	});
	
	$(this).keyup(function(event) {
		if (event.which == 27) { // 27 is 'Ecs' in the keyboard
			disablePopup();  // function close pop up
		}  	
	});
	
	$("div#backgroundPopup").click(function() {
		disablePopup();  // function close pop up
	});
	
	$('a.livebox').click(function() {
		alert('Hello World!');
	return false;
	});
	

	 /************** start: functions. **************/
	function loading() {
		$("div.loader").show();  
	}
	function closeloading() {
		$("div.loader").fadeOut('normal');  
	}
	
	var popupStatus = 0; // set value
	
	function loadPopup() { 
		if(popupStatus == 0) { // if value is 0, show popup
			closeloading(); // fadeout loading
			$("#toPopup").fadeIn(0500); // fadein popup div
			$("#backgroundPopup").css("opacity", "0.7"); // css opacity, supports IE7, IE8
			$("#backgroundPopup").fadeIn(0001); 
			popupStatus = 1; // and set value to 1
		}	
	}
		
	function disablePopup() {
		if(popupStatus == 1) { // if value is 1, close popup
			$("#toPopup").fadeOut("normal");  
			$("#backgroundPopup").fadeOut("normal");  
			popupStatus = 0;  // and set value to 0
		}
	}
	
	/*--mouse over*/
$("#toPopup").mousemove(function(e){
    
    
 
var x=$(this).position().left + ($(this).outerWidth(true) / 2);
var y=($(window).height() / 2);
if(y==e.clientY){
    e.clientY="center";
}else if(y>e.clientY){
    
   e.clientY="+"+parseInt(parseInt(y)-parseInt(e.clientY))+"px";
}else if(y<e.clientY){ 
    e.clientY="-"+parseInt(parseInt(e.clientY)-parseInt(y))+"px";
}

 

 $(this).css('background-position','center '+e.clientY+'');
}).mouseleave(function(){
    if($(this).attr("data-scroll-width")<650){
    $.positionx=120;
}else if($(this).attr('data-scroll-width')<500){
      $.positionx=250;
  }else{
    $.positionx=0;
}
    $(this).css('background-position','center -'+e.clientY+'px');
});
$('.innerimg').click(function(){
$('#'+$('.active').attr('id')).css(' border-width','4px').css('border-style','solid').css('border-color','#666666');
$('#'+$('.active').attr('id')).removeClass('active');
$(this).addClass('active');
$("#toPopup").fadeIn(1000).css('background-image','url("'+$(this).attr('data-scroll')+'")');
$('#toPopup').attr('data-scroll-width',$(this).attr('data-scroll-width'));
 if($('#toPopup').attr('data-scroll-width')>500 && $('#toPopup').attr('data-scroll-width')<650){
    $('#toPopup').css('background-position','center 0px');
  }else if($('#toPopup').attr('data-scroll-width')<500){
      $('#toPopup').css('background-position','center 0px');
  }else{
       $('#toPopup').css('background-position','center 0px');
  };
$('.active').css(' border-width','4px').css('border-style','solid').css('border-color','#333');
});
$("#right").click(function(){
var id=$(this).attr('name');

id=parseInt(id)-parseInt(1);
if(id>=1 ){
$('#'+$('.active').attr('id')).css(' border-width','4px').css('border-style','solid').css('border-color','#666666');
$('#'+$('.active').attr('id')).removeClass('active');
$('.innerimg#image'+id).addClass('active');
$("#toPopup").fadeIn(1000).css('background-image','url("'+$('.innerimg#image'+id).attr('data-scroll')+'")');
$('#toPopup').attr('data-scroll-width',$('.innerimg#image'+id).attr('data-scroll-width'));
if($('#toPopup').attr('data-scroll-width')>500 && $('#toPopup').attr('data-scroll-width')<650){
    $('#toPopup').css('background-position','center center');
  }else if($('#toPopup').attr('data-scroll-width')<500){
      $('#toPopup').css('background-position','center center');
  }else{
       $('#toPopup').css('background-position','center center');
  };
$('.active').css(' border-width','1px').css('border-style','solid').css('border-color','#333');
$(this).attr('name',id);
}else if($(this).attr('name')==1){
$('#'+$('.active').attr('id')).css(' border-width','4px').css('border-style','solid').css('border-color','#666666');
$('#'+$('.active').attr('id')).removeClass('active');
$('.innerimg#image'+$("#left").attr('count')).addClass('active');
$("#toPopup").fadeIn(1000).css('background-image','url("'+$('.innerimg#image'+$("#left").attr('count')).attr('data-scroll')+'")');
$('#toPopup').attr('data-scroll-width',$('.innerimg#image'+$("#left").attr('count')).attr('data-scroll-width'));

if($('#toPopup').attr('data-scroll-width')>500 && $('#toPopup').attr('data-scroll-width')<650){
    $('#toPopup').css('background-position','center center');
  }else if($('#toPopup').attr('data-scroll-width')<500){
      $('#toPopup').css('background-position','center center');
  }else{
       $('#toPopup').css('background-position','center center');
  };
$('.active').css(' border-width','1px').css('border-style','solid').css('border-color','#333');
$(this).attr('name',$("#left").attr('count'));
}

});
$("#left").click(function(){
if($("#right").attr('name')<$(this).attr('count')){
var id=$("#right").attr('name');
id=parseInt(id)+parseInt(1);
$('#'+$('.active').attr('id')).css(' border-width','4px').css('border-style','solid').css('border-color','#666666');
$('#'+$('.active').attr('id')).removeClass('active');
$('.innerimg#image'+id).addClass('active');
$("#toPopup").fadeIn(1000).css('background-image','url("'+$('.innerimg#image'+id).attr('data-scroll')+'")');
$('#toPopup').attr('data-scroll-width',$('.innerimg#image'+id).attr('data-scroll-width'));
if($('#toPopup').attr('data-scroll-width')>500 && $('#toPopup').attr('data-scroll-width')<650){
    $('#toPopup').css('background-position','center center');
  }else if($('#toPopup').attr('data-scroll-width')<500){
      $('#toPopup').css('background-position','center center');
  }else{
       $('#toPopup').css('background-position','center center');
  };
$('.active').css(' border-width','1px').css('border-style','solid').css('border-color','#333');
$("#right").attr('name',id);
//alert($("#right").attr('name'));

}
if($("#right").attr('name')==$(this).attr('count')){
$('#'+$('.active').attr('id')).css(' border-width','4px').css('border-style','solid').css('border-color','#666666');
$('#'+$('.active').attr('id')).removeClass('active');
$('.innerimg#image'+$(this).attr('count')).addClass('active');
$("#toPopup").fadeIn(1000).css('background-image','url("'+$('.innerimg#image'+$(this).attr('count')).attr('data-scroll')+'")');
$('#toPopup').attr('data-scroll-width',$('.innerimg#image'+$(this).attr('count')).attr('data-scroll-width'));
if($('#toPopup').attr('data-scroll-width')>500 && $('#toPopup').attr('data-scroll-width')<650){
    $('#toPopup').css('background-position','center center');
  }else if($('#toPopup').attr('data-scroll-width')<500){
      $('#toPopup').css('background-position','center center');
  }else{
       $('#toPopup').css('background-position','center center');
  };
$('.active').css(' border-width','1px').css('border-style','solid').css('border-color','#333');
$("#right").attr('name',0);
} 	 

});
$("#img_01").elevateZoom({ zoomType: "inner",
cursor: "crosshair",gallery:'gallery_01'
/*, scrollZoom : true,imageCrossfade:true, tintOpacity:0.5,zoomWindowWidth:658,zoomWindowHeight:537,cursor:'pointer' */});
$("#img_01").bind("click", function(e) {  
  var ez =   $('#img_01').data('elevateZoom'); 
 $.fancybox(ez.getGalleryList());
  return false;
});/**/
var image = new Image(); // or document.createElement('img')
var width, height;
image.onload = function() {
    alert($(this).attr('id'));
    if($(this).attr('id')=="img_01"){
            width = this.width;
            height = this.height;
    }
};
