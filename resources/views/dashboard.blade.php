@extends('_layouts.layout')

@section('head_title', __('Dashboard'))

@push('head_links')

@endpush

@section('content')

<style>
 .card {
    width: 100%; /* Obezbeđuje da kartica zauzima punu širinu */
}

.table-responsive {
    width: 100%; /* Obezbeđuje da tabela zauzima punu širinu roditelja */
}

.table {
    width: 100%; /* Obezbeđuje da tabela zauzima punu širinu */
}

.chart-container {
    position: relative;
    width: 100%;
    height: 300px; /* ključno */
}

@media (max-width: 768px) {
    .chart-container {
        height: 220px; /* manji na mobu */
    }
}

canvas {
    width: 100% !important;
    height: 100% !important;
}

.card-body {
    overflow: hidden;
}

@media (max-width: 768px) {
    .card {
        margin-bottom: 15px;
    }
}


</style>

<div class="row page-titles mx-0">
	<ol class="breadcrumb">
		<li class="breadcrumb-item">@lang('Main Menu')</li>
        <li class="breadcrumb-item active">@lang('Dnevni pregled')</li>
	</ol>
</div>

{{-- Broj faktura --}}
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-6">
            <div class="row">
                <div class="col-xl-6 col-sm-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="invoices">
                                <h4 class="card-title font-w600">{{$invoicesStatus0}}</h4>
                                <span>@lang('Izlazne fakture neplacene')</span>
                            </div>
                            <div class="invoice-icon bgl-danger">
                                <span>
                                    <i class="flaticon-project fs-22 text-danger"></i>
                                </span>
                                
                            </div>
                        </div>
                        <div class="card-body p-0 pt-2">
                            <div id="widgetChart4"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-sm-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="invoices">
                                <h4 class="card-title font-w600">{{$invoicesStatus1}}</h4>
                                <span>@lang('Izlazne fakture placene')</span>
                            </div>
                            <div class="invoice-icon bgl-info">
                                <span>
                                    <i class="flaticon-project fs-22 text-info"></i>
                                </span>
                                
                            </div>
                        </div>
                        <div class="card-body p-0 pt-2">
                            <div id="widgetChart5"></div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div><div class="col-xl-6">
            <div class="row">
                <div class="col-xl-6 col-sm-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="invoices">
                                <h4 class="card-title font-w600">{{$procurementStatus0}}</h4>
                                <span>@lang('Ulazne fakture neplacene')</span>
                            </div>
                            <div class="invoice-icon bgl-danger">
                                <span>
                                    <i class="flaticon-project fs-22 text-danger"></i>
                                </span>
                                
                            </div>
                        </div>
                        <div class="card-body p-0 pt-2">
                            <div id="widgetChart4"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-sm-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="invoices">
                                <h4 class="card-title font-w600">{{$procurementStatus1}}</h4>
                                <span>@lang('Ulazne fakture placene')</span>
                            </div>
                            <div class="invoice-icon bgl-info">
                                <span>
                                    <i class="flaticon-project fs-22 text-info"></i>
                                </span>
                                
                            </div>
                        </div>
                        <div class="card-body p-0 pt-2">
                            <div id="widgetChart5"></div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

{{-- TABELE --}}
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header justify-content-center text-center">
                <h4 class="card-title">@lang('Izlazne fakture')</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12 d-flex justify-content-center">
                        <button class="btn btn-primary" id="prev-date">&#8592;</button> <!-- Strelica nazad -->
                        <div class="mx-2">
                            <input
                                type="text"
                                class="form-control text-center"
                                name="date_start"
                                id="date_start"
                                value=""
                            >
                        </div>
                        <button class="btn btn-primary" id="next-date">&#8594;</button> <!-- Strelica napred -->
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-responsive-md" id="entity-list-table" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th class="">@lang('ID')</th>
                                <th class="">@lang('Firma')</th>
                                <th class="">@lang('Iznos')</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header justify-content-center text-center">
                <h4 class="card-title">@lang('Ulazne fakture')</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12 d-flex justify-content-center">
                        <button class="btn btn-primary" id="prev-date1">&#8592;</button> <!-- Strelica nazad -->
                        <div class="mx-2">
                            <input
                                type="text"
                                class="form-control text-center"
                                name="date_start1"
                                id="date_start1"
                                value=""
                            >
                        </div>
                        <button class="btn btn-primary" id="next-date1">&#8594;</button> <!-- Strelica napred -->
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-responsive-md" id="entity-list-table1" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th class="">@lang('ID')</th>
                                <th class="">@lang('Firma')</th>
                                <th class="">@lang('Iznos')</th>
                                {{-- <th></th> --}}
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

