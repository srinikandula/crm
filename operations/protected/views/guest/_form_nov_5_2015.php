<div class="row-fluid">
 <div class="tab-pane active" id="Personal Details">
	<div class="span12 ">
     <fieldset class="portlet" >
	   <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
		 <div class="span11">Details </div>
		 <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
	   <div class="clearfix"></div>
	   </div>
 <div class="portlet-content" id="hide_box_line1">
   <div class="span5">  <?php echo $form->textFieldRow(
           $model['c'],'fullname',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
       ); ?></div>
           <div class="span5 uploading-img-main"> <?php
                        echo $form->fileFieldRow(
                                $model['c'], 'profile_image',
                                array('name' => 'image', 'rel' => 'tooltip', 
                            'data-toggle' => "tooltip", 'data-placement' => "right",
                            'class' => 'Options_design'),
                                array('hint' => '<div class="logo-img"><img src="' . Library::getMiscUploadLink() . $model['c']->profile_image . '"><input type="hidden" name="prev_file" value="' . $model['c']->profile_image . '"></div>')
                        );
                        echo '<p class="image-name-display">' . $model['c']->profile_image . '</p>';?>
            </div>

	   <div class="span5">  <?php echo $form->textFieldRow(
           $model['c'],'mobile',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
       ); ?></div>

	   <div class="span5">  <?php echo $form->textFieldRow(
           $model['c'],'email',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
       ); ?></div>


	 <div class="span5">  
	 <?php 
	  
		echo $form->hiddenField(
           $model['c'],'password',
           array('value'=>'password','rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right"));
	 
	?></div>
	<div class="span5">  <?php echo $form->hiddenField(
           $model['c'],'confirm',
           array('value'=>'password','rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
        ); ?></div>


	   <div class="span5">  <?php echo $form->textFieldRow(
           $model['c'],'company',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
       ); ?></div>

	   <div class="span5">  <?php echo $form->textAreaRow(
           $model['c'],'address',array('rows' => 3, 'cols' => 30)
       ); ?></div>
       
  <div class="span5">  <?php echo $form->textFieldRow(
           $model['c'],'city',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
       ); ?></div>
     
       <div class="span5">  <?php 
	   echo $form->dropDownListRow($model['c'], 'state', Library::getStates());
	   /*echo $form->textFieldRow(
           $model['c'],'state',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
       ); */
	   ?></div>
     
       <div class="span5">  <?php echo $form->textFieldRow(
           $model['c'],'landline',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
       ); ?></div>
     
       <div class="span5">  <?php  
       echo $form->radioButtonListRow($model['c'], 'status',array('1'=>'Enable','0'=>'Disable'));
       ?></div>

		<?php  if(!$model['c']->approved){?>
	   <div class="span5">  <?php  
       echo $form->radioButtonListRow($model['c'], 'approved',array('1'=>'Enable','0'=>'Disable'));
       ?></div>
	   <?php }?>
    
     	<div class="span5">  <?php
		echo $form->radioButtonListRow($model['c'], 'enable_sms_email_ads',array('1'=>'Enable','0'=>'Disable'));
	?></div>
     
	 <?php if((int)$_GET['id']){?>
        <div class="span5">  <div class="control-group"><label for="Customer_password" class="control-label">Rating</label><div class="controls">
         <?php echo $model['c']->rating;?>
             </div></div></div><?php }?>
 
       </div>
   </fieldset>


  </div>
  

  </div>
  
  </div>