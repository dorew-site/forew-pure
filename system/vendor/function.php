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

/**
 * Truy vấn cơ sở dữ liệu
 */

require_once __DIR__ . '/QuerySQL.php';
$QuerySQL = new QuerySQL();

/**
 * Xử lý yêu cầu từ biểu mẫu
 */

$request_method = new class
{
    function query()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    function post($string)
    {
        return htmlspecialchars(addslashes($_POST[$string]));
    }

    function get($string)
    {
        return htmlspecialchars(addslashes($_GET[$string]));
    }

    function get_youtube_id($url)
    {
        preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user|shorts)\/))([^\?&\"'>]+)/", $url, $matches);
        return $matches[1];
    }

    function get_youtube_title($url)
    {
        $youtube_id = $this->get_youtube_id($url);
        $youtube_title = file_get_contents("https://www.youtube.com/watch?v=$youtube_id");
        preg_match("/<title>(.*)<\/title>/", $youtube_title, $matches);
        return str_replace(' - YouTube', '', $matches[1]);
    }
};

/**
 * Làm việc với Cookie
 */

$cookie = new class
{
    function set($name, $value)
    {
        setcookie($name, $value, date('U') + 3600 * 24 * 365, '/');
        return;
    }

    function delete($name)
    {
        setcookie($name, '', date('U') - 3600 * 24 * 365, '/');
        unset($_COOKIE[$name]);
        return;
    }

    function get($name)
    {
        if (!$_COOKIE[$name]) return false;
        return $_COOKIE[$name];
    }

    function is_login()
    {
        global $account_admin, $new_password;
        if ($this->get($account_admin) == $new_password) {
            return true;
        } else {
            return false;
        }
    }
};

/**
 * Trình duyệt
 */

$browser = new class
{
    function ip()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        return $ip;
    }

    function user_agent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    function display_layout()
    {
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $arrUA = strtolower($ua);
        if (preg_match('/windows|ipod|ipad|iphone|android|webos|blackberry|midp/', $arrUA) && preg_match('/mobile/', $arrUA)) {
            return 'mobile';
        } elseif (preg_match('/mobile/', $arrUA)) return 'mobile';
        else return 'desktop';
    }
};

/**
 * Bài đăng
 */

