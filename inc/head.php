<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sugar Log</title>
    <link rel="stylesheet" href="inc/bootstrap.css">
    <link rel="stylesheet" href="inc/timeclock.css">	
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="inc/bootstrap.min.css" rel="stylesheet">
	<link href="inc/bootstrap-slider.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script>
	function ToggleSearch() {
	  var x = document.getElementById('searchbar');
	  var y = document.getElementById('toggle');
	  if (x.style.display === "none") {
		x.style.display = "block";
		y.innerHTML='Hide';
	  } else {
		x.style.display = "none";
		y.innerHTML='Show';
	  }
	}
	</script>
	<script>
	function hideByDiv(divid) {
		td1=document.getElementById('headdate');
		td2=document.getElementById('headtime');
		td3=document.getElementById('headlevel');
		td4=document.getElementById('headsubmit');
		var tbl = document.getElementById('maintable');
		var td = tbl.rows[0].cells[0];
		
		
		var x = document.getElementById(divid);
		if (x.style.display === "none") {
		x.style.display = "block";
		} else {
		x.style.display = "none";
		}
	}
	</script>
</head>
<body>