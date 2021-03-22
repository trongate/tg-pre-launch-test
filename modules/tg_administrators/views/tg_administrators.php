<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?= BASE_URL ?>css/trongate.css">
	<title>Trongate Administrators</title>
</head>
<body>
	<div id="top-gutter">
		<?php 
		echo anchor('#', '<span class="hide-sm">Manage Administrators</span> <i class="fa fa-users"></i>');
		echo anchor('#', '<span class="hide-sm">Your Account</span> <i class="fa fa-gears"></i>'); 
		echo anchor('#', '<span class="hide-sm">Logout</span> <i class="fa fa-sign-out"></i>'); 
		?>
	</div>
    <div class="container">
        <?= Template::display($data) ?>
    </div>
<style>
body {
    background: rgb(0,0,0);
    background: -webkit-linear-gradient(rgba(0,0,0,1) 0%, rgba(51,51,51,1) 35%, rgba(111,111,111,1) 100%);
    background: -o-linear-gradient(rgba(0,0,0,1) 0%, rgba(51,51,51,1) 35%, rgba(111,111,111,1) 100%);
    background: linear-gradient(rgba(0,0,0,1) 0%, rgba(51,51,51,1) 35%, rgba(111,111,111,1) 100%);
    background-repeat: no-repeat;
    background-size: cover;
    min-height: 100vh;
    color: #eee;
    font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
}

h1 { font-size: 2.4em; }
h2 { font-size: 2.0em; }
h3 { font-size: 1.7em; }
h4 { font-size: 1.5em; }
h5 { font-size: 1.2em; }

#top-gutter {
	line-height: 2.4em;
	padding: 0 12px;
	text-align: right;
}

#top-gutter a {
	color: #eee;
	text-decoration: none;
	margin-left: 24px;
}

#top-gutter a:hover {
	color: #fff;
	text-decoration: underline;
}

.container {
	background-color:transparent;
}

.hide-sm {
    display: none;
}

table {
	background-color: #fff;
	color: #000;
}

table button {
	margin: 0;
}

@media (max-width: 840px) {
	h1 { font-size: 1.6em; text-align: center; }
	h2 { font-size: 1.4em; text-align: center; }
	h3 { font-size: 1.3em; text-align: center; }
	h4 { font-size: 1.2em; text-align: center; }
	h5 { font-size: 1.1em; text-align: center; }
}

@media (min-width: 840px) {
    .hide-sm {
      display: inline-block;
    }

}
</style>
</body>
</html>