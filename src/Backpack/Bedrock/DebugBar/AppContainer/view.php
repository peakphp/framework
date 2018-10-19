<h1>Application Container</h1>
<h2><div class="h-block"><?php echo count($view->instances); ?> Instances</div></h2>
<?php
    foreach ($view->instances as $name => $instance) {
        echo '<strong><a href="#'.$name.'">'.$name.'</a></strong><br />';
    }

    foreach ($view->instances as $name => $instance) {
        echo '<h2><div class="h-block"><span id="'.$name.'">'.$name.'</span></div></h2><br /><pre>'.print_r($instance, true).'</pre>';
    }
?>
