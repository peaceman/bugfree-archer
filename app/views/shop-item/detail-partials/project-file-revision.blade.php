<div class="panel panel-default">
	<div class="panel-body">
		<dl>
			<dt>Created</dt>
			<dd>{{{ $shopItem->updated_at }}}</dd>

			<dt>Category</dt>
			<dd>{{{ trans($shopItemRevision->shopCategory->name) }}}</dd>

			<dt>Music genre</dt>
			<dd>{{{ $productRevision->musicGenre->name }}}</dd>

			<dt>BPM</dt>
			<dd>{{{ $productRevision->bpm }}}</dd>

			<dt>Music programs</dt>
			<dd>{{{ implode(', ', $productRevision->getNamesOfCompatibleMusicPrograms()) }}}</dd>

			<dt>Music plugins</dt>
			<dd>{{{ implode(', ', $productRevision->getNamesOfCompatibleMusicPlugins()) }}}</dd>

			<dt>Music plugin banks</dt>
			<dd>{{{ implode(', ', $productRevision->getNamesOfCompatibleMusicPluginBanks()) }}}</dd>
		</dl>
	</div>
</div>
