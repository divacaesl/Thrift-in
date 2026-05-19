@extends('layouts.admin')

@section('title', 'Pengaturan Sistem - Admin')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card p-4">
            <h5 class="fw-bold mb-4"><i class="fas fa-sliders text-secondary me-2"></i> Pengaturan Global Platform</h5>
            
            <form action="{{ route('admin.system.settings.update') }}" method="POST">
                @csrf
                
                <h6 class="fw-bold text-primary mt-4 mb-3 border-bottom pb-2">1. Pengaturan Umum</h6>
                <div class="mb-3 row align-items-center">
                    <label class="col-sm-4 col-form-label fw-bold small text-muted">Nama Website</label>
                    <div class="col-sm-8">
                        @php $siteName = $settings['general']->where('key', 'site_name')->first()->value ?? 'ThriftIn Preloved'; @endphp
                        <input type="text" name="site_name" class="form-control" value="{{ $siteName }}">
                    </div>
                </div>
                <div class="mb-3 row align-items-center">
                    <label class="col-sm-4 col-form-label fw-bold small text-muted">Email Kontak CS</label>
                    <div class="col-sm-8">
                        @php $contactEmail = $settings['general']->where('key', 'contact_email')->first()->value ?? 'support@thriftin.com'; @endphp
                        <input type="email" name="contact_email" class="form-control" value="{{ $contactEmail }}">
                    </div>
                </div>

                <h6 class="fw-bold text-success mt-4 mb-3 border-bottom pb-2">2. Payment Gateway (Midtrans)</h6>
                <div class="mb-3 row align-items-center">
                    <label class="col-sm-4 col-form-label fw-bold small text-muted">Environment</label>
                    <div class="col-sm-8">
                        @php $midtransEnv = $settings['payment']->where('key', 'midtrans_sandbox')->first()->value ?? 'true'; @endphp
                        <select name="midtrans_sandbox" class="form-select">
                            <option value="true" {{ $midtransEnv == 'true' ? 'selected' : '' }}>Sandbox (Testing)</option>
                            <option value="false" {{ $midtransEnv == 'false' ? 'selected' : '' }}>Production (Live)</option>
                        </select>
                    </div>
                </div>

                <h6 class="fw-bold text-danger mt-4 mb-3 border-bottom pb-2">3. Keamanan & Sistem</h6>
                <div class="mb-4 row align-items-center">
                    <label class="col-sm-4 col-form-label fw-bold small text-muted">Maintenance Mode</label>
                    <div class="col-sm-8">
                        @php $maintenance = $settings['security']->where('key', 'maintenance_mode')->first()->value ?? 'false'; @endphp
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="maintenance_mode" value="true" {{ $maintenance == 'true' ? 'checked' : '' }} id="maintenanceSwitch">
                            <label class="form-check-label small" for="maintenanceSwitch">Tutup akses website untuk publik (Maintenance)</label>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-5 fw-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
