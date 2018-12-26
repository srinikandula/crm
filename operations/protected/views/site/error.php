<?php 
print_r($error);?>
<div class="error_msgs span9 offset1">
<div class="span4 myfirst_img">
<img src="<?php echo Yii::app()->baseUrl;?>/img/error_img.png" alt=""  />
</div>

<div class="span6 ">

<div class="main_design">
<h2 class="offset1"><?php echo $code; ?> !</h2>
<!--<h4 class="offset3">Page Not Found.</h4>-->
<div class="error">
<?php echo CHtml::encode($message); ?>
</div>
</div>

</div>

</div>