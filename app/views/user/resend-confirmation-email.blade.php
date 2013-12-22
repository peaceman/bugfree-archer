@extends('layouts.master')
@section('content')
<div class="page-header">
    <h1>{{ trans('user.resend_confirmation_email.page_header') }} <small>{{ trans('user.resend_confirmation_email.page_header_small') }}</small></h1>
</div>
<form class="form-horizontal" method="POST" action="{{ route('user.perform.resend-confirmation-email') }}">
    <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
        {{ Form::label('username', trans('common.username'), ['class' => 'col-md-2 control-label']) }}
        <div class="col-md-4">
            {{ Form::text('username', null, ['class' => 'form-control']) }}
            @if ($errors->has('username'))
            {{ $errors->first('username', '<span class="help-block">:message</span>') }}
            @endif
        </div>
    </div>
    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
        {{ Form::label('email', trans('common.email'), ['class' => 'col-md-2 control-label']) }}
        <div class="col-md-4">
            {{ Form::text('email', null, ['class' => 'form-control']) }}
            @if ($errors->has('email'))
            {{ $errors->first('email', '<span class="help-block">:message</span>') }}
            @endif
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-md-offset-2 col-md-4">
            <button type="submit" class="btn btn-default">{{ trans('user.resend_confirmation_email.submit_button') }}</button>
        </div>
    </div>
</form>
@stop