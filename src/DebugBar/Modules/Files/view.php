<h1>Files information</h1>
<strong>
    <?php count($view->files); ?> Files included<br />
    Total size: <?php echo round($view->files['total_size'] / 1024, 2); ?> Kbs
</strong>
<div class="pre-block">
<?php
    foreach ($view->files['files'] as $file) {
        $filename = str_replace(
            basename($file['shortpath']),
            '<strong>'.basename($file['shortpath']).'</strong>',
            $file['shortpath']
        );
        echo '<div class="line">'.$filename.' <small style="float:right">'.formatSize($file['size']).'</small></div>';
    }
?>
</div>
