<?php $this->widget('ext.Flashmessage.Flashmessage'); ?> 

<div><span style="float:left"><b>#Ord<?php 
$arr=explode("-",substr($model['0']->date_ordered,0,10));
$ord=$arr[0].$arr[1].$arr[2].$model['0']->id_order;
echo $ord;

//echo $_GET['id']; ?></b>
<!-- <table><tr><td colspan="3">Commission</td></tr>
<tr><td>Actual</td><td>Expected</td><td>Obtained</td></tr>
<tr><td>500</td><td>500</td><td>500</td></tr></table>
 --><b>Given Commission:</b> <?php echo $model['0']->commission; if($model['0']->id_order_status==6){?> | <b>Expected Commission:</b> <?php echo $model['BT']['billing']-$model['BT']['tobilling'];?> | <b>Obtained Commission:</b> <?php echo $model['BT']['transaction']-$model['BT']['totransaction']; }?> </span></div>
<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span12">
        <?php
        echo $this->renderPartial('_customer_centertop_block_expenses', array('form' => $form, 'model' => $model), true);
        echo $this->renderPartial('_customer_left_block_billing', array('form' => $form, 'model' => $model), true);
        echo $this->renderPartial('_customer_right_block_billing', array('form' => $form, 'model' => $model), true);
        ?>
        </div>
        <?php
        echo $this->renderPartial('_customer_left_block_transactions', array('form' => $form, 'model' => $model), true);
        echo $this->renderPartial('_customer_right_block_transactions', array('form' => $form, 'model' => $model), true);
		echo $this->renderPartial('_customer_left_block_comment', array('form' => $form, 'model' => $model), true);
        //echo $this->renderPartial('_form_easygaadi_block', array('form' => $form, 'model' => $model), true);
        echo $this->renderPartial('_form_status', array('form' => $form, 'model' => $model), true);
        ?>         
    </div>
</div>
<script type="text/javascript">
    $('#modify_truck').live('click', function() {
        $.ajax({
            url: '<?echo $this->createUrl("order/modifyTruck");?>',
            dataType: 'json',
            type: 'post',
            data: 'id_order=<?php echo (int) $_GET['id']; ?>&truck_reg_no=' + $('#truck_reg_no').val(),
            beforeSend: function() {
            },
            complete: function() {
            },
            success: function(json) {

                if (json['success']) {
                    $('#truck_reg_no_alert').html('Done!!');
                } else {
                    $('#truck_reg_no_alert').html('Failed!!');
                }
            }
        });
    });

    function fnDelete(id, type) {
        if (confirm("are you sure??")) {
            //alert("in"+id)
            //$('#row_'+id+'_type').remove();   
            $.ajax({
                url: '<?php echo $this->createUrl("order/deleterow") ?>',
                type: 'post',
                data: 'oid=<?php echo (int)$_GET[id];?>&id=' + id + '&type=' + type,
                dataType: 'json',
                success: function(data) {
                    if (data['status'] == 1) {
                        location = window.location.href;
                    }
                }
            });
        }
    }
</script>    
<?php if(!Library::allowOrderUpdateForUsers()){?>
<script>
$('#yt1').prop('disabled', true);
$('#yt2').prop('disabled', true);
$('#yt3').prop('disabled', true);
$('#yt4').prop('disabled', true);
$('#yt5').prop('disabled', true);
$('#yt6').prop('disabled', true);
$('.delete-icon-block').css('display', 'none');
</script>
<?php } ?>
