<div style="margin-bottom: 50px;">
	<h2><?php echo h($targetTest->title()) ?></h2>

	<pre>
	<?php foreach ($messages as $message): ?>
	<?php echo($message); ?><br />
	<?php endforeach; ?>
	</pre>
</div>