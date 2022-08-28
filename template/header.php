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

require_once dirname(__DIR__) . '/system/config.php';

$title = $title ? $title : 'Dorew';
if ($title != 'Dorew') $title = $title . ' | Dorew';
$description = $description ? $description : 'Thích Ngao Du';
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="<?php echo $description; ?>">
    <meta property="og:site_name" content="Dorew">
    <meta name="theme-color" content="#22292F">
    <meta name="author" content="Dorew">
    <meta name="robots" content="index,follow">
    <meta name="googlebot" content="index,follow">
    <meta name="google" content="notranslate">
    <meta name="format-detection" content="telephone=no">
    <link rel="dns-prefetch" href="https://i.imgur.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://raw.githack.com">
    <link rel="dns-prefetch" href="https://images.weserv.nl">
    <link rel="preload" href="/assets/css/main.css?t=<?php echo date('U'); ?>" as="style">
    <link type="text/css" rel="stylesheet" href="/assets/css/main.css?t=<?php echo date('U'); ?>" media="all,handheld" />
    <link rel="shortcut icon" href="https://i.imgur.com/2pfDfoN.png" />
    <title>
        <?php echo $title; ?>
    </title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<body>
    <div style="background-image: url('https://images.weserv.nl/?url=https://i.imgur.com/7C1Yh7u.jpg&w=400&h=400');color:#fff;padding:5px;border-style: solid; border-color:#1b1b2f;text-align: center;">
        <a href="/"><img src="/assets/images/man.png" height="60" width="60"></a>
        <br />Dorew - Thích Ngao Du
    </div>

    <div style="background-image: linear-gradient(to bottom, #0a0b0c, #0f1012, #131517, #17181c, #1a1c20);padding-bottom:4px;border-color:#ffffff;" align="center">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="33%">
                    <div style="border-width:3px;border-color:#ffffff;padding-left:2px;padding:8px;" align="center"><a href="https://upi.dorew.gq" title="Up ảnh" style="color:#FFFFFF"> <b><i class="fa fa-bars"></i></b> </a> </div>
                </td>
                <td width="34%">
                    <div style="background-color:#ffffff;border-width:1px;border-color:#fa6c5f;padding:8px;" align="center"> <a href="/" title="Trang Chủ"><b><i class="fa fa-home" aria-hidden="true"></i></b></a></div>
                </td>
                <td width="33%">
                    <div style="border-width:3px;border-color:#ffffff;padding-left:2px;padding:8px;" align="center"><a href="/search/" title="Tìm kiếm" style="color:#FFFFFF;"><b><i class="fa fa-search" aria-hidden="true"></i></b></a></div>
                </td>
            </tr>
        </table>
    </div>
    <hr style="border-top:#f0b400 solid 2px;">