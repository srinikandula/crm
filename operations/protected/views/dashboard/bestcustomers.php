<?php
			$this->widget('bootstrap.widgets.TbExtendedGridView', array(
            'type' => 'striped bordered condensed',
            'summaryText' => 'Displaying {start}-{end} of {count} Results.',
            'enablePagination' => true,
            'ajaxUpdate' => true,
            'id' => 'productinfo-grid2',
            'dataProvider' => $model['bestCustomers']->bestCustomers(),
            'columns' => array(
                array(
                    'header' => 'S.No',
                    'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
                ),
                array('name' => 'customer', 'header' => Yii::t('dashboard','column_customer'), 'value' => array($this,'gridBestCustomers')),
                array('name' => 'id_order', 'header' => Yii::t('dashboard','column_orders'), 'value' => '$data->id_order'),
                //array('name' => 'email_address', 'header' => 'header' => Yii::t('dashboard','column_email'), 'value' => '$data->email_address'),
                //array('name' => 'telephone', 'header' => 'header' => Yii::t('dashboard','column_phone'), 'value' => '$data->telephone'),
                array('name' => 'total', 'header' => Yii::t('dashboard','column_total'), 'value' => array($this,'gridBestCustomers')),
            ),
        ));
        ?>