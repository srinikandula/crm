<div class="row-fluid" >
    <div class="tab-pane active" id="Personal Details">
        <div class="span12">
    <fieldset class="portlet ">
        <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line7').slideToggle();">
            <div class="span11">Truck Request History</div>
            <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
            <div class="clearfix"></div>	
        </div>
        <div id="history" >
        <div class="portlet-content" id="hide_box_line7">
           	 
			<table class="table uploading-status" border='0'>
                <thead >
                    <tr>
                        <th>Source</th>
                        <th>Destination</th>
                        <th>Expected</th>
                        <th>Date Required</th>
                        <th>Goods Type</th>
                        <th>Comment</th>
                        <th>Pickup Point</th>
                        <th>Truck Type</th>
                    </tr>
                </thead>
                <tbody >
                    <?php 
                    $bgcolor="bgcolor='red'";
                    foreach ($model['ltrh'] as $row):  $exp=explode(",",$row->modified_fields); ?>
                        <tr>
                            <td <?php echo in_array('source_address',$exp)?$bgcolor:""; ?>><?php echo $row->source_address; ?></td>
                            <td <?php echo in_array('destination_address',$exp)?$bgcolor:""; ?>><?php echo $row->destination_address; ?></td>
                            <td <?php echo in_array('expected_price',$exp)?$bgcolor:""; ?>><?php echo $row->expected_price; ?></td>
                            <td <?php echo in_array('date_required',$exp)?$bgcolor:""; ?>><?php echo $row->date_required; ?></td>
                            <td <?php echo in_array('id_goods_type',$exp)?$bgcolor:""; ?> ><?php echo $row->id_goods_type; ?></td>
                            <td <?php echo in_array('comment',$exp)?$bgcolor:""; ?> ><?php echo $row->comment; ?></td>
                            <td <?php echo in_array('pickup_point',$exp)?$bgcolor:""; ?> ><?php echo $row->pickup_point; ?></td>
                            <td <?php echo in_array('id_truck_type',$exp)?$bgcolor:""; ?> ><?php echo $row->id_truck_type; ?></td>
                        </tr>
                    <?php endforeach;?>
                    
                </tbody>
                <tfoot>
                </tfoot>
            </table> 
        </div>  
        </div>  </fieldset></div> </div></div>