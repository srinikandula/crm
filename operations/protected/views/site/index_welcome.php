
<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
$baseUrl = Yii::app()->theme->baseUrl; 
?>

<?php
Yii::app()->user->setFlash('success', "<i class='icon-ok icon-white'></i> <strong>Well done !</strong>  You successfully read this important alert message.");
Yii::app()->user->setFlash('error', "<i class='icon-remove icon-white'></i><strong> Error :</strong> No match for E-Mail Address and/or Password.");
Yii::app()->user->setFlash('notice', "<i class='icon-exclamation-sign icon-white'></i> <strong>Alert : </strong>Selected Products Modified Successfully!");	
foreach(Yii::app()->user->getFlashes() as $key => $message) {
	echo ''
			. '<div class="alert alert-' . $key . '">'
	 		.'<button type="button" class="close" data-dismiss="alert">x</button>'
			. $message . "</div>\n";
}
?>




	
<?php
$gridDataProvider = new CArrayDataProvider(array(
    array('id'=>1, 'firstName'=>'Mark', 'lastName'=>'Otto', 'language'=>'CSS','usage'=>'<span class="inlinebar">1,3,4,5,3,5</span>'),
    array('id'=>2, 'firstName'=>'Jacob', 'lastName'=>'Thornton', 'language'=>'JavaScript','usage'=>'<span class="inlinebar">1,3,16,5,12,5</span>'),
    array('id'=>3, 'firstName'=>'Stu', 'lastName'=>'Dent', 'language'=>'HTML','usage'=>'<span class="inlinebar">1,4,4,7,5,9,10</span>'),
	array('id'=>4, 'firstName'=>'Jacob', 'lastName'=>'Thornton', 'language'=>'JavaScript','usage'=>'<span class="inlinebar">1,3,16,5,12,5</span>'),
    array('id'=>5, 'firstName'=>'Stu', 'lastName'=>'Dent', 'language'=>'HTML','usage'=>'<span class="inlinebar">1,3,4,5,3,5</span>'),
));
?>

<div class="row-fluid design_dsm">
  <div class="span2 ">
	<div class="stat-block">
	  <ul>
      <li class="stat-graph users" id="weekly-visit"></li>
      <li class="stat-count"><span>Users</span><span>$23,000</span></li>
	  </ul>
	</div>
  </div>
  <div class="span2 ">
	<div class="stat-block">
	  <ul>
      <li class="stat-graph revenue" id="weekly-visit"></li>
      <li class="stat-count"><span>Revenue</span><span>$23,000</span></li>
	</ul>
	</div>
  </div>
  <div class="span2 ">
	<div class="stat-block">
	   <ul>
      <li class="stat-graph orders" id="weekly-visit"></li>
      <li class="stat-count"><span>Orders</span><span>$23,000</span></li>
	</ul>
	</div>
  </div>
  <div class="span3 ">
	<div class="stat-block">
	 <ul>
      <li class="stat-graph bounce" id="weekly-visit"></li>
      <li class="stat-count"><span>Bounce rate</span><span>$23,000</span></li>
	</ul>
	</div>
  </div>
  
  <div class="span3">
	<div class="stat-block">
	 <ul>
    <li class="stat-graph abandoned" id="weekly-visit"></li>
      <li class="stat-count"><span>Abandoned  Cart</span><span>$23,000</span></li>
	</ul>
	</div>
  </div>
  
</div>



<div class="row-fluid">

	<div class="span6">
