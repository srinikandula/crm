

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
        'htmlOptions'=>array('enctype' => 'multipart/form-data'),
    )
); ?>
<?php echo $form->errorSummary($model['ltr']); ?>

<div class="row-fluid">
<div class="">	

  <div class="span2 pull-right fixed_top_buttons design_fixed_top" >
       <span class="btn open-and-close"><i class="icon-chevron-right"></i> <i class="icon-chevron-left"></i>  </span>       
        <?php if ($_SESSION['id_admin_role'] == 8) {Library::saveButton(array('label'=>'Save','permission'=>$this->editPerm));} ?>
        <?php Library::cancelButton(array('label'=>'Back','url'=> base64_decode(Yii::app()->request->getParam('backurl'))));  ?>

</div>
</div>
   
<?php 
$file=$_SESSION['id_admin_role']!=8?'_form':'_form_transporter';
$this->widget(
    'bootstrap.widgets.TbTabs',
    array(
        'type' => 'pills', 
        'tabs' => array(
			array( 'label' => 'Load', 'active' => true ,  'content' => $this->renderPartial($file, array('form'=>$form,'model'=>$model,'dataProvider'=>$dataProvider),true)),
            array( 'label' => 'Customer',   'content' => $this->renderPartial('_customer', array('form'=>$form,'model'=>$model),true)),
        ),
    )
);

//$this->renderPartial('_form_transporter', array('form'=>$form,'model'=>$model));
?>

<?php
$this->endWidget();
unset($form);?>
</div>
    