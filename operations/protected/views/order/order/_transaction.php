<?php $this->widget('ext.Flashmessage.Flashmessage'); ?> 
<div><b>#Ord<?php echo $_GET['id']; ?></b></div>
<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span12">
        <?php
        echo $this->renderPartial('_customer_left_block_billing', array('form' => $form, 'model' => $model), true);
        echo $this->renderPartial('_customer_right_block_billing', array('form' => $form, 'model' => $model), true);
        ?>
        </div>
        <?php
        echo $this->renderPartial('_customer_left_block_transactions', array('form' => $form, 'model' => $model), true);
        echo $this->renderPartial('_customer_right_block_transactions', array('form' => $form, 'model' => $model), true);
        echo $this->renderPartial('_form_easygaadi_block', array('form' => $form, 'model' => $model), true);
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
                data: 'id=' + id + '&type=' + type,
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