<ul class="nav nav-tabs dabord_tab">

  <li><a href="#home" data-toggle="tab">Year </a></li>
  <li><a href="#profile" data-toggle="tab">Month </a></li>
  <li><a href="#messages" data-toggle="tab">Week  </a></li>
  <li class="active"><a href="#settings" data-toggle="tab">Today</a></li>
  <li class="left_h4_tab"><h4> Revenue</h4></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <div class="tab-pane active" id="home">
  
   <?php $this->widget('zii.widgets.grid.CGridView', array(
			/*'type'=>'striped bordered condensed',*/
			'htmlOptions'=>array('class'=>'table table-striped table-bordered table-condensed'),
			'dataProvider'=>$gridDataProvider,
			'template'=>"{items}",
			'columns'=>array(
				array('name'=>'id', 'header'=>'S.No    '),
				array('name'=>'firstName', 'header'=>'tax   '),
				array('name'=>'lastName', 'header'=>'Shipping    '),
				array('name'=>'language', 'header'=>'total'),
				
			),
		)); ?>
  
  
  </div>
  <div class="tab-pane" id="profile">
   <?php $this->widget('zii.widgets.grid.CGridView', array(
			/*'type'=>'striped bordered condensed',*/
			'htmlOptions'=>array('class'=>'table table-striped table-bordered table-condensed'),
			'dataProvider'=>$gridDataProvider,
			'template'=>"{items}",
			'columns'=>array(
				array('name'=>'id', 'header'=>'S.No    '),
				array('name'=>'firstName', 'header'=>'tax   '),
				array('name'=>'lastName', 'header'=>'Shipping    '),
				array('name'=>'language', 'header'=>'total'),
				
			),
		)); ?>
  </div>
  <div class="tab-pane" id="messages">
   <?php $this->widget('zii.widgets.grid.CGridView', array(
			/*'type'=>'striped bordered condensed',*/
			'htmlOptions'=>array('class'=>'table table-striped table-bordered table-condensed'),
			'dataProvider'=>$gridDataProvider,
			'template'=>"{items}",
			'columns'=>array(
				array('name'=>'id', 'header'=>'S.No    '),
				array('name'=>'firstName', 'header'=>'tax   '),
				array('name'=>'lastName', 'header'=>'Shipping    '),
				array('name'=>'language', 'header'=>'total'),
				
			),
		)); ?>
  </div>
  <div class="tab-pane" id="settings">
   <?php $this->widget('zii.widgets.grid.CGridView', array(
			/*'type'=>'striped bordered condensed',*/
			'htmlOptions'=>array('class'=>'table table-striped table-bordered table-condensed'),
			'dataProvider'=>$gridDataProvider,
			'template'=>"{items}",
			'columns'=>array(
				array('name'=>'id', 'header'=>'S.No    '),
				array('name'=>'firstName', 'header'=>'tax   '),
				array('name'=>'lastName', 'header'=>'Shipping    '),
				array('name'=>'language', 'header'=>'total'),
				
			),
		)); ?>
  </div>
</div>



	
	</div><!--/span-->
    
    
	<div class="span6">
<ul class="nav nav-tabs dabord_tab">

  <li ><a href="#home1" data-toggle="tab">Manutacturers </a></li>
  <li><a href="#profile2" data-toggle="tab">Top Categories </a></li>
  <li class="active"><a href="#messages3" data-toggle="tab">Products	  </a></li>
  <li class="left_h4_tab"><h4> Inventory</h4></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <div class="tab-pane active" id="home1">
  
   <?php $this->widget('zii.widgets.grid.CGridView', array(
			/*'type'=>'striped bordered condensed',*/
			'htmlOptions'=>array('class'=>'table table-striped table-bordered table-condensed'),
			'dataProvider'=>$gridDataProvider,
			'template'=>"{items}",
			'columns'=>array(
				array('name'=>'id', 'header'=>'S.No    '),
				array('name'=>'firstName', 'header'=>'tax   '),
				array('name'=>'lastName', 'header'=>'Shipping    '),
				array('name'=>'language', 'header'=>'total'),
				
			),
		)); ?>
  
  
  </div>
  <div class="tab-pane" id="profile2">
   <?php $this->widget('zii.widgets.grid.CGridView', array(
			/*'type'=>'striped bordered condensed',*/
			'htmlOptions'=>array('class'=>'table table-striped table-bordered table-condensed'),
			'dataProvider'=>$gridDataProvider,
			'template'=>"{items}",
			'columns'=>array(
				array('name'=>'id', 'header'=>'S.No    '),
				array('name'=>'firstName', 'header'=>'tax   '),
				array('name'=>'lastName', 'header'=>'Shipping    '),
				array('name'=>'language', 'header'=>'total'),
				
			),
		)); ?>
  </div>
  <div class="tab-pane" id="messages3">
   <?php $this->widget('zii.widgets.grid.CGridView', array(
			/*'type'=>'striped bordered condensed',*/
			'htmlOptions'=>array('class'=>'table table-striped table-bordered table-condensed'),
			'dataProvider'=>$gridDataProvider,
			'template'=>"{items}",
			'columns'=>array(
				array('name'=>'id', 'header'=>'S.No    '),
				array('name'=>'firstName', 'header'=>'tax   '),
				array('name'=>'lastName', 'header'=>'Shipping    '),
				array('name'=>'language', 'header'=>'total'),
				
			),
		)); ?>
  </div>
  
</div>



	
	</div><!--/span-->
      <div class="clearfix"></div>	
