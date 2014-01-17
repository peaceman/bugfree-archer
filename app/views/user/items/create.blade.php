@extends('layouts.dashboard')
@section('dashboard.header')
<h1>{{ trans('user.items.create.dashboard_header') }}</h1>
@overwrite

@section('content.dashboard')
<div class="row">
	<div class="col-md-6">
		<form method="POST" action="">

			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">{{ trans('user.items.create.form.panel_title') }}</h2>
				</div>
				<div class="panel-body">
					<div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
						{{ Form::label('title', trans('user.items.create.form.title')) }}
						{{ Form::text('title', null, ['class' => 'form-control']) }}
						{{ $errors->first('title') }}
					</div>

					<div class="form-group {{ $errors->has('description') ? 'has-error': '' }}">
						{{ Form::label('description', trans('user.items.create.form.description')) }}
						{{ Form::textarea('description', null, ['rows' => 10, 'class' => 'form-control']) }}
						{{ $errors->first('description') }}
					</div>

					<fieldset class="form-horizontal">
						<legend>{{ trans('user.items.create.form.metadata') }}</legend>
						{{ Form::selectField('shop_category_id', trans('user.items.create.form.shop_category'), $shopCategories) }}
						{{ Form::selectField('music_genre_id', trans('user.items.create.form.music_genre'), $musicGenres) }}
						{{ Form::textField('bpm', trans('user.items.create.form.bpm')) }}

						<div class="form-group">
							<label class="control-label col-md-3" for="price">{{ trans('user.items.create.form.price') }}</label>
							<div class="col-md-9">
								<div class="input-group">
									<span class="input-group-addon">€</span>
									<input class="form-control" type="number" step="0.1" id="price" name="price" />
								</div>
							</div>
						</div>

					</fieldset>
<!--					<fieldset>-->
<!--						<legend>{{ trans('user.items.create.form.compatibility') }}</legend>-->
<!---->
<!--						{% form_select form.compatible_programs %}-->
<!--						{% form_select form.compatible_plugins %}-->
<!--						{% form_select form.compatible_banks %}-->
<!--					</fieldset>-->





				</div>
				<div class="panel-footer">
					<button class="btn btn-default btn-block btn-primary">
						{% trans 'Save for review' %}
					</button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h2 class="panel-title">{{ trans('user.items.create.info.panel_title') }}</h2>
			</div>
			<div class="panel-body">
				{% lorem %}
			</div>
			<ul class="list-group">
				<li class="list-group-item">
					<h4 class="list-group-item-heading">{% trans 'Copyright' %}</h4>

					<p class="list-group-item-text">
						{% lorem %}
					</p>
				</li>
				<li class="list-group-item">
					<h4 class="list-group-item-heading">{% trans 'Licenses' %}</h4>

					<p class="list-group-item-text">
						{% lorem %}
					</p>
				</li>
				<li class="list-group-item">
					<h4 class="list-group-item-heading">{% trans 'Bla bla bla' %}</h4>

					<p class="list-group-item-text">
						{% lorem %}
					</p>
				</li>
			</ul>
		</div>
	</div>
</div>
{% endblock %}
@stop
