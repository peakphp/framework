<h1>Execution time</h1>

<h3><div class="hblock">Current Request</div></h3>
<?php echo $view->stats['current_request']['uri']; ?><br>
<?php echo $view->stats['current_request']['time']; ?> sec.

<?php if (!empty($view->stats['last_request'])) : ?>
    <h3><div class="hblock">Last request</div></h3>
    <?php echo $view->stats['last_request']['uri']; ?><br>
    <?php echo $view->stats['last_request']['time']; ?> sec.
<?php endif; ?>

<h3><div class="hblock">Average request time</div></h3>
<?php echo $view->stats['average_request']; ?> sec.

<?php if (!empty($view->stats['shortest_request'])) : ?>
    <h3><div class="hblock">Shortest request time</div></h3>
    <?php echo $view->stats['shortest_request']['uri']; ?><br>
    <?php echo $view->stats['shortest_request']['time']; ?> sec.
<?php endif; ?>

<?php if (!empty($view->stats['longest_request'])) : ?>
    <h3><div class="hblock">Longest request time</div></h3>
    <?php echo $view->stats['longest_request']['uri']; ?> -
    <code><?php echo $view->stats['longest_request']['time']; ?> sec.</code>
<?php endif; ?>

<pre>
<?php //print_r($view); ?>