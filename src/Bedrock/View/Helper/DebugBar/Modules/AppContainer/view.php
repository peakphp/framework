<h1><div class="hblock"><?php echo count($view->instances); ?> Instances</div></h1>
<?php
    foreach ($view->instances as $name => $instance) {
        echo '<strong><a href="#'.$name.'">'.$name.'</a></strong><br />';
    }

    foreach ($view->instances as $name => $instance) {
        echo '<h2><div class="hblock"><a id="'.$name.'">'.$name.'</a></div></h2><br /><pre>'.print_r($instance, true).'</pre>';
    }
?>
