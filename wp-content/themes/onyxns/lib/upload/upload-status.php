<?php

//Get file upload progress information.
if(isset($_GET['progress_key']) ) {
    $status = apc_fetch('upload_'.$_GET['progress_key']);
    
    if (! isset($status['total']) || $status['total'] == 0){echo "0"; die();}
    
    echo intval($status['current']/$status['total']*100);
    //echo intval( rand(1,100));
    die();
}

$url = basename($_SERVER['SCRIPT_FILENAME']);

if(isset ( $_GET['theme_url'] ) ) $theme_url = urldecode( $_GET['theme_url']);
?>
<!DOCTYPE html>
<head>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $theme_url; ?>/bootstrap/css/bootstrap.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js" type="text/javascript"></script>
<script src="<?php echo $theme_url; ?>/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

<script>
jQuery(document).ready(function() { 
//
    timer = setInterval(function() 
        {
    $.get("<?php echo $url; ?>?progress_key=<?php echo $_GET['up_id']; ?>&randval="+ Math.random(), { 
        //get request to the current URL (upload_frame.php) which calls the code at the top of the page.  It checks the file's progress based on the file id "progress_key=" and returns the value with the function below:
    },
        function(data)    //return information back from jQuery's get request
            {
                $('.bar').css("width",data+"%");    //set width of progress bar based on the $status value (set at the top of this page)
                $('.number').html(data+"%");
                if (data == 100) clearInterval(timer);
            }
        )},300);    //Interval is set at 500 milliseconds (the progress bar will refresh every .5 seconds)

});


</script> 
<style type="text/css">
	body{
		padding:10px 0;
	}
	div {
		padding: 0;
		margin: 0;
	}
	.progress{
		margin-bottom: 1px !important;
	}
	.number{
	font-weight: bold;
	}
</style>
</head>
<body style="margin:0px">
<!--Progress bar divs-->
<div class="progress progress-striped active">
    <div class="bar" style="width: 0%;"></div>
</div>
<div class="number">0%</div>
<!---->
</body>
</html>