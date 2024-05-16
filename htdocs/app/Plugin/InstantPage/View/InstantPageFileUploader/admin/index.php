
<div style="margin-top: 60px;">
	<?php if(!empty($imageDatas)): ?>
	<ol>
		<?php foreach ($imageDatas as $data): ?>
			<li style="margin-bottom: 30px">
				<img src="/img/instant_page_file_uploader/<?php echo $data['InstantPageFileUploader']['image_1'] ?>" style="max-width: 100px">
				<br>
				<a href="delete/<?php echo $data['InstantPageFileUploader']['id'] ?>">削除する</a>
			</li>
		<?php endforeach; ?>
	</ol>
	<?php else: ?>
		<div>
			データがありません
		</div>
	<?php endif; ?>
</div>
