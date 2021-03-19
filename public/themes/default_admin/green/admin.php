<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<header>header</header>
	<div class="wrapper">
	    <div id="sidebar">
	        <h3>Menu</h3>
	        <nav id="left-nav">
	            <?= Template::partial('partials/admin/dynamic_nav') ?>
	        </nav>       
	    </div>
	    <div>
	        <main><?= Template::display($data) ?></main>
	        <footer>
	            <div>Footer</div>
	            <div>Powered by <?= anchor('https://trongate.io', 'Trongate') ?></div>
	        </footer>
	    </div>	
	</div>
	<style>
		body {
			margin: 0;
			min-height: 100vh;
			display: flex;
			flex-direction: column;
		}

		.wrapper {
			flex: 1;
			display: flex;
			flex-direction: row;
		}

	    .wrapper > div:nth-child(1) {
	        display: block;
	        background-color: #eee;
	        min-width: 250px;
	    }

		.wrapper > div:nth-child(2) {
			flex: 1;
		    display: flex;
		    flex-direction: column;
		    justify-content: space-between;
		}
	</style>
</body>
</html>