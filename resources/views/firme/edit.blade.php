@extends('_layouts.layout')

@section('head_title', __('Editovanje Firme'))

@push('head_links')

@endpush

@section('content')

<div class="row page-titles mx-0">
	<ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('firme.index')}}">@lang('Firme')</a></li>
		<li class="breadcrumb-item active">@lang('Editovanje Firme')</li>
	</ol>
</div>

<style>
    #file-table td {
    vertical-align: middle;
}

.file-row {
    cursor: pointer;
}

.file-row:hover {
    background-color: #f5f7fb;
}

.file-icon {
    font-size: 18px;
}

.breadcrumb-item {
    cursor: pointer;
}

.file-toolbar {
    border-bottom: 1px solid #eef1f7;
    padding-bottom: 10px;
}

.file-breadcrumb {
    font-size: 14px;
}

.file-breadcrumb span {
    color: #6c757d;
    cursor: pointer;
    transition: 0.2s;
}

.file-breadcrumb span:hover {
    color: #0d6efd;
}

.file-breadcrumb .active {
    font-weight: 600;
    color: #212529;
    cursor: default;
}

.file-breadcrumb .separator {
    margin: 0 6px;
    color: #adb5bd;
}

/* Ograničava visinu col-5 i scroll za fajlove */
/* Kartica maksimalna visina */
.col-5 .card {
    max-height: 688px; /* visina cijele kartice */
    display: flex;
    flex-direction: column;
}

/* Card body zauzima cijelu karticu */
.col-5 .card-body {
    display: flex;
    flex-direction: column;
    flex: 1 1 auto;
    overflow: hidden; /* sakrij sve izvan */
}

/* Toolbar ostaje fiksno */
.file-toolbar {
    flex: 0 0 auto; /* ne raste, ne skrola */
    margin-bottom: 10px;
}

/* Tabela scrolla */
.table-responsive {
    flex: 1 1 auto; /* zauzima preostali prostor */
    overflow-y: auto; /* scroll unutar tabele/fajlova */
}

/* Fiksni header tabele */
.table-responsive table thead {
    position: sticky;
    top: 0;
    background-color: #fff; /* ili boja kartice */
    z-index: 10;
}
</style>

<div class="row">
    <div class="col-7">
        <div class="card h-auto">
            <div class="card-header">
				<h4 class="card-title">@lang('Editovanje Nove Firme')</h4>
                
                <a href="{{route('firme.index')}}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i>
                    @lang('Nazad')
                </a>
			</div>
            <div class="card-body">
                <div class="card-body">
                    <div class="form-validation">
                        @include('firme.partials.form', ['entity' => $entity])
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-5">
        <div class="card h-auto">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 file-toolbar">
                    <div id="breadcrumb" class="file-breadcrumb"></div>
                    <button id="btn-back" class="btn btn-sm btn-outline-secondary d-none">
                        <i class="fa fa-arrow-left"></i> @lang('Nazad')
                    </button>
                </div>
                <!-- Tabela -->
                <div class="table-responsive">
                    <table id="file-table" class="table table-hover">
                        <thead>
                            <tr>
                                <th>@lang('Naziv')</th>
                                <th>@lang('Tip')</th>
                                <th>@lang('Datum')</th>
                                <th class="text-end">@lang('Akcija')</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@push('footer_scripts')
<script>
let companyId = {{ $entity->id }};
let currentPath = ''; // ROOT

function loadFiles(path = '') {

    currentPath = path;

    $.get(`/file-list/${companyId}`, { path: path }, function(data) {

        let rows = '';

        // ✅ BACK dugme (NE MOŽE IZAĆI IZ FIRME)
        if (path) {

            let parts = path.split('/');

            // dozvoli back samo ako si dublje od firme
            if (parts.length > 2) {

                $('#btn-back').removeClass('d-none');

                let parent = parts.slice(0, -1).join('/');

                $('#btn-back').off().on('click', function () {
                    loadFiles(parent);
                });

            } else {
                // 🔒 na nivou firme → nema nazad
                $('#btn-back').addClass('d-none');
            }

        } else {
            $('#btn-back').addClass('d-none');
        }

        // FILES
        data.forEach(item => {

            rows += `
            <tr class="file-row">
                <td onclick="${item.is_dir ? `loadFiles('${item.path}')` : ''}" style="cursor:pointer;">
                    <div class="d-flex align-items-center">
                        ${
                            item.is_dir
                            ? '<i class="fa fa-folder text-warning"></i>'
                            : '<i class="fa fa-file text-primary"></i>'
                        }
                        <span class="ms-2">${item.name}</span>
                    </div>
                </td>

                <td>${item.type}</td>
                <td>${item.date}</td>

                <td class="text-end">
                    ${
                        item.is_dir
                        ? `<button class="btn btn-xs btn-info" onclick="loadFiles('${item.path}')">Otvori</button>`
                        : `<a href="/storage/${item.path}" target="_blank" class="btn btn-sm btn-light" title="Preview">
                                <i class="fa fa-eye"></i>
                           </a>`
                    }
                </td>
            </tr>`;
        });

        $('#file-table tbody').html(rows);

        renderBreadcrumb(path);
    });
}


// ✅ BREADCRUMB
function renderBreadcrumb(path) {

    // ROOT
    if (!path) {
        $('#breadcrumb').html(`
            <span class="active">
                <i class="fa fa-home me-1"></i> Fakture
            </span>
        `);
        return;
    }

    let parts = path.split('/');
    let html = '';
    let current = '';

    parts.forEach((part, index) => {

        current += (index === 0 ? part : '/' + part);

        let isLast = index === parts.length - 1;

        let icon = !isLast 
            ? '<i class="fa fa-folder text-warning me-1"></i>' 
            : '<i class="fa fa-folder-open text-warning me-1"></i>';

        // ❗ prvi dio (izlazne / ulazne) NIJE klikabilan
        if (index === 0) {
            html += `
                <span class="active">
                    ${icon} ${formatBreadcrumbName(part)}
                </span>
            `;
        } else {
            html += `
                <span 
                    class="${isLast ? 'active' : ''}" 
                    ${!isLast ? `onclick="loadFiles('${current}')"` : ''}
                >
                    ${icon} ${formatBreadcrumbName(part)}
                </span>
            `;
        }

        if (!isLast) {
            html += `<span class="separator"> / </span>`;
        }
    });

    $('#breadcrumb').html(html);
}


// ✅ FORMAT NAZIVA
function formatBreadcrumbName(name) {

    if (name === 'izlazne-fakture') return 'Izlazne Fakture';
    if (name === 'ulazne-fakture') return 'Ulazne Fakture';

    return name.replaceAll('-', ' ');
}


// INIT
$(function(){
    loadFiles(); // ROOT → Izlazne + Ulazne
});
</script>
@endpush