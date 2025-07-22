<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ config('app.name') }} </title>
    {{-- <link href="{{ asset('uploads/' . $profile->logo2) }}" rel="icon"> --}}

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content={{ config('app.name') }} name="description" />
    <meta content={{ config('app.name') }} name="Mr. Nobody" />

    <link href="{{ asset('') }}css/bootstrap.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}css/icons.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}css/app.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}css/style.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="css/style.css">
    @stack('style')
</head>
