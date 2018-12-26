<footer>
<div class="subnav navbar navbar-fixed-bottom">
        <div class="container">
            Powered by <a href="http://www.easygaadi.com" target="_new">easygaadi.com</a>. All Rights Reserved.
        </div>
</div>      
</footer>
<script type="text/javascript">
function fnupload(id){
	//window.open("<?php echo $this->createUrl('cagent/export');?>/id/"+id, "", "width=200, height=100,menubar=0,titlebar=0,status=0,toolbar=0");

  var w='600';
  var h='400';
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open("<?php echo $this->createUrl('cagent/export');?>/id/"+id, 'Upload Trucks/Prices', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);

}

function fnorder(id,p){
	//window.open("<?php echo $this->createUrl('cagent/export');?>/id/"+id, "", "width=200, height=100,menubar=0,titlebar=0,status=0,toolbar=0");

  var w='1000';
  var h='600';
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open("<?php echo $this->createUrl('order/addorder');?>/id/"+id+"/p/"+p, 'Book A Truck', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);

}

function fnordercustomer(id,p){
	//window.open("<?php echo $this->createUrl('cagent/export');?>/id/"+id, "", "width=200, height=100,menubar=0,titlebar=0,status=0,toolbar=0");

  var w='1000';
  var h='600';
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open("<?php echo $this->createUrl('order/addorder');?>/id/"+id+"/p/"+p+"/type/customer", 'Book A Truck', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);

}
</script>
