<?php
/* @var $this ProductinfoController */
/* @var $model Productinfo */
/* @var $form CActiveForm */
?>
<style>

.clicked  {
	font-weight:bold;
}
</style>
<div class="wide form row-fluid fileter_div_main">
<div class="row-fluid design_dsm">
    <div class="span2 ">
        <div class="stat-block">
            <ul>
                <li class="stat-count"><span><?php echo $block['total'];?></span><span>Total</span></li>
				</ul>
		
        </div>
    </div>
	<div class="span2 ">
        <div class="stat-block">
            <ul>
                <li class="stat-count"><span><?php echo $block['pod_not_received'];?></span><span><a href="<?php echo $this->createUrl("order/index",array("pnr"=>1));?>" <?php if($_GET['pnr']){ echo 'class="clicked"';}?> >Pod Not Received</a></span></li>
            </ul>
        </div>
    </div>

	<div class="span2 ">
        <div class="stat-block">
            <ul>
                <li class="stat-count"><span><?php echo $block['pod_sub_pay_delay'];?></span><span><a href="<?php echo $this->createUrl("order/index",array("ppd"=>1));?>" <?php if($_GET['ppd']){ echo 'class="clicked"';}?> >Pending Payment Pod</a></span></li>
            </ul>
        </div>
    </div>
	
	<div class="span2 ">
        <div class="stat-block">
            <ul>
                <li class="stat-count"><span><?php echo number_format((int)($block['profit']['load']-$block['profit']['truck']),2);?></span><span>Net Profit</span></li>
            </ul>
        </div>
    </div>
	
	<div class="span2 ">
        <div class="stat-block">
            <ul>
                <li class="stat-count"><span><?php echo number_format((int)$block['profit']['commission'],2);?></span><span>Commission</span></li>
            </ul>
        </div>
    </div>

</div>
 <div class="note_box" style="float:right;left:837px;">
	<div class="clr_red"></div>
	<div class="note">LO Advance Pending</div>
	<div class="clr_plan5"></div>
	<div class="note">Pod Not Received</div> 
</div>
<?php echo $form->textField($model,'date_available_from',array('id'=>'date_available_from','placeholder'=>'Loading Date From','class'=>"span2 date")); ?>
<?php echo $form->textField($model,'date_available_to',array('id'=>'date_available_to','placeholder'=>'Loading Date To','class'=>"span2 date")); ?>
<?php echo $form->textField($model,'date_ordered_from',array('id'=>'date_ordered_from','placeholder'=>'Date Ordered From','class'=>"span2 date")); ?>
<?php echo $form->textField($model,'date_ordered_to',array('id'=>'date_ordered_to','placeholder'=>'Date Ordered To','class'=>"span2 date")); ?>
<?php echo CHtml::submitButton('Search',array('class'=>'span2 btn btn-info')); ?>
 
</div><!-- search-form -->