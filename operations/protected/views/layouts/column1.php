<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
 <script type='text/javascript'> 
$(document).ready(function(){  
  $(".menu_list_main").click(function(){
	$( ".pull-left.span3" ).toggleClass( "hide_div" );
	$( ".navbar-inner .brand" ).toggleClass( "brand_height" );
	$( ".menu_list_main" ).toggleClass( "menu_list_main_height" );
	if($( "#test" ).hasClass( "hide_div" ))
	{ $( "#content" ).removeClass( "pull-right span9" ).addClass("pull-right span12");
	$( "#tbBreadcrumbs_id" ).removeClass( "pull-right span9" ).addClass("pull-right span12");
	 }
	else
	{ $( "#content" ).removeClass( "pull-right span12" ).addClass("pull-right span9");
	  $( "#tbBreadcrumbs_id" ).removeClass( "pull-right span12" ).addClass("pull-right span9");
	}
	
});

$(".search_div_hide").click(function(){
$( ".navbar-search" ).toggle(  );
  });
  
});
</script>
  <div class="row-fluid">
	<?php 
        require_once('tpl_left_navigation.php');?><!--/span-->
    <div class="pull-right span12 tbBreadcrumbs-table" id="tbBreadcrumbs_id">
<div class="span6 pull-left tb-breand"><?php echo "<a href='".$this->createUrl($this->uniqueid.'/index')."'>".$this->menuTitle[$this->uniqueid]."</a>"; echo $this->action->id!='index'?'<span class="iocn-main '.$this->action->id.'-icon"></span>':'';?>  </div>
<div class="span6 pull-right">
<?php 
$this->widget('bootstrap.widgets.TbBreadcrumbs', array('links' => $this->breadcrumbs,));
?>
</div>
</div>
    <div class="pull-right span12" id="content">
    <!-- Include content pages -->
    <?php echo $content; ?>
<div class="clearfix"></div>	
	</div><!--/span-->
  </div><!--/row-->

 
<?php $this->endContent(); ?>