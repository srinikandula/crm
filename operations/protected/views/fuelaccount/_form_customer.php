<?php
$action = Yii::app()->controller->action->id;
$this->widget('ext.Flashmessage.Flashmessage');
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['config']['admin_url']; ?>js/datetime/jquery.datetimepicker.css"/>
    <div class="tab-pane active" id="Personal Details">
        <div class="span12"  >
            <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                    <div class="span11">Details</div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line1">
    <?php if ($_SESSION['id_admin_role'] != 8) { ?>
                            <div class="span5" id="type_drop_down"  style="margin-left:2.12766%">  <div class="control-group"><label for="Admin_id_admin_role" class="control-label required">Customer Type <span class="required">*</span></label><div class="controls">
                                        <select id="Customer_reg_type" name="Customer_reg_type" onchange="fncustomertype(this);">
                                            <option value="1">Registered</option>
                                            <option value="0">UnRegistered</option>
                                        </select><div style="display:none" id="Admin_id_admin_role_em_" class="help-inline error"></div></div></div> </div>
                            <div id="registered">
                                <div class="span5"  style="margin-left:2.12766%">
                                    <?php
                                    echo $form->textFieldRow(
                                            $model['c'], 'idprefix', array('onkeydown' => 'fnKeyDown("Customer_idprefix")', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                                    );
                                    ?>
                                </div>
                            <?php
                            } /*else {
										exit('out');
                                if ($action == 'create') {
                                    echo $form->textFieldRow(
                                            $model['c'], 'title', array('readonly' => true, 'onkeydown' => 'fnKeyDown("Loadtruckrequest_title")', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right", 'value' => $records['customer'][0]->idprefix . "," . $records['customer'][0]->fullname . "," . $records['customer'][0]->type . "," . $records['customer'][0]->mobile . "," . $records['customer'][0]->email . "," . $records['customer'][0]->landline)
                                    );
                                }
                                ?>
                                <?php } */?>
                        </div> 
                    <div id="unregistered" style="display:none" >
                        <div class="span5"  style="margin-left:2.12766%">  <?php
                            echo $form->textFieldRow(
                                    $model['c'], 'fullname', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>

                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['c'], 'mobile', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>

                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['c'], 'email', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>



                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['c'], 'company', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>

                        <div class="span5">  <?php
                            echo $form->textAreaRow(
                                    $model['c'], 'address', array('rows' => 3, 'cols' => 30)
                            );
                            ?></div>

                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['c'], 'city', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                        <div class="span5">  <?php
                            echo $form->dropDownListRow($model['c'], 'state', Library::getStates());
                            ?></div>  
                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['c'], 'landline', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>

                    </div>
</div>
            </fieldset>
        </div>
    </div>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" /> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="<?php echo Yii::app()->params['config']['admin_url']; ?>js/datetime/jquery.datetimepicker.js"></script>
<script type="text/javascript">
$('.datetimepicker').datetimepicker({
	dayOfWeekStart: 1,
	lang: 'en',
	//format: 'Y-m-d H:i',
	format: 'Y-m-d H:i',
	startDate: '<?php echo date('Y/m/d'); ?>'	//'2015/09/01'
});
function fncustomertype(id) {
	if (id.value == '1') {
		$('#unregistered').hide();
		$('#registered').show();
		/*$('#Customer_fullname').val('');
		$('#Customer_mobile').val('');
		$('#Customer_title').val('');*/

	} else {
		$('#registered').hide();
		$('#unregistered').show();
		/*$('#Customer_fullname').val('');
		$('#Customer_mobile').val('');
		$('#Customer_title').val('empty');*/
	}
}

<?php if($action=='update'){ ?>
$('#unregistered').show();
$('#type_drop_down').hide();
$('#Customer_mobile').attr('disabled',true);
$('#Customer_idprefix').attr('disabled',true);
<?php } ?>
</script>
<script type="text/javascript">
function fnKeyDown(id){
//alert(id)
$(function() {
var availableTags = [
<?php foreach($model['cust_list'] as $row){ echo								$pre.'"'.$row->mobile.','.$row->idprefix.','.$row->fullname.','.Library::getCustomerType($row->type).','.$row->gps_account_id.'"';$pre=",";}?>
];
function split( val ) {
return val.split( /,\s*/ );
}
function extractLast( term ) {
return split( term ).pop();
}
$( "#"+id )
// don't navigate away from the field on tab when selecting an item
.bind( "keydown", function( event ) {

//alert(event.keyCode +'==='+$.ui.keyCode.TAB)
if ( event.keyCode === $.ui.keyCode.TAB &&
$( this ).data( "ui-autocomplete" ).menu.active ) {
event.preventDefault();
}
})
.autocomplete({
minLength: 0,
source: function( request, response ) {
//	alert(extractLast( request.term ))
//stop concatination after ,
if(extractLast( request.term )=="")
{
	return false;
}
//stop concatination after ,

// delegate back to autocomplete, but extract the last term
response( $.ui.autocomplete.filter(
availableTags, extractLast( request.term ) ) );
},
focus: function() {
// prevent value inserted on focus
return false;
},select: function (event, ui) {
		$(event.target).val(ui.item.label);
		//alert(ui.item.value);
		var str=ui.item.value;
		var splt=str.split(",");
		$('#Customer_fullname').val(splt[2]);
		$('#Customer_mobile').val(splt[0]);

		return false;
	}

});
});
}
</script>