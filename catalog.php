<?php
include("inc/functions.php");

$pageTitle = "Full Catalog";
$section = null;
$search = null;
$item_per_page = 8;


if (isset($_GET["cat"])) {
    if ($_GET["cat"] == "books") {
        $pageTitle = "Books";
        $section = "books";
    } else if ($_GET["cat"] == "movies") {
        $pageTitle = "Movies";
        $section = "movies";
    } else if ($_GET["cat"] == "music") {
        $pageTitle = "Music";
        $section = "music";
    }
}

if (isset($_GET["s"])) {
    $search = filter_input(INPUT_GET, "s", FILTER_SANITIZE_STRING);
}
if (isset($_GET["pg"])) {
    $current_page = filter_input(INPUT_GET, "pg", FILTER_SANITIZE_NUMBER_INT);
}
if (empty($current_page)) {
    $current_page = 1;
}

$total_items = get_catalog_count($section, $search);
$total_pages = 1;
$offset = 0;
//$pagination = "";

if ($total_items > 0  ) {
    $total_pages = ceil($total_items / $item_per_page);

    $limit_result = "";
    if (!empty($search)){
        $limit_result = "s=" . urlencode(htmlspecialchars($search)) . "&";
    } elseif (!empty($section)) {
        $limit_result = "cat=" . $section . "&";
    }

    if ($current_page > $total_pages) {
        header("location:catalog.php?" . $limit_result . "pg=" . $total_pages);
    }
    if ($current_page < 1) {
        header("location:catalog.php?" . $limit_result . "pg=1");
    }

    $offset = ($current_page - 1) * $item_per_page;

    $pagination = "<div class=\"pagination\">";
    $pagination .= "Pages:";
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $current_page) {
            $pagination .= " <span>$i</span>";
        } else {
            $pagination .= " <a href='catalog.php?";
            if (!empty($search)){
                $pagination .= "s=" . urlencode(htmlspecialchars($search)) . "&";
            } elseif (!empty($section)) {
                $pagination .= "cat=" . $section . "&";
            }
            $pagination .= "pg=$i'>$i</a>";
        }
    }
    $pagination .= "</div>";
}

if (!empty($search)) {
    $catalog = search_catalog_array($search, $item_per_page, $offset);
} elseif (empty($section)) {
    $catalog = full_catalog_array($item_per_page, $offset);
} else {
    $catalog = category_catalog_array($section, $item_per_page, $offset);
}




include("inc/header.php"); ?>

    <div class="section catalog page">

        <div class="wrapper">

            <h1><?php
                if ($search != null) {
                    echo "Search results for\"" . htmlspecialchars($search) . "\"";
                } else {
                    if ($section != null) {
                        echo "<a href='catalog.php'>Full Catalog</a> &gt; ";
                    }
                    echo $pageTitle;
                } ?></h1>
            <?php
            if ($total_items < 1) {
                echo "<p>No items were found matching that search term.</p>";
                echo "<p>Search Again or" . "<a href = '/catalog.php'>Browse the Full Catalog</a></p>";
            } else {
                echo $pagination ;
                ?>
                <ul class="items">
                    <?php
                    foreach ($catalog as $item) {
                        echo get_item_html($item);
                    }
                    ?>
                </ul>
                <?php echo $pagination;
            } ?>

        </div>
    </div>

<?php include("inc/footer.php"); ?>