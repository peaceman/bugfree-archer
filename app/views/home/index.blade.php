@extends('layouts/master')

@section('content')
aloha from the home controller
@if (Auth::check())
<pre>{{ Auth::user()->toJson() }}</pre>
@endif
@stop
