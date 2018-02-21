<h1>Message(s)</h1>
<?php
foreach ($view->messages as $message) {
    echo '<div class="message message-'.$message->level.'">
            '.$message->content.'
            <div class="message-level">'.$message->level.'</div>
          </div>';
}