{{-- Ukupne sume za fakture --}}
<div class="row">
    <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
        <div class="widget-stat card">
            <div class="card-body  p-4">
                <div class="media ai-icon">
                    <span class="me-3 bgl-danger text-danger">
                        <svg id="icon-revenue" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </span>
                    <div class="media-body">
                        <p class="mb-1">@lang('Izlazne fakture')</p>
                        <h4 class="mb-0">{{$invoicesPrice0}}</h4>
                        <span class="badge badge-danger">EUR</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
        <div class="widget-stat card">
            <div class="card-body p-4">
                <div class="media ai-icon">
                    <span class="me-3 bgl-success text-success">
                        <svg id="icon-database-widget" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database">
                            <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                            <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                            <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                        </svg>
                    </span>
                    <div class="media-body">
                        <p class="mb-1">@lang('Izlazne fakture')</p>
                        <h4 class="mb-0">{{$invoicesPrice1}}</h4>
                        <span class="badge badge-success">EUR</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
        <div class="widget-stat card">
            <div class="card-body  p-4">
                <div class="media ai-icon">
                    <span class="me-3 bgl-danger text-danger">
                        <svg id="icon-revenue" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </span>
                    <div class="media-body">
                        <p class="mb-1">@lang('Ulazne fakture')</p>
                        <h4 class="mb-0">{{$procurementPrice0}}</h4>
                        <span class="badge badge-danger">EUR</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
        <div class="widget-stat card">
            <div class="card-body p-4">
                <div class="media ai-icon">
                    <span class="me-3 bgl-success text-success">
                        <svg id="icon-database-widget" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database">
                            <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                            <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                            <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                        </svg>
                    </span>
                    <div class="media-body">
                        <p class="mb-1">@lang('Ulazne fakture')</p>
                        <h4 class="mb-0">{{$procurementPrice1}}</h4>
                        <span class="badge badge-success">EUR</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<hr>
<div class="row">
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header justify-content-center text-center">
                <h4 class="card-title">@lang('Izlazne fakture po mesecima')</h4>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="monthlyInvoicesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header justify-content-center text-center">
                <h4 class="card-title">@lang('Ulazne fakture po mesecima')</h4>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="monthlyProcurementsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('footer_scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const monthlyInvoicesData = @json($monthlyInvoices);
    const monthlyProcurementsData = @json($monthlyProcurements);
    const monthlyInvoicesNotPaid = @json($monthlyInvoicesNotPaid);
    const monthlyProcurementsNotPaid = @json($monthlyProcurementsNotPaid);

    console.log(monthlyInvoicesData);
    console.log(monthlyProcurementsData);
    console.log(monthlyInvoicesNotPaid);
    console.log(monthlyProcurementsNotPaid);
console.log("boooooroo");
    const months = ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'];

    function prepareData(data) {
        let result = new Array(12).fill(0);

        data.forEach(item => {
            let monthIndex = item.month - 1;
            result[monthIndex] = parseFloat(item.total);
        });

        return result;
    }

    const invoicesData = prepareData(monthlyInvoicesData);
    const procurementsData = prepareData(monthlyProcurementsData);

    new Chart(document.getElementById('monthlyInvoicesChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Bezahlt',
                    data: invoicesData, // ✅ FIX
                    backgroundColor: 'rgba(75, 192, 192, 0.6)'
                },
                {
                    label: 'Unbezahlt',
                    data: prepareData(monthlyInvoicesNotPaid), // ✅ FIX
                    backgroundColor: 'rgba(255, 99, 132, 0.6)'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // 🔥 OBAVEZNO
            scales: {
                x: { stacked: false }, // ❌ ukloni stack
                y: { beginAtZero: true } // ✅ obavezno od nule
            }
        }
    });

    new Chart(document.getElementById('monthlyProcurementsChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Bezahlt',
                    data: procurementsData, // ✅ FIX
                    backgroundColor: 'rgba(75, 192, 192, 0.6)'
                },
                {
                    label: 'Unbezahlt',
                    data: prepareData(monthlyProcurementsNotPaid), // ✅ FIX
                    backgroundColor: 'rgba(255, 99, 132, 0.6)'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // 🔥 OBAVEZNO
            scales: {
                x: { stacked: false }, // ❌ ukloni stack
                y: { beginAtZero: true } // ✅ obavezno od nule
            }
        }
    });
});


