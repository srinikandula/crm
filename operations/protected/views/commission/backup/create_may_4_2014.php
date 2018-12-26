<div class="row-fluid">
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
<div class="">	

<div class="span2 pull-right fixed_top_buttons design_fixed_top" >
    <?php
$this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'label' => 'Save',
        'buttonType'=>'submit',
        'visible'=>$this->addPerm,
        'type' => 'info',
	)
);?>

<?php
$this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'label' => 'Cancel',
        'type' => 'danger',
	'url'=>base64_decode (Yii::app()->request->getParam('backurl')))
);?>
</div>
</div>
   
<?php $this->renderPartial('_form', array('form'=>$form,'model'=>$model));?>

<?php //$this->renderPartial('_form', array('model'=>$model)); ?>
<?php
$this->endWidget();
unset($form);?>
</div>