<div class="span12 top_box_fixed">
    <?php    $this->widget('ext.Flashmessage.Flashmessage'); ?>
    <?php
    $form = $this->beginWidget('CActiveForm',
            array(
        'id' => 'gridForm',
        'enableAjaxValidation' => false,
    ));
    ?>
    <div class="row-fluid grid-menus span12 pull-left ">
        <div class="span12">
            <div class="span10 buttons_top">
            <?php Library::addButton(array('label'=>'Create','url'=> $this->createUrl('create')));  ?>
            <?php Library::buttonBulk(array('label'=>'Delete','permission'=>$this->deletePerm,'url'=> $this->createUrl($this->uniqueid . "/delete")));?>
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
        $this->widget('bootstrap.widgets.TbExtendedGridView',
                array(
            'type' => 'striped bordered condensed',
            'template' => "{summary}{pager}{items}",
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
                array('name' => 'accountID', 'header' => 'Account ID', 'value' => '$data->accountID'),
                array('name' => 'deviceID', 'header' => 'Device ID', 'value' => '$data->deviceID'),
                array('name' => 'vehicleModel', 'header' => 'Vehicle Model', 'value' => '$data->vehicleModel'),
				array('name' => 'vehicleType', 'header' => 'Vehicle Type', 'value' => array($this,'grid'),'filter' => CHtml::dropDownList('GpsDevice[vehicleType]',
                            $_GET['GpsDevice']['vehicleType'],
                            array('' => 'All','TK' => 'Truck', 'TR' => 'Transporter', 'NTK' => 'Non Truck')
                    )),
				//array('name' => 'lastOdometerKM', 'header' => 'Vehicle Type', 'value' => '$data->lastOdometerKM'),
				array('name' => 'simPhoneNumber', 'header' => 'SIM Phone No', 'value' => '$data->simPhoneNumber'),
                array('name' => 'imeiNumber', 'header' => 'imeiNumber', 'value' => '$data->imeiNumber'),
				array('name' => 'isActive', 'header' => 'Status',
                    'filter' => CHtml::dropDownList('GpsDevice[isActive]',
                            $_GET['GpsDevice']['isActive'],
                            array('' => 'All', '1' => 'Enable', '0' => 'Disable',)
                    ), 'value' => '$data->isActive==1?"Enabled":"Disabled"'),
                array('class' => 'bootstrap.widgets.TbButtonColumn',
                    'htmlOptions' => array('style' => 'min-width:50px;'),
                    'template' => $this->gridPerm['template'],
                    'buttons' => array('update' => array('label' => $this->gridPerm[buttons][update][label],
                            'url' => 'Yii::app()->createUrl(Yii::app()->controller->id."/update/",array("ids"=>$data->primaryKey,"backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))'),
                        'delete' => array('url' => ' Yii::app()->createUrl(Yii::app()->controller->id."/delete/",array("ids"=>$data->primaryKey,"backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))')),
                ),
            ),
        ));
        ?>
        <input type="hidden" name="backurl" value="<?php echo base64_encode(urldecode(Yii::app()->request->requestUri)); ?>">
<?php $this->endWidget(); ?>
    </div>
</div>
<div class="clearfix"></div>
