<!-- <div class="alert alert-success"><button data-dismiss="alert" class="close" type="button">x</button><i class="icon-ok icon-white"></i> <strong>Well done!</strong> You successfully read this important alert message.</div>
<div class="alert alert-error"><button data-dismiss="alert" class="close" type="button">x</button><i class="icon-remove icon-white"></i> Error: No match for E-Mail Address and/or Password.</div>
<div class="alert alert-notice"><button data-dismiss="alert" class="close" type="button">x</button><i class="icon-exclamation-sign icon-white"></i> Selected Products Modified Successfully!</div> -->
<?php 
    foreach($this->flashMessages as $key => $message):
	echo '<div class="alert alert-'.$key.'"><button data-dismiss="alert" class="close" type="button">x</button><i class="icon-'.$key.' icon-white"></i> <strong>'.$message.'</strong></div>';
    endforeach;
?>