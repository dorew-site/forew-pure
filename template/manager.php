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

if (!$cookie->is_login()) {
    header('Location: /');
    exit;
}

$title = 'Quản lý';
require_once __DIR__ . '/header.php';

$act = $request_method->get('act');
$type = $request_method->get('type');

if ($act == 'create-category') {
    if ($request_method->query() == 'post') {
        $name = $request_method->post('name');
        $slug = $home->rwurl($name);
        $description = $request_method->post('description');
        if (strlen($name) < 3 && strlen($name) > 20) {
            $notice = 'Chiều dài tên phải từ 3 đến 20 ký tự';
        } elseif (strlen($description) < 3 && strlen($description) > 100) {
            $notice = 'Chiều dài mô tả phải từ 3 đến 100 ký tự';
        } else {
            $QuerySQL->insert_row_array_table('category', [
                'name' => $name,
                'slug' => $slug,
                'description' => $description
            ]);
            header('Location: /category');
        }
    }
?>
    <div class="phdr"><i class="fa fa-plus-circle" aria-hidden="true"></i> <b>Tạo chuyên mục</b></div>
    <?php if ($notice) echo '<div class="rmenu">' . $notice . '</div>'; ?>
    <div class="menu">
        <form method="post" action="">
            <p>
                <b>Tên:</b><br />
                <input type="text" class="w3-input" name="name" class="form-control">
            </p>
            <p>
                <b>Mô tả:</b><br />
                <textarea type="text" class="w3-input" name="description"></textarea>
            </p>
            <p><button class="btn btn-primary btn-block" type="submit">Tạo</button></p>
        </form>
    </div>
<?php
} elseif ($act == 'post') {
    $edit_id = $request_method->get('id');
    $data_blog = $QuerySQL->select_table_row_data('blog', 'id', $edit_id);
    if ($data_blog['id'] >= 1) {
        $old_title = html_entity_decode($data_blog['title'], ENT_QUOTES, 'UTF-8');
        $old_content = html_entity_decode($data_blog['content'], ENT_QUOTES, 'UTF-8');
        $old_category = $data_blog['category'];
    }
    if ($request_method->query() == 'post') {
        $title = $request_method->post('title');
        $slug = $home->rwurl($title);
        $content = $request_method->post('content');
        $view = 1;
        $time = date('U');
        $category = $request_method->post('category');
        $check_cat = $QuerySQL->select_table_row_data('category', 'id', $category);
        if (!$check_cat) {
            $notice = 'Chuyên mục không tồn tại';
        } elseif (strlen($title) < 3 && strlen($title) > 300) {
            $notice = 'Chiều dài tiêu đề phải từ 3 đến 300 ký tự';
        } elseif (strlen($content) < 3) {
            $notice = 'Chiều dài nội dung phải tối thiểu 3 ký tự';
        } else {
            if ($old_title) {
                $QuerySQL->update_row_array_table('blog', [
                    'title' => $title,
                    'slug' => $slug,
                    'content' => $content,
                    'category' => $category
                ], 'id', $edit_id);
                header('Location: /view/' . $edit_id . '-' . $data_blog['slug']);
            } else {
                $QuerySQL->insert_row_array_table('blog', [
                    'title' => $title,
                    'slug' => $slug,
                    'content' => $content,
                    'view' => $view,
                    'time' => $time,
                    'category' => $category
                ]);
                header('Location: /');
            }
        }
    }
    if (!$old_title) echo '<div class="phdr" style="font-weight:700"><i class="fa fa-pencil" aria-hidden="true"></i> Viết bài</div>';
    else echo '<div class="phdr" style="font-weight:700"><i class="fa fa-pencil" aria-hidden="true"></i> Chỉnh sửa bài viết</div>';
    if ($notice) echo '<div class="rmenu">' . $notice . '</div>';
?>
    <form name="form" action="" method="post">
        <input style="display:none" type="file" id="f" accept="image/*">
        <div class="menu">
            <b><i class="fa fa-gg" aria-hidden="true"></i> Tiêu đề:</b>
            <p><input class="w3-input w3-border" type="text" name="title" value="<?php if ($old_title) echo $old_title; ?>" maxlength="300" style="height:100%; width:100%"></p>
        </div>
        <div class="menu">
            <b><i class="fa fa-bars"></i> Chuyên mục:</b>
            <p><select name="category" class="w3-select w3-border">
                    <?php
                    $categories = $QuerySQL->select_table_data('category', 'id', 'DESC');
                    foreach ($categories as $category) {
                        if ($data_blog['id'] >= 1 && $data_blog['category'] == $category['id']) {
                            echo '<option value="' . $category['id'] . '" selected>' . $category['name'] . '</option>';
                        } else {
                            echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                        }
                    }
                    ?>
                </select></p>
        </div>
        <div class="menu">
            <b><i class="fa fa-newspaper-o" aria-hidden="true"></i> Nội dung:</b>
            <?php echo $home->toolbar('form', 'content'); ?>
            <p><textarea name="content" rows="15"><?php if ($old_content) echo $old_content; ?></textarea></p>
            <p>
                <button class="btn btn-primary btn-block" type="submit">
                    <?php if ($old_title) echo 'Lưu lại';
                    else echo 'Đăng bài'; ?>
                </button>
            </p>
        </div>
    </form>
    <?php
} elseif ($act == 'delete') {
    $delete_id = $request_method->get('id');
    $data_blog = $QuerySQL->select_table_row_data('blog', 'id', $delete_id);
    echo '<div class="phdr" style="font-weight:700"><i class="fa fa-trash" aria-hidden="true"></i> Xóa bài viết</div>';
    if ($data_blog['id'] >= 1) {
        $category = $QuerySQL->select_table_row_data('category', 'id', $data_blog['category']);
        if ($request_method->query() == 'post') {
            $QuerySQL->delete_row_table('blog', 'id', $delete_id);
            $file_list = $QuerySQL->select_table_where_data('file', 'blog', $data_blog['id']);
            foreach ($file_list as $file) {
                $QuerySQL->delete_row_table('file', 'id', $file['id']);
            }
            header('Location: /category/' . $category['id'] . '-' . $category['slug']);
        }
    ?>
        <form method="post">
            <div class="menu">
                <p>Bạn có chắc chắn muốn xóa bài viết <b><?php echo html_entity_decode($data_blog['title'], ENT_QUOTES, 'UTF-8'); ?></b> không?</p>
                <p><button type="submit" class="submit">Đồng ý</button></p>
            </div>
        </form>
        <?php
    } else echo '<div class="rmenu">Bài viết không tồn tại!</div>';
} elseif ($act == 'file') {
    $id = $request_method->get('id');
    switch ($type) {
        case 'upload':
            $blog_id = $id;
            $data_blog = $QuerySQL->select_table_row_data('blog', 'id', $blog_id);
            if ($data_blog['id'] >= 1) {
                echo '<div class="phdr" style="font-weight:700"><i class="fa fa-cloud-upload" aria-hidden="true"></i> Tải lên tập tin</div>';
                if ($request_method->query() == 'post') {
                    $filename = $request_method->post('filename');
                    $filecate = $request_method->post('filecate');
                    $filesize = $request_method->post('filesize');
                    $passphrase = $request_method->post('passphrase');
                    if ($filename && $filecate && $filesize) {
                        $QuerySQL->insert_row_array_table('file', [
                            'blog' => $blog_id,
                            'filename' => $filename,
                            'filecate' => $filecate,
                            'filesize' => $filesize,
                            'passphrase' => $passphrase
                        ]);
                        header('Location: /view/' . $blog_id . '-' . $data_blog['slug']);
                    } else echo '<div class="rmenu">Tập tin không hợp lệ</div>';
                }
        ?>
                <form id="form" method="post">
                    <div class="menu" style="text-align:center">
                        <div style="margin:5px;" id="dai"></div>
                        <input id="uploadfile" type="file" name="file" />
                        <input id="filename" name="filename" value="" type="hidden" />
                        <input id="filesize" name="filesize" value="" type="hidden" />
                        <input id="filecate" name="filecate" value="" type="hidden" />
                        <input id="passphrase" name="passphrase" type="hidden" value="<?php echo sha1(rand() . rand()); ?>" />
                        <p>
                            <button type="button" id="btnUpload" class="btn btn-primary" onclick="frUpload()">Xác nhận</button>
                        </p>
                    </div>
                </form>
                <script src="/assets/js/ipfs.upload.js?t=<?php echo date('U') ?>"></script>
            <?php
            } else echo '<div class="rmenu">Bài viết không tồn tại!</div>';
            break;
        case 'delete':
            $file_id = $id;
            $data_file = $QuerySQL->select_table_row_data('file', 'id', $file_id);
            echo '<div class="phdr" style="font-weight:700"><i class="fa fa-trash" aria-hidden="true"></i> Xóa tập tin</div>';
            if ($data_file['id'] == $file_id) {
                $blog = $QuerySQL->select_table_row_data('blog', 'id', $data_file['blog']);
                if ($request_method->query() == 'post') {
                    $QuerySQL->delete_row_table('file', 'id', $file_id);
                    header('Location: /view/' . $blog['id'] . '-' . $blog['slug']);
                }
            ?>
                <form method="post">
                    <div class="menu">
                        <p>Bạn có chắc chắn muốn xóa tập tin <b><?php echo html_entity_decode($data_file['filename'], ENT_QUOTES, 'UTF-8'); ?></b> không?</p>
                        <p><button type="submit" class="submit">Đồng ý</button></p>
                    </div>
                </form>
<?php
            } else echo '<div class="rmenu">Tập tin không tồn tại!</div>';
            break;
        default:
            header('Location: /');
            break;
    }
} else {
    header('Location: /');
}

require_once __DIR__ . '/footer.php';
