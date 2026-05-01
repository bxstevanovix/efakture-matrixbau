@php
    if($entity->date_start){
        $dateStart = date('d-m-Y', strtotime($entity->date_start));
        $dateEnd = date('d-m-Y', strtotime($entity->date_end));
    }else{
        $dateStart = '';
        $dateEnd = '';
    }
@endphp
<style>
    .mb-3 {
        position: relative;
    }

    .autocomplete-box {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;

        background: #fff;
        border: 1px solid #e6e6e6;
        border-top: none;

        border-radius: 0 0 10px 10px;

        max-height: 260px;
        overflow-y: auto;

        z-index: 9999;

        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        margin-left: 15px;
        margin-right: 15px;
    }

    .autocomplete-item {
        padding: 10px 14px;
        cursor: pointer;
        font-size: 14px;
        color: #333;

        transition: all 0.15s ease;
        border-bottom: 1px solid #f2f2f2;
    }

    /* HOVER */
    .autocomplete-item:hover {
        background: #f2f6ff;
        color: #0d6efd;
    }

    /* LAST ITEM BORDER REMOVE */
    .autocomplete-item:last-child {
        border-bottom: none;
    }

    /* SCROLL BAR (Chrome / Edge / Safari) */
    .autocomplete-box::-webkit-scrollbar {
        width: 6px;
    }

    .autocomplete-box::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }

    .autocomplete-box::-webkit-scrollbar-thumb:hover {
        background: #999;
    }

    .autocomplete-item strong {
        color: #0d6efd;
    }

    .autocomplete-item.active {
        background: #e9f2ff;
        color: #0d6efd;
    }
</style>

