 <!--<link rel="stylesheet" type="text/css" href="/easygaadi.com/osadmin/assets/9ba8c562/bootstrap/css/bootstrap.min.css" />
 
<link rel="stylesheet" type="text/css" href="/easygaadi.com/osadmin/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" type="text/css" href="/easygaadi.com/osadmin/css/abound.css" />-->

<?php
if ($return) {
    echo "<p style='color:green'>Upload Successful!!</p>";
}
if ($error != "") {
    echo "<p style='color:red'>" . $error . "</p>";
}
?>
<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span6">
            <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                    <div class="span11">Truck Details </div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line1">

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Truck Reg No </label><div class="controls"><b><?php echo $model['truck_reg_no']; ?></b></div></div></div>
                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Source </label><div class="controls"><b><?php echo $model['source_address']; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Destination </label><div class="controls"><b><?php echo $model['destination_address']; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Date Available </label><div class="controls"><b><?php echo $model['date_available']; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Price </label><div class="controls"><b><?php echo $model['price']; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Goods Type </label><div class="controls"><b><?php echo $model['id_goods_type']; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Load Type </label><div class="controls"><b><?php echo $model['id_load_type']; ?></b></div></div></div>

                </div>
            </fieldset>


        </div>

        <div class="span6">
            <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line2').slideToggle();">
                    <div class="span11">Truck Owner Details </div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line2">

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Name </label><div class="controls"><b><?php echo $model['fullname']; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Mobile </label><div class="controls"><b><?php echo $model['mobile']; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Email </label><div class="controls"><b><?php echo $model['email']; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Company </label><div class="controls"><b><?php echo $model['company']; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Address </label><div class="controls"><b><?php echo $model['address']; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">City/State </label><div class="controls"><b><?php echo $model['city'] . "/" . $model['state']; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">landline </label><div class="controls"><b><?php echo $model['landline']; ?></b></div></div></div>

                </div>
            </fieldset>


        </div>

        <div class="span12" id="book_a_truck_details">
            <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line2').slideToggle();">
                    <div class="span11">Order Details </div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line2">

                    <div><label>Order By </label><div class="controls"><input type="text" name="order_by" id="order_by" onkeydown="fnKeyDownOrder('order_by')" value="" ></div>

                        <div class="span11"><div class="control-group"><label for="Customer_fullname" class="control-label required">Original Amount </label><div class="controls"><b><?php echo $model['price']; ?></b></div></div></div>

                        <div class="span11">

                            <table id="table_person" class="table">
                                <thead>
                                    <tr><th>Additional Charges</th></tr>
                                    <tr>
                                         <th>Comment</th> 
                                         <th>Type</th>
                                        <th>Commission</th>
                                    </tr>
                                </thead>
                                <tbody id="row-0">
                                    <tr>
                                        <td><input  id="Commission_Person_0_title" name="Commission[Person][0][title]" type="text" ></td>
                                        <td><select name="Commission[Person][0][commission_type]" ><option value="+">+</option></select></td>
                                        <td>
                                            <input type="text" onkeyup="fnCalc()" value="15.00" data-original-title="enter commission" name="Commission[Person][0][commission]" rel="tooltip" data-toggle="tooltip" data-placement="right" class="Options_design" id="Commission_Person_0_commission">
                                        </td>

                                     </tr>
                                </tbody>
                                <tbody id="row-1">
                                    <tr>
                                        <td><input  id="Commission_Person_1_title" name="Commission[Person][1][title]" type="text" ></td>
                                        <td><select   name="Commission[Person][1][commission_type]"><option value="-">-</option></select></td>
                                        <td>
                                            <input type="text" onkeyup="fnCalc()" value="15.00" data-original-title="enter commission" name="Commission[Person][1][commission]" rel="tooltip" data-toggle="tooltip" data-placement="right" class="Options_design" id="Commission_Person_1_commission">
                                        </td>

 
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--<div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Additional Amount </label><div class="controls"><input type="text" name="additional_amount" id="additional_amount" onkeydown="fnAddAmount(this.value)"></div></div></div>-->

                        <!--<div class="span11"><div class="control-group"><label for="Customer_fullname" class="control-label required">Payment </label><div class="controls"><input type="text" name="payment" id="payment" onkeydown="fnPayment('0',this.value);"></div></div></div>-->

                        <div class="span11"><div class="control-group"><label for="Customer_fullname" class="control-label required">Total </label><div class="controls" id="grand_total"><b><?php echo $model['price']; ?></b></div></div></div>
                        
                        <div class="span11"><div class="control-group"><input type="button" name="submit_order" id="submit_order" value="Place Order"></div></div>
                    </div>
            </fieldset>


        </div>


    </div>

