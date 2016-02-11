<div class="panel panel-default">
	<div class="panel-body">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Code</th>
					<th>Name</th>
					<th>Link</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($ads as $ad): ?>
				<tr>
					<td><?php echo $ad->code ?></td>
					<td><?php echo $ad->name ?> (<?php echo $ad->size ?> px)</td>
					<td><?php echo $ad->link ?></td>
					<td><a href="<?php echo site_url('ads/edit/'.$ad->code) ?>" class="btn btn-sm btn-primary">Set Iklan</a></td>
				</tr>					
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>