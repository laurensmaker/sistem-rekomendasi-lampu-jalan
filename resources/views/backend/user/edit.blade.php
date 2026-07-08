@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Edit Data User</h3>
    <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
        <i data-feather="arrow-left"></i> Kembali
    </a>
</div>

<div class="card bg-white border-0 rounded-10 mb-4">
    <div class="card-body p-4">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $user->name) }}"
                           placeholder="Masukkan nama lengkap" 
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $user->email) }}"
                           placeholder="Masukkan alamat email" 
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Kosongkan jika tidak ingin mengubah">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i data-feather="eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    <small class="text-muted">Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password.</small>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" 
                           name="password_confirmation" 
                           id="password_confirmation" 
                           class="form-control" 
                           placeholder="Ulangi password jika mengubah">
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                    <select name="role" 
                            id="role" 
                            class="form-select @error('role') is-invalid @enderror" 
                            required>
                        <option value="">Pilih Role</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                        <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6 mb-3">
                    <label class="form-label">Status</label>
                    <div class="form-control bg-light">
                        <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'manager' ? 'warning' : 'info') }}">
                            {{ ucfirst($user->role) }}
                        </span>
                        <small class="text-muted ms-2">Role saat ini: {{ ucfirst($user->role) }}</small>
                    </div>
                </div>

                <div class="col-lg-12 mb-3">
                    <div class="alert alert-info">
                        <i data-feather="info" class="me-1"></i>
                        <strong>Informasi:</strong> 
                        <ul class="mb-0 mt-1">
                            <li>Password hanya diubah jika diisi</li>
                            <li>Role dapat diubah kapan saja</li>
                            <li>Email harus unik dan belum digunakan oleh user lain</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-12 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="save"></i> Update User
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i data-feather="x"></i> Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.setAttribute('data-feather', 'eye-off');
        } else {
            passwordInput.type = 'password';
            eyeIcon.setAttribute('data-feather', 'eye');
        }
        feather.replace();
    });

    // Optional: Show password strength indicator (simple)
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strengthIndicator = document.getElementById('passwordStrength');
        
        if (password.length > 0 && password.length < 8) {
            // You can add a strength indicator element if needed
            console.log('Password too short');
        }
    });
</script>
@endpush