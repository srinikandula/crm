<?php 
$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'horizontalForm',
        'type' => 'horizontal',
        'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
    )
); ?>
<?php echo $form->errorSummary($model);?>

<div class="row-fluid">
  <div class="span2 pull-right fixed_top_buttons design_fixed_top" >
       <span class="btn open-and-close"><i class="icon-chevron-right"></i> <i class="icon-chevron-left"></i>  </span>
	   <?php
        Library::saveButton(array('label'=>'Save','permission'=>$this->addPerm)); 
        Library::cancelButton(array('label'=>'Cancel','url'=> $this->createUrl('index')));            
        ?>
</div>
   
<?php
$this->renderPartial('_form', array('form'=>$form,'model'=>$model));
?>

<?php
$this->endWidget();
unset($form);?>
</div>