$home = new class
{

    function current_url()
    {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        return $url;
    }

    function fulltime($time_in_thePast)
    {
        $result = date('H:i, d/m/Y', $time_in_thePast);
        return $result;
    }

    function ago($time_in_thePast)
    {
        if (!$time_in_thePast) {
            $time_in_thePast = time();
        }
        $countdown = date('U') - $time_in_thePast;
        $time_day = date('z') - date('z', $time_in_thePast);
        if ($time_day < 0) {
            $time_day = date('z', $time_in_thePast) - date('z');
        }
        if ($countdown < 60 && $time_day == 0) {
            if ($countdown == 0) {
                $result = 'vừa xong';
            } else {
                $result = $countdown . ' giây trước';
            }
        } elseif ($countdown >= 60 && $time_day <= 1) {
            if ($time_day == 0) {
                if ($countdown > 3600) {
                    $result = 'Hôm nay, ' . date('H:i', $time_in_thePast);
                } else {
                    $result = round(trim($countdown / 60), '0') . ' phút trước';
                }
            } else {
                $result = 'Hôm qua, ' . date('H:i', $time_in_thePast);
            }
        } else {
            if ($countdown > 31622400) {
                $result = date('H:i, d/m/Y', $time_in_thePast);
            } elseif ($countdown >= 2592000) {
                $result = round(trim($countdown / 2592000), '0') . ' tháng trước';
            } elseif ($countdown >= 604800) {
                $result = round(trim($countdown / 604800), '0') . ' tuần trước';
            } else {
                $day = round(trim($countdown / 86400), '0');
                if ($day == 7) {
                    $result = '1 tuần trước';
                } else {
                    $result = $day . ' ngày trước';
                }
            }
        }
        return $result;
    }

    function rwurl($string)
    {
        $string = strtolower($string);
        //bỏ dấu tiếng việt
        $string = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $string);
        $string = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $string);
        $string = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $string);
        $string = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $string);
        $string = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $string);
        $string = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $string);
        $string = preg_replace("/(đ)/", 'd', $string);
        $string = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $string);
        $string = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $string);
        $string = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $string);
        $string = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $string);
        $string = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $string);
        $string = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $string);
        $string = preg_replace("/(Đ)/", 'D', $string);
        //xoá toàn bộ ký tự đặc biệt
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //xoá khoảng trắng thừa
        $string = trim(preg_replace("/[\s-]+/", " ", $string));
        //thay thế khoảng trắng bằng ký tự -
        $string = preg_replace("/[\s-]/", "-", $string);
        $string = mb_strtolower($string, 'utf8');
        return $string;
    }

    function paging($url, $p, $max)
    {
        $p = (int)$p;
        $max = (int)$max;
        $b = '';
        if ($max > 1) {
            $a = ' <a class="pagenav" href="' . $url;
            if ($p > $max) {
                $p = $max;
                $b .= 'a';
            }
            if ($p > 1) {
                $b .= $a . ($p - 1) . '">&laquo;</a> ';
            }
            if ($p > 3) {
                $b .= $a . '1">1</a>';
            }
            if ($p > 4) {
                $b .= ' <span class="disabled">...</span> ';
            }
            if ($p > 2) {
                $b .= $a . ($p - 2) . '">' . ($p - 2) . '</a>';
            }
            if ($p > 1) {
                $b .= $a . ($p - 1) . '">' . ($p - 1) . '</a>';
            }
            $b .= ' <span class="currentpage"><b>' . $p . '</b></span> ';
            if ($p < ($max - 1)) {
                $b .= $a . ($p + 1) . '">' . ($p + 1) . '</a>';
            }
            if ($p < ($max - 2)) {
                $b .= $a . ($p + 2) . '">' . ($p + 2) . '</a>';
            }
            if ($p < ($max - 3)) {
                $b .= ' <span class="disabled">...</span> ';
            }
            if ($p < $max) {
                $b .= $a . $max . '">' . $max . '</a> ';
            }
            if ($p < $max) {
                $b .= $a . ($p + 1) . '">&raquo;</a> ';
            }
            return '<div class="topmenu pagination">' . $b . '</div>';
        }
    }

    public static function bb_url($text)
    {
        if (!function_exists('url_callback')) {
            function url_callback($type, $whitespace, $url, $relative_url)
            {
                $orig_url = $url;
                $orig_relative = $relative_url;
                $url = htmlspecialchars_decode($url);
                $relative_url = htmlspecialchars_decode($relative_url);
                $text = '';
                $chars = array('<', '>', '"');
                $split = false;
                foreach ($chars as $char) {
                    $next_split = strpos($url, $char);
                    if ($next_split !== false) {
                        $split = ($split !== false) ? min($split, $next_split) : $next_split;
                    }
                }
                if ($split !== false) {
                    $url = substr($url, 0, $split);
                    $relative_url = '';
                } else {
                    if ($relative_url) {
                        $split = false;
                        foreach ($chars as $char) {
                            $next_split = strpos($relative_url, $char);
                            if ($next_split !== false) {
                                $split = ($split !== false) ? min($split, $next_split) : $next_split;
                            }
                        }
                        if ($split !== false) {
                            $relative_url = substr($relative_url, 0, $split);
                        }
                    }
                }
                $last_char = ($relative_url) ? $relative_url[strlen($relative_url) - 1] : $url[strlen($url) - 1];
                switch ($last_char) {
                    case '.':
                    case '?':
                    case '!':
                    case ':':
                    case ',':
                        $append = $last_char;
                        if ($relative_url) {
                            $relative_url = substr($relative_url, 0, -1);
                        } else {
                            $url = substr($url, 0, -1);
                        }
                        break;
                    default:
                        $append = '';
                        break;
                }
                $short_url = (mb_strlen($url) > 40) ? mb_substr($url, 0, 30) . ' ... ' . mb_substr($url, -5) : $url;
                switch ($type) {
                    case 1:
                        $relative_url = preg_replace('/[&?]sid=[0-9a-f]{32}$/', '', preg_replace('/([&?])sid=[0-9a-f]{32}&/', '$1', $relative_url));
                        $url = $url . '/' . $relative_url;
                        $text = $relative_url;
                        if (!$relative_url) {
                            return $whitespace . $orig_url . '/' . $orig_relative;
                        }
                        break;
                    case 2:
                        $text = $short_url;
                        $url = '/redirect?url=' . rawurlencode($url);
                        break;
                    case 4:
                        $text = $short_url;
                        $url = 'mailto:' . $url;
                        break;
                }
                $url = htmlspecialchars($url);
                $text = htmlspecialchars($text);
                $append = htmlspecialchars($append);

                return $whitespace . '<i class="fa fa-link fa-spin"></i><a href="' . $url . '" target="_blank">' . $text . '</a>' . $append;
            }
        }

        // Liên kết nội bộ
        $text = preg_replace_callback(
            '#(^|[\n\t (>.])(' . preg_quote($GLOBALS['http_host'], '#') . ')/((?:[a-z0-9\-._~!$&\'(*+,;=:@|]+|%[\dA-F]{2})*(?:/(?:[a-z0-9\-._~!$&\'(*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&\'(*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&\'(*+,;=:@/?|]+|%[\dA-F]{2})*)?)#iu',
            function ($matches) {
                return url_callback(1, $matches[1], $matches[2], $matches[3]);
            },
            $text
        );

        // Liên kết thông thường
        $text = preg_replace_callback(
            '#(^|[\n\t (>.])([a-z][a-z\d+]*:/{2}(?:(?:[a-z0-9\-._~!$&\'(*+,;=:@|]+|%[\dA-F]{2})+|[0-9.]+|\[[a-z0-9.]+:[a-z0-9.]+:[a-z0-9.:]+\])(?::\d*)?(?:/(?:[a-z0-9\-._~!$&\'(*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&\'(*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&\'(*+,;=:@/?|]+|%[\dA-F]{2})*)?)#iu',
            function ($matches) {
                return url_callback(2, $matches[1], $matches[2], '');
            },
            $text
        );

        return $text;
    }

    private static $geshi;

    private static function codeCallback($code)
    {
        $parsers = array(
            'php'  => 'php',
            'css'  => 'css',
            'html' => 'html5',
            'js'   => 'javascript',
            'sql'  => 'sql',
            'xml'  => 'xml',
        );

        $parser = isset($code[1]) && isset($parsers[$code[1]]) ? $parsers[$code[1]] : 'php';

        if (null === self::$geshi) {
            require_once($_SERVER['DOCUMENT_ROOT'] . '/system/vendor/geshi.php');
            self::$geshi = new \GeSHi;
            self::$geshi->set_link_styles(GESHI_LINK, 'text-decoration: none');
            self::$geshi->set_link_target('_blank');
            self::$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 2);
            self::$geshi->set_line_style('background: rgba(255, 255, 255, 0.5)', 'background: rgba(255, 255, 255, 0.35)', false);
            self::$geshi->set_code_style('padding-left: 6px; white-space: pre-wrap');
        }

        self::$geshi->set_language($parser);
        $php = strtr($code[2], array('<br />' => ''));
        $php = html_entity_decode(trim($php), ENT_QUOTES, 'UTF-8');
        self::$geshi->set_source($php);

        return '<div class="bbCodeBlock bbCodePHP"><div class="type">' . mb_strtoupper($code[1]) . '</div><div class="code" style="overflow-x: auto">' . self::$geshi->parse_code() . '</div></div>';
    }

    private static function phpCodeCallback($code)
    {
        return self::codeCallback(array(1 => 'php', 2 => $code[1]));
    }

    private static function smile($text)
    {
        $arr_emo_name = ["ami", "anya", "aru", "dauhanh", "dora", "le", "menhera", "moew", "nam", "pepe", "qoobee", "qoopepe", "thobaymau", "troll"];
        foreach ($arr_emo_name as $emo_name) {
            if (strpos($text, ':' . $emo_name) !== false) {
                $pattern = '/[:]' . $emo_name . '(\\d+)[:]/';
                $text = preg_replace_callback($pattern, function ($matches) use ($emo_name) {
                    return '<img loading="lazy" src="https://dorew-site.github.io/assets/smileys/' . $emo_name . '/' . $emo_name . $matches[1] . '.png" alt="' . $matches[1] . '"/>';
                }, $text);
            }
        }
        return $text;
    }

    function bbcode($text)
    {
        $text = nl2br($text);

        // Highlight code
        $text = preg_replace_callback('#\[php\](.+?)\[\/php\]#s', 'self::phpCodeCallback', $text);
        $text = preg_replace_callback('#\[code\](.+?)\[\/code\]#s', 'self::phpCodeCallback', $text);
        $text = preg_replace_callback('#\[code=(.+?)\](.+?)\[\/code]#is', 'self::codeCallback', $text);

        // Media
        $text = preg_replace_callback('#\[img\](.+?)\[\/img\]#s', function ($matches) {
            return '<p><a href="$1"><picture><source srcset="' . $matches[1] . '" type="image/webp"><source srcset="' . $matches[1] . '" type="image/jpeg"><img loading="lazy" style="border-radius:1%; display: block; margin: 0 auto; max-width: 70%; max-height: 70%" src="' . $matches[1] . '" border="2"></picture></a></p>';
        }, $text);
        $text = preg_replace_callback('#\[vid\](.+?)\[\/vid\]#s', function ($matches) {
            return '<div class="video-wrapper" style="text-align: center;"><iframe loading="lazy" src="/video-embed?link=' . $matches[1] . '" height="315" width="560" scrolling="no" allowfullscreen="" frameborder="0"></iframe></div>';
        }, $text);

        // BBcode đơn giản
        $search = [
            '~\[b\](.+?)\[/b\]~s',
            '~\[i\](.+?)\[/i\]~s',
            '~\[u\](.+?)\[/u\]~s',
            '~\[color=(.+)\](.+?)\[/color\]~s',
            '~\[size=(.+)\](.+)\[/size\]~s',
            '~\[center\](.+)\[/center\]~s',
            '~\[right\](.+)\[/right\]~s',
            '~\[d\](.+?)\[/d\]~s',
        ];
        $replace = [
            '<span style="font-weight:700">$1</span>',
            '<i>$1</i>',
            '<span style="text-decoration:underline;">$1</span>',
            '<span style="color:$1;">$2</span>',
            '<span style="font-size:$1px;">$2</span>',
            '<div style="text-align:center;">$1</div>',
            '<div style="text-align:right;">$1</div>',
            '<center><a class="demo_btn_bacsiwindows" target="_blank" href="$1"><i class="fa fa-download"></i> TẢI XUỐNG </a></center>'
        ];
        $text =  preg_replace($search, $replace, $text);

        $text = preg_replace_callback('#\[url=(.+?)\](.+?)\[\/url]#is', function ($matches) {
            $url = $matches[1];
            return '<i class="fa fa-link fa-spin"></i><a href="' . $url . '">' . $matches[2] . '</a>';
        }, $text);
        $text = self::bb_url($text);
        return self::smile($text);
    }

    function toolbar($form = null, $textarea = null)
    {
        $code = ['php', 'css', 'js', 'html', 'sql', 'twig'];
        $color = ['bcbcbc', '708090', '6c6c6c', '454545', 'fcc9c9', 'fe8c8c', 'fe5e5e', 'fd5b36', 'f82e00', 'ffe1c6', 'ffc998', 'fcad66', 'ff9331', 'ff810f', 'd8ffe0', '92f9a7', '34ff5d', 'b2fb82', '89f641', 'b7e9ec', '56e5ed', '21cad3', '03939b', '039b80', 'cac8e9', '9690ea', '6a60ec', '4866e7', '173bd3', 'f3cafb', 'e287f4', 'c238dd', 'a476af', 'b53dd2'];
?>
        <script src="/assets/js/toolbar.js"></script>
        <div class="redactor_box" style="border-bottom: 1px solid #D7EDFC;margin-bottom:2px;">
            <ul class="redactor_toolbar">
                <li class="redactor_btn_group">
                    <a href="javascript:show_hide('colorShow')"><i class="fa fa-paint-brush" aria-hidden="true"></i></a>
                    <a href="javascript:tag('[b]', '[/b]'<?php if ($form && $textarea) echo ", '$form', '$textarea'"; ?>)"><i class="fa fa-bold" aria-hidden="true"></i></a>
                    <a href="javascript:tag('[i]', '[/i]'<?php if ($form && $textarea) echo ", '$form', '$textarea'"; ?>)"><i class="fa fa-italic" aria-hidden="true"></i></a>
                    <a href="javascript:tag('[u]', '[/u]'<?php if ($form && $textarea) echo ", '$form', '$textarea'"; ?>)"><i class="fa fa-underline" aria-hidden="true"></i></a>
                    <a href="javascript:tag('[s]', '[/s]'<?php if ($form && $textarea) echo ", '$form', '$textarea'"; ?>)"><i class="fa fa-strikethrough" aria-hidden="true"></i></a>
                </li>
                <li class="redactor_btn_group">
                    <a href="javascript:tag('[center]', '[/center]'<?php if ($form && $textarea) echo ", '$form', '$textarea'"; ?>)"><i class="fa fa-align-center" aria-hidden="true"></i></a>
                    <a href="javascript:tag('[right]', '[/right]'<?php if ($form && $textarea) echo ", '$form', '$textarea'"; ?>)"><i class="fa fa-align-right" aria-hidden="true"></i></a>
                    <a href="javascript:show_hide('codeShow')"><i class="fa fa-code" aria-hidden="true"></i></a>
                    <a href="javascript:tag('[url=]', '[/url]'<?php if ($form && $textarea) echo ", '$form', '$textarea'"; ?>)"><i class="fa fa-link" aria-hidden="true"></i></a>
                    <a href="javascript:tag('[d]', '[/d]'<?php if ($form && $textarea) echo ", '$form', '$textarea'"; ?>)"><i class="fa fa-download" aria-hidden="true"></i></a>
                </li>
                <li class="redactor_btn_group">
                    <a href="javascript:tag('[img]', '[/img]'<?php if ($form && $textarea) echo ", '$form', '$textarea'"; ?>)"><i class="fa fa-picture-o" aria-hidden="true"></i></a>
                    <a href="javascript:tag('[vid]', '[/vid]'<?php if ($form && $textarea) echo ", '$form', '$textarea'"; ?>)"><i class="fa fa-play-circle" aria-hidden="true"></i></a>
                    <a href="javascript:show_hide('sm');"><i class="fa fa-smile-o" aria-hidden="true"></i></a>
                </li>
                <li class="redactor_btn_group">
                    <a id="upload"><i class="fa fa-upload" aria-hidden="true"></i></a>
                    <a id="upload2"><i class="fa fa-cube" aria-hidden="true"></i></a>
                </li>
            </ul>
            <div id="codeShow" style="display:none">
                <div style="padding:2px">
                    <?php
                    foreach ($code as $valcode) {
                    ?>
                        <a href="javascript:tag('[code=<?php echo $valcode ?>]', '[/code]'<?php if ($form && $textarea) echo ", '$form', '$textarea'"; ?>); show_hide('codeShow');" tabindex="-1" class="btn btn-default"><?php echo strtoupper($valcode) ?></a>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div id="colorShow" style="display:none">
                <div style="padding:2px">
                    Màu chữ:
                    <?php
                    foreach ($color as $valcolor) {
                    ?>
                        <a href="javascript:tag('[color=<?php echo $valcolor ?>]', '[/color]'<?php if ($form && $textarea) echo ", '$form', '$textarea'"; ?>); show_hide('codeShow');" tabindex="-1" style="background-color:#<?php echo $valcolor ?>;width:3px;height:3px">ㅤ</a>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div id="sm" style="display:none">
                <?php
                for ($i = 1; $i <= 49; $i++) {
                ?>
                    <a href="javascript:tag(':aru<?php echo $i ?>:', ' '<?php if ($form && $textarea) echo ", '$form', '$textarea'"; ?>); show_hide('sm');"><img loading="lazy" src="https://images.weserv.nl/?url=https://moleys.github.io/assets/images/aru<?php echo $i ?>.png" width="50px" /></a>
                <?php
                }
                for ($i = 1; $i <= 17; $i++) {
                ?>
                    <a href="javascript:tag(':qoopepe<?php echo $i ?>:', ' '<?php if ($form && $textarea) echo ", '$form', '$textarea'"; ?>); show_hide('sm');"><img loading="lazy" src="https://images.weserv.nl/?url=https://moleys.github.io/assets/images/qoopepe<?php echo $i ?>.png" width="50px" /></a>
                <?php
                }
                ?>
            </div>
        </div>
        <input style="display:none" type="file" id="f" accept="image/*">
        <input style="display:none" type="file" id="f2">
        <script src="/assets/js/form.imgur.js"></script>
        <script src="/assets/js/form.ipfs.js"></script>
<?php
    }
};

/**
 * Tập tin đính kèm
 */

$archive = new class
{
    function size($byte)
    {
        if ($byte >= '1073741824') {
            $result = round(trim($byte / 1073741824), '2') . ' GB';
        } else if ($byte >= '1048576') {
            $result = round(trim($byte / 1048576), '2') . ' MB';
        } else if ($byte >= '1024') {
            $result = round(trim($byte / 1024), '2') . ' KB';
        } else {
            $result = round($byte, '2') . ' Bytes';
        }
        return $result;
    }

    function checkExtension($string)
    {
        $string = strtolower($string);
        //sử dụng fork awesome 1.1.7
        $stringArray = explode('.', rtrim($string, '.'));
        if (in_array(end($stringArray), ['jpg', 'jpeg', 'png', 'webp', 'psd', 'heic', 'gif'])) {
            return 'file-image-o';
        } elseif (in_array(end($stringArray), ['mp4', 'mkv', 'webm', 'flv', '3gp'])) {
            return 'file-video-o';
        } elseif (in_array(end($stringArray), ['mp3', 'mkv', 'm4a', 'flac', 'wav'])) {
            return 'file-audio-o';
        } elseif (in_array(end($stringArray), ['txt', 'md'])) {
            return 'file-text-o';
        } elseif (in_array(end($stringArray), ['docx', 'doc', 'odt'])) {
            return 'file-word-o';
        } elseif (in_array(end($stringArray), ['xls', 'xlsx', 'csv'])) {
            return 'file-excel-o';
        } elseif (in_array(end($stringArray), ['ppt', 'pptx'])) {
            return 'file-powerpoint-o';
        } elseif (in_array(end($stringArray), ['zip', 'rar', '7z', 'tar'])) {
            return 'file-archive-o';
        } elseif (in_array(end($stringArray), ['apk', 'xpak', 'aab'])) {
            return 'android';
        } elseif (in_array(end($stringArray), ['cpp', 'cs', 'php', 'html', 'xml', 'xhtml', 'js', 'py', 'twig', 'go'])) {
            return 'file-code-o';
        } elseif (end($stringArray) == 'pdf') {
            return 'file-pdf-o';
        } elseif (end($stringArray) == 'epub') {
            return 'file-epub';
        } elseif (end($stringArray) == 'sql') {
            return 'database';
        } else {
            return 'file-o';
        }
    }
};

/**
 * Thư điện tử
 */

require_once  __DIR__ . '/SMTP.php';
require_once  __DIR__ . '/PHPMailer.php';

function sendMail($title, $content)
{
    global $config;
    $nFrom            = $config['blog']['author']['name'];
    $mFrom            = $config['email']['sender'];
    $mPass            = $config['email']['pass'];
    $mail             = new \PHPMailer\PHPMailer\PHPMailer();
    $body             = $content;
    $mail->IsSMTP();
    $mail->CharSet    = "utf-8";
    $mail->SMTPDebug  = 0;
    $mail->SMTPAuth   = true;
    $mail->SMTPSecure = "ssl";
    $mail->Host       = $config['email']['smtp_host'];
    $mail->Port       = $config['email']['smtp_port'];
    $mail->Username   = $mFrom;
    $mail->Password   = $mPass;
    $mail->SetFrom($mFrom, $nFrom);

    $mail->Subject    = $title;
    $mail->MsgHTML($body);
    $address = $config['email']['receiver'];
    $mail->AddAddress($address, $nFrom);
    if (!$mail->Send()) {
        return 0;
    } else {
        return 1;
    }
}
