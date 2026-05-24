@extends('_layouts.layout')

@section('head_title', __('Projekti'))

@push('head_links')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.css">
<style>
    .project-card-slider {
        padding-bottom: 42px;
    }

    .project-card-slider .swiper-slide {
        height: auto;
    }

    .project-card-slider .project-card {
        height: 100%;
    }

    .project-card-slider .swiper-pagination-bullet-active {
        background: var(--primary);
    }

    .project-list-progress {
        min-width: 150px;
    }

    .project-list-progress h6 {
        font-size: 13px;
        margin-bottom: 7px;
    }

    .project-list-header {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        align-items: center;
        gap: 16px;
    }

    .project-list-header .card-title {
        margin-bottom: 0;
    }

    .project-list-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .project-status-segment {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px;
        background: #f8f8fb;
        border: 1px solid #e8e8ef;
        border-radius: 8px;
    }

    .project-status-filter-btn {
        border: 0;
        border-radius: 6px;
        background: transparent;
        color: #6e6b7b;
        font-size: 12px;
        font-weight: 600;
        line-height: 1;
        padding: 8px 12px;
        transition: all .2s ease;
    }

    .project-status-filter-btn:hover {
        color: var(--primary);
        background: #ffffff;
    }

    .project-status-filter-btn.active {
        color: #ffffff;
        background: var(--primary);
        box-shadow: 0 4px 12px rgba(90, 69, 170, .18);
    }

    #projectlist th.project-status-column,
    #projectlist td.project-status-column {
        text-align: center;
    }

    .project-status-badge {
        width: 108px;
        min-width: 108px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        white-space: nowrap;
    }

    .project-list-toolbar {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 16px;
    }

    .project-row-actions {
        display: flex;
        justify-content: flex-end;
    }

    #projectlist th.project-actions-column,
    #projectlist td.project-actions-column {
        width: 46px !important;
        min-width: 46px;
        max-width: 46px;
        padding-left: 6px;
        padding-right: 6px;
        text-align: right;
        white-space: nowrap;
    }

    .project-action-toggle {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e8e8ef;
        color: #6e6b7b;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(18, 18, 23, .05);
    }

    .project-action-toggle:hover,
    .project-action-toggle:focus {
        color: var(--primary);
        border-color: rgba(90, 69, 170, .25);
        background: #f8f8fb;
    }

    .project-action-toggle svg {
        width: 5px;
        height: 18px;
    }

    .project-action-menu {
        min-width: 150px;
        padding: 8px;
        border: 1px solid #e8e8ef;
        border-radius: 8px;
        box-shadow: 0 14px 32px rgba(18, 18, 23, .12);
    }

    .project-action-menu .dropdown-item {
        display: flex;
        align-items: center;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        padding: 9px 10px;
    }

    .project-action-menu .dropdown-item:hover {
        background: #f8f8fb;
        color: var(--primary);
    }

    .project-action-menu .dropdown-item.text-danger:hover {
        background: #fff5f5;
        color: #dc3545 !important;
    }

    @media (max-width: 575.98px) {
        .project-list-header {
            grid-template-columns: 1fr;
            align-items: stretch;
        }

        .project-list-actions,
        .project-status-segment {
            width: 100%;
        }

        .project-status-filter-btn {
            flex: 1;
            padding-left: 8px;
            padding-right: 8px;
        }

        .project-list-toolbar .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
@php
    $progressBars = [
        'open' => 'bg-warning',
        'in_progress' => 'bg-info',
        'completed' => 'bg-success',
        'cancelled' => 'bg-danger',
    ];

    $statusLabels = [
        'Pending' => 'Na čekanju',
        'In Progress' => 'U toku',
        'Done' => 'Završeno',
        'Cancelled' => 'Otkazano',
    ];

    $logoImages = ['pic1.jpg', 'pic2.jpg', 'pic3.jpg'];
    $userImages = ['pic1.jpg', 'pic2.jpg', 'pic3.jpg', 'pic4.jpg', 'pic5.jpg', 'pic6.jpg', 'pic7.jpg'];
@endphp

@if($inProgressProjects->count() > 0)
    <div class="swiper projectCardSwiper project-card-slider mb-4">
        <div class="swiper-wrapper">
            @foreach($inProgressProjects as $project)
                @php
                    $statusLabel = $statusLabels[$project->timeline_status_label] ?? $project->timeline_status_label;
                    $badgeClass = $project->timeline_status_badge;
                    $progressClass = $progressBars[$project->timeline_status] ?? 'bg-primary';
                    $progress = $project->timeline_progress;
                    $logoImage = $logoImages[$loop->index % count($logoImages)];
                    $projectLead = $usersById[$project->created_by] ?? 'Administrator';
                    $assignee = $usersById[$project->updated_by] ?? $projectLead;
                @endphp
                <div class="swiper-slide">
                    <div class="card project-card">
                        <div class="card-body">
                            <div class="d-flex mb-4 align-items-start">
                                <div class="me-auto">
                                    <h5 class="title font-w600 mb-2">
                                        <a href="{{ route('projekti.show', $project) }}" class="text-black">{{ $project->project_name }}</a>
                                    </h5>
                                </div>
                                <span class="badge {{ $badgeClass }} d-sm-inline-block d-none">{{ __($statusLabel) }}</span>
                            </div>
                            <p class="mb-4">{{ $project->description ?: __('Opis projekta nije upisan.') }}</p>
                            <div class="row mb-4">
                                <div class="col-sm-6 mb-sm-0 mb-3 d-flex">
                                    <div class="dt-icon bgl-info me-3">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19 5H18V3C18 2.73478 17.8946 2.48043 17.7071 2.29289C17.5196 2.10536 17.2652 2 17 2C16.7348 2 16.4804 2.10536 16.2929 2.29289C16.1054 2.48043 16 2.73478 16 3V5H8V3C8 2.73478 7.89464 2.48043 7.70711 2.29289C7.51957 2.10536 7.26522 2 7 2C6.73478 2 6.48043 2.10536 6.29289 2.29289C6.10536 2.48043 6 2.73478 6 3V5H5C4.20435 5 3.44129 5.31607 2.87868 5.87868C2.31607 6.44129 2 7.20435 2 8V9H22V8C22 7.20435 21.6839 6.44129 21.1213 5.87868C20.5587 5.31607 19.7957 5 19 5Z" fill="#92caff"/>
                                            <path d="M2 19C2 19.7956 2.31607 20.5587 2.87868 21.1213C3.44129 21.6839 4.20435 22 5 22H19C19.7957 22 20.5587 21.6839 21.1213 21.1213C21.6839 20.5587 22 19.7956 22 19V11H2V19Z" fill="#51A6F5"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span>{{ __('Datum početka') }}</span>
                                        <p class="mb-0 pt-1 font-w500 text-black">{{ $project->start_date?->format('d M Y') ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-sm-6 d-flex">
                                    <div class="dt-icon me-3 bgl-danger">
                                        <svg width="19" height="24" viewBox="0 0 19 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M18.6601 8.6858C18.5437 8.44064 18.2965 8.28427 18.025 8.28427H10.9728L15.2413 1.06083C15.3697 0.843469 15.3718 0.573844 15.2466 0.354609C15.1214 0.135375 14.8884 -9.37014e-05 14.6359 4.86277e-08L8.61084 0.000656299C8.3608 0.000750049 8.12957 0.1335 8.00362 0.349453L0.0958048 13.905C-0.0310859 14.1224 -0.0319764 14.3911 0.0934142 14.6094C0.218805 14.8277 0.451352 14.9624 0.703117 14.9624H7.71037L5.66943 23.1263C5.58955 23.4457 5.74213 23.7779 6.03651 23.9255C6.13691 23.9757 6.24459 24 6.35123 24C6.55729 24 6.75923 23.9094 6.89638 23.7413L18.5699 9.43186C18.7415 9.22148 18.7766 8.93105 18.6601 8.6858V8.6858Z" fill="#FF4C41"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span>{{ __('Rok') }}</span>
                                        <p class="mb-0 pt-1 font-w500 text-black">{{ $project->end_date?->format('d M Y') ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div>{{ __('ID projekta') }}:<span class="text-black ms-3 font-w600">{{ $project->id }}</span></div>
                                </div>
                                <div class="col-6">
                                    <h6>{{ __('Progres') }}
                                        <span class="pull-right">{{ $progress }}%</span>
                                    </h6>
                                    <div class="progress">
                                        <div class="progress-bar {{ $progressClass }} progress-animated" style="width: {{ $progress }}%; height:8px;" role="progressbar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="swiper-pagination"></div>
    </div>
@else
    <div class="alert alert-info" role="alert">
        <i class="fas fa-info-circle"></i> {{ __('Nema projekata u statusu U toku.') }}
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header project-list-header">
                <h4 class="card-title">{{ __('Najnovije kreirani projekti') }}</h4>
                <div class="project-list-actions">
                    <div class="project-status-segment" role="group" aria-label="{{ __('Filter projekata po statusu') }}">
                        <button type="button" class="project-status-filter-btn active" data-status="">{{ __('Svi') }}</button>
                        <button type="button" class="project-status-filter-btn" data-status="{{ __('Na čekanju') }}">{{ __('Na čekanju') }}</button>
                        <button type="button" class="project-status-filter-btn" data-status="{{ __('U toku') }}">{{ __('U toku') }}</button>
                        <button type="button" class="project-status-filter-btn" data-status="{{ __('Završeno') }}">{{ __('Završeno') }}</button>
                        <button type="button" class="project-status-filter-btn" data-status="{{ __('Otkazano') }}">{{ __('Otkazano') }}</button>
                    </div>
                </div>
                <div class="project-list-toolbar">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newProjectModal">
                        {{ __('Dodaj projekat') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="projectlist" class="display">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
	                                <th>{{ __('Projekat') }}</th>
                                <th>{{ __('Datum početka') }}</th>
                                <th>{{ __('Datum završetka') }}</th>
                                <th>{{ __('Progres') }}</th>
                                <th class="project-status-column">{{ __('Status') }}</th>
                                <th class="project-actions-column" data-orderable="false" data-searchable="false"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allProjects as $project)
                                @php
                                    $statusLabel = $statusLabels[$project->timeline_status_label] ?? $project->timeline_status_label;
                                    $badgeClass = $project->timeline_status_badge;
                                    $progressClass = $progressBars[$project->timeline_status] ?? 'bg-primary';
                                    $progress = $project->timeline_progress;
	                                @endphp
                                <tr>
                                    <td data-order="{{ $project->created_at?->format('YmdHis') ?? '00000000000000' }}{{ str_pad((string) $project->id, 10, '0', STR_PAD_LEFT) }}">{{ $project->id }}</td>
	                                    <td>
	                                        <div>
	                                            <h6>
	                                                <a href="{{ route('projekti.show', $project) }}" class="text-black">{{ $project->project_name }}</a>
	                                            </h6>
		                                            <span>{{ $project->address ?: '-' }}</span>
	                                        </div>
	                                    </td>
                                    <td>{{ $project->start_date?->format('d M Y') ?? '-' }}</td>
                                    <td>{{ $project->end_date?->format('d M Y') ?? '-' }}</td>
                                    <td data-order="{{ $progress }}">
                                        <div class="project-list-progress">
                                            <h6>{{ __('Progres') }}
                                                <span class="pull-right">{{ $progress }}%</span>
                                            </h6>
                                            <div class="progress">
                                                <div class="progress-bar {{ $progressClass }} progress-animated" style="width: {{ $progress }}%; height:8px;" role="progressbar"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="project-status-column" data-order="{{ __($statusLabel) }}" data-search="{{ __($statusLabel) }}">
                                        <span class="badge {{ $badgeClass }} light project-status-badge">{{ __($statusLabel) }}</span>
                                    </td>
                                    <td class="project-actions-column">
                                        <div class="dropdown project-row-actions">
                                            <button type="button" class="btn btn-light btn-xs sharp project-action-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{{ __('Akcije za projekat') }}">
                                                <svg viewBox="0 0 5 18" role="presentation" aria-hidden="true">
                                                    <g class="new-more" fill="none">
                                                        <circle cx="2.5" cy="3" r="2" stroke="currentColor"></circle>
                                                        <circle cx="2.5" cy="9" r="2" stroke="currentColor"></circle>
                                                        <circle cx="2.5" cy="15" r="2" stroke="currentColor"></circle>
                                                    </g>
                                                </svg>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end project-action-menu">
                                                <button type="button"
                                                    class="dropdown-item edit-project"
                                                    data-id="{{ $project->id }}"
                                                    data-name="{{ $project->project_name }}"
                                                    data-description="{{ $project->description }}"
                                                    data-address="{{ $project->address }}"
                                                    data-start-date="{{ $project->start_date?->format('Y-m-d') }}"
                                                    data-end-date="{{ $project->end_date?->format('Y-m-d') }}"
                                                    data-planned-budget="{{ $project->planned_budget !== null ? number_format((float) $project->planned_budget, 2, '.', '') : '' }}">
                                                    <i class="fa fa-pencil me-2"></i> {{ __('Uredi') }}
                                                </button>
                                                <button type="button"
                                                    class="dropdown-item text-danger delete-project"
                                                    data-id="{{ $project->id }}"
                                                    data-name="{{ $project->project_name }}">
                                                    <i class="fa fa-trash me-2"></i> {{ __('Izbriši') }}
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('modals')
<!-- MODAL: NOVI PROJEKT -->
<div class="modal fade" id="newProjectModal" tabindex="-1" aria-labelledby="newProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newProjectModalLabel">{{ __('Novi projekat') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Zatvori') }}"></button>
            </div>
            <form id="projectForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="projectName" class="form-label">{{ __('Naziv projekta') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="projectName" name="project_name" required>
                    </div>

	                    <div class="mb-3">
	                        <label for="projectDescription" class="form-label">{{ __('Opis') }}</label>
	                        <textarea class="form-control" id="projectDescription" name="description" rows="3"></textarea>
	                    </div>

		                    <div class="row">
	                        <div class="col-md-6">
	                            <div class="mb-3">
	                                <label for="startDate" class="form-label">{{ __('Početak') }} <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="startDate" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="endDate" class="form-label">{{ __('Završetak') }}</label>
                                <input type="date" class="form-control" id="endDate" name="end_date">
	                            </div>
		                        </div>
		                    </div>

	                    <div class="row">
	                        <div class="col-md-8">
	                            <div class="mb-3">
	                                <label for="projectAddress" class="form-label">{{ __('Adresa') }}</label>
	                                <input type="text" class="form-control" id="projectAddress" name="address">
	                            </div>
	                        </div>
	                        <div class="col-md-4">
	                            <div class="mb-3">
	                                <label for="plannedBudget" class="form-label">{{ __('Planirani budžet') }}</label>
	                                <div class="input-group">
	                                    <input type="number" class="form-control" id="plannedBudget" name="planned_budget" min="0" step="0.01" placeholder="0.00">
	                                    <span class="input-group-text">€</span>
	                                </div>
	                            </div>
	                        </div>
	                    </div>

	                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Otkaži') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Kreiraj projekat') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL: EDIT PROJEKT -->
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProjectModalLabel">{{ __('Uredi projekat') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Zatvori') }}"></button>
            </div>
            <form id="editProjectForm">
                <input type="hidden" id="editProjectId" name="project_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editProjectName" class="form-label">{{ __('Naziv projekta') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editProjectName" name="project_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="editProjectDescription" class="form-label">{{ __('Opis') }}</label>
                        <textarea class="form-control" id="editProjectDescription" name="description" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="editProjectAddress" class="form-label">{{ __('Adresa') }}</label>
                        <input type="text" class="form-control" id="editProjectAddress" name="address">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editStartDate" class="form-label">{{ __('Početak') }} <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="editStartDate" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editEndDate" class="form-label">{{ __('Završetak') }}</label>
                                <input type="date" class="form-control" id="editEndDate" name="end_date">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editPlannedBudget" class="form-label">{{ __('Planirani budžet') }}</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="editPlannedBudget" name="planned_budget" min="0" step="0.01" placeholder="0.00">
                            <span class="input-group-text">€</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Otkaži') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Sačuvaj izmjene') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@push('footer_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.js"></script>
<script>
    if (document.querySelector('.projectCardSwiper')) {
        new Swiper('.projectCardSwiper', {
            slidesPerView: 1,
            spaceBetween: 24,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1200: {
                    slidesPerView: 3,
                },
                1600: {
                    slidesPerView: 3,
                },
            },
        });
    }

    $(function() {
        const projectStatusColumn = 5;
        const $projectStatusButtons = $('.project-status-filter-btn');
        const projectUpdateUrlTemplate = "{{ route('projekti.update', '__PROJECT_ID__') }}";
        const projectDeleteUrlTemplate = "{{ route('projekti.delete', '__PROJECT_ID__') }}";
        const projectTexts = {
            success: @json(__('Uspešno')),
            error: @json(__('Greška')),
            saving: @json(__('Čuvanje...')),
            saveError: @json(__('Greška pri čuvanju!')),
            deleteError: @json(__('Greška pri brisanju!')),
            deleteTitle: @json(__('Da li želite da izbrišete projekat?')),
            deleteFallbackName: @json(__('projekat')),
            deleteConfirm: @json(__('Obriši')),
            confirmDefault: @json(__('Potvrdi')),
            cancel: @json(__('Odustani')),
        };

        if ($.fn.DataTable.isDataTable('#projectlist')) {
            $('#projectlist').DataTable().order([[0, 'desc']]).draw();
        }

        function projectUrl(template, id) {
            return template.replace('__PROJECT_ID__', encodeURIComponent(id));
        }

        $projectStatusButtons.on('click', function() {
            if (!$.fn.DataTable.isDataTable('#projectlist')) {
                return;
            }

            const $button = $(this);
            const selectedStatus = $button.data('status') || '';
            const statusSearch = selectedStatus ? `^${$.fn.dataTable.util.escapeRegex(selectedStatus)}$` : '';

            $projectStatusButtons.removeClass('active');
            $button.addClass('active');

            $('#projectlist').DataTable()
                .column(projectStatusColumn)
                .search(statusSearch, true, false)
                .draw();
        });

        $('#projectForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('projekti.store') }}",
                type: "POST",
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#newProjectModal').modal('hide');
                        $('#projectForm')[0].reset();
                        showToast(projectTexts.success, response.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    }
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON?.errors || {};
                    const message = Object.values(errors).flat().join(', ') || projectTexts.saveError;
                    showToast(projectTexts.error, message, 'error');
                }
            });
        });

        $(document).on('click', '.edit-project', function() {
            const $button = $(this);

            $('#editProjectId').val($button.data('id'));
            $('#editProjectName').val($button.attr('data-name') || '');
            $('#editProjectDescription').val($button.attr('data-description') || '');
            $('#editProjectAddress').val($button.attr('data-address') || '');
            $('#editStartDate').val($button.attr('data-start-date') || '');
            $('#editEndDate').val($button.attr('data-end-date') || '');
            $('#editPlannedBudget').val($button.attr('data-planned-budget') || '');
            $('#editProjectModal').modal('show');
        });

        $('#editProjectForm').on('submit', function(e) {
            e.preventDefault();

            const projectId = $('#editProjectId').val();
            const $submitButton = $(this).find('button[type="submit"]');
            const originalText = $submitButton.text();

            $submitButton.prop('disabled', true).text(projectTexts.saving);

            $.ajax({
                url: projectUrl(projectUpdateUrlTemplate, projectId),
                type: "POST",
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#editProjectModal').modal('hide');
                        showToast(projectTexts.success, response.message, 'success');
                        setTimeout(() => location.reload(), 800);
                    }
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON?.errors || {};
                    const message = Object.values(errors).flat().join(', ') || xhr.responseJSON?.message || projectTexts.saveError;
                    showToast(projectTexts.error, message, 'error');
                },
                complete: function() {
                    $submitButton.prop('disabled', false).text(originalText);
                }
            });
        });

        $(document).on('click', '.delete-project', function() {
            const projectId = $(this).data('id');
            const projectName = $(this).attr('data-name') || projectTexts.deleteFallbackName;

            confirmProjectAction({
                title: projectTexts.deleteTitle,
                text: projectName,
                confirmButtonText: projectTexts.deleteConfirm,
                confirmButtonColor: '#dc3545',
                onConfirm: function() {
                    $.ajax({
                        url: projectUrl(projectDeleteUrlTemplate, projectId),
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                showToast(projectTexts.success, response.message, 'success');
                                setTimeout(() => location.reload(), 800);
                            }
                        },
                        error: function(xhr) {
                            const message = xhr.responseJSON?.message || projectTexts.deleteError;
                            showToast(projectTexts.error, message, 'error');
                        }
                    });
                }
            });
        });

        function confirmProjectAction(options) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: options.title,
                    text: options.text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: options.confirmButtonText || projectTexts.confirmDefault,
                    cancelButtonText: projectTexts.cancel,
                    confirmButtonColor: options.confirmButtonColor || '#dc3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        options.onConfirm();
                    }
                });
                return;
            }

            if (window.confirm(`${options.title}\n${options.text}`)) {
                options.onConfirm();
            }
        }

        function showToast(title, message, type) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const toast = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <strong>${title}:</strong> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            $('body').prepend(toast);
            setTimeout(() => $('.alert').fadeOut(() => $('.alert').remove()), 3000);
        }
    });
</script>
@endpush
