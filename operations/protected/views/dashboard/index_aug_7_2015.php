<?php
/* @var $this SiteController */
$this->pageTitle = Yii::app()->name;
$baseUrl = Yii::app()->theme->baseUrl;
?>
<?php
/* Yii::app()->user->setFlash('success', "<i class='icon-ok icon-white'></i> <strong>Well done !</strong>  You successfully read this important alert message.");
  Yii::app()->user->setFlash('error', "<i class='icon-remove icon-white'></i><strong> Error :</strong> No match for E-Mail Address and/or Password.");
  Yii::app()->user->setFlash('notice', "<i class='icon-exclamation-sign icon-white'></i> <strong>Alert : </strong>Selected Products Modified Successfully!"); */
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo ''
    . '<div class="alert alert-' . $key . '">'
    . '<button type="button" class="close" data-dismiss="alert">x</button>'
    . $message . "</div>\n";
}
?>


<div class="row-fluid design_dsm">
    <div class="span2 ">
        <div class="stat-block">
            <ul>
                <li class="stat-count"><span><?php echo $data['totalCustomers']; ?></span><span><?php echo '<a href="' . $this->createUrl('customer/index') . '">' . Yii::t('dashboard', 'text_total_customer') . '</a>'; ?></span></li>
            </ul>
        </div>
    </div>
    <div class="span2 ">
        <div class="stat-block revenue-div">
            <ul>
                <li class="stat-count"><span><?php echo $data['customersRegisteredToday']; ?></span><span><?php echo '<a href="' . $this->createUrl('customer/index', array('Customer[date_created]' => date(Y) . "-" . date(m) . "-" . date(d))) . '">' . Yii::t('dashboard', 'text_total_customer_today') . '</a>' ?></span></li>
            </ul>
        </div>
    </div>
	<?php if(Yii::app()->config->getData('CONFIG_STORE_APPROVE_NEW_CUSTOMER')){?>
    <div class="span2 ">
        <div class="stat-block orders-div">
            <ul>
                <li class="stat-count"><span><?php echo $data['customersPendingApproval']; ?></span><span><?php echo '<a href="' . $this->createUrl('customer/index', array('Customer[approved]' => '0')) . '">' . Yii::t('dashboard', 'text_total_customer_approval') . '</a>' ?></span></li>
            </ul>
        </div>
    </div>
	<?php }?>
    <div class="span2 ">
        <div class="stat-block bounce-rate-div">
            <ul>
                <li class="stat-count"><span><?php echo $data['ordersToday']; ?></span><span><?php echo "<a href='" . $this->createUrl('order/index', array('Order[date_created]' => date(Y) . "-" . date(m) . "-" . date(d))) . "'>" . Yii::t('dashboard', 'text_order_today') . "</a>" ?></span></li>
            </ul>
        </div>
    </div>

    <div class="span2">
        <div class="stat-block abandoned-cart">
            <ul>
                <li class="stat-count"><span><?php echo $data['totalOrders']; ?></span> <span><?php echo '<a href="' . $this->createUrl('order/index') . '">' . Yii::t('dashboard', 'text_total_order') . '</a>'; ?></span></li>
            </ul>
        </div>
    </div>

    <div class="span2">
        <div class="stat-block revenues-div">
            <ul>
                <li class="stat-count"><span><?php echo $data['totalRevenue']; ?></span> <span><?php echo Yii::t('dashboard', 'text_total_revenue') ?></span></li>
            </ul>
        </div>
    </div>

    <div class="span2">
        <div class="stat-block current-totla-cart">
            <ul>
                <li class="stat-count"><span><?php echo $data['currentRevenue']; ?></span> <span><?php echo date(Y); ?> <?php echo Yii::t('dashboard', 'text_total_revenue_today') ?></span></li>
            </ul>
        </div>
    </div>

    <div class="span2">
        <div class="stat-block outofstock-div">
            <ul>
                <li class="stat-count"><span><?php echo $data['OutOfStock']; ?></span> <span><?php echo '<a href="' . $this->createUrl('product/index', array('Product[quantity]' => '<1')) . '">' . Yii::t('dashboard', 'text_total_outofstock') . '</a>' ?></span></li>
            </ul>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span6 dashboard-div">
        <div class="bootstrap-widget bootstrap-widget-table"><div class="bootstrap-widget-header"><h3><?php echo Yii::t('dashboard', 'text_best_selling_products'); ?></h3></div>
            <?php Yii::app()->runController('dashboard/bestsellers'); ?>
            <div class="clearfix"></div></div>
    </div>

    <div class="span6 dashboard-div">
        <div class="bootstrap-widget bootstrap-widget-table"><div class="bootstrap-widget-header"><h3><?php echo Yii::t('dashboard', 'text_best_customers'); ?></h3></div>
            <?php Yii::app()->runController('dashboard/bestcustomers'); ?>
            <div class="clearfix"></div></div>
    </div>
    <div class="clearfix"></div>
