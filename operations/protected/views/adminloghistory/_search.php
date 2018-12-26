<div class="wide form row-fluid fileter_div_main">
<?php
    $form = $this->beginWidget('CActiveForm',
        array(
        'id' => 'gridForm',
        'action'=>'',    
        'method'=>'get',        
        'enableAjaxValidation' => false,
    ));
?>
<?php echo $form->textField($model,'start_date',array('id'=>'start_date','placeholder'=>'Date From','class'=>"span2 date")); ?>
<?php echo $form->textField($model,'end_date',array('id'=>'end_date','placeholder'=>'Date To','class'=>"span2 date")); ?>
<?php echo $form->textField($model,'first_name',array('id'=>'first_name','placeholder'=>'First Name','class'=>"span2")); ?>
<?php echo $form->textField($model,'last_name',array('id'=>'last_name','placeholder'=>'Last Name','class'=>"span2")); ?>
<?php echo CHtml::button('Go',array('class'=>'btn btn-info','onclick'=>'fnsubmit()')); ?>
<?php $this->endWidget(); ?> 
</div><!-- search-form -->
<script>
function fnsubmit(){
    var addr='AdminLogHistory[start_date]='+$("#start_date").val()+'&AdminLogHistory[end_date]='+$("#end_date").val()+'&AdminLogHistory[first_name]='+$("#first_name").val()+'&AdminLogHistory[last_name]='+$("#last_name").val();
    window.location='?'+addr;
    //alert(addr);
}
</script>