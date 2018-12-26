<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details"><div class="span6">
                <fieldset class="portlet " style="width:104%">
        <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line7').slideToggle();">
            <div class="span11">Quotes</div>
            <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
            <div class="clearfix"></div>	
        </div>
        <div id="comment_quote_design">
        <div class="portlet-content" id="hide_box_line7">
            <?php $contactRow=$_SESSION['id_admin_role']==8?'style="display:none"':'';//contact details not visible for transporters
                  $bookingRow=$_SESSION['id_admin_role']!=8?'style="display:none"':'';//book available only for transporters
                  $deleteRow=in_array($_SESSION['id_admin_role'],Library::getQuoteDeleteAccess())?'':'style="display:none"';?>
            <table class="table uploading-status" border='0' id="table_quotes">
                <thead>
                    <tr>
                        <th>#id</th>
                        <th <?php echo $contactRow;?>>Contact</th>
                        <th>Quote</th>
                        <th>Comment</th>
                        <th <?php echo $bookingRow;?> >Confirm</th>
                        <th <?php echo $deleteRow;?>></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($model['ltrq'] as $row): 
            $url=$row->idprefix;
            if(substr($row->idprefix,0,2)=='TO' && $_SESSION['id_admin_role']!=8){ 
            $url="<a href='".$this->createUrl('truckowner/update',array('id'=>$row->id_customer,'backurl'=>base64_encode($this->createUrl('truckowner/index'))))."' target='_blank' >".$row->idprefix."</a>";
}else if(substr($row->idprefix,0,2)=='GT'  && $_SESSION['id_admin_role']!=8){
	$url="<a href='".$this->createUrl('guest/update',array('id'=>$row->id_customer,'backurl'=>base64_encode($this->createUrl('guest/index'))))."' target='_blank' >".$row->idprefix."</a>";
} ?>
                        <tr <?php if($row->booking_request){ echo 'style="border:3px solid green;color:green;font-weight:bold"'; }?> id="row_quote_<?php echo $row->id_load_truck_request_quotes; ?>">
                            <td><?php echo $url; ?></td>
                            <td <?php echo $contactRow;?>><?php echo $row->fullname.",".$row->mobile; ?></td>
                            <td><?php echo $row->quote; ?></td>
                            <td><?php echo $row->message; ?></td>
                            <td <?php echo $bookingRow;?>>
                                <?php if($row->booking_request){?>
                                <button id="request_booking" disabled="true" name="request_booking" type="button"  class="btn btn-info" >Processing</button><?php }else{?><button id="request_booking"  name="request_booking" type="button"  class="btn btn-primary" onclick="fnBookRequest(this,'<?php echo $row->id_load_truck_request_quotes;?>');">Request Booking</button>
                                
                                <?php }?>
                            
                            </td>
                            <td <?php echo $deleteRow;?>> <a onclick="fnDeleteQuote('<?php echo $row->id_load_truck_request_quotes;?>')"  class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>
                        </tr>
                    <?php endforeach;?>
                    
                </tbody>
                <tfoot>
                </tfoot>
            </table> 
        </div>
        </div>
        <?php if($_SESSION['id_admin_role']!=8){?>
        <div class="portlet-content" id="hide_box_line3">
            <table style="width:120%">
                <tr colspan="4"  style="padding-bottom:0px;line-height: 0px">
                    <th>Customer Id</th>
                    <th>Quote</th>
                    <th>Comment</th>
                </tr>
                <tr>
                    <td valign="top"><input type="text" name="Loadtruckrequestquote[idprefix]"></td>
                    <td valign="top"><input type="text" name="Loadtruckrequestquote[quote]"></td>
                    <td><textarea name="Loadtruckrequestquote[comment]" rows="2" cols="90"></textarea></td>
                    <td><?php
                        echo CHtml::ajaxButton("Submit", $this->createUrl('load/Addquote', array('id' => (int) $_GET['id'])), array('dataType' => 'text', 'type' => 'post', 'success' => 'function(data){
	res=data.split("---");
	if(res[0]==1){
	//alert("in if"+res[1]);	
            $("#table_quotes thead").after(res[1]);
	}else{
            alert("Invalid data.try again!!");
		//$("#amount_recived").html(res[1]);
	}

	//$("#uploadBox").dialog("open");
}', /* ,'update' => '#amount_recived' */), array('confirm' => 'Are you sure??'));
                        ?></td>
                </tr>
            </table>
        </div><?php }?>
    </fieldset>
</div>
            </div>
    </div>
<script>
    function fnBookRequest(obj,rid) {
	$.ajax({
		url: '<?php echo $this->createUrl("load/bookrequest")?>',
		type: 'post',
		data: 'id=' + rid,
		dataType: 'json',
		success: function(json) {
			if (json['status']) {
                            $(obj).addClass('btn btn-info');
                            $(obj).html('Processing');
				$('#notification').html('<div class="alert in fade alert-success" id="success">Thank You,Will process your request in few mins!!<a style="cursor:pointer" class="close" data-dismiss="alert">×</a></div>');
				$('#success').fadeIn('slow').delay(2000).fadeOut(2000);
			}	
		}
	});
}

function fnDeleteQuote(id) {
    if(confirm("are you sure??")){
        //alert("in"+id)
        $('#row_quote_'+id).remove();   
        $.ajax({
                url: '<?php echo $this->createUrl("load/deletequote")?>',
                type: 'post',
                data: 'id=' + id,
                dataType: 'json',
        });
    }
}
</script>    

