@extends('layouts.front')
@section('content')
    <section class="">
		{!! isset($page) ? $page->html : '' !!}
    </section>
@endsection

@section('page-css')
    <link href="{{ url('assets/css/aboutus.css') }}" rel="stylesheet">
    <style>
        {!! isset($page) ? $page->css : '' !!}
    </style>
@endsection