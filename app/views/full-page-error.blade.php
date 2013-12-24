@extends('layouts.master')
@section('content')
<div class="page-header">
	<h1>{{ $header }} <small>{{ $header_small or '' }}</small></h1>
</div>
<p>
	{{ $text }}
</p>
@stop
