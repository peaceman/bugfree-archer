<fieldset>
	<legend>{{{ trans('common.files') }}}</legend>
	<ul class="media-list">
		@foreach($files as $fileInfo)
		@include('admin.review.details-partials.file', ['file' => $fileInfo['file'], 'useAs' => $fileInfo['use_as']])
		@endforeach
	</ul>
</fieldset>
