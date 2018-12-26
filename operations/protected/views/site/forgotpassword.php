<?php    $this->widget('ext.Flashmessage.Flashmessage');    ?> 
<div class="main_login_bg">
	<div class="page-header">
	<h1><!-- <img src="<?php echo Yii::app()->baseUrl?>/img/Logo-big.png" alt="" title="" /> -->EasyGaadi.com</h1>
	</div>

<div class="row-fluid">
    <div class="span5 offset3 fotgot">
	<?php
	$this->beginWidget('zii.widgets.CPortlet', array(
		'title'=>"Forget Password",
	));
	
?>
   
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'fotgot-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>
    
     <div class="form">
        <div class=" row  span11 offset3">
          <div class="login_main">

            <?php echo $form->textField($model,'email',array('placeholder'=>'Email'),array('autofocus'=>'autofocus')); ?>
            <i class="icon-envelope loging_page"></i>
            <?php echo $form->error($model,'forgot Password'); ?>
            

            
        </div>
    
      
    
    <div class=" two_left_right">
      <div class="buttons">
            <?php echo CHtml::submitButton('Send',array('class'=>'btn btn-info right_side')); ?>
          
        </div>
        <!-- <input class="btn btn-warning left_side" type="submit" name="yt1" value="Back"> -->
	<?	$this->widget(
    'bootstrap.widgets.TbButton',
    array(
		'url'=>$this->createAbsoluteUrl('site/login'),
        'label' => 'Back',
        'type' => 'warning',
    )
);?>
        
        </div>
    
    <?php $this->endWidget(); ?>
    </div><!-- form -->

<?php $this->endWidget();?>
<div class="clearfix"></div>
    </div>
    
  	 </div>   
  </div>
  <div class="clearfix"></div>
</div> 