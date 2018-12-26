<?php
/* @var $this ProductinfoController */
/* @var $model Productinfo */
/* @var $form CActiveForm */
?>

<div class="wide form row-fluid fileter_div_main">

 
<?php echo $form->textField($model,'date_available_from',array('id'=>'date_available_from','placeholder'=>'Loading Date From','class'=>"span2 date")); ?>
<?php echo $form->textField($model,'date_available_to',array('id'=>'date_available_to','placeholder'=>'Loading Date To','class'=>"span2 date")); ?>
<?php echo $form->textField($model,'date_ordered_from',array('id'=>'date_ordered_from','placeholder'=>'Date Ordered From','class'=>"span2 date")); ?>
<?php echo $form->textField($model,'date_ordered_to',array('id'=>'date_ordered_to','placeholder'=>'Date Ordered To','class'=>"span2 date")); ?>
<?php echo CHtml::submitButton('Search',array('class'=>'span2 btn btn-info')); ?>
 
</div><!-- search-form -->