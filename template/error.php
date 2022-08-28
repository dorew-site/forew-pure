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
$title = 'Lỗi!';
require_once __DIR__ . '/header.php';
?>
<div class="phdr"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Các trang yêu cầu không tồn tại</b></div>
<div class="menu" style="text-align: center">
    <p><img src="https://i.imgur.com/vbcVQJ8.png"></p>
</div>
<?php
require_once __DIR__ . '/footer.php';
