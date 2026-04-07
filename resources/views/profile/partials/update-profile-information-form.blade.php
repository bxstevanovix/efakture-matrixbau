<form method="POST" action="{{ route('profile.update') }}">
    @csrf
    @method('PATCH')

    <div class="row mb-3 align-items-center">
        <label class="col-sm-2 col-form-label">Ime</label>
        <div class="col-sm-10">
            <input type="text" name="name" class="form-control"
                   value="{{ old('name', $user->name) }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
    </div>

    <div class="row mb-3 align-items-center">
        <label class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-10">
            <input type="email" name="email" class="form-control"
                   value="{{ old('email', $user->email) }}" required>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-10 offset-sm-2">
            <button style="float: right;" class="btn btn-primary">Sačuvaj</button>
            @if (session('status') === 'profile-updated')
                <span class="text-success ms-2">Sačuvano ✔</span>
            @endif
        </div>
    </div>
</form>