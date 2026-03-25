@extends('layouts.app')

@section('content')
    <h1>Dashboard Participant</h1>
    <p>Bienvenue {{ auth()->user()->name }}</p>
@endsection
