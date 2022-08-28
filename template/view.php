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

$blog = $QuerySQL->select_table_row_data('blog', 'id', $request_method->get('id'));
if ($blog['id'] >= 1) {
    $title = html_entity_decode($blog['title'], ENT_QUOTES, 'UTF-8');
    $QuerySQL->update_row_table('blog', 'view', ($blog['view'] + 1), 'id', $blog['id']);
} else {
    header('Location: /category');
    exit;
}

require_once __DIR__ . '/header.php';

if ($blog['id'] >= 1) {
    $category = $QuerySQL->select_table_row_data('category', 'id', $blog['category']);
?>
    <div class="phdr">
        <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemscope="itemscope"><a itemprop="url" href="/"><span itemprop="title"><i class="fa fa-home" aria-hidden="true"></i> Trang chủ</span></a></span>
        » <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemscope="itemscope"><a itemprop="url" href="<?php echo '/category/' . $blog['category']; ?>"><span itemprop="title"><?php echo html_entity_decode($category['name'], ENT_QUOTES, 'UTF-8'); ?></span></a></span>
    </div>
    <div class="phdr" style="font-weight:bold;"><i class="fa fa-spinner fa-pulse fa-fw"></i><?php echo html_entity_decode($blog['title'], ENT_NOQUOTES, 'UTF-8'); ?></div>

    <div class="blog_info">
        <div class="thumb_blog"><img src="https://images.weserv.nl/?url=<?php echo $config['blog']['author']['avatar']; ?>&w=120&h=120&mask=circle" alt="Avatar" width="60px" height="60px"></div>
        <div class="more_info">
            <span class="row tit"><i class="fa fa-github"></i> <a href="<?php echo $config['blog']['author']['url']; ?>"><?php echo $config['blog']['author']['name']; ?></a></span>
            <span class="row more"><i class="fa fa-clock-o"></i> <?php echo $home->ago($blog['time']); ?>, <i class="fa fa-eye"></i> <?php echo $blog['view']; ?></span>
            <span class="row more">
                <label>
                    <a href="http://www.facebook.com/sharer.php?u=<?php echo $home->current_url(); ?>" target="_blank" title="Share Facebook"><i class="fa fa-facebook-square fa-2x"></i></a>
                    <a href="http://twitter.com/share?url=<?php echo $home->current_url(); ?>&text=Simple Share Buttons" target="_blank" title="Share Twitter"><i class="fa fa-twitter-square fa-2x"></i></a>
                </label>
            </span>
        </div>
    </div>

    <?php if ($cookie->is_login()) { ?>
        <div class="menu">
            <b><i class="fa fa-wrench" aria-hidden="true"></i> Công cụ:</b> &emsp;&emsp;
            <a href="/manager?act=post&id=<?php echo $blog['id'] ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Chỉnh sửa</a>
            / <a href="/manager?act=file&type=upload&id=<?php echo $blog['id'] ?>"><i class="fa fa-cloud-upload" aria-hidden="true"></i> Thêm file</a>
            / <a href="/manager?act=delete&id=<?php echo $blog['id'] ?>"><i class="fa fa-trash-o" aria-hidden="true"></i> Xoá</a>
        </div>
    <?php } ?>
    <div id="<?php echo rand(1000, 9999); ?>" class="menu">
        <?php echo $home->bbcode($blog['content']); ?>
    </div>

    <?php
    // Tập tin đính kèm
    $file_list = $QuerySQL->select_table_where_data('file', 'blog', $blog['id'], 'id', 'DESC');
    if ($QuerySQL->get_row_count('file', ['blog' => $blog['id'], 'operator' => '=']) > 0) {
        echo '<div class="phdr dsfile"><b><i class="fa fa-clone" aria-hidden="true"></i> Tập tin đính kèm (' . $QuerySQL->get_row_count('file', ['blog' => $blog['id'], 'operator' => '=']) . ')</b></div>';
        foreach ($file_list as $file) {
            $file_url = 'https://' . $file['filecate'] . '.ipfs.ipfs-gateway.cloud/';
            $file_name = html_entity_decode($file['filename'], ENT_QUOTES, 'UTF-8');
            $file_size = $file['filesize'];
            if ($file['filecate']) {
    ?>
                <div class="list1">
                    <a href="<?php echo $file_url; ?>">
                        <table style="table-layout:fixed">
                            <tbody>
                                <tr>
                                    <td rowspan="3" style="padding-right:10px"><i class="fa fa-2x fa-<?php echo $archive->checkExtension($file_name); ?>" aria-hidden="true"></i></td>
                                </tr>
                                <tr>
                                    <td style="word-break:break-all;"><i class="fa fa-circle-o-notch" aria-hidden="true"></i> <b><?php echo $file_name; ?></b></td>
                                </tr>
                                <tr>
                                    <td>
                                        <small>
                                            <i class="fa fa-info-circle" aria-hidden="true"></i> <?php echo $archive->size($file_size); ?>
                                            <?php if ($cookie->is_login()) { ?>
                                                / <i class="fa fa-trash-o" aria-hidden="true"></i> <a href="/manager?act=file&type=delete&id=<?php echo $file['id'] ?>">Xoá</a>
                                            <?php } ?>
                                        </small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </a>
                </div>
        <?php
            }
        }
    }

    // Cùng chuyên mục
    $same_cat_query = mysqli_query($db, 'SELECT * FROM blog WHERE `category` = ' . $blog['category'] . ' AND `id` != ' . $blog['id'] . ' ORDER BY rand() LIMIT 5');
    $same_cat = [];
    while ($row = mysqli_fetch_assoc($same_cat_query)) {
        $same_cat[] = $row;
    }
    if (count($same_cat) > 0) {
        ?>
        <div class="phdr" style="font-weight:700">Cùng chuyên mục</div>
        <?php
        foreach ($same_cat as $row) {
        ?>
            <div class="list1">
                <a href="/view/<?php echo $row['id'] . '-' . $row['slug']; ?>.html">
                    <i class="fa fa-rss" aria-hidden="true"></i>
                    <?php echo html_entity_decode($row['title'], ENT_QUOTES, 'UTF-8') ?>
                </a>
            </div>
            <?php
        }
    }

    // Bình luận bài viết
    echo '<div class="phdr" style="font-weight:700" id="comment"><i class="fa fa-comments" aria-hidden="true"></i> Bình luận</div>';
    $total = $QuerySQL->get_row_count('comment', ['blog' => $blog['id'], 'operator' => '=']);
    $per = $config['blog']['per_page'];
    $namepage = 'page';
    $page_max = ceil($total / $per);
    $page = $request_method->get($namepage) ? $request_method->get($namepage) : 1;
    if ($page >= $page_max) $page = $page_max;
    if ($page < 1) $page = 1;
    $start = ($page - 1) * $per;
    $comment_list = $QuerySQL->query_select_table('comment','*','WHERE `blog` = ' . $blog['id'] . ' ORDER BY `id` DESC LIMIT ' . $start . ',' . $per);
    if ($QuerySQL->get_row_count('comment', ['blog' => $blog['id'], 'operator' => '=']) > 0) {
        foreach ($comment_list as $comment) {
            if (strlen($comment['author']) > 3) {
                $author = html_entity_decode($comment['author'], ENT_QUOTES, 'UTF-8');
                $cmt = $home->bbcode($comment['content']);
                $time = $home->ago($comment['time']);
            ?>
                <div class="list1">
                    <span style="font-weight:700"><?php echo $author ?>: </span>
                    <span><?php echo $cmt ?> </span>
                    <span style="color:#444">(<?php echo $time ?>)</span>
                </div>
            <?php
            }
        }
        echo $home->paging('/view/' . $blog['id'] . '/page-', $page, $page_max);
        echo '<div class="phdr"><i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm bình luận</div>';
    }
    if ($request_method->query() == 'post') {
        $author = $request_method->post('author');
        $content = $request_method->post('content');
        $email = '
        Bạn nhận được một bình luận mới từ <b>' . html_entity_decode($author, ENT_QUOTES, 'UTF-8') . '</b>
        về bài viết <a href="http://' . $_SERVER['HTTP_HOST'] . '/view/' . $blog['id'] . '-' . $blog['slug'] . '.html">' . html_entity_decode($blog['title'], ENT_QUOTES, 'UTF-8') . '</a>
        <br/>
        <p style="padding:4px;border:1px solid;margin-top:4px;background:#ffc;color:#514721;border-color:#ffd324;-moz-border-radius-topleft:4px;-webkit-border-top-left-radius:4px;-moz-border-radius-topright:4px;-webkit-border-top-right-radius:4px;-moz-border-radius-bottomleft:4px;-webkit-border-bottom-left-radius:4px;-moz-border-radius-bottomright:4px;-webkit-border-bottom-right-radius:4px">' . $home->bbcode($content) . '</p>
        ';
        $time = date('U');
        if (strlen($author) < 3 || strlen($author) > 20) {
            echo '<div class="rmenu">Tên tối thiểu 3 ký tự, tối đa 20 ký tự</div>';
        } elseif (strlen($content) < 3 || strlen($content) > 500) {
            echo '<div class="rmenu">Nội dung tối thiểu 3 ký tự, tối đa 500 ký tự</div>';
        } else {
            $QuerySQL->insert_row_array_table('comment', [
                'blog' => $blog['id'],
                'author' => $author,
                'content' => $content,
                'time' => $time
            ]);
            $status = sendMail('Bình luận từ bài viết có ID: ' . $blog['id'], $email);
            if ($status) {
                echo '<div class="gmenu">Bình luận thành công</div>';
            } else {
                echo '<div class="rmenu">Bình luận thất bại</div>';
            }
            ?>
            <script>
                setTimeout(function() {
                    window.location.href = '/view/<?php echo $blog['id'] . '-' . $blog['slug'] ?>.html';
                }, 2000);
            </script>
    <?php
        }
    }
    ?>
    <div class="menu">
        <form action="" method="post">
            <div class="form-group">
                <label for="author">Tên:</label>
                <input type="text" class="form-control" id="author" name="author" placeholder="Tên của bạn" required>
            </div>
            <div class="form-group">
                <label for="content">Nội dung:</label>
                <textarea class="form-control" id="content" name="content" rows="3" placeholder="Nội dung bình luận" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Gửi</button>
        </form>
    </div>
<?php
} else {
    header('Location: /category');
}

require_once __DIR__ . '/footer.php';
