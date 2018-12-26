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
                array('name' => 'id_customer', 'header' => 'Customer/GPS', 'value' => '$data->fullname."/".$data->gps_account_id','filter'=>false),
				array('name' => 'mobile', 'header' => 'Mobile', 'value' => '$data->mobile','filter'=>false),
				array('name' => 'id_admin', 'header' => 'Process By', 'value' => '$data->id_admin','filter'=>false),
				array('name' => 'vehicle_number', 'header' => 'Vehicle Number', 'value' => '$data->vehicle_number'),
                array('name' => 'idv', 'header' => 'IDV', 'value' => '$data->idv','filter'=>false),
                array('name' => 'age', 'header' => 'Vehicle Age', 'value' => '$data->age','filter'=>false),
				array('name' => 'ncb', 'header' => 'NCB', 'value' => '$data->ncb','filter'=>false),
				array('name' => 'imt', 'header' => 'IMT 23', 'value' => '$data->imt==1?"Yes":"No"','filter'=>false),
				array('name' => 'weight', 'header' => 'Gross Weight', 'value' => '$data->weight','filter'=>false),
                /*array('name' => 'status', 'header' => 'Gross Weight',
                    'filter' => CHtml::dropDownList('Franchise[status]',
                            $_GET['Franchise']['status'],
                            array('' => 'All', '1' => 'Enable', '0' => 'Disable',)
                    ), 'value' => '$data->status==1?"Enabled":"Disabled"'),*/
				array('name' => 'pa_owner_driver', 'header' => 'PA Onwer/Driver', 'value' => '$data->pa_owner_driver==1?"Yes":"No"','filter'=>false),
				array('name' => 'nil_dep', 'header' => 'Nil Dep', 'value' => '$data->nil_dep==1?"Yes":"No"','filter'=>false),
				array('name' => 'file', 'header' => 'File','type'=>'raw', 'value' => array($this,'grid'),'filter'=>false),
				array('name' => 'status', 'header' => 'Status', 'value' => '$data->status'),
				array('name' => 'date_created', 'header' => 'Date Created', 'value' => '$data->date_created'),
                array('class' => 'bootstrap.widgets.TbButtonColumn',
                    'htmlOptions' => array('style' => 'min-width:50px;'),
                    'template' => $this->gridPerm['template'],
                    'buttons' => array('update' => array('label' => $this->gridPerm[buttons][update][label],
                            'url' => 'Yii::app()->createUrl(Yii::app()->controller->id."/update/",array("backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))'),
                        'delete' => array('url' => ' Yii::app()->createUrl(Yii::app()->controller->id."/delete/",array("backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))')),
                ),
            ),
        ));
        ?>
        <input type="hidden" name="backurl" value="<?php echo base64_encode(urldecode(Yii::app()->request->requestUri)); ?>">
<?php $this->endWidget(); ?>
    </div>
</div>
<div class="clearfix"></div>