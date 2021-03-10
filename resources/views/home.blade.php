@extends('layouts.app')

@section('title') Личный кабинет @endsection

@section('content')
<div class="container">
    <div class="">
        <?php
        $user = \Illuminate\Support\Facades\Auth::user();
        ?>
        <dashboard-component :user="{{ json_encode($user) }}"></dashboard-component>
    </div>
</div>
@endsection
