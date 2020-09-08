<html>
<head>
	<title><?php echo $pageTitle; ?></title>
	<link rel="stylesheet" href="css/style.css" type="text/css">
    <style>
        .search form
        {
            margin: 10px 0 10px auto;
            width: 300px;
        }
        .search form input[type="submit"]
        {
            padding: 6px;
            width: auto;
        }
    </style>
</head>
<body>

	<div class="header">

		<div class="wrapper">

			<h1 class="branding-title"><a href="/treehouse/MediaProject">Personal Media Library</a></h1>

			<ul class="nav">
                <li class="books<?php if ($section == "books") { echo " on"; } ?>"><a href="catalog.php?cat=books">Books</a></li>
                <li class="movies<?php if ($section == "movies") { echo " on"; } ?>"><a href="catalog.php?cat=movies">Movies</a></li>
                <li class="music<?php if ($section == "music") { echo " on"; } ?>"><a href="catalog.php?cat=music">Music</a></li>
                <li class="suggest<?php if ($section == "suggest") { echo " on"; } ?>"><a href="suggest.php">Suggest</a></li>
            </ul>

		</div>

	</div>

    <div class="search">
        <form method="get" action="catalog.php">
            <label for="s">Search:</label>
            <input type="text" name="s" id="s" />
            <input type="submit" value="go" />
        </form>
    </div>

	<div id="content">
