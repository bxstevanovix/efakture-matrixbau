@php
    if($entity->date_start){
        $dateStart = date('d-m-Y', strtotime($entity->date_start));
        $dateEnd = date('d-m-Y', strtotime($entity->date_end));
    }else{
        $dateStart = '';
        $dateEnd = '';
    }
@endphp

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
                                type="text" 
                                class="form-control @errorClass('address', 'is-invalid')" 
                                name="address" 
                                placeholder="@lang('Adresa')" 
                                value="{{old('address', $entity->address)}}" 
                                required
                            >
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
                                                placeholder="@lang('Broj Fakture')" 
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
                                                placeholder="@lang('Iznos Fakture')" 
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
                            >
                            {{old('text', $entity->text)}}
                        </textarea>
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
});
</script>
@endpush
