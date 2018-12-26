
<div class="row-fluid">
 <div class="tab-pane active" id="Information">
	<div class="span12">
		<fieldset class="portlet " >
		<div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
<div class="span11"><?php echo  yii::t('states','heading_sub_title');?> </div>
<div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" id="hide_box_btn1" type="button"></button> </div>
<div class="clearfix"></div>
</div>
 <div class="portlet-content" id="hide_box_line1">
 
     <div class="span5">   <?php
        $list=CHtml::listData(Country::getCountries(),'id_country','name');
       echo $form->dropDownListRow($model,'id_country', $list, array('prompt'=>'Select Country'));
      ?></div>
	  
	  
      <div class="span5"> <?php echo $form->textFieldRow(
           $model,
           'name',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
//            array('hint' => 'In addition to freeform text, any HTML5 text-based input appears like so.')
       ); ?> </div>
	  
	  
      <div class="span5"> <?php echo $form->textFieldRow(
           $model,
           'code',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
//            array('hint' => 'In addition to freeform text, any HTML5 text-based input appears like so.')
       ); ?>  </div>
	  
	  
      <div class="span5"> <?php
           echo $form->radioButtonListRow($model, 'status', 
                   array('1'=>'Enable','0'=>'Disable'),
                    array('class' => 'true')
                   );
        ?>	 </div>
       </div>
   </fieldset>

       </div>  
  </div>
</div>