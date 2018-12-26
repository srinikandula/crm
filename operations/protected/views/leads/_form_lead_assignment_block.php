<?php if(sizeof($model['cla'])){?><div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span6">
            <fieldset class="portlet " >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line5').slideToggle();">
                    <div class="span11">Assignment</div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
                    <div class="clearfix"></div>	
                </div>
                <div class="portlet-content" id="hide_box_line5" >
                    <table class="table uploading-status" border='0' >
                        <thead>
                            <tr>
                                <th>Assigned By</th>
                                <th>Assigned To</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Date Created</th>
                            </tr>
                        </thead>
                        <tbody id="grand_total">
                            <?php foreach ($model['cla'] as $cla) { ?>
                                <tr>
                                    <td><?php echo $cla['from_admin']; ?></td>
                                    <td><?php echo $cla['to_admin']; ?></td>
                                    <td><?php echo $cla['message']; ?></td>
                                    <td><?php echo $cla['status']; ?></td>
                                    <td><?php echo $cla['date_created']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
            </fieldset>
        </div>
    </div>
</div>
<?php }?>    