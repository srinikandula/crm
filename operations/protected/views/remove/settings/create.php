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
<?php 

echo $form->errorSummary($model['o']); 

?>

<div class="row-fluid">

  <div class="span2 pull-right fixed_top_buttons design_fixed_top" >
    <span class="btn open-and-close"><i class="icon-chevron-right"></i> <i class="icon-chevron-left"></i>  </span>
	<?php
		Library::saveButton(array('label'=>Yii::t('common','button_save'),'permission'=>$this->addPerm)); 
        Library::cancelButton(array('label'=>Yii::t('common','button_cancel'),'url'=> $this->createUrl('index')));            
    ?>
    
</div>
   
<?php $this->renderPartial('_form', array('form'=>$form,'model'=>$model));?>
<?php
$this->endWidget();
unset($form);?>
</div>