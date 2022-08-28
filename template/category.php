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

$category = $QuerySQL->select_table_row_data('category', 'id', $request_method->get('id'));
if ($category['id'] >= 1) {
    $title = html_entity_decode($category['name'], ENT_QUOTES, 'UTF-8');
} else  $title = 'Chuyên mục';

require_once __DIR__ . '/header.php';

if ($category['id'] >= 1) {
    $total = $QuerySQL->get_row_count('blog', [
        'category' => $category['id'],
        'operator' => '='
    ]);
    $per = $config['blog']['per_page'];
    $namepage = 'page';
    $page_max = ceil($total / $per);
    $page = $request_method->get($namepage) ? $request_method->get($namepage) : 1;
    if ($page >= $page_max) $page = $page_max;
    if ($page < 1) $page = 1;
    $start = ($page - 1) * $per;
    $id_cat = $category['id'];
?>
    <div class="phdr"><a href="/"><i class="fa fa-home" aria-hidden="true"></i> Trang chủ</a> » <b><?php echo html_entity_decode($category['name'], ENT_QUOTES, 'UTF-8'); ?></b></div>
    <div class="gmenu"><a href="/search"><i class="fa fa-search" aria-hidden="true"></i> Tìm kiếm</a></div>
    <?php
    if ($total <= 0) {
        echo '<div class="menu">Chưa có bài viết nào!</div>';
    } else {
        foreach ($QuerySQL->query_select_table('blog', '*', "WHERE `category` = '$id_cat' ORDER BY time DESC LIMIT $start,$per ") as $blog) {
    ?>
            <div class="list1">
                <a href="/view/<?php echo $blog['id'] . '-' . $blog['slug']; ?>.html">
                    <i class="fa fa-rss" aria-hidden="true"></i>
                    <?php echo html_entity_decode($blog['title'], ENT_QUOTES, 'UTF-8') ?>
                </a>
            </div>
    <?php
        }
        echo $home->paging('?page=', $page, $page_max);
    }
} else {
    ?>
    <div class="phdr"><b><i class="fa fa-bars"></i> Chuyên mục</b>
        <a href="/search" style="float:right" title="Tìm"><button type="submit" name="submit"><i class="fa fa-search" aria-hidden="true"></i> Tìm</button></a>
    </div>
    <?php
    $total = $QuerySQL->get_table_count('category');
    if ($total <= 0) {
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
}

require_once __DIR__ . '/footer.php';
