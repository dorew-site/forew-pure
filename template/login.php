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

if ($cookie->is_login()) {
    header('Location: /');
    exit;
}

$title = 'Đăng nhập';
require_once __DIR__ . '/header.php';

echo '<div class="phdr" style="font-weight:700"><i class="fa fa-sign-in" aria-hidden="true"></i> Đăng Nhập</div>';
$user = strtolower($_POST['user']);
$pass = $_POST['pass'];
if ($request_method->query() == 'post') {
    if ($user == $account_admin && $pass == $password_admin) {
        $div = 'gmenu';
        $result = 'Đăng nhập thành công';
        setcookie($account_admin, $new_password, date('U') + 31536000);
        header('Location: /');
        exit();
    } else {
        $div = 'rmenu';
        $result = 'Thông tin đăng nhập sai cmnr!';
    }
}
if (isset($result)) {
    echo '<div class="' . $div . '">' . $result . '</div>';
}
?>
<div class="menu">
    <form method="post" action="">
        <p>
            <i class="fa fa-user" aria-hidden="true"></i> Tên tài khoản:<br />
            <input type="text" class="w3-input" name="user">
        </p>
        <p>
            <i class="fa fa-lock" aria-hidden="true"></i> Mật khẩu:<br />
            <input type="password" class="w3-input" name="pass">
        </p>
        <p><button class="btn btn-primary btn-block" type="submit">Đăng nhập</button></p>
    </form>
</div>
<?php
require_once __DIR__ . '/footer.php';
