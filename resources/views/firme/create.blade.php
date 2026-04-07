@extends('_layouts.layout')

@section('head_title', __('Kreiranje Firme'))

@push('head_links')

@endpush

@section('content')
<style>
    .error{
        color: red;
    }
</style>

<div class="row page-titles mx-0">
	<ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('firme.index')}}">@lang('Firme')</a></li>
		<li class="breadcrumb-item active">@lang('Kreiranje Firme')</li>
	</ol>
</div>

<div class="row">
    <div class="col-8">
        <div class="card h-auto">
            <div class="card-header">
				<h4 class="card-title">@lang('Kreiranje Nove Firme')</h4>
                
                <a href="{{route('firme.index')}}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i>
                    @lang('Nazad')
                </a>
			</div>
            <div class="card-body">
                <div class="form-validation">
                    @include('firme.partials.form', ['entity' => $entity])
                </div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="right-sidebar-sticky">
            <div class="filter cm-content-box box-primary">
                <div style="height: 100px;" class="cm-content-body publish-content form excerpt textarea-container text-center">
                    <p style="margin: 10px;">Dokumenti:</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
