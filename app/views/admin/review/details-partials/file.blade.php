<li class="media">
	<a href="{{{ $file->download_url }}}" target="_blank" class="pull-left col-sm-2">
		@if(starts_with($file->mime_type, 'image/'))
		<img src="{{{ $file->download_url }}}" class="img-rounded media-object" style="width: 100%;">
		@else

		@endif
	</a>

	<div class="media-body">
		<h4 class="media-heading">{{{ $file->original_name }}} <small>{{{ $file->mime_type }}}</small></h4>
		<p>
		<table class="table table-condensed table-bordered">
			<tr>
				<th colspan="1">Download URL</th>
				<td colspan="3"><a href="{{{ $file->download_url }}}" target="_blank">{{ $file->download_url }}</a></td>
			</tr>
			<tr>
				<th>File size</th>
				<td>{{{ nice_bytesize($file->size) }}}</td>
				<th>Usage</th>
				<td>{{{ $useAs }}}</td>
			</tr>
		</table>
		</p>
	</div>
</li>
