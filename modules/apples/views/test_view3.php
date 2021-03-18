<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
<script>
var apiUrl = '<?= BASE_URL ?>api/';
const http = new XMLHttpRequest();
http.open('GET', apiUrl);
http.setRequestHeader('Content-type', 'application/json');
http.setRequestHeader('trongateToken', '<?= $token ?>');
http.send(JSON.stringify(params));
http.onload == function() {
	console.log(JSON.stringify(params));
	console.log('check1');
}
</script>
</body>
</html>