@extends('layouts/master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>{{{ $shopItemRevision->title }}}</h1>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-9">
		<div class="panel panel-default">
			<div class="panel-body" style="height: 200px;">
				preview player
			</div>
		</div>
		<p>
			{{{ $shopItemRevision->productRevision->description }}}
		</p>
		<fieldset>
			<legend>Comments</legend>
			<ul class="list-unstyled">
				<li>
					<blockquote>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
						<footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
					</blockquote>
				</li>
				<li>
					<blockquote>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
						<footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
					</blockquote>
				</li>
				<li>
					<blockquote>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
						<footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
					</blockquote>
				</li>
				<li>
					<blockquote>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
						<footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
					</blockquote>

				</li>
			</ul>
		</fieldset>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<div class="rating">
							<span class="glyphicon glyphicon-star"></span>
							<span class="glyphicon glyphicon-star"></span>
							<span class="glyphicon glyphicon-star"></span>
							<span class="glyphicon glyphicon-star"></span>
							<span class="glyphicon glyphicon-star-empty"></span>
						</div>
						<em>23 {{{ trans('shop.ratings') }}}</em>
					</div>
					<div class="col-md-6">
						<div class="price">{{{ $shopItem->activeRevision->price }}} &euro;</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<a class="btn btn-primary btn-block" href="">{{{ trans('shop.buy_now') }}}</a>
					</div>
				</div>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-3" style="padding-right: 0px;">
						@if($seller->getProfile()->hasAvatar())
						<img src="{{{ $seller->getProfile()->avatar->getUrl() }}}" alt="" style="width: 100%;"/>
						@endif
					</div>
					<div class="col-md-9">
						<a href="{{{ route('user.public-profile', [$seller->username]) }}}">
							{{{ $seller->username }}}
						</a><br>
						<small>{{{ trans('shop.seller') }}}</small>
					</div>
				</div>
			</div>
		</div>

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
	</div>
</div>
@stop
