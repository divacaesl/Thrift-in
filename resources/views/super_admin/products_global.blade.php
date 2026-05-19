@extends('layouts.super_admin')

@section('title', 'Global Products - Super Admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-dark);">Global Products Catalog</h2>
            <p class="text-muted mb-0">Monitor catalog items, process automated AI moderation flags, and disable/delete illegal listings.</p>
        </div>
    </div>

    <!-- Product list -->
    <div class="card border-0">
        <div class="card-header border-0 bg-transparent py-3">
            <h5 class="mb-0 text-white">All Platform Listings</h5>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Product Details</th>
                        <th>Seller</th>
                        <th>Price</th>
                        <th>AI Moderation Risk</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-secondary rounded" style="width: 50px; height: 50px; background-image: radial-gradient(circle, #333 0%, #111 100%);"></div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-white">Vintage Levi's Jacket 501</h6>
                                    <small class="text-muted">Category: Outerwear</small>
                                </div>
                            </div>
                        </td>
                        <td>Sari Dewi Boutique</td>
                        <td class="text-white fw-bold">Rp 350,000</td>
                        <td>
                            <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50">Low Risk (0.01)</span>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-danger me-1"><i class="fas fa-trash"></i> Delete</button>
                            <button class="btn btn-sm btn-outline-light"><i class="fas fa-eye"></i> View</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-secondary rounded" style="width: 50px; height: 50px; background-image: radial-gradient(circle, #333 0%, #111 100%);"></div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-white">Premium Rolex Oyster (Replica)</h6>
                                    <small class="text-muted">Category: Watches</small>
                                </div>
                            </div>
                        </td>
                        <td>Kaos Polos Murah</td>
                        <td class="text-white fw-bold">Rp 8,500,000</td>
                        <td>
                            <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50">High Risk (0.85 - Replica/Fake Keyword)</span>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-danger me-1"><i class="fas fa-trash"></i> Ban & Delete</button>
                            <button class="btn btn-sm btn-outline-light"><i class="fas fa-eye"></i> View</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
