<div class="row-fluid design_dsm">


    <div class="logo"><img src="<?php  echo $baseUrl;?>/operations/img/logo.jpg" width="20%"></div>
    <h2>welcome <?php echo ucfirst($_SESSION['franchise']);?>!</h2>
    <div class="clearfix"></div>
</div><!--/row-->


<script type="text/javascript">
$.ajax({
  dataType: "json",
  url: 'http://localhost:8001/api/member/track_truck/AP29AR9739',
  data: {days : 5},
  //success: success
});
</script>