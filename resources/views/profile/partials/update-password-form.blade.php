<form method="POST" action="{{ route('password.update') }}">
    @csrf
    @method('PUT')

    <div class="row mb-3 align-items-center">
        <label class="col-sm-3 col-form-label">Trenutna šifra</label>
        <div class="col-sm-9">
            <input type="password" name="current_password" class="form-control">
            @error('current_password', 'updatePassword')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="row mb-3 align-items-center">
        <label class="col-sm-3 col-form-label">Nova šifra</label>
        <div class="col-sm-9">
            <input type="password" name="password" class="form-control">
            @error('password', 'updatePassword')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="row mb-3 align-items-center">
        <label class="col-sm-3 col-form-label">Potvrdi šifru</label>
        <div class="col-sm-9">
            <input type="password" name="password_confirmation" class="form-control">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-10 offset-sm-2">
            <button style="float: right;" class="btn btn-primary">Sačuvaj</button>
            @if (session('status') === 'password-updated')
                <span class="text-success ms-2">Promijenjeno ✔</span>
            @endif
        </div>
    </div>
</form>