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
			@foreach($shopItemRevisions as $shopItemRevision)
			<li class="media">
				<a class="pull-left" href="">
					<img class="media-object" src="" alt="" style="height: 80px; width: 80px;"/>
				</a>
				<div class="media-body">
					<div class="row">
						<div class="col-md-4">
							<h4 class="media-heading">
								<a href="{{{ route('shop-items.show', [$shopItemRevision->slug]) }}}">
									{{{ $shopItemRevision->title }}}
								</a>
							</h4>
							Seller:
							<a href="{{{ route('user.public-profile', [$shopItemRevision->getSubmitter()->username]) }}}">
								{{{ $shopItemRevision->getSubmitter()->username }}}
							</a>
							<div class="rating">
								<span class="glyphicon glyphicon-star"></span>
								<span class="glyphicon glyphicon-star"></span>
								<span class="glyphicon glyphicon-star"></span>
								<span class="glyphicon glyphicon-star"></span>
								<span class="glyphicon glyphicon-star-empty"></span>
							</div>
						</div>
						<div class="col-md-5">
							<small>
								{{{ implode(', ', $shopItemRevision->getMetaData()) }}}
							</small>
						</div>
						<div class="col-md-3">
							<div class="price">{{{ $shopItemRevision->price }}} &euro;</div>
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
