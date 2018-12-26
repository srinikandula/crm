<?php
$form = $this->beginWidget(
        'bootstrap.widgets.TbActiveForm',
        array(
    'id' => 'horizontalForm',
    'type' => 'horizontal',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
	'htmlOptions'=>array('enctype' => 'multipart/form-data'),
        )
);
?>
<?php echo $form->errorSummary($model['t']); ?>
<div class="row-fluid">
        <div class="span2 pull-right fixed_top_buttons design_fixed_top" >
            <span class="btn open-and-close"><i class="icon-chevron-right"></i> <i class="icon-chevron-left"></i>  </span>
            <?php Library::saveButton(array('label'=>'Save','permission'=>$this->editPerm)); ?>
            <?php Library::cancelButton(array('label'=>'Cancel','url'=> base64_decode(Yii::app()->request->getParam('backurl'))));  ?>
        </div>
    <?php   // $this->renderPartial('_form', array('form' => $form, 'model' => $model));    
    
    
    ?>
            <?php
         $this->widget(
          'bootstrap.widgets.TbTabs',
          array(
          'type' => 'pills',
          'tabs' => array(
                        array( 'label' => 'Truck','active' => true ,'content' =>  $this->renderPartial('_form', array('form' => $form, 'model' => $model),true)),
              
                        array( 'label' => 'Owner',  'content' => $this->renderPartial('_customer', array('form'=>$form,'model'=>$model),true)),
              
                        array( 'label' => 'Plan',  'content' => $this->renderPartial('_plan', array('form'=>$form,'model'=>$model),true)),
                      ),
                )
          );
        ?>
<?php
$this->endWidget();
unset($form);
?>
</div>