<h1><div class="hblock">Files information</div></h1>
<strong>
    <?php count($view->files); ?> Files included<br />
    Total size: <?php echo round($view->files['total_size'] / 1024, 2); ?> Kbs
</strong>
<hr />
<?php
    foreach ($view->files['files'] as $file) {
        $filename = str_replace(
            basename($file['shortpath']),
            '<strong>'.basename($file['shortpath']).'</strong>',
            $file['shortpath']
        );
        echo $filename.' - <small>'.$file['size'].' Kbs</small><br />';
    }
?>

