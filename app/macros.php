<?php
/*
|--------------------------------------------------------------------------
| Delete form macro
|--------------------------------------------------------------------------
|
| This macro creates a form with only a submit button.
| We'll use it to generate forms that will post to a certain url with the DELETE method,
| following REST principles.
|
*/
Form::macro('delete', function ($url, $button_label = 'Delete', $form_parameters = array(), $button_options = array()) {

	if (empty($form_parameters)) {
		$form_parameters = array(
			'method' => 'DELETE',
			'class' => 'delete-form',
			'url' => $url
		);
	} else {
		$form_parameters['url'] = $url;
		$form_parameters['method'] = 'DELETE';
	};

	return Form::open($form_parameters)
	. Form::submit($button_label, $button_options)
	. Form::close();
});

Form::macro('textField', function ($name, $label = null, $value = null, $attributes = array()) {
	$element = Form::text($name, $value, fieldAttributes($name, $attributes));

	return fieldWrapper($name, $label, $element);
});

Form::macro('passwordField', function ($name, $label = null, $attributes = array()) {
	$element = Form::password($name, fieldAttributes($name, $attributes));

	return fieldWrapper($name, $label, $element);
});

Form::macro('textareaField', function ($name, $label = null, $value = null, $attributes = array()) {
	$element = Form::textarea($name, $value, fieldAttributes($name, $attributes));

	return fieldWrapper($name, $label, $element);
});

Form::macro('selectField', function ($name, $label = null, $options, $value = null, $attributes = array()) {
	$element = Form::select($name, $options, $value, fieldAttributes($name, $attributes));

	return fieldWrapper($name, $label, $element);
});

Form::macro('selectMultipleField', function ($name, $label = null, $options, $value = null, $attributes = array()) {
	$attributes = array_merge($attributes, ['multiple' => true]);
	$element = Form::select($name, $options, $value, fieldAttributes($name, $attributes));

	return fieldWrapper($name, $label, $element);
});

Form::macro('checkboxField', function ($name, $label = null, $value = 1, $checked = null, $attributes = array()) {
	$attributes = array_merge(['id' => 'id-field-' . $name], $attributes);

	$out = '<div class="checkbox';
	$out .= fieldErrorClass($name) . '">';
	$out .= '<label>';
	$out .= Form::checkbox($name, $value, $checked, $attributes) . ' ' . $label;
	$out .= '</div>';

	return $out;
});

function fieldWrapper($name, $label, $element)
{
	$out = '<div class="form-group';
	$out .= fieldErrorClass($name) . '">';
	$out .= fieldLabel($name, $label);
	$out .= '<div class="col-md-9">';
	$out .= $element;
	$out .= fieldErrorMessage($name);
	$out .= '</div>';
	$out .= '</div>';

	return $out;
}

function fieldErrorClass($field)
{
	$error = '';

	if ($errors = Session::get('errors')) {
		$error = $errors->has($field) ? ' has-error' : '';
	}

	return $error;
}

function fieldErrorMessage($field)
{
	$error = '';

	if ($errors = Session::get('errors')) {
		if ($message = $errors->first($field)) {
			$error = $message;
		}
	}

	return $error;
}

function fieldLabel($name, $label)
{
	if (is_null($label)) return '';

	$name = str_replace('[]', '', $name);

	$out = '<label for="id-field-' . $name . '" class="control-label col-md-3">';
	$out .= $label . '</label>';

	return $out;
}

function fieldAttributes($name, $attributes = array())
{
	$name = str_replace('[]', '', $name);

	return array_merge(['class' => 'form-control', 'id' => 'id-field-' . $name], $attributes);
}

function nice_bytesize($size)
{
	$units = array('Bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB');
	return @round($size / pow(1024, ($i = max(floor(log($size, 1024)), 0))), 2).' '.$units[$i];
}
