<div><b>Note:</b>Commission will be applied on price either in fixed or percent amount.Commission on price will be calculated based on the condition applied.Comission priority is ordered in the following way and which ever matches first in the given order will be applied.
1.customer based,2.truck based,3.Customer truck route based,4.Route based,5.Point based,6.default global commission.if none of the condition is matched then global commission will be applied to the price.</div><div>Here, the given commission will be applied to all the matching source and destination trucks</div>
<?php 
$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'horizontalForm',
        'type' => 'horizontal',
        'action'=>$this->createUrl('pointcomm/update'),
        'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
    )
); ?>
<?php 
echo $form->errorSummary($model); 
//echo $form->errorSummary($model['o']); 

?>
<?php
    $this->widget('ext.Flashmessage.Flashmessage');
    ?>
<div class="row-fluid">
<div class="span2 pull-right fixed_top_buttons design_fixed_top">
            <span class="btn open-and-close"><i class="icon-chevron-right"></i> <i class="icon-chevron-left"></i>  </span>
    <?php
$this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'label' => 'Save',
        'buttonType'=>'submit',
        'visible'=>true,//$this->addPerm,
        'type' => 'info',
	)
);?>

<?php
$this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'label' => 'Cancel',
        'type' => 'danger',
	'url'=>base64_decode (Yii::app()->request->getParam('backurl')))
);?>
</div>

<?php
$this->renderPartial('_point', array('form'=>$form,'model'=>$model));

$this->endWidget();
unset($form);?>
</div>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" /> 
<!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript">
    //function fnKeyDownCustTruck(id){
function fnKeyDown(id,type){
//alert(id)
$(function() {

var availableTags_Source = [
<?foreach($source as $row){ echo $pre1.'"'.$row->source_city.'"'; $pre1=",";}?>
];

var availableTags_Destination = [
<?foreach($destination as $row){ echo $pre2.'"'.$row->destination_city.'"';$pre2=",";}?>
];

if(type=='Source'){
var availableTags=availableTags_Source;
} else if(type=='Destination'){
var availableTags=availableTags_Destination;
}

//alert(availableTags);

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
},

});
});
}


</script>

