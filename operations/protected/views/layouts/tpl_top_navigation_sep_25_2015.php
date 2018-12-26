<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
   		 <div class="container">
       		 <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
           		 <span class="icon-bar"></span>
          		 <span class="icon-bar"></span>
           		 <span class="icon-bar"></span>
        	  </a>
     
          <!-- Be sure to leave the brand out there if you want it shown -->
          <a class="brand" href="<?php echo $this->createAbsoluteUrl(array("dashboard","index"));?>">
		  EasyGaadi.com<!-- <img src="<?php  echo $baseUrl;?>/img/Logo-small.png" alt="" title="" height="40px" /> --></a>
          
          <div class="nav-collapse">
          		<!-- <div class="menu_list_main"></div> -->
         			<ul id="yw1" class="pull-right nav">
<?php  if($_SESSION['id_admin_role']==8){?>
<li    ><a href="<?php echo $this->createAbsoluteUrl('load/index');?>" <?php if($this->uniqueid=='load'){?> style="background-color:#23b5ec"<?php }?> ><b>Request A Truck</b></a></li>
<li  ><a href="<?php echo $this->createAbsoluteUrl('order/index');?>" <?php if($this->uniqueid=='order'){?> style="background-color:#23b5ec"<?php }?> ><b>Orders</b></a></li><?php }?> 
<li tabindex="-1" class="dropdown item-test"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="icon-admin"></i>Weclome <?php echo Yii::app()->session['first_name'];?><span class="caret"></span></a>
<ul class="dropdown-menu">
<li class="item-test"><a href="<?php echo $this->createUrl('site/logout');?>">Logout</a></li>
</ul>
</li>
</ul>
					<?php /*$this->widget('zii.widgets.CMenu',array(
                    'htmlOptions'=>array('class'=>'pull-right nav'),
                    'submenuHtmlOptions'=>array('class'=>'dropdown-menu'),
					'itemCssClass'=>'item-test',
                    'encodeLabel'=>false,
                    'items'=>$this->menuTopItemsList,
					));
					*/?>
    	</div>
    </div>
	</div>
</div>

<!-- subnav -->