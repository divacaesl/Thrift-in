<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuperDashboardController extends Controller
{
    public function index()
    {
        // Pilar 1 & 2: Full Access Dashboard
        return view('super_admin.dashboard');
    }

    public function globalUsers()
    {
        // Pilar 4: Global Users
        return view('super_admin.users_global');
    }

    public function kycVerification()
    {
        // Pilar 5: Seller Verification
        return view('super_admin.users_kyc');
    }

    public function globalProducts()
    {
        // Pilar 6: Global Products Monitoring
        return view('super_admin.products_global');
    }

    public function globalTransactions()
    {
        // Pilar 7: Global Transactions Monitoring
        return view('super_admin.transactions_global');
    }

    public function financeDashboard()
    {
        // Pilar 8: System Finance
        return view('super_admin.finance_dashboard');
    }

    public function paymentSettings()
    {
        // Pilar 9: Payment Gateway Settings
        return view('super_admin.payment_settings');
    }

    public function cmsIndex()
    {
        // Pilar 10 & 16: Website Settings & CMS
        return view('super_admin.cms_index');
    }

    public function analyticsBi()
    {
        // Pilar 15: Analytics & Business Intelligence
        return view('super_admin.analytics_bi');
    }

    public function promoVouchers()
    {
        // Pilar 17: Promos & Vouchers
        return view('super_admin.promo_vouchers');
    }

    public function disputes()
    {
        // Pilar 18: Resolution Center
        return view('super_admin.disputes');
    }
}
