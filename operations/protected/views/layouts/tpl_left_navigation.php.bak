<div class="pull-left span3" id="test">
    <div class="navbare_left_bg">
    <div class=" navbare_left">
        	<div class="style-switcher pull-left">
         <i class="icon-user"></i>
		 
		  Hello <?php echo Yii::app()->session['first_name'];?> | <a href="<?php echo $this->createUrl('site/logout');?>"><i class="icon-off"></i> logout </a>
          	</div>
    </div>
		<div class="sidebar-nav">
                <?php 
                    $this->widget('zii.widgets.CMenu',array(
                    'htmlOptions'=>array('class'=>'pull-right nav'),
                    'submenuHtmlOptions'=>array('class'=>'dropdown-menu'),
		  			'itemCssClass'=>'item-test',
                    'encodeLabel'=>false,
                    'items'=>$this->menuItemsList,//$this->itemArray,
                  )); ?>
		</div>
	</div>
</div>