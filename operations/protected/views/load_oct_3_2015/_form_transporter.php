<?php //echo Yii::app()->controller->id." and ".Yii::app()->controller->action->id."<br/>";exit;
$action = Yii::app()->controller->action->id;
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['config']['admin_url']; ?>js/datetime/jquery.datetimepicker.css"/>
<div id="notification"></div>
<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span<?php echo $_SESSION['id_admin_role']==10?'6':'12';?>"  >
            <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                    <div class="span11">Details </div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line1">
                        <div class="span5">  
                            	<div class="control-group">
                                <label class="control-label required" for="Loadtruckrequest_source_address">Source Address</label>
                                <div class="controls"><?php echo $model['ltr']->source_address;?></div>
                            </div>
                        </div>    

                        <div class="span5">  
                        <div class="control-group">
                                <label class="control-label required" for="Loadtruckrequest_source_address">Destination Address</label>
                                <div class="controls"><?php echo $model['ltr']->destination_address;?></div>
                            </div>
                        </div>
                        
                        <div class="span5">  
                        <div class="control-group">
                                <label class="control-label required" for="Loadtruckrequest_expected_price">Expected Price</label>
                                <div class="controls"><?php echo $model['ltr']->expected_price;?></div>
                            </div>
                        </div>

                        <div class="span5">   <?php
                        $list = CHtml::listData(Goodstype::model()->findAll(array('condition' => 'status=1 order by title asc')), 'id_goods_type', 'title');
                        echo $form->dropDownListRow($model['ltr'], 'id_goods_type', $list);
                            ?> </div>

                        <div class="span5">   <?php
                        $list = CHtml::listData(Trucktype::model()->findAll(array('condition' => 'status=1 order by title asc')), 'id_truck_type', 'title');
                        echo $form->dropDownListRow($model['ltr'], 'id_truck_type', $list);
                        ?> </div>


                        <div class="span5">  <div class="control-group">
                                <label class="control-label required" for="Loadtruckrequest_source_address">Tracking Required</label>
                                <div class="controls"><?php echo $model['ltr']->tracking==1?'Yes':'No';?></div>
                            </div></div>
                        <div class="span5">   
                        <div class="control-group">
                                <label class="control-label required" for="Loadtruckrequest_source_address">Date Required</label>
                                <div class="controls"><?php echo $model['ltr']->date_required;?></div>
                            </div>
                        </div>

						<div class="span5">   
                        <div class="control-group">
                                <label class="control-label required" for="Loadtruckrequest_source_address">Pickup Point</label>
                                <div class="controls"><?php echo $model['ltr']->pickup_point;?></div>
                            </div>
                        </div>
                        <div class="span5">  <div class="control-group">
                                <label class="control-label required" for="Loadtruckrequest_source_address">Insurance Required</label>
                                <div class="controls"><?php echo $model['ltr']->insurance==1?'Yes':'No';?></div>
                            </div></div>

						<div class="span5">   
                        <div class="control-group">
                                <label class="control-label required" for="Loadtruckrequest_source_address">Response Time/Comment</label>
                                <div class="controls"><?php echo $model['ltr']->comment;?></div>
                            </div>
                        </div>

                        <div class="span5">  <?php
                            echo $form->dropDownListRow($model['ltr'], 'status', Library::getLTRStatuses());
                            ?></div>
        </div>
            </fieldset>
        </div>

<?php if ($action != 'create' && sizeof($model['ltrq'])) { echo $this->renderPartial('_form_comments_block', array('form'=>$form,'model'=>$model),true);}?>
<?php if ($action != 'create' && sizeof($model['ltrq'])) { echo $this->renderPartial('_form_quotes_block', array('form'=>$form,'model'=>$model),true);}?>

    </div></div>
