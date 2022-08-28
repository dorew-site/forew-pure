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
require_once __DIR__ . '/header.php';

if ($cookie->is_login()) {
?>
    <div class="phdr"><i class="fa fa-tachometer" aria-hidden="true"></i> <b>Quản trị</b></div>
    <div class="list1"><i class="fa fa-pencil-square" aria-hidden="true"></i> <a href="/manager?act=post">Viết bài mới</a></div>
    <div class="list1"><i class="fa fa-cube" aria-hidden="true"></i> <a href="/manager?act=create-category">Quản lý chuyên mục</a></div>
<?php
}

$total = $QuerySQL->get_table_count('blog');
$per = $config['blog']['per_page'];
$namepage = 'page';
$page_max = ceil($total / $per);
$page = $request_method->get($namepage) ? $request_method->get($namepage) : 1;
if ($page >= $page_max) $page = $page_max;
if ($page < 1) $page = 1;
$start = ($page - 1) * $per;
?>
<div class="phdr"><b><i class="fa fa-book" aria-hidden="true"></i> Bài viết mới</b></div>
<?php
if ($total <= 0) {
    echo '<div class="menu">Chưa có bài viết nào!</div>';
} else {
    foreach ($QuerySQL->query_select_table('blog', '*', "ORDER BY time DESC LIMIT $start,$per ") as $blog) {
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
    echo $home->paging('?page=', $page, $page_max);
}
?>
<div class="phdr"><b><i class="fa fa-bars"></i> Chuyên mục</b>
    <a href="/search" style="float:right" title="Tìm"><button type="submit" name="submit"><i class="fa fa-search" aria-hidden="true"></i> Tìm</button></a>
</div>
<?php
if ($QuerySQL->get_table_count('category') <= 0) {
    echo '<div class="menu">Chưa có chuyên mục nào!</div>';
} else {
    $categories = $QuerySQL->select_table_data('category', 'id', 'DESC');
    foreach ($categories as $category) {
?>
        <div class="list1">
            <a href="/category/<?php echo $category['id'] . '-' . $category['slug'] ?>/">
                <i class="fa fa-cube" aria-hidden="true"></i> <?php echo $category['name'] ?>
            </a>
            (<?php
                echo $QuerySQL->get_row_count('blog', [
                    'category' => $category['id'],
                    'operator' => '='
                ]);
                ?>)
        </div>
<?php
    }
}
require_once __DIR__ . '/footer.php';
