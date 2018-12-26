<?php
/* @var $this ProductinfoController */
/* @var $model Productinfo */
/* @var $form CActiveForm */
?>

<div class="wide form row-fluid fileter_div_main">
 <input type="text" value="<?php echo $_GET['Customer']['truck_reg_no'];?>" id="Customer_truck_reg_no" name="Customer[truck_reg_no]" class="span2" placeholder="Truck Reg No"> 
<?php //echo $form->textField($model,'tonnes',array('placeholder'=>Tonnes,'class'=>span2)); ?>
<?php //echo CHtml::submitButton('Search',array('class'=>'span2 btn btn-info')); ?>
 
</div><!-- search-form -->