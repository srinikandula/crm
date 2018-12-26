<div class="row-fluid">
        <div class="tab-pane active" id="Personal Details"><div class="span12">
    <fieldset class="portlet " >
        <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line7').slideToggle();">
            <div class="span11">Search Results</div>
            <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
            <div class="clearfix"></div>	
        </div>
        <div class="portlet-content" id="hide_box_line3">
            
        <?php
        $this->widget('bootstrap.widgets.TbExtendedGridView',
                array(
            'type' => 'striped bordered condensed',
            //'type'=>'striped',
            'template' => "{summary}{pager}<div class='items_main_div span12 page'>{items}</div>",
            'summaryText' => 'Displaying {start}-{end} of {count} Results.',
            'enablePagination' => true,
            //'pager' => array('class' => 'CListPager', 'header' => '&nbsp;Page', 'id' => 'no-widthdesign_left'),
            'ajaxUpdate' => true,
            'id' => 'productinfo-grid',
            'dataProvider' => $dataProvider,
            //'filter' => $model,
            'columns' => array(
                array(
                    'header' => 'S.No',
                    'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
                ),
		//array('name'=>'file','header'=>"Shipping Gateway",'value'=>'$data[file]','type'=>'raw',value=>array($this,'grid')),
                array('name'=>'idprefix','header'=>"Id",'value'=>'$data[idprefix]','type'=>'raw'),
                array('name'=>'fullname','header'=>"Name",'type'=>'raw','value'=>array($this,'grid')),
                array('name'=>'type','header'=>"Type",'value'=>array($this,'grid')),
                array('name'=>'mobile','header'=>"Mobile",'value'=>'$data[mobile]','type'=>'raw','value'=>array($this,'grid')),
                array('name'=>'no_of_vechiles','header'=>"No Of Trucks",'value'=>'$data[no_of_vechiles]','type'=>'raw'),
                //array('name'=>'id_truck_type','header'=>"Truck Type",'value'=>array($this,'grid')),
                //array('name'=>'id_goods_type','header'=>"Goods Type",'value'=>array($this,'grid')),
                array('name'=>'company','header'=>"Company",'value'=>'$data[company]','type'=>'raw'),
                array('name'=>'address','header'=>"address",'type'=>'raw','value'=>array($this,'grid')),
                //array('name'=>'source_address','header'=>"Source",'value'=>'$data[date_available]','type'=>'raw'),
                array('name'=>'source_address','header'=>"Source",'value'=>'$data[source_address]','type'=>'raw'),
                array('name'=>'destination_address','header'=>"Destination",'value'=>'$data[destination_address]','type'=>'raw'),
                //array('name'=>'idprefix','header'=>"Id",'value'=>'$data[idprefix]','type'=>'raw'),
                array('name'=>'status','header'=>"Add Info",'value'=>array($this,'grid'),'type'=>'raw'),

					
            ),
        ));?>
        </div>
    </fieldset>
</div>
            </div>
    </div>
