
<div class="row-fluid">
  <div class="tab-pane active" id="Information">
	 <div class="span12 pull-left">
		<div class="span12">
			<fieldset class="portlet" >
             
             
  <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
<div class="span11">Administrator Details</div>
<div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" id="hide_box_btn2" type="button" ></button> </div>
<div class="clearfix"></div>
</div>
 <div class="portlet-content" id="hide_box_line1">
 
    <div class="span5"> <?php 
		echo $form->textFieldRow(
           $model,'first_name',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
		); 
		?></div>
        
	 <div class="span5">  <?php echo $form->textFieldRow(
           $model,'last_name',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
		); ?></div>
        
	 <div class="span5">  <?php 
         if((int)$_GET['id']){
         echo $form->textFieldRow(
           $model,'email',
           array('readonly'=>true,'rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)); 
         }else{
         echo $form->textFieldRow(
           $model,'email',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",));
         }
         ?></div>
        
	 <div class="span5">  
	 <?php 
	 if(Yii::app()->controller->action->id=='update'){  
		echo $form->passwordFieldRow(
           $model,'password',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",'value'=>'')
        );
	}else{
		echo $form->passwordFieldRow(
           $model,'password',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right"));
	}
	?></div>
	<div class="span5">  <?php echo $form->passwordFieldRow(
           $model,'confirm',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
        ); ?></div>	
	<div class="span5">  <?php echo $form->textFieldRow(
           $model,'phone',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
        ); ?></div>
		<div class="span5">  <?php echo $form->textFieldRow(
           $model,'city',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
        ); ?></div>
	<div class="span5">  <?php echo $form->dropDownListRow($model,'state', Library::getStates(), array('prompt'=>'Select State'));
       ?></div>	

	 <div class="span5">  <?php 
		 //echo '<pre>';print_r($_SESSION);	echo '</pre>';
		 $cond=$_SESSION['id_franchise']==1?'':'id_franchise="'.$_SESSION['id_franchise'].'"';
		 $list=CHtml::listData(Franchise::model()->findAll($cond),'id_franchise','account');
		 echo $form->dropDownListRow($model,'id_franchise', $list, array('prompt'=>'Select Franchise'));
       ?> </div>
	 
	 <?php //if($model->id_admin_role==1 or $model->id_admin_role==2){?>
	 <?php if($_SESSION['id_franchise']==1 && (int)$_GET['id']!=0 && $model->id_franchise!=1 && $model->id_admin_role!=2){ $statusHide="style='display:none'";} ?>
	 <div class="span5" <?php echo $statusHide;?> >  <?php echo $form->radioButtonListRow($model,'status',array('1'=>'Enable','0'=>'Disable') ); 
	 
		  $list=CHtml::listData(AdminRole::getAdminRole(),'id_admin_role','role');
         ?></div>
	 <div class="span5" <?php echo $statusHide;?> >  <?php echo $form->dropDownListRow($model,'id_admin_role', $list, array('prompt'=>'Select Admin Role'));
       ?> </div>

	   <?php //}?>
       </div>
       
       
               </fieldset>
            </div>  
 		</div>
	</div>
</div>