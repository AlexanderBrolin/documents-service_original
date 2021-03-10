@extends('layouts.app')

@section('title') Физическое лицо #{{ $id }} @endsection

@section('content')
    <div class="container">
        <individual-page :id="{{ json_encode($id) }}"></individual-page>
    </div>
@endsection
