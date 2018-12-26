 
<?php 
/*$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'horizontalForm',
        'type' => 'horizontal',
        'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
    )
);*/ ?>


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
<!--start -->   
<input type='hidden' value='4' id='count_div'>
<div class="row-fluid">
    <div class="tab-pane active" id="Information">
        <div class="span6 pull-left">
            <div class="span12">
                <fieldset class="portlet " >
                    <div class="portlet-decoration">
                        <div class="span11">Details </div>
                        <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" id="hide_box_btn1" type="button"></button> </div>
                        <div class="clearfix"></div>	
                    </div>
                    <div class="portlet-content" id="hide_box_line1">
                       <?php //echo $form;
                       echo $form->renderBegin();
                        foreach($form->getElements() as $element)
                        echo $element->render();
                        echo $form->renderEnd();
                       
                       echo CHtml::activeTextField($model,'CONFIG_STORE_OWNER');
                       
                       
                       ?>
                    </div>

                   
                </fieldset>

            </div>   

        </div>
    </div>
<!--end -->


<?php //$this->renderPartial('_form', array('model'=>$model)); ?>
<?php
//$this->endWidget();
//unset($form);?>
</div>