<?php

function get_catalog_count($category = null, $search = null)
{
    $category = strtolower($category);
    include "connection.php";
    try {
        $sql = "SELECT COUNT(media_id) FROM media";
        if (!empty($search)) {
            $result = $db->prepare($sql . " WHERE title LIKE ?");
            $result->bindValue(1,"%".$search."%",PDO::PARAM_STR);
        } elseif (!empty($category)) {
            $result = $db->prepare($sql . " WHERE LOWER(category) = ?");
            $result->bindParam(1, $category, PDO::PARAM_STR);
        } else {
            $result = $db->prepare($sql);
        }
        $result->execute();

    } catch (Exception $e) {
        echo $e->getMessage();
        exit();
    }

    $count = $result->fetchColumn(0);
    return $count;
}

function genre_array($category = null)
{
    $category = strtolower($category);
    include "connection.php";
    try {
        $sql = "SELECT genre , category FROM genres INNER JOIN genre_categories ON genres.genre_id = genre_categories.genre_id";
        if (!empty($category)) {
            $result = $db->prepare($sql . " WHERE LOWER(category) = ? ORDER BY genre");
            $result->bindParam(1, $category, PDO::PARAM_STR);
        } else {
            $result = $db->prepare($sql . " ORDER BY genre");
        }
        $result->execute();

    } catch (Exception $e) {
        echo $e->getMessage();
        exit();
    }

    $genres = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $genres[$row["category"]][] = $row["genre"];
    }
    return $genres;
}

function full_catalog_array($limit = null, $offset = 0)
{
    include "connection.php";

    try {
        $sql = "SELECT media_id, title, category , img FROM media 
        ORDER BY 
            REPLACE(
           REPLACE(
              REPLACE(title,'The ',''),
              'An ',
              ''
           ),
           'A ',
           ''
         )";

        if (is_integer($limit)) {
            $result = $db->prepare($sql . " LIMIT ? OFFSET ?");
            $result->bindParam(1, $limit, PDO::PARAM_INT);
            $result->bindParam(2, $offset, PDO::PARAM_INT);
        } else {
            $result = $db->prepare($sql);
        }
        $result->execute();

    } catch (Exception $e) {
        echo $e->getMessage();
        exit();
    }
    $catalog = $result->fetchAll(PDO::FETCH_ASSOC);
    return $catalog;
}

function category_catalog_array($category, $limit = null, $offset = 0)
{
    include "connection.php";
    $category = strtolower($category);
    try {
        $sql = "SELECT media_id, title, category , img FROM media WHERE LOWER(category) = ?
            ORDER BY 
            REPLACE(
           REPLACE(
              REPLACE(title,'The ',''),
              'An ',
              ''
           ),
           'A ',
           ''
         )";

        if (is_integer($limit)) {
            $result = $db->prepare($sql . " LIMIT ? OFFSET ?");
            $result->bindParam(1, $category, PDO::PARAM_STR);
            $result->bindParam(2, $limit, PDO::PARAM_INT);
            $result->bindParam(3, $offset, PDO::PARAM_INT);
        } else {
            $result = $db->prepare($sql);
            $result->bindParam(1, $category, PDO::PARAM_STR);
        }
        $result->execute();

    } catch (Exception $e) {
        echo $e->getMessage();
        exit();
    }
    $catalog = $result->fetchAll(PDO::FETCH_ASSOC);
    return $catalog;
}


function search_catalog_array($search, $limit = null, $offset = 0)
{
    include "connection.php";
    try {
        $sql = "SELECT media_id, title, category , img FROM media WHERE title LIKE ?
            ORDER BY 
            REPLACE(
           REPLACE(
              REPLACE(title,'The ',''),
              'An ',
              ''
           ),
           'A ',
           ''
         )";

        if (is_integer($limit)) {
            $result = $db->prepare($sql . " LIMIT ? OFFSET ?");
            $result->bindValue(1, "%". $search . "%", PDO::PARAM_STR);
            $result->bindParam(2, $limit, PDO::PARAM_INT);
            $result->bindParam(3, $offset, PDO::PARAM_INT);
        } else {
            $result = $db->prepare($sql);
            $result->bindValue(1, '%'. $search . '%', PDO::PARAM_STR);
        }
        $result->execute();

    } catch (Exception $e) {
        echo $e->getMessage();
        exit();
    }
    $catalog = $result->fetchAll(PDO::FETCH_ASSOC);
    return $catalog;
}

function random_catalog_array()
{
    include "connection.php";

    try {
        $result = $db->query("SELECT media_id, title, category , img FROM media ORDER BY RAND() LIMIT 4");
    } catch (Exception $e) {
        echo $e->getMessage();
        exit();
    }
    $catalog = $result->fetchAll(PDO::FETCH_ASSOC);
    return $catalog;
}

function single_item_array($id)
{
    include "connection.php";

    try {
        $result = $db->prepare("SELECT media.media_id, title, category , img, format, year, genre , publisher, isbn FROM Media
                                INNER JOIN genres ON media.genre_id = genres.genre_id
                                LEFT OUTER JOIN books ON media.media_id = books.media_id
                                WHERE media.media_id = ?");
        $result->bindParam(1, $id, PDO::PARAM_INT);
        $result->execute();
    } catch (Exception $e) {
        echo $e->getMessage();
        exit();
    }

    $item = $result->fetch(PDO::FETCH_ASSOC);
    if (empty($item)) return $item;

    try {
        $result = $db->prepare("SELECT fullname, role FROM media_people
                                INNER JOIN people ON media_people.people_id = people.people_id
                                WHERE media_people.media_id = ?");
        $result->bindParam(1, $id, PDO::PARAM_INT);
        $result->execute();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $item[$row["role"]][] = $row["fullname"];
        }
    } catch (Exception $e) {
        echo $e->getMessage();
        exit();
    }
    return $item;
}

function get_item_html($item)
{
    $output = "<li><a href='details.php?id="
        . $item["media_id"] . "'><img src='"
        . $item["img"] . "' alt='"
        . $item["title"] . "' />"
        . "<p>View Details</p>"
        . "</a></li>";
    return $output;
}

function array_category($catalog, $category)
{
    $output = array();

    foreach ($catalog as $id => $item) {
        if ($category == null or strtolower($category) == strtolower($item["category"])) {
            $sort = $item["title"];
            $sort = ltrim($sort, "The ");
            $sort = ltrim($sort, "A ");
            $sort = ltrim($sort, "An ");
            $output[$id] = $sort;
        }
    }

    asort($output);
    return array_keys($output);
}