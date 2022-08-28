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
header('Content-Type: application/xml; charset=utf-8');

$start_xml = '<?';
$end_xml = '?>';
?>
<?php echo $start_xml ?>xml version="1.0" encoding="UTF-8"<?php echo $end_xml ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
  <url><loc><?php echo $home->url(); ?></loc></url>
  <url><loc><?php echo $home->url(); ?>/category</loc></url>
  <?php
  foreach ($QuerySQL->select_table_data('category', 'id', 'asc') as $cat) {
      ?>
      <url>
          <loc><?php echo $home->url(); ?>/category/<?php echo $cat['id'] . '-' . $cat['slug'] ?>.html</loc>
      </url>
      <?php
  }
  foreach ($QuerySQL->select_table_data('blog', 'id', 'asc') as $blog) {
      ?>
      <url>
          <loc><?php echo $home->url(); ?>/view/<?php echo $blog['id'] . '-' . $blog['slug'] ?>.html</loc>
          <lastmod><?php echo date('c', $blog['time']) ?></lastmod>
      </url>
      <?php
  }
  ?>
</urlset>
