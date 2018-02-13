<h1>Application Configurations</h1>
<table class="border-inside">
<?php
    $part = '';
    foreach ($view->config as $name => $val) {
        $name_parts = explode('.', $name);
        if ($part !== $name_parts[0]) {
            $part = $name_parts[0];
            echo '<tr><td>&nbsp;</td><td></td></tr>';
        }
        if (is_bool($val)) {
            $val = ($val === true) ? 'true' : 'false';
        }
        echo '<tr><td style="width:1px;font-weight:bold;">'.$name.'</td><td>'.$val.'</td></tr>';
    }
?>
</table>

