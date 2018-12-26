 
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
<input type='hidden' value='4' id='count_div'>
<div class="row-fluid">
    <div class="tab-pane active" id="Information">
  
        <?php
        /*echo $form->renderBegin();
        foreach($form->getElements() as $element)
        echo $element->render();
        echo $form->renderEnd();*/
       //  echo $form->textField($model,'CONFIG_STORE_NAME',array('placeholder'=>'Username'),array('autofocus'=>'autofocus'));
       // echo CHtml::ActiveTextField($model,'CONFIG_STORE_NAME');
         echo $form->textFieldRow(
                                $model, 'CONFIG_STORE_NAME', array(
                            'rel' => 'tooltip', 'title' => 'image', 'data-toggle' => "tooltip",
                            'data-placement' => "right",
                                )
                        );
        ?>
        
    </div>
</div>
<?php
$this->endWidget();
unset($form);?>