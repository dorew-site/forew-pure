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

$full_url = $request_method->get('link');
$arr_ytb = ['youtube', 'youtu.be/'];

if (strpos($full_url, $arr_ytb[0]) !== false || strpos($full_url, $arr_ytb[1]) !== false) {
    $vidUrl = $request_method->get_youtube_id($full_url);
?>
    <iframe id="ytplayer" type="text/html" allowfullscreen="" width="100%" height="100%" src="https://youtube.076.ne.jp/embed/<?php echo $vidUrl; ?>" frameborder="0"></iframe>

<?php
} else {
?>
    <div id="dplayer"></div>
    <script src="https://cdn.statically.io/gh/kn007/DPlayer-Lite/00dab19fc8021bdb072034c0415184a638a3e3b2/dist/DPlayer.min.js"></script>
    <script>
        const dp = new DPlayer({
            container: document.getElementById('dplayer'),
            video: {
                url: '<?php $full_url ?>',
            },
        });
    </script>
<?php
}
?>
<style>body{margin:0}</style>