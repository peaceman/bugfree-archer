<fieldset>
	<legend>Comments</legend>
	<ul class="list-unstyled">
		@foreach ($comments as $comment)
		<li>
			<blockquote>
				<p>{{{ $comment->content }}}</p>
				<footer>{{{ $comment->submitter->username }}} at {{{ $comment->created_at }}}</footer>
			</blockquote>
		</li>
		@endforeach
		@if (!count($comments))
		<li>
			{{{ trans('shop.no_comments') }}}
		</li>
		@endif
	</ul>
</fieldset>
