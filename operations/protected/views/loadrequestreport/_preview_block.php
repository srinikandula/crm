<?php
			$this->widget('bootstrap.widgets.TbExtendedGridView', array(
            'type' => 'striped bordered condensed',
            'summaryText' => 'Displaying {start}-{end} of {count} Results.',
            'enablePagination' => true,
            'ajaxUpdate' => true,
			'template' => "{items}{summary}{pager}",
            'id' => 'productinfo-grid0',
            'dataProvider' => $model,
            'columns' => array(
                array(
                    'header' => 'S.No',
                    'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
                ),
                //array('name' => 'name', 'header' => Yii::t('dashboard','column_name'), 'value' => array($this,'gridBestSellers')),
                //array('name' => 'model', 'header' => Yii::t('dashboard','column_model'), 'value' => array($this,'gridBestSellers')),
                array('name' => 'title', 'header' => 'Customer', 'value' => '$data->title'),
                array('name' => 'truck_reg_no', 'header' => 'Truck Reg No', 'value' => '$data->truck_reg_no'),
                array('name' => 'source_address', 'header' => 'Source Address', 'value' => '$data->source_address'),
                array('name' => 'destination_address', 'header' => 'Destination Address', 'value' => '$data->destination_address'),
                array('name' => 'date_created', 'header' => 'Date Created', 'value' => '$data->date_created'),
                array('name' => 'date_available', 'header' => 'Date Available', 'value' => '$data->date_available'),
                array('name' => 'id_goods_type', 'header' => 'Goods Type', 'value' => '$data->id_goods_type'),
                array('name' => 'id_truck_type', 'header' => 'Truck Type', 'value' => '$data->id_truck_type'),
                array('name' => 'add_info', 'header' => 'Comment', 'value' => '$data->add_info'),
                array('name' => 'status', 'header' => 'status', 'value' => '$data->status'),
                array('name' => 'approved', 'header' => 'approved', 'value' => '$data->approved'),
            ),
        ));
        ?>