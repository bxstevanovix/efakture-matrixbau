@extends('_layouts.layout')

@section('head_title', __('Firme'))

@push('head_links')

@endpush

@section('content')

<div class="row page-titles mx-0">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('firme.index')}}">@lang('Firme')</a></li>
		<li class="breadcrumb-item active">@lang('Pregled')</li>
	</ol>
</div>

{{-- @dd() --}}

<!-- row -->
<div class="row">
	
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">@lang('Lista firmi')</h4>
                
                <a href="{{route('firme.create')}}" class="btn btn-primary">
					<i class="fa fa-plus"></i>
					@lang('Dodaj')
				</a>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table id="exampledb" class="display" style="min-width: 850px">
						<thead>
							<tr>
								<th class="">@lang('Ime firme')</th>
								<th class="">@lang('Adresa')</th>
								<th class="">@lang('Ort')</th>
								<th class="">@lang('UID-Nummer')</th>
								<th class="">@lang('Telefon')</th>
								<th class="">@lang('Email')</th>
								<th class="disabled-sorting text-right">@lang('Opcije')</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	
</div>
@endsection

@push('footer_scripts')
<script>
$(function() {
	
	var blade = {
		datatablesAjaxUrl:"{{ route('firme.datatable') }}"
	};
	
	// DATATABLES
	$('#exampledb').DataTable({
		"processing": true,
		"serverSide": true,
		"ajax": {
			url: blade.datatablesAjaxUrl,
			type: "post",
		},
		"columns": [
			{"data": "name", "className": ""},
			{"data": "address", "className": ""},
			{"data": "ort", "className": ""},
			{"data": "uid", "className": ""},
			{"data": "phone", "className": ""},
			{"data": "email", "className": ""},
			{"data": "actions", orderable: false, searchable: false, "className": "text-right"}
		],
		
		"language": {
			"search": "Suchen:",
			"info": "Zeige _START_ bis _END_ von _TOTAL_ Einträgen",
			"infoEmpty": "Keine Einträge verfügbar",
			"infoFiltered": "(gefiltert von _MAX_ gesamten Einträgen)",
			"lengthMenu": "Zeige _MENU_ Einträge",
			"zeroRecords": "Keine passenden Einträge gefunden",
			"paginate": {
				"previous": '<i class="fa-solid fa-angle-left"></i>', // Strelica levo
				"next": '<i class="fa-solid fa-angle-right"></i>' // Strelica desno
			},
			// "lengthMenu": `
            //     <div class="dropdown">
            //         <select name="exampledb_length" aria-controls="example" 
            //                 class="form-select custom-dropdown">
            //             <option value="10">10</option>
            //             <option value="25">25</option>
            //             <option value="50">50</option>
            //             <option value="100">100</option>
            //         </select>
            //     </div>`
		},
		"drawCallback": function(settings) {
			var api = this.api();
			var totalPages = api.page.info().pages;
			if (totalPages <= 1) {
				// Sakrij paginaciju ako ima samo jedna stranica
				$(api.table().container()).find('.dataTables_paginate').hide();
			} else {
				$(api.table().container()).find('.dataTables_paginate').show();
			}
		},
		"order": [[0, "asc"]]
	});

});

</script>
@endpush