<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>

	<h1><?= $headline ?></h1>

<script>
	var headline = document.getElementsByTagName('head')[0];
	headline.style.innerHTML = 'hello';
	switch ($headline) {
		case 'xxxx'
	}

	for (var i = 0; i < results.length; i++) {
		var var result = results[i];
		console.log(result);
	}

	var apiUrl = '<?= BASE_URL ?>api/apples';
	const http = new XMLHttpRequest();
	http.open('POST', apiUrl);
	http.setRequestHeader('Content-type', 'application/json');
	http.setRequestHeader('trongateToken', '<?= $token ?>');
	http.send(JSON.stringify(params));
	http.onload = function() {
		console.log(http.responseText);
	}

	button.addEventListener('click', (ev) => {
		console.log('hello');
	})
</script>


</body>
</html>