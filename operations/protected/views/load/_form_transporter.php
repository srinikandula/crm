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
                    <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'source_address', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                       <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'destination_address', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                        
                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'expected_price', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );?></div>
     <div class="span5" id="price_comment">
                                   
 <?php echo $form->textAreaRow(
           $model['ltr'],'expected_price_comment',array('rows' => 3, 'cols' => 70)
         ); ?>
                  
     
             
             
     </div>
                    
                    
                    <div class="span5">   <?php
                        $list = CHtml::listData(Goodstype::model()->findAll(array('condition' => 'status=1 order by title asc')), 'id_goods_type', 'title');
                        echo $form->dropDownListRow($model['ltr'], 'id_goods_type', $list);
                            ?> </div>

                        <div class="span5">   <?php
                        $list = CHtml::listData(Trucktype::model()->findAll(array('condition' => 'status=1 order by title asc')), 'id_truck_type', 'title');
                        echo $form->dropDownListRow($model['ltr'], 'id_truck_type', $list);
                        ?> </div>


                        <div class="span5">  <?php
                            echo $form->dropDownListRow($model['ltr'], 'tracking', array('1' => 'Yes', '0' => 'No'));
                            ?></div>
                        <div class="span5">   <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'date_required', array('class' => 'datetimepicker', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            
                                    );?></div>
                    

			<div class="span5">   <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'pickup_point', array( 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );?></div>
                        <div class="span5">  <?php
                            echo $form->dropDownListRow($model['ltr'], 'insurance', array('1' => 'Yes', '0' => 'No'));
                            ?></div>
                    <div class="span5">  <?php echo $form->textAreaRow(
           $model['ltr'],'comment',array('rows' => 3, 'cols' => 30)
       ); ?></div>
                        <div class="span5">   
                        <div class="control-group">
                                <label class="control-label required">Status</label>
                                <div class="controls"><?php echo $model['ltr']->status;?></div>
                            </div>
                        </div>
                    <div class="span5">   
                        <div class="control-group">
                            <input type="checkbox" id="dlg" name="reason" value="Cancel"> Cancel request</br>
                          </div>
                        </div>
               
<div id="box">
    <div id="content1" style="padding:10px" >

<?php echo $form->textAreaRow(
           $model['ltr'],'cancel_reason',array('rows' => 3, 'cols' => 50)
       ); ?>
    <form id="fb" action="" method="post" >
    <input type="submit" class=button id="ok" style="margin-left:300px;" value="Submit" />
</form>
<input type="button" class=button id="cancel" name="cancelbtn" style="margin-left:14px" value="Cancel" />
    </div>
    
</div>
                    
                    
    
                   
        </div>
            </fieldset>
        </div>

<?php if ($action != 'create' && sizeof($model['ltrq'])) { echo $this->renderPartial('_form_comments_block', array('form'=>$form,'model'=>$model),true);}?>
<?php if ($action != 'create' && sizeof($model['ltrq'])) { echo $this->renderPartial('_form_quotes_block', array('form'=>$form,'model'=>$model),true);}?>

    </div></div>
<!-- <script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places" type="text/javascript"></script> -->
    <script type='text/javascript'>
        var input = document.getElementById('Loadtruckrequest_source_address');
        var autocomplete = new google.maps.places.Autocomplete(input);

        var input1 = document.getElementById('Loadtruckrequest_destination_address');
        var autocomplete = new google.maps.places.Autocomplete(input1);
        
        
        </script>
<script src="<?php echo Yii::app()->params['config']['admin_url']; ?>js/datetime/jquery.datetimepicker.js"></script>
<script type="text/javascript">
$('.datetimepicker').datetimepicker({
                        dayOfWeekStart: 1,
                        lang: 'en',
                        format: 'Y-m-d H:i',
                        startDate: '<?php echo date('Y/m/d'); ?>'	//'2015/09/01'
                    });
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
$(document).ready(function(){

$("#dlg").click(function(){
   //$("#box").css("display","block");
   $("#box").toggle();
});
$("#ok").click(function(){
  $("#box").css("display","none");
});
$("#cancel").click(function(){
  $("#box").css("display","none");
  $('input:checkbox').removeAttr('checked');
});
$("#Loadtruckrequest_expected_price").click(function(){
        $("#price_comment").css("display","block");
    });
    $("#cancel1").click(function(){
  $("#price_comment").css("display","none");
});
$("#cancel2").click(function(){
  $("#price_comment").css("display","none");
});
});
function ClearFields() {

     document.getElementById("Loadtruckrequest_expected_price_comment").value = "";
     
}
</script>    
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script>
  $(function() {
    $( "#box,#price_comment" ).draggable();
  });
  </script>