const dateInput = document.getElementById('date_start');
const dateInput1 = document.getElementById('date_start1');
const today = new Date();
dateInput.value = formatDate(today);
dateInput1.value = formatDate(today);

var blade = {
    datatablesAjaxCustomer: "{{ route('datatable_customers') }}",
    datatablesAjaxSupplier: "{{ route('datatable_suppliers') }}",
};

// Funkcija za formatiranje datuma u DD-MM-YYYY
function formatDate(date) {
    const day = ("0" + date.getDate()).slice(-2);
    const month = ("0" + (date.getMonth() + 1)).slice(-2);
    const year = date.getFullYear();
    return `${day}-${month}-${year}`;
}

// Strelica napred - povećava datum za jedan dan
document.getElementById('next-date').addEventListener('click', function() {
    const currentDate = new Date(dateInput.value.split('-').reverse().join('-'));
    currentDate.setDate(currentDate.getDate() + 1);
    dateInput.value = formatDate(currentDate);
    table.ajax.reload(); // Osvježi tablicu nakon promjene datuma
});

// Strelica nazad - smanjuje datum za jedan dan
document.getElementById('prev-date').addEventListener('click', function() {
    const currentDate = new Date(dateInput.value.split('-').reverse().join('-'));
    currentDate.setDate(currentDate.getDate() - 1);
    dateInput.value = formatDate(currentDate);
    table.ajax.reload(); // Osvježi tablicu nakon promjene datuma
});

// Strelica napred - povećava datum za jedan dan
document.getElementById('next-date1').addEventListener('click', function() {
    const currentDate = new Date(dateInput1.value.split('-').reverse().join('-'));
    currentDate.setDate(currentDate.getDate() + 1);
    dateInput1.value = formatDate(currentDate);
    table1.ajax.reload(); // Osvježi tablicu nakon promjene datuma
});

// Strelica nazad - smanjuje datum za jedan dan
document.getElementById('prev-date1').addEventListener('click', function() {
    const currentDate = new Date(dateInput1.value.split('-').reverse().join('-'));
    currentDate.setDate(currentDate.getDate() - 1);
    dateInput1.value = formatDate(currentDate);
    table1.ajax.reload(); // Osvježi tablicu nakon promjene datuma
});

// Customer Invoices DataTable
var table = $('#entity-list-table').DataTable({
    "autoWidth": true,
    "processing": true,
    "serverSide": true,
    "paging": false, // Onemogućava paginaciju
    "info": false, // Onemogućava informacije o prikazu
    "language": {
            emptyTable: "Keine Rechnung für heute gefunden."
        },
    "searching": false,
    "ajax": {
        url: blade.datatablesAjaxCustomer,
        type: "post",
        data: function(d) {
            d.date_start = $('#date_start').val(); // Pošalji trenutni datum
        }
    },
    "columns": [
        {"data": "id_invoice", orderable: false, "className": ""},
        {"data": "company", orderable: false, "className": ""},
        {"data": "price", orderable: false, "className": ""},
    ],
    "order": [[0, "asc"]],
});

// Supplier Invoices DataTable
var table1 = $('#entity-list-table1').DataTable({
    "autoWidth": true,
    "processing": true,
    "serverSide": true,
    "paging": false, // Onemogućava paginaciju
    "info": false, // Onemogućava informacije o prikazu
    "language": {
            emptyTable: "Keine Rechnung für heute gefunden."
        },
    "searching": false,
    "ajax": {
        url: blade.datatablesAjaxSupplier,
        type: "post",
        data: function(d) {
            d.date_start1 = $('#date_start1').val(); // Pošalji trenutni datum
        }
    },
    "columns": [
        {"data": "id_invoice", orderable: false, "className": ""},
        {"data": "company", orderable: false, "className": ""},
        {"data": "price", orderable: false, "className": ""},
    ],
    "order": [[0, "asc"]],
});
</script>
@endpush