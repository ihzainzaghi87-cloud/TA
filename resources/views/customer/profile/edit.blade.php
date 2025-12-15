@extends('customer.layouts.app')

@section('title', 'Edit Profil')

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
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center mb-8 text-sm">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-amber-600 transition-colors">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <a href="{{ route('customer.index') }}" class="text-gray-500 hover:text-amber-600 transition-colors">Profil</a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <span class="text-gray-900 font-medium">Edit Profil</span>
        </nav>

        <!-- Flash Messages -->
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
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                    </div>

                    <!-- Menu Navigation -->
                    <nav class="space-y-2">
                        <a href="{{ route('customer.index') }}" class="menu-item active">
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
                        <a href="{{ route('customer.change-password') }}" class="menu-item">
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
                            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-edit text-amber-600"></i>
                            </div>
                            Edit Profil
                        </h3>
                    </div>

                    <form action="{{ route('customer.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="form-label">
                                    <i class="fas fa-user text-amber-500 mr-2"></i>
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}"
                                       class="form-input @error('name') error @enderror"
                                       placeholder="Masukkan nama lengkap"
                                       required>
                                @error('name')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope text-amber-500 mr-2"></i>
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}"
                                       class="form-input @error('email') error @enderror"
                                       placeholder="Masukkan email"
                                       required>
                                @error('email')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone text-amber-500 mr-2"></i>
                                    Nomor Telepon
                                </label>
                                <input type="tel" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $user->phone) }}"
                                       class="form-input @error('phone') error @enderror"
                                       placeholder="Contoh: 081234567890">
                                @error('phone')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label for="date_of_birth" class="form-label">
                                    <i class="fas fa-birthday-cake text-amber-500 mr-2"></i>
                                    Tanggal Lahir
                                </label>
                                <input type="date" 
                                       id="date_of_birth" 
                                       name="date_of_birth" 
                                       value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}"
                                       class="form-input @error('date_of_birth') error @enderror"
                                       max="{{ date('Y-m-d') }}">
                                @error('date_of_birth')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div class="md:col-span-2">
                                <label class="form-label">
                                    <i class="fas fa-venus-mars text-amber-500 mr-2"></i>
                                    Jenis Kelamin
                                </label>
                                <div class="flex flex-wrap gap-4 mt-2">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" 
                                               name="gender" 
                                               value="male" 
                                               {{ old('gender', $user->gender) == 'male' ? 'checked' : '' }}
                                               class="w-4 h-4 text-amber-500 border-gray-300 focus:ring-amber-500">
                                        <span class="ml-2 text-gray-700">Laki-laki</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" 
                                               name="gender" 
                                               value="female" 
                                               {{ old('gender', $user->gender) == 'female' ? 'checked' : '' }}
                                               class="w-4 h-4 text-amber-500 border-gray-300 focus:ring-amber-500">
                                        <span class="ml-2 text-gray-700">Perempuan</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" 
                                               name="gender" 
                                               value="other" 
                                               {{ old('gender', $user->gender) == 'other' ? 'checked' : '' }}
                                               class="w-4 h-4 text-amber-500 border-gray-300 focus:ring-amber-500">
                                        <span class="ml-2 text-gray-700">Lainnya</span>
                                    </label>
                                </div>
                                @error('gender')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 mt-8 pt-6 border-t border-gray-200">
                            <button type="submit" 
                                    class="flex-1 bg-gradient-to-r from-amber-500 to-yellow-500 text-white px-6 py-3 rounded-xl font-bold text-sm hover:from-amber-600 hover:to-yellow-600 transition-all duration-300 flex items-center justify-center gap-2">
                                <i class="fas fa-save"></i>
                                Simpan Perubahan
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
