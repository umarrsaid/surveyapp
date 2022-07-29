<!DOCTYPE html>
<html>
<head>
	<title>{!! $data['name'] !!}</title>
</head>
<body>
	<?php
	header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
	header("Accept-Language: en-US,en;q=0.9,id;q=0.8");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	header("Content-Disposition: attachment; filename=".$data['name'].".xls");
	?>
{!! $data['table'] !!}
</body>
</html>