</div><!--/row-->



<div class="row-fluid">
	<!--chart start  here -->   
    <div class="span8 dashboard-div desing-box-last">
  <!-- Tab panes -->
            <div class="tab-pane active" id="home3">
            <div class="bootstrap-widget-header"><h3>Order's Chart</h3>
               <div class="span3 pull-right widget-header-select"> <?php
                echo CHtml::dropDownList('range','year', array('day'=>'Today','week'=>'Week','month'=>'Month','year'=>'Year'),
array(
'ajax' => array(
'type'=>'GET', //request type
'url'=>CController::createUrl('dashboard/orderschart'), //url to call.
'update'=>'#yearcontainer', //selector to update
'data'=>"js:'range='+$('#range').val()", 

)));?></div>
</div>
<div id="yearcontainer" style="width: 98%; height: 384px; margin: 0 auto"><?php Yii::app()->runController('dashboard/orderschart');?></div>

        </div>
        <div class="clear"></div>
    </div><!--/span-->
    
    
    <div class="span4">
        <?php
        
        $box = $this->beginWidget(
                'bootstrap.widgets.TbBox', array('title' => Yii::t('dashboard', 'text_chart_lable'),
            'htmlOptions' => array('class' => 'bootstrap-widget-table'),
        ));

        $chartData = array();
        foreach ($data['ordersByStatus'] as $info) {
            $chartData[] = array($info->order_status_name, (int) $info->total);
        }
         
        $this->widget('bootstrap.widgets.TbHighCharts', array(
            'options' => array(
                'title' => array(
                    'text' => '',//Yii::t('dashboard', 'text_chart_lable'),
                ),
                'series' => array(array(
                        'type' => 'pie',
                        'data' => $chartData,
                    ))
            )
        ));

        $this->endWidget(); 
        ?>
    </div>
    
    <div class="clearfix"></div>
	<!--chart end   here -->
	 </div>


<div class="row-fluid">
	<!--chart start  here -->
	    
       
    <div class="span8 dashboard-div desing">
        <ul class="nav nav-tabs dabord_tab">
            <li class="active"><a href="#home6" data-toggle="tab"><?php echo Yii::t('dashboard', 'text_total_product_by_category'); ?></a></li>
            <li><a href="#home4" data-toggle="tab"><?php echo Yii::t('dashboard', 'text_total_product_by_manufacturer'); ?></a></li>
            <li><a href="#home5" data-toggle="tab"><?php echo Yii::t('dashboard', 'text_total_product_overview'); ?></a></li>
            <li class="left_h4_tab"><h4><?php echo Yii::t('dashboard', 'text_total_inventory'); ?></h4></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="home6">

                <?php Yii::app()->runController('dashboard/productsbycategories'); ?>

            </div>



            <div class="tab-pane" id="home4">
                <?php Yii::app()->runController('dashboard/productsbymanufacturers'); ?>
            </div>

            <div class="tab-pane" id="home5">
                <div class="table table-striped table-bordered table-condensed" id="yw2">
                    <table class="items">
                        <thead>
                            <tr>
                                <th id="yw2_c0"><?php echo Yii::t('dashboard', 'text_total_product_total'); ?></th><th id="yw2_c1"><?php echo Yii::t('dashboard', 'text_total_product_instock'); ?></th><th id="yw2_c2"><?php echo Yii::t('dashboard', 'text_total_product_outofstock'); ?></th><th id="yw2_c2"><?php echo Yii::t('dashboard', 'text_total_product_active'); ?></th><th id="yw2_c2"><?php echo Yii::t('dashboard', 'text_total_product_inactive'); ?></th></tr>
                        </thead>
                        <tbody>
                            <tr class="odd"><td><?php echo $data['TotalProducts']; ?></td><td><?php echo $data['InStock']; ?></td><td><?php echo $data['OutOfStock']; ?></td><td><?php echo $data['ActiveProducts']; ?></td><td><?php echo $data['InActiveProducts']; ?></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="clear"></div>
    </div><!--/span-->



<div class="span4 dashboard-div bestcategories-div">
        <div class="bootstrap-widget bootstrap-widget-table"><div class="bootstrap-widget-header"><h3><?php echo Yii::t('dashboard', 'text_best_categories'); ?></h3></div>
            <?php Yii::app()->runController('dashboard/bestcategories'); ?>
            <div class="clearfix"></div></div>
    </div>

    <div class="clearfix"></div>
</div><!--/row-->


