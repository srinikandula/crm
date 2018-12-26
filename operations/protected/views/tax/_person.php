<fieldset class="portlet " >
    <div class="span12 pull-right" id="region_list">
        <table class="table" id="table_person">
            <thead>
                <tr>
                    <th>State</th>
                    <th>Tax</th>
                </tr>
            </thead>
            <?php
            $row = 0;
            foreach ($model as $modelRow):
                ?>
                <tbody id='row-<?php echo $row; ?>'>
                    <tr >
                        <td>
                            <input type="text" id="Commission_Person_<?php echo $row; ?>_state" class="Options_design" data-placement="right" data-toggle="tooltip" rel="tooltip"
                                   name="Commission[Person][<?php echo $row; ?>][state]" readonly data-original-title="enter state" value="<?php echo $modelRow->state; ?>">
                            <input type="hidden" id="Commission_Person_<?php echo $row; ?>_state_code" name="Commission[Person][<?php echo $row; ?>][state_code]"  value="<?php echo $modelRow->state_code; ?>">
                        </td>
                        
                        <td>
                            <input type="text" id="Commission_Person_<?php echo $row; ?>_tax" class="Options_design" data-placement="right" data-toggle="tooltip" rel="tooltip"
                                   name="Commission[Person][<?php echo $row; ?>][tax]" data-original-title="enter tax" value="<?php echo $modelRow->tax; ?>">
                        </td>
                    </tr>
                </tbody>
                <?php
                $row++;
            endforeach;
            ?>
            
        </table>
    </div>
</fieldset>