<form id="entity-form" method="post" action="" enctype="multipart/form-data" autocomplete="off" class="needs-validation" novalidate>
    @csrf
        <div class="form-validation">
            <div class="row">
                <div class="mb-3 col-md-7">
                    <div class="row">
                        <!-- Company Selection -->
                        <div class="mb-3 col-md-6">
                            <label class="form-label">@lang('Firma')</label>
                            <select id="companySelect" name="company" class="form-control @errorClass('company', 'is-invalid')">
                                <option></option>
                                @foreach($companies as $company)
                                    <option value="{{$company->id}}" data-currency="{{$company->currency}}" @if(old('company', $entity->company) == $company->id) selected @endif>
                                        {{$company->name}}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                @lang('Izaberite firmu.')
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">@lang('Adresa')</label>
                            <input 
                                id="address"
                                type="text" 
                                class="form-control @errorClass('address', 'is-invalid')" 
                                name="address" 
                                placeholder="@lang('Adresa')" 
                                value="{{old('address', $entity->address)}}" 
                                required
                            >
                            <div id="address_box" class="autocomplete-box"></div>
                            <div class="invalid-feedback">
                                @lang('Unesite adresu posla.')
                            </div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">@lang('Datum Pocetka')</label>
                            <input 
                                type="text" 
                                class="form-control @errorClass('date_start', 'is-invalid')" 
                                name="date_start" 
                                id="date_start" 
                                placeholder="@lang('Datum')" 
                                value="{{old('date_start', $dateStart)}}" 
                                style="background-color: #fff; text-align: center"
                            >
                            <div class="invalid-feedback">
                                @lang('Unesite datum pocetka.')
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">@lang('Datum Završetka')</label>
                            <input 
                                type="text" 
                                class="form-control @errorClass('date_end', 'is-invalid')" 
                                name="date_end" 
                                id="date_end" 
                                placeholder="@lang('Datum')" 
                                value="{{old('date_end', $dateEnd)}}" 
                                style="background-color: #fff; text-align: center"
                            >
                            <div class="invalid-feedback">
                                @lang('Unesite datum završetka.')
                            </div>
                        </div>

                        <div class="row" style="margin-top: 35px;">
                            <div class="mb-3 col-md-6">
                                <div class="d-flex align-items-center">
                                    <label class="form-label me-2 mb-0" style="white-space: nowrap;">@lang('Broj Fakture')</label>
                                    <div class="flex-grow-1">
                                        <div class="input-group">
                                            <input 
                                                type="text" 
                                                class="form-control @errorClass('id_invoice', 'is-invalid')" 
                                                name="id_invoice" 
                                                placeholder="023/2026" 
                                                value="{{old('id_invoice', $entity->id_invoice)}}"
                                                maxlength="30"
                                                required
                                            >
                                            <div class="invalid-feedback">
                                                @lang('Unesite broj fakture.')
                                            </div>
                                        </div>
                                    </div>        
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <div class="d-flex align-items-center">
                                    <label class="form-label me-2 mb-0" style="white-space: nowrap;">@lang('Iznos Fakture'):</label>
                                    <div class="flex-grow-1">
                                        <div class="input-group">
                                            <input 
                                                type="number" 
                                                class="form-control @errorClass('price', 'is-invalid')" 
                                                name="price" 
                                                placeholder="0.00" 
                                                value="{{ old('price', $entity->price) }}" 
                                                min="1" 
                                                max="99999999999999999999" 
                                                required
                                            >
                                            <span class="input-group-text">EUR</span>
                                            @error('price')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <div class="invalid-feedback">
                                                @lang('Unesite iznos fakture.')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3 col-md-5">
                    <div class="mb-3 col-md-12">
                        <label class="form-label">@lang('Napomene')</label>
                        <textarea 
                            class="form-control @errorClass('text', 'is-invalid')" 
                            name="text" 
                            rows="11" 
                            placeholder="@lang('Unesite napomene vezane za fakturu')"
                        >{{ old('text', $entity->text) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    <button style="float: right;" type="submit" class="btn btn-success waves-effect waves-light">
        <i class="fa fa-save"></i> @lang('Sačuvaj')
    </button>
</form>

@push('footer_scripts')

<script>
$(function() {
    $('#companySelect').select2({
        placeholder: "Firma auswählen",
        allowClear: true,
        width: '100%'
    });
        
    $('#date_start').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });

    $('#date_end').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });

    $('#entity-form [name="date_start"]').on('change', function(e){
        e.preventDefault();
        let start = $('#entity-form [name="date_start"]').val();
        let split = start.split('-');
        let end10 = parseInt(split[2]) + 10;

        $('#entity-form [name="date_end"]').val(split[0] + '-' + split[1] + '-' + end10);

    });

        function setupAddressAutocomplete(inputId, boxId, url) {
            const input = document.getElementById(inputId);
            const box = document.getElementById(boxId);

            let timeout;
            let items = [];
            let activeIndex = -1;

            function renderActive() {
                const all = box.querySelectorAll(".autocomplete-item");

                all.forEach((el, i) => {
                    el.classList.remove("active");
                    if (i === activeIndex) {
                        el.classList.add("active");
                    }
                });
            }

            input.addEventListener("input", function () {
                let query = this.value;

                clearTimeout(timeout);
                activeIndex = -1;

                if (query.length < 2) {
                    box.innerHTML = "";
                    return;
                }

                timeout = setTimeout(() => {
                    fetch(url + "?q=" + encodeURIComponent(query))
                        .then(res => res.json())
                        .then(data => {
                            box.innerHTML = "";
                            items = data;

                            data.forEach((item, index) => {
                                let div = document.createElement("div");
                                div.classList.add("autocomplete-item");
                                div.innerText = item;

                                div.addEventListener("click", () => {
                                    input.value = item;
                                    box.innerHTML = "";
                                });

                                box.appendChild(div);
                            });
                        });
                }, 300);
            });

            // ===== KEYBOARD NAVIGATION =====
            input.addEventListener("keydown", function (e) {
                const list = box.querySelectorAll(".autocomplete-item");

                if (!list.length) return;

                // ↓
                if (e.key === "ArrowDown") {
                    e.preventDefault();
                    activeIndex++;
                    if (activeIndex >= list.length) activeIndex = 0;
                    renderActive();
                }

                // ↑
                else if (e.key === "ArrowUp") {
                    e.preventDefault();
                    activeIndex--;
                    if (activeIndex < 0) activeIndex = list.length - 1;
                    renderActive();
                }

                // ENTER
                else if (e.key === "Enter") {
                    if (activeIndex > -1) {
                        e.preventDefault();
                        list[activeIndex].click();
                    }
                }
            });

            // click outside
            document.addEventListener("click", function (e) {
                if (!box.contains(e.target) && e.target !== input) {
                    box.innerHTML = "";
                }
            });
        }

        setupAddressAutocomplete(
            "address",
            "address_box",
            "/customer-invoices/autocomplete/address"
        );
    });
</script>
@endpush
