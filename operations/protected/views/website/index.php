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
echo $form->errorSummary($model); 
//echo $form->errorSummary($model['o']); 

?>
<?php
    $this->widget('ext.Flashmessage.Flashmessage');
    ?>
<div class="row-fluid">
<div class="span2 pull-right fixed_top_buttons design_fixed_top">
            <span class="btn open-and-close"><i class="icon-chevron-right"></i> <i class="icon-chevron-left"></i>  </span>
    <?php
$this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'label' => 'Save',
        'buttonType'=>'submit',
        'visible'=>true,//$this->addPerm,
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

<?php

$this->renderPartial('_form', array('form'=>$form,'model'=>$model));

/*echo $form->textFieldRow(
                                $model, 'CONFIG_STORE_NAME', array(
                            'rel' => 'tooltip', 'title' => 'image', 'data-toggle' => "tooltip",
                            'data-placement' => "right",
                                )
                        ); */?>
<?php
$this->endWidget();
unset($form);?>
</div>