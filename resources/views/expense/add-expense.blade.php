@extends('layouts.app')
@section('content')
<div id="app">
    <expense-form></expense-form>
</div>
@endsection

@section('script')
@vite('resources/js/app.js')
@endsection
