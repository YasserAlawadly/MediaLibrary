<?php
include("inc/functions.php");

if (isset($_GET["id"])) {
    $id = filter_input(INPUT_GET,"id",FILTER_SANITIZE_NUMBER_INT);
    $item = single_item_array($id);
}

if (empty($item)) {
    header("location:catalog.php");
    exit;
}

$pageTitle = $item["title"];
$section = null;

include("inc/header.php"); ?>

<div class="section page">

    <div class="wrapper">
        
        <div class="breadcrumbs">
            <a href="catalog.php">Full Catalog</a>
            &gt; <a href="catalog.php?cat=<?php echo strtolower($item["category"]); ?>">
            <?php echo $item["category"]; ?></a>
            &gt; <?php echo $item["title"]; ?>
        </div>
        
        <div class="media-picture">
    
        <span>
            <img src="<?php echo $item["img"]; ?>" alt="<?php echo $item["title"]; ?>" />
        </span>
            
        </div>
        
        <div class="media-details">
        
            <h1><?php echo $item["title"]; ?></h1>
            <table>
            
                <tr>
                    <th>Category</th>
                    <td><?php echo $item["category"]; ?></td>
                </tr>
                <tr>
                    <th>Genre</th>
                    <td><?php echo $item["genre"]; ?></td>
                </tr>
                <tr>
                    <th>Format</th>
                    <td><?php echo $item["format"]; ?></td>
                </tr>
                <tr>
                    <th>Year</th>
                    <td><?php echo $item["year"]; ?></td>
                </tr>
                <?php if (strtolower($item["category"]) == "books") { ?>
                <tr>
                    <th>Authors</th>
                    <td><?php echo implode(", ",$item["author"]); ?></td>
                </tr>
                <tr>
                    <th>Publisher</th>
                    <td><?php echo $item["publisher"]; ?></td>
                </tr>
                <tr>
                    <th>ISBN</th>
                    <td><?php echo $item["isbn"]; ?></td>
                </tr>    
                <?php } else if (strtolower($item["category"]) == "movies") { ?>
                <tr>
                    <th>Director</th>
                    <td><?php echo implode(", ",$item["director"]); ?></td>
                </tr>
                <tr>
                    <th>Writers</th>
                    <td><?php echo implode(", ",$item["writer"]); ?></td>
                </tr>
                <tr>
                    <th>Stars</th>
                    <td><?php echo implode(", ",$item["star"]); ?></td>
                </tr>
                <?php } else if (strtolower($item["category"]) == "music") { ?>
                <tr>
                    <th>Artist</th>
                    <td><?php echo implode(", ",$item["artist"]); ?></td>
                </tr>
                <?php } ?>
            </table>
        
        </div>
    
    </div>

</div>