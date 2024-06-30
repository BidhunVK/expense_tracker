@extends('layouts.app')
@section('content')
<div id="app">
    <expense-form :csrf-token="{{ json_encode(csrf_token()) }}"></expense-form>
</div>
@endsection

@section('script')
@vite('resources/js/app.js')
@endsection
