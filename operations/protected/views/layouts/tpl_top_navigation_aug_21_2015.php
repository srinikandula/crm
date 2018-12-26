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
          		<div class="menu_list_main"></div>
         			<?php $this->widget('zii.widgets.CMenu',array(
                    'htmlOptions'=>array('class'=>'pull-right nav'),
                    'submenuHtmlOptions'=>array('class'=>'dropdown-menu'),
					'itemCssClass'=>'item-test',
                    'encodeLabel'=>false,
                    'items'=>$this->menuTopItemsList,
					)); ?>
    	</div>
    </div>
	</div>
</div>

<!-- subnav -->