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
		@include('shop-item.comments-partial')
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

		@include('shop-item.seller-partial')
		@include('shop-item.detail-partials.' . snake_case($shopItemRevision->product_revision_type, '-'))
	</div>
</div>
@stop
