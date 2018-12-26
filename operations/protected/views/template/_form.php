<i>Use below keywords in mail to get populate data automatically.</i><br/>
[%site_name%, %site_logo%,%site_url%, %site_owner%, %site_address%, %site_telephone% , %site_support_email%, %site_customer_login_url%, %site_customer_register_url%]
<br/>
<div class="row-fluid">
 <div class="tab-pane active" id="Information">
	<div class="span12">
	<?php if($model->type=='B' || $model->type=='E'){?>
        <fieldset class="portlet " >
	 <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
		<div class="span11">Details</div>
		<div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
		<div class="clearfix"></div>
	</div>
    
 <div class="portlet-content" id="hide_box_line1">
 
    <div class="span5"> <?php echo $form->textFieldRow(
           $model,'title', //'readonly'=>'readonly'
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
       ); ?> </div>
       

       
	<div class="span5"> <?php 
		echo $form->textFieldRow(
           $model,
           'keywords',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)); ?> 
     </div>
       
	<div class="span5"> <?php
	   echo $form->textFieldRow(
           $model,
           'subject',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
       ); ?> </div>
	<div class="span5"> <?php 
		 echo $form->checkBoxRow($model,'html',
            array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",));?> 
    </div>
       
       </div>
   </fieldset>
   
    <fieldset class="portlet" >
		 <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line2').slideToggle();">
			<div class="span11">Email Description</div>
			<div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
			<div class="clearfix"></div>
	    </div>
		<div class="portlet-content" id="hide_box_line2">
			<div class="span11"> <?php 
			if($_GET['id']!=1){
				$this->widget('bootstrap.widgets.TbCKEditor',
				array('editorOptions'=>array('height'=>'100px','width'=>'100%',),
				'model'=> '$model',
				'name' => 'Emailtemplate[description]',
				'id'=>'Emailtemplate_description',
				'value'=>$model->description
				)
				);  echo $form->error($model,'description');
				//echo '</div>';
			}else
			{
				echo $form->textAreaRow(
					$model,'description', array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right","rows"=>"20","cols"=>"50")
				);	
			}
            ?>
			</div>
       </div>
  		 </fieldset><?php }?>
        <?php if($model->type=='B' || $model->type=='M'){?>
            <fieldset class="portlet " >
	 <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
		<div class="span11">Mobile Details</div>
		<div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
		<div class="clearfix"></div>
	</div>
    
 <div class="portlet-content" id="hide_box_line1">
 
    <div class="span5"> <?php echo $form->textFieldRow(
           $model,'mobile_keywords', //'readonly'=>'readonly'
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
       ); ?> </div>
       
<div class="span5"> <?php echo $form->textAreaRow(
           $model,'mobile_description', //'readonly'=>'readonly'
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
       ); ?> </div>
       
       </div>
   </fieldset>
            <?php }?>
 	</div>  
  </div>
</div>