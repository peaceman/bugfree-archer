@extends('layouts/master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>{{{ trans($shopCategory->name) }}}</h1>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-9">
		<ul class="media-list">
			@foreach($shopItems as $shopItem)
			<li class="media">
				<a class="pull-left" href="{{{ route('shop-items.show', [$shopItem->activeRevision->slug]) }}}">
					<img class="media-object" src="{{{ $shopItem->activeRevision->productRevision->getListingPictureFileAttribute()->getUrl() }}}" alt="" style="width: 130px;"/>
				</a>
				<div class="media-body">
					<div class="row">
						<div class="col-md-4">
							<h4 class="media-heading">
								<a href="{{{ route('shop-items.show', [$shopItem->activeRevision->slug]) }}}">
									{{{ $shopItem->activeRevision->title }}}
								</a>
							</h4>
							Seller:
							<a href="{{{ route('user.public-profile', [$shopItem->activeRevision->submitter->username]) }}}">
								{{{ $shopItem->activeRevision->submitter->username }}}
							</a>
							@include('shop-item.rating-partial')
						</div>
						<div class="col-md-6">
							@include('shop-item.listing-detail-partials.' . snake_case($shopItem->activeRevision->product_revision_type, '-'))
						</div>
						<div class="col-md-2">
							<div class="price">{{{ $shopItem->activeRevision->price }}} &euro;</div>
						</div>
					</div>
				</div>
			</li>
			@endforeach
		</ul>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">{{{ trans('shop.search_filter') }}}</h3>
			</div>
			<div class="panel-body">
				bla blub
			</div>
		</div>
	</div>
</div>
@stop
