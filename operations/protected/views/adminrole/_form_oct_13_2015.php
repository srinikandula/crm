<div class="row-fluid">
    <div class="tab-pane active" id="Information">
        <div class="span12 pull-left">
            <div class="span12">
                <fieldset class="portlet" >
                    <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                        <div class="span11">Role Details</div>
                        <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" id="hide_box_btn2" type="button" ></button> </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="portlet-content" id="hide_box_line1">
                        <div class="span5"> <?php
                            echo $form->textFieldRow(
                                    $model, 'role', array('rel' => 'tooltip','data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                        <div class="span5">  <?php
                            echo $form->radioButtonListRow($model, 'status', array('1' => 'Enable', '0' => 'Disable'));
                            ?></div>
						<div class="span12"><b>Caution:</b> Administrators should be given only view and edit permission to see and edit there profile.</div>

                        <?php $i=0;
						 foreach($permissions as $k=>$v){ 
							 $show=0;
							 foreach($v as $l=>$m)
							 {
								 if($result[$k][$m[file][file_name]]['view']=='Y'){ //echo '<pre>';print_r($m); echo '</pre>'; echo $m['file']['view']."inside <br/>"; 
									 $show=1; break; }
							 }
							 //echo $show."<br/>";
							 //echo '<pre>';print_r($v);echo '</pre>';exit;?>
                        
                        <table  cellpadding="0" border="0" class="designs-forms">
                            <tbody>
                                <tr class="permissions-div">
                                    <td onclick=" $('.hide_box_line<?php echo $i; ?>').slideToggle();"><b>&nbsp;&nbsp;<?php echo ucfirst($k);?></b></td>
                                </tr>
                                <tr id="SUB_customers" class="hide_box_line<?php echo $i; ?>" <?php if($show==1){?>style="display:table-row"<?php }?>>
                                    <td style="padding-left: 9px;">
                                        <table cellspacing="0" cellpadding="0" border="0" class="middle table">
                                            <tbody>
                                                <tr class="sub-title-div">
                                                    <td width="25%" align="left" class="admin-role-div-name"><strong>Section Name</strong></td>
                                                    <td width="12%" align="center" class="admin-role-div"><strong>View</strong></td>
                                                    <td width="15%" align="center" class="admin-role-div"><strong>Add</strong></td>
                                                    <td width="18%" align="center" class="admin-role-div"><strong>Edit</strong></td>
                                                    <td width="8%" align="center" class="admin-role-div"><strong>Delete</strong></td>
                                                </tr>
                                                <?php 
                                                
                                                foreach($v as $subK=>$subV){
													//echo '<pre>';print_r($subV);echo '</pre>';?>
												<input type="hidden"   name="<?php echo 'permissions['.$k.']['.$subV[file][file_name].'][title]';?>" value="<?php echo $subV['file']['title']; ?>">

                                                <tr class="sub-links-div">
                                                    <td height="22" align="left" class="admin-role-div-name"><?php echo  $this->menuTitle[$subV['file']['file_name']];?></td>
                                                    <td height="22" align="center" class="admin-role-div"><input type="checkbox" <?php echo $subV['file']['view']!='Y'?'disabled':''; ?>  <?php echo $result[$k][$subV[file][file_name]]['view']=='Y'?'checked':''; ?>  name="<?php echo 'permissions['.$k.']['.$subV[file][file_name].'][view]';?>" value="Y"></td>
                                                    <td height="22" align="center" class="admin-role-div"><input type="checkbox" <?php echo $subV['file']['add']!='Y'?'disabled':''; ?> <?php echo $result[$k][$subV[file][file_name]]['add']=='Y'?'checked':''; ?>  name="<?php echo 'permissions['.$k.']['.$subV[file][file_name].'][add]';?>" value="Y"></td>
                                                    <td height="22" align="center" class="admin-role-div"><input type="checkbox" <?php echo $subV['file']['edit']!='Y'?'disabled':''; ?>  <?php echo $result[$k][$subV[file][file_name]]['edit']=='Y'?'checked':''; ?> name="<?php echo 'permissions['.$k.']['.$subV[file][file_name].'][edit]';?>" value="Y"></td>
                                                    <td height="22" align="center" class="admin-role-div"><input type="checkbox" <?php echo $subV['file']['trash']!='Y'?'disabled':''; ?>  <?php echo $result[$k][$subV[file][file_name]]['trash']=='Y'?'checked':''; ?> name="<?php echo 'permissions['.$k.']['.$subV[file][file_name].'][trash]';?>" value="Y"></td>
                                                <input type="hidden" name="<?php echo 'permissions['.$k.']['.$subV[file][file_name].'][file_sort_order]'?>" value="<?php echo  $subV['file']['file_sort_order'];?>">
                                                <input type="hidden" name="<?php echo 'permissions['.$k.']['.$subV[file][file_name].'][module_sort_order]'?>" value="<?php echo $subV['module_sort_order'];?>">
                                                <input type="hidden" name="<?php echo 'permissions['.$k.']['.$subV[file][file_name].'][menu_type]'?>" value="<?php echo $subV['menu_type'];?>">
                                                </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php $i++; }?>
                    </div>
                </fieldset>
            </div>  
        </div>
    </div>
</div>