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
<?php //echo $form->errorSummary($model['c']); ?>
<div class="row-fluid">
    <?php /*$this->widget(
    'bootstrap.widgets.TbTabs',
    array( 'type' => 'pills',  'tabs' => array(array( 'label' => 'Personal Details', 'active' => true ,  'content' => $this->renderPartial('_form', array('form'=>$form,'model'=>$model),true)))));*/
	$this->renderPartial('_form', array('form'=>$form,'model'=>$model));
?>
<?php
$this->endWidget();
unset($form);
?>
</div>