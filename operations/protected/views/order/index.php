    <?php $this->widget('ext.Flashmessage.Flashmessage'); ?> 
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'gridForm',
        'method'=>'get',
        'enableAjaxValidation' => false,
    ));
    ?>
    <?php $this->renderPartial('_search',array('block'=>$block,'model'=>$model,'form'=>$form)); ?>

<div class="span12 top_box_fixed">
<div class="row-fluid grid-menus span12 pull-left ">
        <div class="span12">
            <div class="span10 buttons_top">
                <?php Library::addButton(array('label' => 'Create', 'url' => $this->createUrl('create'))); ?>
                <?php //Library::buttonBulkApprove(array('type'=>'info','label'=>'Approve','permission'=>$this->deletePerm,'url'=> $this->createUrl($this->uniqueid . "/approve")));?>
                <?php Library::buttonBulk(array('label' => 'Delete', 'permission' => $this->deletePerm, 'url' => $this->createUrl($this->uniqueid . "/delete"))); ?>
                <?php //Library::buttonBulk(array('label'=>'Approve Customers','permission'=>$this->editPerm,'type'=>'info','url'=> $this->createUrl($this->uniqueid . "/approve")));?>
				<?php Library::addButton(array('label' => 'Donwload Order Advance','permission'=>true, 'url' => $this->createUrl('downloadPendingOrderAdvance'))); ?>
            </div>
            <div class="span2 dropdown_cut_main pull-right">
                <div class="span7 pull-right">
                    <?php Library::getPageList(array('totalItemCount' => $dataSet->totalItemCount)); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span12 top_box_margin">



        <?php

        $this->widget('bootstrap.widgets.TbExtendedGridView', array(
            'type' => 'striped bordered condensed',
            'template' => "{summary}{pager}<div>{items}</div>",
            'summaryText' => 'Displaying {start}-{end} of {count} Results.',
            'enablePagination' => true,
            'pager' => array('class' => 'CListPager', 'header' => '&nbsp;Page', 'id' => 'no-widthdesign_left'),
            'ajaxUpdate' => false,
            'id' => 'productinfo-grid',
            'dataProvider' => $dataSet,
            'filter' => $model,
            'bulkActions' => array(
                'actionButtons' => array(
                ),
                'checkBoxColumnConfig' => array(
                    'name' => 'id',
                    'id' => 'id',
                    'value' => '$data->primaryKey',
                ),
            ),
			'rowCssClassExpression' => '( $row%2 ? $this->rowCssClass[1] : $this->rowCssClass[0] ).( ($data->load_owner_advance_recv == 0 ) ?" alert":""). ( ((strtotime($data->date_ordered)+864000<strtotime(now)) && $data->date_pod_received=="0000-00-00" ) ?" plan_exp5":"")',
            'columns' => array(
                array(
                    'header' => 'S.No',
                    'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
                ),
				array('name' => 'id_order', 'header' => 'Order Id', 'value' => array($this,'grid'),'type'=>'raw','filter'=>false),
                //array('name' => 'id_order', 'header' => 'Order Id', 'value' => array($this,'grid'),'type'=>'raw'),
				array('name' => 'id_order', 'header' => 'Order Id', 'value' => '$data->id_order','type'=>'raw'),
				array('name' => 'admin_created', 'header' => 'Admin Created', 'value' => '$data->admin_created'),
                array('name' => 'date_ordered', 'header' => 'Date Ordered', 'value' => '$data->date_ordered'),
                array('name' => 'date_available', 'header' => 'Loading Date', 'value' => '$data->date_available'),
				array('name' => 'date_created', 'header' => 'Date Created', 'value' => 'date("Y-m-d",strtotime($data->date_created))'),
                array('name' => 'source_address', 'header' => 'Source', 'value' => 'substr($data->source_address,0,-7)'),
                array('name' => 'destination_address', 'header' => 'Destination', 'value' => 'substr($data->destination_address,0,-7)'),
                array('name' => 'amount', 'header' => 'Amount', 'value' => '$data->amount'),
				array('name' => 'id_order_status', 'header' => 'Status', 'value' => '$data->order_status_name','filter'=>$orderStatus),
                array('name' => 'message', 'header' => 'Comment', 'value' => array($this,'grid'),'type'=>'raw'),
                array('name' => 'orderperson_fullname', 'header' => 'Load Owner', 'value' => '$data->orderperson_fullname'),
                array('name' => 'orderperson_mobile', 'header' => 'Load Mobile', 'value' => '$data->orderperson_mobile'),
                array('name' => 'customer_fullname', 'header' => 'Truck Owner', 'value' => '$data->customer_fullname'),
                array('name' => 'customer_mobile', 'header' => 'Truck Mobile', 'value' => '$data->customer_mobile'),
                //array('name' => 'orderperson_email', 'header' => 'Email', 'value' => '$data->orderperson_email'),
                array('name' => 'driver_name', 'header' => 'Driver Name', 'value' => '$data->driver_name'),
                array('name' => 'driver_mobile', 'header' => 'Driver Mobile', 'value' => '$data->driver_mobile'),
                array('name' => 'truck_reg_no', 'header' => 'Truck Reg No', 'value' => '$data->truck_reg_no'),
                //array('name'=>'amount','header'=>'amount','value'=>array($this,'grid')),
                array('name' => 'truck_type', 'header' => 'Truck Type', 'value' => '$data->truck_type'),
				array('name' => 'billing', 'header' => 'Lo Bill', 'value' => '$data->billing'),
				array('name' => 'tobilling', 'header' => 'To Bill', 'value' => '$data->tobilling'),
				array('name' => 'truck_mileage', 'header' => 'Net Profit', 'value' => '$data->billing-$data->tobilling'),
				array('name' => 'truck_owner_commission', 'header' => 'Commission', 'value' => '$data->truck_owner_commission'),
                //array('name'=>'load_type','header'=>'Load Type','value'=>'$data->load_type'),
                //array('name' => 'goods_type', 'header' => 'Goods Type', 'value' => '$data->goods_type','filter'=>false),
                //array('name'=>'customer_type','header'=>'Customer Type','filter'=>CHtml::dropDownList('Order[customer_type]', $_GET['Order']['customer_type'],array(''=>'All','1'=>'Enable','0'=>'Disable',)), 'value'=>'$data->customer_type==1?"Enabled":"Disabled"'),
                //array('name'=>'approved','header'=>'Approved','filter'=>CHtml::dropDownList('Customer[approved]', $_GET['Customer']['approved'],array(''=>'All','1'=>'Approved','0'=>'Un-Approved',)), 'value'=>'$data->approved==1?"Approved":"Un-Approved"'),
                array('class' => 'bootstrap.widgets.TbButtonColumn',
                    'htmlOptions' => array('style' => 'min-width:50px;'),
                    'template' => $this->gridPerm['template'],
                    'buttons' => array('update' => array('label' => $this->gridPerm[buttons][update][label], 'url' => 'Yii::app()->createUrl(Yii::app()->controller->id."/update/",array("backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))'), 'delete' => array('url' => ' Yii::app()->createUrl(Yii::app()->controller->id."/delete/",array("backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))')),
                ),
            ),
        ));
        ?>
        <input type="hidden" name="backurl" value="<?php echo base64_encode(urldecode(Yii::app()->request->requestUri)); ?>">
        <?php $this->endWidget(); ?>
    </div>
</div>

<div class="clearfix"></div>	
</div>
<div class="clearfix"></div>	
</div>
<?php Yii::app()->clientScript->registerCoreScript('jquery.ui');?>

<script type="text/javascript">
  /*$(".items tbody tr").live("click", function(){
  window.location.href = $(this).find(".grid_link").attr("href");
});*/

//$('#Order_date_ordered').attr('placeholder','From,To');
//$('#Order_date_available').attr('placeholder','From,To');
jQuery('#date_available_from').datepicker({'dateFormat': 'yy-mm-dd', 'altFormat': 'dd-mm-yy', 'changeMonth': 'true', 'changeYear': 'true'});
jQuery('#date_available_to').datepicker({'dateFormat': 'yy-mm-dd', 'altFormat': 'dd-mm-yy', 'changeMonth': 'true', 'changeYear': 'true'});
jQuery('#date_ordered_from').datepicker({'dateFormat': 'yy-mm-dd', 'altFormat': 'dd-mm-yy', 'changeMonth': 'true', 'changeYear': 'true'});
jQuery('#date_ordered_to').datepicker({'dateFormat': 'yy-mm-dd', 'altFormat': 'dd-mm-yy', 'changeMonth': 'true', 'changeYear': 'true'});
</script>