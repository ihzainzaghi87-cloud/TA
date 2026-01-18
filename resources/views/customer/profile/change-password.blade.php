@extends('customer.layouts.app')

@section('title', 'Ubah Password')

@push('styles')
<style>
    .profile-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        transition: all 0.3s ease;
    }

    .profile-card:hover {
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
    }

    .menu-item {
        display: flex;
        align-items: center;
        padding: 16px 20px;
        border-radius: 12px;
        transition: all 0.2s ease;
        color: #374151;
    }

    .menu-item:hover {
        background: #fffbeb;
        color: #92400e;
    }

    .menu-item.active {
        background: #FAD470;
        color: #92400e;
        font-weight: 600;
    }

    .avatar-ring {
        background: linear-gradient(135deg, #FAD470 0%, #F8B500 100%);
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.2s ease;
        background: #f9fafb;
    }

    .form-input:focus {
        outline: none;
        border-color: #FAD470;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(250, 212, 112, 0.2);
    }

    .form-input.error {
        border-color: #ef4444;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }

    .form-error {
        color: #ef4444;
        font-size: 12px;
        margin-top: 4px;
    }

    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        cursor: pointer;
        transition: color 0.2s;
    }

    .password-toggle:hover {
        color: #374151;
    }

    .password-requirements {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
    }

    .requirement-item {
        display: flex;
        align-items: center;
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 8px;
    }

    .requirement-item:last-child {
        margin-bottom: 0;
    }

    .requirement-item i {
        margin-right: 8px;
        font-size: 12px;
    }

    .requirement-item.valid {
        color: #10b981;
    }

    .requirement-item.valid i {
        color: #10b981;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center mb-8 text-sm">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-600 transition-colors">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <a href="{{ route('customer.index') }}" class="text-gray-500 hover:text-gray-600 transition-colors">Profil</a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <span class="text-gray-900 font-medium">Ubah Password</span>
        </nav>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Menu -->
            <div class="lg:col-span-1">
                <div class="profile-card p-6">
                    <!-- User Avatar & Info -->
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 mx-auto avatar-ring rounded-full flex items-center justify-center text-white text-3xl font-bold mb-4">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ Auth::user()->name }}</h2>
                        <p class="text-gray-500 text-sm">{{ Auth::user()->email }}</p>
                    </div>

                    <!-- Menu Navigation -->
                    <nav class="space-y-2">
                        <a href="{{ route('customer.index') }}" class="menu-item">
                            <i class="fas fa-user w-5 mr-3"></i>
                            <span>Profil Saya</span>
                        </a>
                        <a href="{{ route('customer.points') }}" class="menu-item">
                            <i class="fas fa-coins w-5 mr-3"></i>
                            <span>Poin Saya</span>
                        </a>
                        <a href="{{ route('customer.orders') }}" class="menu-item">
                            <i class="fas fa-box w-5 mr-3"></i>
                            <span>Pesanan Saya</span>
                        </a>
                        <a href="{{ route('addresses.index') }}" class="menu-item">
                            <i class="fas fa-map-marker-alt w-5 mr-3"></i>
                            <span>Alamat</span>
                        </a>
                        <a href="{{ route('customer.change-password') }}" class="menu-item active">
                            <i class="fas fa-lock w-5 mr-3"></i>
                            <span>Ubah Password</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-4">
                            @csrf
                            <button type="submit" class="menu-item w-full text-red-600 hover:bg-red-50 hover:text-red-700">
                                <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="profile-card p-6">
                    <div class="flex items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-lock text-gray-600"></i>
                            </div>
                            Ubah Password
                        </h3>
                    </div>

                    <!-- Info Alert -->
                    <div class="mb-6 bg-gray-50 border border-gray-200 text-gray-800 px-4 py-3 rounded-xl flex items-start">
                        <i class="fas fa-info-circle mr-3 mt-0.5"></i>
                        <div class="text-sm">
                            <p class="font-semibold">Untuk keamanan akun Anda:</p>
                            <ul class="list-disc list-inside mt-1 text-gray-700">
                                <li>Gunakan kombinasi huruf besar, huruf kecil, dan angka</li>
                                <li>Jangan gunakan password yang sama dengan akun lain</li>
                                <li>Password minimal 8 karakter</li>
                            </ul>
                        </div>
                    </div>

                    <form action="{{ route('customer.update-password') }}" method="POST" id="changePasswordForm">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Current Password -->
                            <div>
                                <label for="current_password" class="form-label">
                                    <i class="fas fa-key text-gray-500 mr-2"></i>
                                    Password Lama <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           id="current_password" 
                                           name="current_password" 
                                           class="form-input @error('current_password') error @enderror"
                                           placeholder="Masukkan password lama"
                                           required>
                                    <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock text-gray-500 mr-2"></i>
                                    Password Baru <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           id="password" 
                                           name="password" 
                                           class="form-input @error('password') error @enderror"
                                           placeholder="Masukkan password baru"
                                           required
                                           minlength="8"
                                           oninput="checkPasswordStrength(this.value)">
                                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror

                                <!-- Password Requirements -->
                                <div class="password-requirements mt-3">
                                    <p class="text-xs font-semibold text-gray-600 mb-2">Persyaratan Password:</p>
                                    <div class="requirement-item" id="req-length">
                                        <i class="fas fa-circle"></i>
                                        <span>Minimal 8 karakter</span>
                                    </div>
                                    <div class="requirement-item" id="req-uppercase">
                                        <i class="fas fa-circle"></i>
                                        <span>Mengandung huruf besar</span>
                                    </div>
                                    <div class="requirement-item" id="req-lowercase">
                                        <i class="fas fa-circle"></i>
                                        <span>Mengandung huruf kecil</span>
                                    </div>
                                    <div class="requirement-item" id="req-number">
                                        <i class="fas fa-circle"></i>
                                        <span>Mengandung angka</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-check-circle text-gray-500 mr-2"></i>
                                    Konfirmasi Password Baru <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           class="form-input"
                                           placeholder="Masukkan ulang password baru"
                                           required
                                           minlength="8"
                                           oninput="checkPasswordMatch()">
                                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <p id="password-match-error" class="form-error hidden">Password tidak cocok</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 mt-8 pt-6 border-t border-gray-200">
                            <button type="submit" 
                                    class="flex-1 bg-gradient-to-r from-gray-500 to-gray-500 text-white px-6 py-3 rounded-xl font-bold text-sm hover:from-gray-600 hover:to-gray-600 transition-all duration-300 flex items-center justify-center gap-2">
                                <i class="fas fa-save"></i>
                                Ubah Password
                            </button>
                            <a href="{{ route('customer.index') }}" 
                               class="flex-1 bg-white border-2 border-gray-200 text-gray-700 px-6 py-3 rounded-xl font-bold text-sm hover:border-gray-300 transition-all duration-300 flex items-center justify-center gap-2">
                                <i class="fas fa-times"></i>
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.parentElement.querySelector('.password-toggle i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function checkPasswordStrength(password) {
        const requirements = {
            'req-length': password.length >= 8,
            'req-uppercase': /[A-Z]/.test(password),
            'req-lowercase': /[a-z]/.test(password),
            'req-number': /[0-9]/.test(password)
        };

        for (const [id, isValid] of Object.entries(requirements)) {
            const element = document.getElementById(id);
            const icon = element.querySelector('i');
            
            if (isValid) {
                element.classList.add('valid');
                icon.classList.remove('fa-circle');
                icon.classList.add('fa-check-circle');
            } else {
                element.classList.remove('valid');
                icon.classList.remove('fa-check-circle');
                icon.classList.add('fa-circle');
            }
        }

        checkPasswordMatch();
    }

    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        const errorElement = document.getElementById('password-match-error');
        const confirmInput = document.getElementById('password_confirmation');

        if (confirmPassword.length > 0) {
            if (password !== confirmPassword) {
                errorElement.classList.remove('hidden');
                confirmInput.classList.add('error');
            } else {
                errorElement.classList.add('hidden');
                confirmInput.classList.remove('error');
            }
        } else {
            errorElement.classList.add('hidden');
            confirmInput.classList.remove('error');
        }
    }

    // Form validation before submit
    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;

        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Password baru dan konfirmasi password tidak cocok');
            return false;
        }

        if (password.length < 8) {
            e.preventDefault();
            alert('Password minimal 8 karakter');
            return false;
        }
    });
</script>
@endpush
