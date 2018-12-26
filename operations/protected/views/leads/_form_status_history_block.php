<?php if(sizeof($model['clsh'])){?>
<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span6">
            <fieldset class="portlet " >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line5').slideToggle();">
                    <div class="span11">Status History</div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
                    <div class="clearfix"></div>	
                </div>
                <div class="portlet-content" id="hide_box_line5">
                    <div id="for_scroll">
                        <table class="table" border='0'>
                            <thead>
                                <tr>
                                    <th>Updated By</th>
                                    <th>Status</th>
                                    <th>Message</th>
                                    <th>Date Created</th>
                                </tr>
                            </thead>
                            <tbody id="grand_total">
                                <?php foreach ($model['clsh'] as $clsh) { ?>
                                    <tr>
                                        <td><?php echo $clsh['admin']; ?></td>
                                        <td><?php echo $clsh['status']; ?></td>
                                        <td><?php echo $clsh['message']; ?></td>
                                        <td><?php echo $clsh['date_created']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table> 
                    </div>
            </fieldset>
        </div> 
    </div>
</div>
<?php }?>