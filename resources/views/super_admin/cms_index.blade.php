@extends('layouts.super_admin')

@section('title', 'CMS & Global Settings - Super Admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-dark);">CMS & Global Settings</h2>
            <p class="text-muted mb-0">Manage static pages, legal documents, main banner sliders, and global SEO metadata.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Site Info -->
        <div class="col-md-7">
            <div class="card border-0 mb-4">
                <div class="card-header"><h5 class="mb-0 text-white">Global Site Identity</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Website Name</label>
                            <input type="text" class="form-control bg-dark border-secondary text-white" value="ThriftIn Indonesia">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Support Email</label>
                            <input type="email" class="form-control bg-dark border-secondary text-white" value="support@thriftin.com">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted small">Footer Copyright Text</label>
                            <input type="text" class="form-control bg-dark border-secondary text-white" value="© 2026 ThriftIn Indonesia. All rights reserved.">
                        </div>
                    </div>
                    <button class="btn btn-primary mt-2">Update Identity Info</button>
                </div>
            </div>

            <!-- Homepage Banners -->
            <div class="card border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">Homepage Banners</h5>
                    <button class="btn btn-sm btn-outline-light"><i class="fas fa-plus"></i> Add Slide</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Slide Preview</th>
                                    <th>Title</th>
                                    <th>Action Link</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-4">
                                        <div style="width: 100px; height: 40px; background-color: #333; border-radius: 4px;"></div>
                                    </td>
                                    <td class="text-white fw-bold">Flash Sale 50% Off</td>
                                    <td class="text-muted">/promo/flash-sale</td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO Management -->
        <div class="col-md-5">
            <div class="card border-0 mb-4">
                <div class="card-header"><h5 class="mb-0 text-white">SEO Settings</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Default Meta Title</label>
                        <input type="text" class="form-control bg-dark border-secondary text-white" value="ThriftIn - Premium Thrift & Secondhand E-Commerce Platform">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Default Meta Description</label>
                        <textarea class="form-control bg-dark border-secondary text-white" rows="3">Find rare vintage clothes, retro shoes, and secondhand apparel securely in Indonesia's premier escrow-protected thrift store marketplace.</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Google Analytics ID</label>
                        <input type="text" class="form-control bg-dark border-secondary text-white" value="G-TRX-1029482">
                    </div>
                    <button class="btn btn-primary mt-2 w-100">Update SEO Metadata</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
