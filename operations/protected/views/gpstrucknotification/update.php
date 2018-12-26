<?php 
Yii::app()->clientScript->registerCoreScript('jquery.ui');
?>
<?php
$form = $this->beginWidget(
        'bootstrap.widgets.TbActiveForm',
        array(
    'id' => 'horizontalForm',
    'type' => 'horizontal',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
        )
);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row-fluid">
        <div class="span2 pull-right fixed_top_buttons design_fixed_top" >
            <span class="btn open-and-close"><i class="icon-chevron-right"></i> <i class="icon-chevron-left"></i>  </span>
            <?php Library::saveButton(array('label'=>'Save','permission'=>$this->editPerm)); ?>
            <?php Library::cancelButton(array('label'=>'Cancel','url'=> base64_decode(Yii::app()->request->getParam('backurl'))));  ?>
        </div>
    <?php    $this->renderPartial('_form', array('form' => $form, 'model' => $model));    ?>
<?php
$this->endWidget();
unset($form);
?>
</div>
<script type="text/javascript">
function fnKeyDown(id){
//alert(id)
$(function() {
var availableTags = [
<?php foreach($records['customer'] as $row){ echo $pre.'"'.$row->idprefix.','.$row->fullname.','.Library::getCustomerType($row->type).','.$row->mobile.','.$row->email.','.$row->gps_account_id.'"';$pre=",";}?>
];
//alert(<?php //echo $records['customer']['0']['id_customer'] ?>);
function split( val ) {
return val.split( /,\s*/ );
}
function extractLast( term ) {
return split( term ).pop();
}
$( "#"+id )
//.css({ 'backgroundColor':'yellow'})
// don't navigate away from the field on tab when selecting an item
.bind( "keydown", function( event ) {
//alert(event.keyCode +'==='+$.ui.keyCode.TAB);
//alert(event.keyCode);
console.log($.ui.keyCode.TAB);
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
},

});
});
}
</script>