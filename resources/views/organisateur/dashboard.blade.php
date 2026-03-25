@extends('layouts.app')

@section('content')
    <h1>Dashboard Organisateur</h1>
    <p>Bienvenue {{ auth()->user()->name }}</p>
@endsection
