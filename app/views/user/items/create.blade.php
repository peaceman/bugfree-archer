@extends('layouts.dashboard')
@section('dashboard.header')
<h1>{{ trans('user.items.create.dashboard_header') }}</h1>
@overwrite

@section('content.dashboard')
<div class="row">
	<div class="col-md-6">
		<form class="form-horizontal" method="POST" action="">

			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">{{ trans('user.items.create.form.panel_title') }}</h2>
				</div>
				<div class="panel-body">
					{% for error in form.non_field_errors %}
					<div class="alert alert-danger"></div>
					{% endfor %}


					{% form_input form.title %}
					{% form_custom form.description %}
					<textarea class="form-control" name="" id="" rows="10"></textarea>
					{% endform_custom %}


					<fieldset>
						<legend>{{ trans('user.items.create.form.metadata') }}</legend>
						{% form_select form.category %}
						{% form_select form.genre %}
						{% form_input form.bpm %}
						{% form_custom form.price %}
						<div class="input-group">
							<span class="input-group-addon">€</span>
							<input class="form-control" type="number" step="0.1" id="" name="" value=""/>
						</div>
						{% endform_custom %}
					</fieldset>
					<fieldset>
						<legend>{{ trans('user.items.create.form.compatibility') }}</legend>

						{% form_select form.compatible_programs %}
						{% form_select form.compatible_plugins %}
						{% form_select form.compatible_banks %}
					</fieldset>





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
