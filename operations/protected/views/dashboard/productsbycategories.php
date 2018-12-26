		<?php
		$this->widget('bootstrap.widgets.TbExtendedGridView', array(
			'type' => 'striped bordered condensed',
			'summaryText' => 'Displaying {start}-{end} of {count} Results.',
			'enablePagination' => true,
			'ajaxUpdate' => true,
			'id' => 'productinfo-grid14',
			'dataProvider' => $model['productsByCategories']->productsByCategories(),
			'columns' => array(
				array(
					'header' => 'S.No',
					'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
				),
				array('name' => 'name', 'header' => Yii::t('dashboard','column_name'),'value' => '$data->name'),
				array('name' => 'total', 'header' => Yii::t('dashboard','column_total_products'), 'value' => '$data->products_by_categories_total'),
			),
		));
		?>
