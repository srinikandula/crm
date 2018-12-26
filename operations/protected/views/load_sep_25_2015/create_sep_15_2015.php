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
             'htmlOptions'=>array('enctype' => 'multipart/form-data'),
        )
);
?>
<?php echo $form->errorSummary($model['ltr']); ?>
<div class="row-fluid">
        <div class="span2 pull-right fixed_top_buttons design_fixed_top" >
            <span class="btn open-and-close"><i class="icon-chevron-right"></i> <i class="icon-chevron-left"></i>  </span>
            <?php
            Library::saveButton(array('label'=>'Save','permission'=>$this->addPerm)); 
            Library::cancelButton(array('label'=>'Cancel','url'=> $this->createUrl('index')));            
            ?>
        </div>
    <?php /*$this->widget(
    'bootstrap.widgets.TbTabs',
    array( 'type' => 'pills',  'tabs' => array(array( 'label' => 'Personal Details', 'active' => true ,  'content' => $this->renderPartial('_form', array('form'=>$form,'model'=>$model),true)))));*/
	$this->renderPartial('_form', array('form'=>$form,'model'=>$model,'records'=>$records));
?>
<?php
$this->endWidget();
unset($form);
?>
</div>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" /> 
<!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript">
    //function fnKeyDownCustTruck(id){
function fnKeyDown(id){
//alert(id)
$(function() {
var availableTags = [
<?foreach($records['customer'] as $row){ echo $pre.'"'.$row->idprefix.','.$row->fullname.','.Library::getCustomerType($row->type).','.$row->mobile.','.$row->email.'"';$pre=",";}?>
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
},

});
});
}
</script>
