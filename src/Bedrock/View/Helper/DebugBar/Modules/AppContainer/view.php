<div class="pkdebugbar-window pkdebugbar-window-appcontainer">
    <h1><div class="hblock"><?php echo count($this->instances); ?> Instances</div></h1>
    <?php
        foreach ($this->instances as $name => $instance) {
            echo '<strong><a href="#'.$name.'">'.$name.'</a></strong><br />';
        }

        foreach ($this->instances as $name => $instance) {
            echo '<h2><div class="hblock"><a id="'.$name.'">'.$name.'</a></div></h2><br /><pre>'.print_r($instance, true).'</pre>';
        }
    ?>
</div>
