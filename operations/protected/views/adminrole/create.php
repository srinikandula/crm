
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
<?php echo $form->errorSummary($model); ?>

<div class="row-fluid">


  <div class="span2 pull-right fixed_top_buttons design_fixed_top" >
            
              <span class="btn open-and-close"><i class="icon-chevron-right"></i> <i class="icon-chevron-left"></i>  </span>
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
<?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
<?php
$this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'label' => 'Cancel',
        'type' => 'danger',
	'url'=>$this->createUrl('index'))
);?>
</div>

   
<?php /*$this->widget(
    'bootstrap.widgets.TbTabs',
    array(
        'type' => 'pills', 
        'tabs' => array(
            array( 'label' => 'Details', 'active' => true ,  'content' => $this->renderPartial('_form', array('form'=>$form,'model'=>$model),true)),
            //array( 'label' => 'Information', 'content' => $this->renderPartial('_form', array('model'=>$model),true)),
            
        ),
    )
);*/
$this->renderPartial('_form', array('form'=>$form,'model'=>$model,'permissions'=>$permissions));
?>

<?php //$this->renderPartial('_form', array('model'=>$model)); ?>
<?php
$this->endWidget();
unset($form);?>

</div>
