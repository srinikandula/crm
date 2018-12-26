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
        )
);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row-fluid">
        <div class="span2 pull-right fixed_top_buttons design_fixed_top" >
            <span class="btn open-and-close"><i class="icon-chevron-right"></i> <i class="icon-chevron-left"></i>  </span>
            <?php Library::saveButton(array('label'=>'Save','permission'=>$this->editPerm)); ?>
            <?php Library::cancelButton(array('label'=>'Cancel','url'=> base64_decode(Yii::app()->request->getParam('backurl'))));  ?>
        </div>
    <?php 
	
	$this->widget(
          'bootstrap.widgets.TbTabs',
          array(
          'type' => 'pills',
          'tabs' => array(
                        array( 'label' => 'Details','active' => true ,'content' =>  $this->renderPartial('_form', array( 'rows' => $rows,'form' => $form, 'model' => $model),true)),
              
                        array( 'label' => 'Order',  'content' => $this->renderPartial('_customer', array('form'=>$form,'model'=>$rows),true)),
              
                      ),
                )
          );

	//$this->renderPartial('_form', array('form' => $form, 'model' => $model, 'rows' => $rows));    ?>
<?php
$this->endWidget();
unset($form);
?>
</div>