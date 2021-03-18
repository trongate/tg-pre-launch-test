<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<ul>
		<li><?= anchor('build/clear_members_stuff', 'Clear Members Stuff') ?></li>
	</ul>

<script>
const http = new XMLHttpRequest();
http.open('get', 'http://localhost/football/build/get_sample_content');
http.setRequestHeader('Content-type', 'application/json');
http.send();
http.onload = function() {

	var fileContent = http.responseText;
	extractModuleSingularAndPlural(fileContent);
}




function extractModuleSingularAndPlural(fileContent) {
    var test_str = fileContent
    var start_pos = test_str.indexOf('View All ') + 9
    var end_pos = test_str.indexOf('</button>',start_pos)
    var plural = test_str.substring(start_pos,end_pos)

    var startPos2 = test_str.indexOf('You are about to delete a');
    var endPos2 = test_str.indexOf(' record. This cannot be undone.');
    var singular = test_str.substring(startPos2,endPos2);
    var ditch = 'You are about to delete ';
    singular = singular.replace(ditch, '');

    var firstTwo = singular.substring(0, 2);

    if (firstTwo == 'a ') {
    	singular = singular.replace(firstTwo, '');
    } else {
    	singular = singular.replace('an ', '');
    }

    singular = singular.toLowerCase();
    plural = plural.toLowerCase();

    var result = {
        singular,
        plural
    }

    return result
}


</script>
</body>
</html>