</div><!--/row-->

<br />

<div class="row-fluid">

	<div class="span6">
<ul class="nav nav-tabs dabord_tab">

  <li><a href="#home2" data-toggle="tab">Year </a></li>

  <li class="left_h4_tab"><h4> Last 5 Orders</h4></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <div class="tab-pane active" id="home2">
  
   <?php $this->widget('zii.widgets.grid.CGridView', array(
			/*'type'=>'striped bordered condensed',*/
			'htmlOptions'=>array('class'=>'table table-striped table-bordered table-condensed'),
			'dataProvider'=>$gridDataProvider,
			'template'=>"{items}",
			'columns'=>array(
				array('name'=>'Order id     ', 'header'=>'S.No    '),
				array('name'=>'Customer    ', 'header'=>'tax   '),
				array('name'=>'Date Purchased   ', 'header'=>'Shipping    '),
				array('name'=>'Total   ', 'header'=>'total'),
				array('name'=>'Action   ', 'header'=>'total'),
				
				
			),
		)); ?>
  
  
  </div>
 
</div>



	
	</div><!--/span-->
    
    
	<div class="span6">

    	<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>"<i class='icon-adjust'></i> Social Networrk",
		));
		
	?>
    	<div class="simple-donut" style="height: 180px;width:100%;margin-top:15px; margin-bottom:15px;"></div>
        
    <?php $this->endWidget();?>
 
	</div><!--/span-->
    <div class="clearfix"></div>	
    
</div>




<div class="row-fluid">
	<div class="span6">
	  <?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'<span class="icon-th-large"></span>Orders /Customer Registrons Statistics',
			'titleCssClass'=>''
		));
		?>
        
        <div class="visitors-chart" style="height: 230px;width:100%;margin-top:15px; margin-bottom:15px;"></div>
        
        <?php $this->endWidget(); ?>
	</div><!--/span-->
    <div class="span6">
    	<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'<span class="icon-th-list"></span> Visitors Chart',
			'titleCssClass'=>''
		));
		?>
        
        <div class="pieStats" style="height: 230px;width:100%;margin-top:15px; margin-bottom:15px;"></div>
        
        <?php $this->endWidget(); ?>
    </div>
    <div class="clearfix"></div>	

</div><!--/row-->

          


<script>
            $(function() {

                $(".knob").knob({

                    draw : function () {

                        // "tron" case
                        if(this.$.data('skin') == 'tron') {

                            var a = this.angle(this.cv)  // Angle
                                , sa = this.startAngle          // Previous start angle
                                , sat = this.startAngle         // Start angle
                                , ea                            // Previous end angle
                                , eat = sat + a                 // End angle
                                , r = 1;

                            this.g.lineWidth = this.lineWidth;

                            this.o.cursor
                                && (sat = eat - 0.3)
                                && (eat = eat + 0.3);

                            if (this.o.displayPrevious) {
                                ea = this.startAngle + this.angle(this.v);
                                this.o.cursor
                                    && (sa = ea - 0.3)
                                    && (ea = ea + 0.3);
                                this.g.beginPath();
                                this.g.strokeStyle = this.pColor;
                                this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
                                this.g.stroke();
                            }

                            this.g.beginPath();
                            this.g.strokeStyle = r ? this.o.fgColor : this.fgColor ;
                            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
                            this.g.stroke();

                            this.g.lineWidth = 2;
                            this.g.beginPath();
                            this.g.strokeStyle = this.o.fgColor;
                            this.g.arc( this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
                            this.g.stroke();

                            return false;
                        }
                    }
                });

                var v, up=0,down=0,i=0
                    ,$idir = $("div.idir")
                    ,$ival = $("div.ival")
                    ,incr = function() { i++; $idir.show().html("+").fadeOut(); $ival.html(i); }
                    ,decr = function() { i--; $idir.show().html("-").fadeOut(); $ival.html(i); };
                $("input.infinite").knob(
                                    {
                                    min : 0
                                    , max : 20
                                    , stopper : false
                                    , change : function () {
                                                    if(v > this.cv){
                                                        if(up){
                                                            decr();
                                                            up=0;
                                                        }else{up=1;down=0;}
                                                    } else {
                                                        if(v < this.cv){
                                                            if(down){
                                                                incr();
                                                                down=0;
                                                            }else{down=1;up=0;}
                                                        }
                                                    }
                                                    v = this.cv;
                                                }
                                    });
            });
        </script>