<?php
			$this->widget('bootstrap.widgets.TbExtendedGridView', array(
            'type' => 'striped bordered condensed',
            'summaryText' => 'Displaying {start}-{end} of {count} Results.',
            'enablePagination' => true,
            'ajaxUpdate' => true,
            'id' => 'productinfo-grid0',
            'dataProvider' => $model['bestSellers']->bestSellers(),
            'columns' => array(
                array(
                    'header' => 'S.No',
                    'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
                ),
                array('name' => 'name', 'header' => Yii::t('dashboard','column_name'), 'value' => array($this,'gridBestSellers')),
                array('name' => 'model', 'header' => Yii::t('dashboard','column_model'), 'value' => array($this,'gridBestSellers')),
                array('name' => 'id_order', 'header' => Yii::t('dashboard','column_total_orders'), 'value' => '$data->id_order'),
                array('name' => 'quantity', 'header' => Yii::t('dashboard','column_total_sales'), 'value' => '$data->quantity'),
            ),
        ));
        ?>