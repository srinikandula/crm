<?php
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'type' => 'striped bordered condensed',
	'id' => 'productinfo-grid3',
    'dataProvider' => $model['bestCategories']->bestCategories(),
	'summaryText' => 'Displaying {start}-{end} of {count} Results.',
    
    'ajaxUpdate' => true,
	'enablePagination' => false,
    'columns' => array(
        array(
            'header' => 'S.No',
            'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
        ),
        array('name' => 'name', 'header' => Yii::t('dashboard','column_name'), 'value' => array($this,'gridBestCategories')),
        array('name' => 'id_order', 'header' => Yii::t('dashboard','column_orders'), 'value' => '$data[id_order]'),
        array('name' => 'quantity', 'header' => Yii::t('dashboard','column_total_sales'), 'value' => '$data[quantity]'),
    ),
));
?>