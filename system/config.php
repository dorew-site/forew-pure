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

defined('_DOREW') or die('Access denied');

$root = $_SERVER['DOCUMENT_ROOT'];
$http_host = $_SERVER['HTTP_HOST'];
$request_uri = $_SERVER['REQUEST_URI'];
$uri_segments = explode('/', $request_uri);

$php_server = strtolower($_SERVER['SERVER_SOFTWARE']);
if (strpos($php_server, 'nginx') !== false) {
    die('Chỉ hỗ trợ Apache Server!');
    exit;
}

$config = [
    'QuerySQL' => [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'db' => 'dorewsite',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci'
    ],
    'blog' => [
        'author' => [
            'name' => 'Dorew',
            'avatar' => 'https://i.imgur.com/2pfDfoN.png',
            'url' => 'https://dorew.gq',
        ],
        'per_page' => 10,
        'domain_search_google' => 'dorew.org'
    ],
    'email' => [
        'smtp_host' => 'smtp.example.com', // host gửi email
        'smtp_port' => 465, // port gửi email
        'sender' => 'comment@' . str_replace('www.', '', $http_host), // email gửi
        'pass' => 'password', // mật khẩu email gửi
        'receiver' => 'author_blog@example.com', // email nhận
    ]
];

ini_set('display_errors', 0);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
ini_set('default_charset', 'UTF-8');
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Thông tin cấu hình CSDL
$db = mysqli_connect(
    $config['QuerySQL']['host'],
    $config['QuerySQL']['user'],
    $config['QuerySQL']['pass'],
    $config['QuerySQL']['db']
);

// Thông tin đăng nhập của admin
$account_admin = 'admin';
$password_admin = 'dorew';
$passMd5 = md5(md5(md5($password_admin)).'dorew');
$new_password = sha1(substr($passMd5, 0, 8));

// Kiểm tra kết nối
if (version_compare(PHP_VERSION, '7.0.0', '<')) {
    die('Yêu cầu tối thiểu PHP 7.0.0');
    exit;
} elseif (!$db) {
    die('Không thể kết nối với CSDL! Vui lòng kiểm tra lại (Đường dẫn: /system/config.php).');
    exit;
}

// Các hàm cần thiết
require_once __DIR__ . '/vendor/function.php';

// Khởi tạo dữ liệu cần thiết
$QuerySQL->create_table_with_column('category', [
    'name' => 'TEXT NOT NULL',
    'slug' => 'VARCHAR(255) NOT NULL',
    'description' => 'TEXT NOT NULL'
]); // bảng `category`
$QuerySQL->create_table_with_column('blog', [
    'title' => 'TEXT NOT NULL',
    'slug' => 'VARCHAR(255) NOT NULL',
    'content' => 'TEXT NOT NULL',
    'view' => 'INT(11) NOT NULL',
    'time' => 'INT(11) NOT NULL',
    'category' => 'INT(11) NOT NULL'
]); // bảng `blog`
$QuerySQL->create_table_with_column('file', [
    'blog' => 'INT(11) NOT NULL',
    'filename' => 'TEXT NOT NULL',
    'filecate' => 'VARCHAR(255) NOT NULL',
    'filesize' => 'INT(11) NOT NULL'
]); // bảng `file`
$QuerySQL->create_table_with_column('comment', [
    'blog' => 'INT(11) NOT NULL',
    'time' => 'INT(11) NOT NULL',
    'author' => 'VARCHAR(255) NOT NULL',
    'content' => 'TEXT NOT NULL',
]); // bảng `comment`