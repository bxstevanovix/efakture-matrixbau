@extends('_layouts.layout')

@section('head_title', __('Editovanje') . ' - ' . __('Ulazne fakture'))

@push('head_links')

@endpush

@section('content')

<div class="row page-titles mx-0">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('supplier-invoices.index')}}">@lang('Ulazne fakture')</a></li>
		<li class="breadcrumb-item active">@lang('Editovanje Fakture')</li>
	</ol>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card h-auto">
            <div class="card-header">
				<h4 class="card-title">{{$entity->id_invoice}}</h4>
                @if ($entity->pdf)
                    <form action="{{ route('supplier-invoices.delete_pdf', $entity) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Sind Sie sicher, dass Sie den PDF löschen möchten?')" class="btn btn-danger btn-sm">
                            Löschen PDF
                        </button>
                    </form>
                @endif
                <a href="{{route('supplier-invoices.index')}}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i>
                    @lang('Nazad')
                </a>
			</div>
            <div class="card-body">
                <div class="card-body">
                    <div class="form-validation">
                        @include('supplier_invoices.partials.form', ['entity' => $entity])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
