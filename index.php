<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Is Analytics Set?</title>
<link rel="stylesheet" href="style.css" media="screen">
</head>
<body>

<div id="progress">
	<div class="bar">&nbsp;</div>
	<div class="msg">Please Wait...</div> 
	<a href="#" class="btn" id="start">Start</a>
</div>

<div id="results">
	<div id="errors"></div>

	<h2>Site scripts in the <code><?=htmlspecialchars('<head>');?></code>:</h2> 
	<hr><br>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="verify-analytics.js"></script>
</body>
</html>