<?php

/**
 * DorewSite Software
 * Version: Pure PHP
 * Author: Dorew
 * Email: khanh65me1@gmail.com or awginao@protonmail.com
 * Website: https://dorew.gq
 * Copyright: (C) 2022 Dorew All Rights Reserved.
 * This file is part of the source code.
 */

define('_DOREW', 1);

require_once dirname(__DIR__) . '/system/config.php';
$title = 'Tìm kiếm';
require_once __DIR__ . '/header.php';
$query = $request_method->get('query');
?>
<div class="phdr" style="font-weight:700"><i class="fa fa-search" aria-hidden="true"></i> Tìm kiếm trong trang </div>
<div class="menu">
    <form method="get">
        <table style="width:100%">
            <tr>
                <td style="width:80%"><input type="text" value="<?php echo $query; ?>" name="query" class="form-control" /></td>
                <td style="text-align:center;width:20%"><button type="submit" class="btn btn-default btn-block"><i class="fa fa-search" aria-hidden="true"></i> Tìm</button></td>
            </tr>
        </table>
        <div style="font-size:15px">
            <input class="w3-check" type="checkbox" name="act" value="google"> Tìm kiếm với Google
        </div>
    </form>
</div>
<?php
if (!in_array($query, ['', ' ', null, NULL])) {
    if ($request_method->get('act') == 'google') {
        header('Location: https://www.google.com/search?q=' . $query . '+site:' . $config['blog']['domain_search_google']);
    }
    $list_id_blog = [];
    $list_blog = $QuerySQL->query_select_table('blog', '*', 'WHERE title LIKE "%' . $query . '%" OR content LIKE "%' . $query . '%"');
    foreach ($list_blog as $blog) {
        if ($blog['id'] >= 1 && $blog['title']) {
            $list_id_blog[] = $blog['id'];
        }
    }
    $list_id_blog = array_unique($list_id_blog);
    $total = count($list_id_blog);
    $per = $config['blog']['per_page'];
    $namepage = 'page';
    $page_max = ceil($total / $per);
    $page = $request_method->get($namepage) ? $request_method->get($namepage) : 1;
    if ($page >= $page_max) $page = $page_max;
    if ($page < 1) $page = 1;
    $start = ($page - 1) * $per;
    if ($total <= 0) {
        echo '<div class="rmenu">Không tìm thấy kết quả nào!</div>';
    } else {
        $list_blog = array_slice($list_id_blog, $start, $per);
        foreach ($list_blog as $blog_id) {
            $blog = $QuerySQL->select_table_row_data('blog', 'id', $blog_id);
            $cat = $QuerySQL->select_table_row_data('category', 'id', $blog['category']);
            $topic_cat_color = ['red', 'blue', 'green', 'orange'];
            $topic_cat_color = $topic_cat_color[array_rand($topic_cat_color)];
?>
            <div class="list1">
                <a href="/view/<?php echo $blog['id'] . '-' . $blog['slug']; ?>.html">
                    <span class="topic cat_<?php echo $topic_cat_color ?>"> <?php echo $cat['name'] ?></span>
                    <?php echo html_entity_decode($blog['title'], ENT_QUOTES, 'UTF-8') ?>
                </a>
            </div>
<?php
        }
        echo $home->paging('?query=' . $query .'&page=', $page, $page_max);
    }
}
require_once __DIR__ . '/footer.php';