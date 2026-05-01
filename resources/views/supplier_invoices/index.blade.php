@extends('_layouts.layout')

@section('head_title', __('Ulazne Fakture'))

@push('head_links')

@endpush

@section('content')

<style>
    #exampledb {
        margin-bottom: 30px;
    }

    .dropdown-menu {
        padding: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        border-radius: 10px;
    }
    .dropdown-menu .dropdown-item {
        margin-bottom: 5px;
    }
    .dropdown-menu .dropdown-item:last-child {
        margin-bottom: 0;
    }
    .dropdown-menu .dropdown-item i {
        margin-right: 8px;
    }

    #exampledb thead th:first-child.sorting::after,
    #exampledb thead th:first-child.sorting_asc::after,
    #exampledb thead th:first-child.sorting_desc::after {
        display: none;
    }

    #exampledb th:nth-child(2),
    #exampledb td:nth-child(2) {
        max-width: 400px; 
        overflow: hidden;
        text-overflow: ellipsis; 
        white-space: nowrap; 
    }

    #exampledb th:nth-child(3),
    #exampledb td:nth-child(3) {
        max-width: 400px; 
        overflow: hidden;
        text-overflow: ellipsis; 
        white-space: nowrap; 
    }

    th, td {
        white-space: nowrap;
    }
    
    .date-highlight {
        padding: 5px;
        border-radius: 5px;
        display: inline-block;
    }
    .date-red {
        background-color: red;
        color: white;
    }
    .date-yellow {
        background-color: yellow;
        color: black;
    }
    .date-green {
        background-color: #09BD3C;
        color: white;
    }
    .summary-box {
        padding: 20px;
        border-radius: 10px;
        color: black;
        text-align: center;
        font-size: 20px;
    }

    .summary-box {
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            background: #f8f9fa;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

    .summary-eur {
        background-color: white;
    }
    .summary-km {
        background-color: white;
    }
    .summary-rsd {
        background-color: white;
    }

    .card-option {
        cursor: pointer;
        border-radius: 10px;
        border: 2px solid transparent;
    }
    .card-option:hover {
        border-color: #007bff;
    }
    .card-option.active {
        border-color: #007bff;
        background-color: #e9ecef;
    }
    .input-amount {
        display: none;
        margin-top: 10px;
    }
    
    .modal-content {
        border-radius: 10px !important;
    }

    .rounded-swal {
        border-radius: 10px;
    }

    .debt-debt {
        color: red;
    }

    .modal-values {
        border-top: 1px solid #ddd; /* Linija iznad */
        padding-top: 10px;
        padding-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-align: center;
    }

    .modal-values div {
        flex: 1;
        padding: 0 3px;
    }

</style>

<div class="row page-titles mx-0">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('supplier-invoices.index')}}">@lang('Ulazne fakture')</a></li>
		<li class="breadcrumb-item active">@lang('Pregled')</li>
	</ol>
</div>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">@lang('Lista ulaznih faktura')</h4>
                
                <a href="{{route('supplier-invoices.create')}}" class="btn btn-primary">
					<i class="fa fa-plus"></i>
					@lang('Kreiraj fakturu')
				</a>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table id="exampledb" class="display" style="width:100%">
						<thead>
							<tr>
								<th class="">@lang('ID')</th>
                                <th class="">@lang('Firma')</th>
                                <th class="">@lang('Adresa')</th>
                                <th class="">@lang('Datum')</th>
                                <th class="">@lang('Rok placanja')</th>
                                <th class="">@lang('Za uplatu')</th>
                                <th></th>
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

<div class="row">
    <div class="col-md-8"></div>
    <div class="col-md-4">
        <div class="summary-box summary-eur">
            <div class="text-danger">@lang('Ukupno'): <span id="pending-eur">0</span> EUR</div>
        </div>
    </div>
</div>

<!-- Modal za plaćanje fakture -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="invoicePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="invoicePaymentModalLabel">@lang('Plaćanje fakture'):</h5>
                <h2 class="invoice_id" style="margin-left: auto; color: black;"></h2>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="row">
                    <!-- Pun iznos fakture -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body text-center">
                                @lang('Pun iznos fakture'): <h2 id="pun_iznos"></h2>
                            </div>
                            <div class="col-md-12 text-center">
                                <label for="payment_date">@lang('Datum uplate'):</label>
                                <input 
                                    type="text" 
                                    id="payment_date" 
                                    name="payment_date" 
                                    class="form-control text-center" 
                                    required
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">@lang('Zatvori')</button>
                <button type="button" class="btn btn-success" id="saveBtn">@lang('Plati')</button>
            </div>
        </div>
    </div>
</div>



@endsection

@push('footer_scripts')

