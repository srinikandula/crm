<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo Yii::app()->name;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content=" themes, free web application theme">
    <meta name="author" content="cartnex.org">
      <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	<?php
	  //$baseUrl = Yii::app()->theme->baseUrl; 
	  $baseUrl = Yii::app()->baseUrl; 
	  $cs = Yii::app()->getClientScript();
	  Yii::app()->clientScript->registerCoreScript('jquery');
	?>
    <!-- Fav and Touch and touch icons -->
    <link rel="shortcut icon" href="<?php echo $baseUrl;?>/img/icons/favicon.ico">
	<?php  
	  $cs->registerCssFile($baseUrl.'/css/bootstrap.min.css');
	  $cs->registerCssFile($baseUrl.'/css/abound.css');
	  ?>
      <!-- styles for style switcher -->
        <?php
	  $cs->registerScriptFile($baseUrl.'/js/bootstrap.min.js');
	?>
  </head>
  <body class="login_pages">
    <section class="main-body">
    <div class="container-fluid ">
          <?php echo $content; ?>
    </div>
   </section>
 </body>
</html>