<div class="span12 top_box_fixed">
    <?php $this->widget('ext.Flashmessage.Flashmessage'); ?> 
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'gridForm',
        'enableAjaxValidation' => false,
    ));
    ?>
    <div class="row-fluid grid-menus span12 pull-left ">
        <div class="span12">
            <div class="span10 buttons_top">
                <?php Library::addButton(array('label' => 'Create', 'url' => $this->createUrl('create'))); ?>
                <?php //Library::buttonBulkApprove(array('type'=>'info','label'=>'Approve','permission'=>$this->deletePerm,'url'=> $this->createUrl($this->uniqueid . "/approve")));?>
                <?php Library::buttonBulk(array('label' => 'Delete', 'permission' => $this->deletePerm, 'url' => $this->createUrl($this->uniqueid . "/delete"))); ?>
                <?php //Library::buttonBulk(array('label'=>'Approve Customers','permission'=>$this->editPerm,'type'=>'info','url'=> $this->createUrl($this->uniqueid . "/approve")));?>
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
//exit;
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
            'columns' => array(
                array(
                    'header' => 'S.No',
                    'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
                ),
                array('name' => 'id_order', 'header' => 'Order Id', 'value' => array($this,'grid'),'type'=>'raw'),
                array('name' => 'date_ordered', 'header' => 'Date Ordered', 'value' => '$data->date_ordered'),
                array('name' => 'date_available', 'header' => 'Loading Date', 'value' => '$data->date_available'),
                array('name' => 'source_address', 'header' => 'Source', 'value' => '$data->source_address'),
                array('name' => 'destination_address', 'header' => 'Destination', 'value' => '$data->destination_address'),
                array('name' => 'orderperson_fullname', 'header' => 'Customer Name', 'value' => '$data->orderperson_fullname'),
                array('name' => 'orderperson_mobile', 'header' => 'Mobile', 'value' => '$data->orderperson_mobile'),
                array('name' => 'orderperson_email', 'header' => 'Email', 'value' => '$data->orderperson_email'),
                array('name' => 'driver_name', 'header' => 'Driver Name', 'value' => '$data->driver_name'),
                array('name' => 'driver_mobile', 'header' => 'Driver Mobile', 'value' => '$data->driver_mobile'),
                //array('name'=>'amount','header'=>'amount','value'=>array($this,'grid')),
                array('name' => 'truck_type', 'header' => 'Truck Type', 'value' => '$data->truck_type'),
                //array('name'=>'load_type','header'=>'Load Type','value'=>'$data->load_type'),
                array('name' => 'goods_type', 'header' => 'Goods Type', 'value' => '$data->goods_type'),
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