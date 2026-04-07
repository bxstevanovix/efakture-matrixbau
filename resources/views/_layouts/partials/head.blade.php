<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

@php
    $timestamp = date('d-H');
@endphp

@stack('head_links')





<link rel="stylesheet" href="{{ asset('files/vendor/select2/css/select2.min.css') }}">
<link rel="shortcut icon" type="image/png" href="{{ asset('mini-f-logo.svg') }}">
<link href="{{ asset('files/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{ asset('files/vendor/datatables/css/buttons.dataTables.min.css') }}" rel="stylesheet">	
<link href="{{asset('files/vendor/datatables/responsive/responsive.css')}}" rel="stylesheet">	

<link href="{{ asset('files/vendor/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('files/vendor/nouislider/nouislider.min.css') }}">
<link href="{{ asset('files/css/style.css') }}" rel="stylesheet">
<link href="{{ asset('files/css/custom.css') }}" rel="stylesheet">

<!-- Toastr i Sweetalert -->
<link rel="stylesheet" href="{{ asset('files/vendor/toastr/css/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('files/vendor/sweetalert2/dist/sweetalert2.min.css') }}">

<link href="{{ asset('files/vendor/bootstrap-datepicker-master/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">

<!-- CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.fancytree/2.38.2/skin-vista/ui.fancytree.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@stack('head_scripts')