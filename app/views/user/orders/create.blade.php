@extends('layouts/master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>{{{ trans('user.orders.confirm_purchase') }}}
				<small>{{{ $shopItemRevision->title }}}</small>
			</h1>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					{{{ trans('user.orders.buy_now') }}}
					{{{ trans('user.orders.from_seller') }}}
					<em>{{{ $seller->username }}}</em>
				</h3>
			</div>

			<div class="panel-body">
				<fieldset>
					<legend><span class="text-muted">Product title:</span> {{{ $shopItemRevision->title }}}</legend>
					conditions / terms?
				</fieldset>
			</div>

			<div class="panel-footer">
				<button type="button" class="btn btn-primary btn-lg btn-block">
					<strong>{{{ trans('user.orders.buy_now') }}}</strong> {{{ trans('user.orders.with_paypal') }}} - <span class="price">{{{ $shopItemRevision->price }}} &euro;</span>
				</button>
			</div>
		</div>

	</div>
</div>
@stop
