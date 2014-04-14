<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			{{{ trans('common.files') }}}
		</h3>
	</div>
	<div class="panel-body">
		<ul class="media-list">
			@foreach($files as $fileInfo)
			@include('admin.review.details-partials.file', ['file' => $fileInfo['file'], 'useAs' => $fileInfo['use_as']])
			@endforeach
		</ul>
	</div>
</div>