<script>
$(function() {

    // Ako koristiš plain HTML5 date picker sa tekstom
   

    var blade = {
        datatablesAjaxUrl: "{{ route('supplier-invoices.datatable') }}"
    };

    // Function to update summary values
    function updateSummary() {
        $.ajax({
            url: "{{ route('supplier-invoices.summary') }}",
            type: "GET",
            dataType: "json",
            success: function(response) {
                // Update summary values for EUR
                $('#pending-eur').text(response.pending.eur);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }
    updateSummary();

    // DATATABLES
    $('#exampledb').DataTable({
        "stateSave": true,
        "responsive": true,
        "autoWidth": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: blade.datatablesAjaxUrl,
            type: "post",
        },
        "columns": [
            {"data": "id_invoice", orderable: false, "className": "", responsivePriority: 1},
            {"data": "company", orderable: false, "className": "", responsivePriority: 4},
            {"data": "address", orderable: false, "className": "", responsivePriority: 6},
            {"data": "date_start", orderable: false, searchable: false, "className": "", responsivePriority: 7},
            {"data": "date_end", orderable: false, searchable: false, "className": "", responsivePriority: 3},
            {"data": "debt", orderable: false, searchable: false, "className": "", responsivePriority: 5},
            {"data": "paid", orderable: false, searchable: false, "className": "", responsivePriority: 2},
            {"data": "actions", orderable: false, searchable: false, "className": "text-right", responsivePriority: 5}
        ],
        "order": [[0, "asc"]],
        "rowCallback": function(row, data, index) {
            var today = moment();
            var dateEnd = moment(data.date_end, 'DD-MM-YYYY');

            // Reset any previous highlighting
            $('td:eq(4)', row).removeClass('date-red date-yellow date-green');

            // Add the appropriate class based on the date condition
            if (data.status == 1) {
                $('td:eq(4)', row).html('<span class="date-highlight date-green">' + data.date_end + '</span>');
            } else if (today.isSameOrAfter(dateEnd, 'day')) {
                $('td:eq(4)', row).html('<span class="date-highlight date-red">' + data.date_end + '</span>');
            } else if (today.isSameOrAfter(dateEnd.subtract(7, 'days'), 'day')) {
                $('td:eq(4)', row).html('<span class="date-highlight date-yellow">' + data.date_end + '</span>');
            } else {
                $('td:eq(4)', row).html(data.date_end);
            }

            if (parseFloat(data.debt) < parseFloat(data.price)) {
                $('td:eq(5)', row).addClass('debt-debt')
                                .attr('title', 'Iznos fakture ' + data.price)
                                .tooltip();
            } else {
                $('td:eq(5)', row).removeClass('debt-debt')
                                .removeAttr('title')
                                .tooltip('dispose');
            }
        },
        "initComplete": function() {
            $('[data-toggle="tooltip"]').tooltip();
        },
        "language": {
            "info": "Zeige _START_ bis _END_ von _TOTAL_ Einträgen",
            "infoEmpty": "Keine Einträge verfügbar",
            "infoFiltered": "(gefiltert von _MAX_ gesamten Einträgen)",
            "lengthMenu": "Zeige _MENU_ Einträge",
            "zeroRecords": "Keine passenden Einträge gefunden",
            "search": "Geben Sie die Rechnungsnummer ein:",
            "paginate": {
                "previous": '<i class="fa-solid fa-angle-left"></i>', // Strelica levo
                "next": '<i class="fa-solid fa-angle-right"></i>' // Strelica desno
            }
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
    });

    //MODAL ZA POTVRDU PLACANJA

    var id;
    var idInvoice;
    var price;
    var debt;

    $(document).on('click', '#openModalBtn', function() {

        id = $(this).data('id');
        idInvoice = $(this).data('id-invoice');
        price = $(this).data('price');

        $('#myModal').modal('show');

        // Popuni modal
        $('.invoice_id').text(idInvoice);
        $('#pun_iznos').text(price + ' €');

        // Postavi datepicker sa današnjim datumom
        $('#payment_date').datepicker({
            format: 'dd-mm-yyyy',    // format datuma
            todayHighlight: true,    // istakni današnji datum
            autoclose: true
        });
        $('#payment_date').datepicker('setDate', new Date());

        // Sakrij input za unos iznosa jer nema parcijalnog plaćanja
        $('#amountInputContainer').hide();
    });

    $('#saveBtn').click(function() {

        var data = {
            id: id,
            payment_date: $('#payment_date').val(),
            isFullPayment: true
        };

        $.ajax({
            url: "{{ route('supplier-invoices.paid') }}",
            method: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response);
                $('#myModal').modal('hide');
                $('#exampledb').DataTable().draw('page');
                updateSummary();

                Swal.fire({
                    icon: 'success',
                    title: "@lang('Plaćeno')",
                    text: "@lang('Faktura je uspešno plaćena.')",
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: "@lang('Greška!')",
                    text: "@lang('Došlo je do greške prilikom plaćanja fakture.')"
                });
            }
        });
    });
});
</script>
@endpush