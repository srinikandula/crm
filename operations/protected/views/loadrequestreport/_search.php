<?php
/* @var $this ProductinfoController */
/* @var $model Productinfo */
/* @var $form CActiveForm */
?>

<div class="wide form row-fluid fileter_div_main">
    <div class="row-fluid design_dsm">
    <div class="span2 ">
        <div class="stat-block">
            <ul>
                <li class="stat-count"><span><?php echo $block['canceled_requests']['id_load_truck_request'];?></span><span><a href="<?php echo Yii::app()->request->requestUri.$this->getPrefix.'data='.base64_encode($block['canceled_requests']['title'])?>">Canceled Requests</a></span></li>
            </ul>
        </div>
    </div>
    <!--<div class="span2 ">
        <div class="stat-block">
            <ul>
                <li class="stat-count"><span><?php echo $block['booked_requests']['id_load_truck_request'];?></span><span><a href="<?php echo Yii::app()->request->requestUri.$this->getPrefix.'data='.base64_encode($block['booked_requests']['title'])?>">Booked Requests</a></span></li>
            </ul>
        </div>
    </div>-->
    </div>
 <?php
    $form = $this->beginWidget('CActiveForm',
        array(
        'id' => 'gridForm',
        'action'=>'',    
        'method'=>'get',        
        'enableAjaxValidation' => false,
    ));
    ?>
<?php echo $form->textField($model,'date_created_from',array('id'=>'date_created_from','placeholder'=>'Date From','class'=>"span2 date")); ?>
<?php echo $form->textField($model,'date_created_to',array('id'=>'date_created_to','placeholder'=>'Date To','class'=>"span2 date")); ?>
<?php echo $form->textField($model,'source_address',array('id'=>'source_address','placeholder'=>'Source Address','class'=>"span2")); ?>
<?php echo $form->textField($model,'destination_address',array('id'=>'destination_address','placeholder'=>'Destination Dddress','class'=>"span2")); ?>
<?php //echo $form->dropdownlist($model,'status',Library::getLTRStatuses(),array("prompt"=>"All Status",'class'=>"span2")); ?>
<?php echo $form->dropdownlist($model,'group_by',Library::getGroupBy(),array('class'=>"span2")); ?>
<?php echo $form->dropdownlist($model,'id_truck_type',Trucktype::model()->getTruckTypes(),array("prompt"=>"All Truck Types",'class'=>"span2")); ?>
<?php echo CHtml::button('Go',array('class'=>'btn btn-info','onclick'=>'fnsubmit()')); ?>
<?php $this->endWidget(); ?> 
</div><!-- search-form -->
<script>
function fnsubmit(){
    var addr='Truckloadrequest[date_created_from]='+$("#date_created_from").val()+'&Truckloadrequest[date_created_to]='+$("#date_created_to").val()+'&Truckloadrequest[source_address]='+$("#source_address").val()+'&Truckloadrequest[destination_address]='+$("#destination_address").val()+'&Truckloadrequest[group_by]='+$("#Truckloadrequest_group_by").val()+'&Truckloadrequest[id_truck_type]='+$("#Truckloadrequest_id_truck_type").val();
    window.location='?'+addr;
    //alert(addr);
}
</script>