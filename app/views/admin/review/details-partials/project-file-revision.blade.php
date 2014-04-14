<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			{{{ $reviewee->product_revision_type }}}
		</h3>
	</div>
	<div class="panel-body">
		<dl class="dl-horizontal">
			<dt>{{{ trans('common.table.headers.music_genre') }}}</dt>
			<dd>{{{ $productRevision->musicGenre->name }}}</dd>

			<dt>{{{ trans('common.table.headers.bpm') }}}</dt>
			<dd>{{{ $productRevision->bpm }}}</dd>

			<dt>{{{ trans('common.table.headers.description') }}}</dt>
			<dd style="white-space: pre-line;">{{{ $productRevision->description }}}</dd>

			<dt>{{{ trans('common.table.headers.music_programs') }}}</dt>
			<dd>{{{ implode(', ', $productRevision->getNamesOfCompatibleMusicPrograms()) }}}</dd>

			<dt>{{{ trans('common.table.headers.music_plugins') }}}</dt>
			<dd>{{{ implode(', ', $productRevision->getNamesOfCompatibleMusicPlugins()) }}}</dd>

			<dt>{{{ trans('common.table.headers.music_plugin_banks') }}}</dt>
			<dd>{{{ implode(', ', $productRevision->getNamesOfCompatibleMusicPluginBanks()) }}}</dd>
		</dl>
	</div>
</div>
@include('admin.review.details-partials.files', ['files' => $productRevision->getFiles()])
