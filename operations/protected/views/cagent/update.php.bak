
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
<?php echo $form->errorSummary($model['c']); ?>

<div class="row-fluid">
<div class="">	

  <div class="span2 pull-right fixed_top_buttons design_fixed_top" >
       <span class="btn open-and-close"><i class="icon-chevron-right"></i> <i class="icon-chevron-left"></i>  </span>       
	   <?php Library::saveButton(array('label'=>Yii::t('common','button_save'),'permission'=>$this->editPerm)); ?>
        <?php Library::cancelButton(array('label'=>Yii::t('common','button_cancel'),'url'=> base64_decode(Yii::app()->request->getParam('backurl'))));  ?>

</div>
</div>
   
<?php 
/*
$this->widget(
    'bootstrap.widgets.TbTabs',
    array(
        'type' => 'pills', 
        'tabs' => array(
            array( 'label' => 'Personal Details', 'active' => true ,  'content' => $this->renderPartial('_form', array('form'=>$form,'model'=>$model),true)),
        ),
    )
);*/

$this->renderPartial('_form', array('form'=>$form,'model'=>$model));
?>

<?php
$this->endWidget();
unset($form);?>
</div>