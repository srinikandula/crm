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
                array('name' => 'idprefix', 'header' => 'Customer Id', 'value' => '$data->idprefix','filter'=>false),
				array('name' => 'fullname', 'header' => 'Customer Name', 'value' => '$data->fullname'),
				array('name' => 'mobile', 'header' => 'Customer Mobile', 'value' => '$data->mobile'),
				array('name' => 'gps_account_id', 'header' => 'Gps', 'value' => '$data->gps_account_id'),
				array('name' => 'card_customer_no', 'header' => 'Customer No', 'value' => '$data->card_customer_no'),
				array('name' => 'card_status', 'header' => 'Card Status', 'value' => '$data->card_status'),
				array('name' => 'date_fuel_card_applied', 'header' => 'Date Applied', 'value' => '$data->date_fuel_card_applied'),
                array('name' => 'card_username', 'header' => 'Username', 'value' => '$data->card_username'),
				array('name' => 'id_franchise', 'header' => 'Franchise','filter' => CHtml::dropDownList('Customer[id_franchise]',
                            $_GET['Customer']['id_franchise'],CHtml::listData(Franchise::model()->findAll(), 'id_franchise', 'account'),array('prompt'=>'All')
                    ), 'value' => '$data->franchise_account."-".$data->franchise_fullname'),
				//array('name' => 'id_franchise', 'header' => 'Franchise', 'value' => '$data->id_franchise'),
                /*array('name' => 'status', 'header' => 'Status',
                    'filter' => CHtml::dropDownList('Franchise[status]',
                            $_GET['Franchise']['status'],
                            array('' => 'All', '1' => 'Enable', '0' => 'Disable',)
                    ), 'value' => '$data->status==1?"Enabled":"Disabled"'),*/
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