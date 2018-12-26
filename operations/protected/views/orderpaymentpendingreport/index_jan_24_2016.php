<div class="span12 top_box_fixed">
<div class="row-fluid grid-menus span12 pull-left ">
        <div class="span12">
            <div class="span10 buttons_top">
                <?php Library::addButton(array('label' => 'Download Report','permission'=>true, 'url' => $this->createUrl('download'))); ?>
            </div>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details"> 
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'gridForm',
            'enableAjaxValidation' => false,
        ));
        ?>

        <?php
        $this->widget('bootstrap.widgets.TbExtendedGridView', array(
            'type' => 'striped bordered condensed',
            //'type'=>'striped',
            'template' => "{summary}{pager}<div >{items}</div>",
            'summaryText' => 'Displaying {start}-{end} of {count} Results.',
            'enablePagination' => true,
            //'pager' => array('class' => 'CListPager', 'header' => '&nbsp;Page', 'id' => 'no-widthdesign_left'),
            'ajaxUpdate' => false,
            'id' => 'productinfo-grid',
            'dataProvider' => $dataProvider,
            'filter' => $model,
            'columns' => array(
                array(
                    'header' => 'S.No',
                    'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
                ),
                array('name' => 'id_order', 'header' => "Id", 'value' => array($this,'grid'),'type'=>'raw'),
                array('name' => 'orderperson_fullname', 'header' => "Load Owner", 'value' => '$data[orderperson_fullname]'),
                array('name' => 'orderperson_mobile', 'header' => "Mobile", 'value' => '$data[orderperson_mobile]'),
                array('name' => 'customer_fullname', 'header' => "Truck Owner", 'value' => '$data[customer_fullname]'),
                array('name' => 'customer_mobile', 'header' => "Mobile", 'value' => '$data[customer_mobile]'),
                array('name' => 'source_city', 'header' => "Source", 'value' => '$data[source_city]'),
                array('name' => 'destination_city', 'header' => "Destination", 'value' => '$data[destination_city]'),
                array('name' => 'date_ordered', 'header' => "Date Ordered", 'value' => '$data[date_ordered]'),
                array('name' => 'truck_attachment_policy_title', 'header' => "Plan", 'value' => '$data[truck_attachment_policy_title]'),
                array('name' => 'truck_reg_no', 'header' => "Truck No", 'value' => '$data[truck_reg_no]'),
                array('name' => 'truck_type', 'header' => "Truck Type", 'value' => '$data[truck_type]'),
                array('name' => 'order_status_name', 'header' => "Status", 'value' => '$data[order_status_name]'),
                array('name' => 'amount', 'header' => "Amount", 'value' => '$data[amount]'),
                //array('name' => 'transaction', 'header' => "Transaction", 'value' => '$data[transaction]'),
                //array('name' => 'billing', 'header' => "Billing", 'value' => '$data[billing]'),
                array('name' => 'billing', 'header' => "TR Payslip", 'value' => array($this,'grid'),'filter'=>false),
                array('name' => 'transaction', 'header' => "TR Due", 'value' => array($this,'grid'),'filter'=>false),
                array('name' => 'trlastpaid', 'header' => "TR Last Paid", 'value' => '$data[trlastpaid]','filter'=>false),
                array('name' => 'tobilling', 'header' => "TO Payslip", 'value' => array($this,'grid'),'filter'=>false),
                array('name' => 'totransaction', 'header' => "TO Due", 'value' => array($this,'grid'),'filter'=>false),
                array('name' => 'tolastpaid', 'header' => "TO Last Paid", 'value' => '$data[tolastpaid]','filter'=>false),
            ),
        ));
        ?>

        <?php $this->endWidget(); ?>
    </div>
</div>