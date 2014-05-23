@extends('layouts/master')

@section('scripts.header')
<script src="{{{ asset('/assets/mediaelement-and-player.min.js') }}}"></script>
<link rel="stylesheet" href="{{{ asset('/assets/mediaelementplayer.css') }}}" />
@stop

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
				<audio class="mejs-player" src="{{{ $productRevision->sampleFile->getUrl() }}}" style="height: 100%; width: 100%;"></audio>
			</div>
		</div>
		<p>
			{{{ $shopItemRevision->productRevision->description }}}
		</p>
		@include('shop-item.comments-partial')
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						@include('shop-item.rating-partial')
					</div>
					<div class="col-md-6">
						<div class="price">{{{ $shopItem->activeRevision->price }}} &euro;</div>
					</div>
				</div>
				@if(Auth::check() && !$shopItem->isSeller($user))
				<div class="row">
					<div class="col-md-12">
						<a class="btn btn-primary btn-block" href="{{{ $buyNowLink }}}">{{{ trans('shop.buy_now') }}}</a>
					</div>
				</div>
				@endif
			</div>
		</div>

		@include('shop-item.seller-partial')
		@include('shop-item.detail-partials.' . snake_case($shopItemRevision->product_revision_type, '-'))
	</div>
</div>
@stop