</div>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" /> 
<!--<script src="http://sun-network/easygaadi.com/osadmin/assets/8a880920/jquery.min.js"></script>
<script src="http://sun-network/easygaadi.com/osadmin/js/bootstrap-switch.js"></script>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript">
                            //function fnKeyDownCustTruck(id){

                            function fnKeyDownOrder(id) {
//alert(id)
                                $(function() {
                                    var availableTags = [
                                        "Rakesh Sharma,Truck,9988776655", "Ranvir Ahuja,Load,9876543210", "Sharath Kumar,Commission Agent,9876543210"];
                                    function split(val) {
                                        return val.split(/,\s*/);
                                    }
                                    function extractLast(term) {
                                        return split(term).pop();
                                    }
                                    $("#" + id)
// don't navigate away from the field on tab when selecting an item
                                            .bind("keydown", function(event) {

//alert(event.keyCode +'==='+$.ui.keyCode.TAB)
                                                if (event.keyCode === $.ui.keyCode.TAB &&
                                                        $(this).data("ui-autocomplete").menu.active) {
                                                    event.preventDefault();
                                                }
                                            })
                                            .autocomplete({
                                                minLength: 0,
                                                source: function(request, response) {
//	alert(extractLast( request.term ))
//stop concatination after ,
                                                    if (extractLast(request.term) == "")
                                                    {
                                                        return false;
                                                    }
//stop concatination after ,

// delegate back to autocomplete, but extract the last term
                                                    response($.ui.autocomplete.filter(
                                                            availableTags, extractLast(request.term)));
                                                },
                                                focus: function() {
// prevent value inserted on focus
                                                    return false;
                                                },
                                            });
                                });
                            }
                            
   function fnCalc(){

       //alert(parseFloat($('#Commission_Person_0_commission').val())+" "+parseFloat($('#Commission_Person_1_commission').val()));
       var price="<?php echo $model['price']; ?>";
        var total;//=parseFloat(price)+(parseFloat($('#Commission_Person_0_commission').val()))-(parseFloat($('#Commission_Person_1_commission').val()));
    if($.isNumeric($('#Commission_Person_0_commission').val())){
        total=parseFloat(price)+(parseFloat($('#Commission_Person_0_commission').val()));
    }
    
    if($.isNumeric($('#Commission_Person_1_commission').val())){
        total=total-(parseFloat($('#Commission_Person_1_commission').val()));
    }
    //alert(total)    
    $('#grand_total').html("<b>"+total+"</b>");
       
   }                         



$('#submit_order').live('click', function() {
	$.ajax({
		url: '<?echo $this->createUrl("order/addorder",array("id"=>(int)$_GET[id]));?>',
		type: 'post',
		//data: $('#frmcheckoutmethod :input'),
		data: $('#book_a_truck_details input[type=\'text\']'),
		dataType: 'json',
		beforeSend: function() {
	/*		
        //$('#button-account').attr('disabled', true);

			$('#button-account').after('<span class="wait">&nbsp;<img src="/includes/images/loading.gif" alt="" /></span>');*/
		},
		complete: function() {
			/*$('#button-account').attr('disabled', false);
			$('.wait').remove();*/
		},
		success: function(json) {
			//$('.warning').remove();
			$('.alert-danger').remove();

			if (json['redirect']) {
				location = json['redirect'];
			}

			if (json['error']) 
			{
				for (var key in json['error']) 
				{
					//$('.checkout_options_div').prepend('<div class="warning">' + json['error'][key] + '</div>');
					$('#notification').prepend('<div class="alert in fade alert-danger">' + json['error'][key] + '<a data-dismiss="alert" class="close" style="cursor:pointer">×</a></div>');
				}
			}

			 
			$('#confirmButton').html(json['confirmButton']);
			
			
		}
	});
});
</script>