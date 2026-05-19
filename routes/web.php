<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PencairanController;
use App\Http\Controllers\LaporanController;

use App\Http\Controllers\BuyerHomeController;
use App\Http\Controllers\BuyerAuthController;
use App\Http\Controllers\BuyerAccountController;
use App\Http\Controllers\BuyerCartController;
use App\Http\Controllers\BuyerPaymentController;
use App\Http\Controllers\BuyerChatController;
use App\Http\Controllers\BuyerReviewController;
use App\Http\Controllers\BuyerSupportController;

use App\Http\Controllers\SellerAuthController;
use App\Http\Controllers\SellerDashboardController;
use App\Http\Controllers\SellerProductController;
use App\Http\Controllers\SellerOrderController;
use App\Http\Controllers\SellerFinanceController;
use App\Http\Controllers\SellerChatController;
use App\Http\Controllers\SellerPromoController;
use App\Http\Controllers\SellerReviewController;

// Public Buyer Storefront
Route::get('/', [BuyerHomeController::class, 'index'])->name('buyer.home');
Route::get('/detail/{id}', [BuyerHomeController::class, 'detail'])->name('buyer.detail');
Route::get('/toko/{id}', [BuyerHomeController::class, 'shop'])->name('buyer.shop');
Route::post('/buyer/seller/follow/{id}', [BuyerHomeController::class, 'followSeller'])->name('buyer.follow-seller');
Route::get('/buyer/toggle-lang/{lang}', [BuyerHomeController::class, 'toggleLanguage'])->name('buyer.lang');
Route::post('/buyer/toggle-theme/{theme}', [BuyerHomeController::class, 'toggleTheme']);

// Buyer Authentication
Route::get('/buyer/login', [BuyerAuthController::class, 'showLoginForm'])->name('buyer.login')->middleware('guest');
Route::post('/buyer/login', [BuyerAuthController::class, 'login']);
Route::get('/buyer/register', [BuyerAuthController::class, 'showRegisterForm'])->name('buyer.register')->middleware('guest');
Route::post('/buyer/register', [BuyerAuthController::class, 'register']);
Route::get('/buyer/login-google', [BuyerAuthController::class, 'loginGoogle'])->name('buyer.login.google');
Route::post('/buyer/forgot-password', [BuyerAuthController::class, 'forgotPassword'])->name('buyer.forgot');
Route::post('/buyer/verify-otp', [BuyerAuthController::class, 'verifyOtp'])->name('buyer.verify.otp');

// Seller Guest Authentication
Route::get('/seller/login', [SellerAuthController::class, 'showLoginForm'])->name('seller.login')->middleware('guest');
Route::post('/seller/login', [SellerAuthController::class, 'login'])->middleware('guest');
Route::get('/seller/register', [SellerAuthController::class, 'showRegisterForm'])->name('seller.register')->middleware('guest');
Route::post('/seller/register', [SellerAuthController::class, 'register'])->middleware('guest');

// Authenticated Buyer Features
Route::middleware('auth')->group(function () {
    Route::post('/buyer/logout', [BuyerAuthController::class, 'logout'])->name('buyer.logout');
    
    // Dashboard & Profile
    Route::get('/buyer/dashboard', [BuyerAccountController::class, 'dashboard'])->name('buyer.dashboard');
    Route::get('/buyer/profile', [BuyerAccountController::class, 'profile'])->name('buyer.profile');
    Route::post('/buyer/profile/update', [BuyerAccountController::class, 'updateProfile'])->name('buyer.profile.update');
    Route::post('/buyer/profile/change-password', [BuyerAccountController::class, 'changePassword'])->name('buyer.profile.password');
    Route::post('/buyer/address/add', [BuyerAccountController::class, 'addAddress'])->name('buyer.address.add');
    Route::get('/buyer/address/delete/{id}', [BuyerAccountController::class, 'deleteAddress'])->name('buyer.address.delete');
    Route::get('/buyer/address/utama/{id}', [BuyerAccountController::class, 'setUtamaAddress'])->name('buyer.address.utama');
    Route::post('/buyer/notification/read/{id}', [BuyerAccountController::class, 'readNotification']);
    Route::post('/buyer/notification/readall', [BuyerAccountController::class, 'readAllNotifications'])->name('buyer.notif.readall');
    Route::post('/buyer/receipt/confirm/{id}', [BuyerAccountController::class, 'confirmReceipt'])->name('buyer.receipt.confirm');
    
    // Delivery status simulation (for demonstration)
    Route::get('/buyer/simulate-shipment/{id}/{nextStatus}', [BuyerPaymentController::class, 'simulateDeliveryUpdate']);

    // Cart & Wishlist
    Route::get('/buyer/cart', [BuyerCartController::class, 'index'])->name('buyer.cart');
    Route::post('/buyer/cart/add', [BuyerCartController::class, 'addToCart'])->name('buyer.cart.add');
    Route::post('/buyer/cart/update/{id}', [BuyerCartController::class, 'updateQty'])->name('buyer.cart.update');
    Route::delete('/buyer/cart/delete/{id}', [BuyerCartController::class, 'destroy'])->name('buyer.cart.delete');
    Route::post('/buyer/cart/save/{id}', [BuyerCartController::class, 'saveForLater'])->name('buyer.cart.save');
    Route::post('/buyer/wishlist/toggle/{id}', [BuyerCartController::class, 'toggleWishlist'])->name('buyer.wishlist.toggle');

    // Checkout & Payment Escrow
    Route::get('/buyer/checkout', [BuyerPaymentController::class, 'checkout'])->name('buyer.checkout');
    Route::post('/buyer/checkout/process', [BuyerPaymentController::class, 'processCheckout'])->name('buyer.checkout.process');
    Route::get('/buyer/payment/confirm/{id}', [BuyerPaymentController::class, 'showPaymentConfirm'])->name('buyer.payment.confirm');
    Route::post('/buyer/payment/upload/{id}', [BuyerPaymentController::class, 'uploadPaymentConfirm'])->name('buyer.payment.upload');

    // Chat & Negotiation
    Route::get('/buyer/chat', [BuyerChatController::class, 'index'])->name('buyer.chat');
    Route::post('/buyer/chat/send', [BuyerChatController::class, 'sendMessage'])->name('buyer.chat.send');
    Route::post('/buyer/chat/offer', [BuyerChatController::class, 'submitOffer'])->name('buyer.chat.offer');

    // Reviews & Ratings
    Route::get('/buyer/review/{id}', [BuyerReviewController::class, 'create'])->name('buyer.review.create');
    Route::post('/buyer/review/store/{id}', [BuyerReviewController::class, 'store'])->name('buyer.review.store');

    // Help Center & CS Chat
    Route::get('/buyer/support', [BuyerSupportController::class, 'index'])->name('buyer.support');
    Route::post('/buyer/support/complaint', [BuyerSupportController::class, 'submitComplaint'])->name('buyer.support.complaint');
    Route::post('/buyer/support/chat', [BuyerSupportController::class, 'simulateSupportChat'])->name('buyer.support.chat');
});

// Admin Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Admin Area
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Users Management
    Route::get('/users', [App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users/{id}/suspend', [App\Http\Controllers\Admin\AdminUserController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{id}/activate', [App\Http\Controllers\Admin\AdminUserController::class, 'activate'])->name('users.activate');
    Route::get('/users/seller-kyc', [App\Http\Controllers\Admin\AdminUserController::class, 'sellerVerificationQueue'])->name('users.seller_kyc');
    Route::post('/users/verify-seller/{id}', [App\Http\Controllers\Admin\AdminUserController::class, 'verifySeller'])->name('users.verify_seller');
    Route::post('/users/reject-seller/{id}', [App\Http\Controllers\Admin\AdminUserController::class, 'rejectSeller'])->name('users.reject_seller');

    // Products & Categories
    Route::get('/products', [App\Http\Controllers\Admin\AdminProductController::class, 'index'])->name('products.index');
    Route::post('/products/{id}/moderate', [App\Http\Controllers\Admin\AdminProductController::class, 'moderationAction'])->name('products.moderate');
    Route::get('/categories', [App\Http\Controllers\Admin\AdminProductController::class, 'categories'])->name('categories');
    Route::post('/categories', [App\Http\Controllers\Admin\AdminProductController::class, 'storeCategory'])->name('categories.store');
    Route::delete('/categories/{id}', [App\Http\Controllers\Admin\AdminProductController::class, 'destroyCategory'])->name('categories.destroy');

    // Transactions
    Route::get('/transactions', [App\Http\Controllers\Admin\AdminTransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions/{id}/dispute', [App\Http\Controllers\Admin\AdminTransactionController::class, 'resolveDispute'])->name('transactions.dispute');

    // Finance
    Route::get('/finance', [App\Http\Controllers\Admin\AdminFinanceController::class, 'index'])->name('finance.index');
    Route::post('/finance/{id}/process', [App\Http\Controllers\Admin\AdminFinanceController::class, 'processWithdrawal'])->name('finance.process');

    // Support
    Route::get('/support', [App\Http\Controllers\Admin\AdminSupportController::class, 'index'])->name('support.index');
    Route::get('/support/{id}', [App\Http\Controllers\Admin\AdminSupportController::class, 'show'])->name('support.show');
    Route::post('/support/{id}/reply', [App\Http\Controllers\Admin\AdminSupportController::class, 'reply'])->name('support.reply');

    // Content
    Route::get('/content/banners', [App\Http\Controllers\Admin\AdminContentController::class, 'banners'])->name('content.banners');
    Route::post('/content/banners', [App\Http\Controllers\Admin\AdminContentController::class, 'storeBanner'])->name('content.banners.store');
    Route::delete('/content/banners/{id}', [App\Http\Controllers\Admin\AdminContentController::class, 'destroyBanner'])->name('content.banners.destroy');
    Route::get('/content/vouchers', [App\Http\Controllers\Admin\AdminContentController::class, 'vouchers'])->name('content.vouchers');
    Route::post('/content/vouchers', [App\Http\Controllers\Admin\AdminContentController::class, 'storeVoucher'])->name('content.vouchers.store');

    // System Settings
    Route::get('/system/settings', [App\Http\Controllers\Admin\AdminSystemController::class, 'settings'])->name('system.settings');
    Route::post('/system/settings', [App\Http\Controllers\Admin\AdminSystemController::class, 'updateSettings'])->name('system.settings.update');
    Route::get('/system/logs', [App\Http\Controllers\Admin\AdminSystemController::class, 'activityLogs'])->name('system.logs');
});

// Authenticated Seller Features
Route::middleware('auth')->group(function () {
    Route::post('/seller/logout', [SellerAuthController::class, 'logout'])->name('seller.logout');
    
    Route::get('/seller/dashboard', [SellerDashboardController::class, 'index'])->name('seller.dashboard');
    Route::get('/seller/profile', [SellerDashboardController::class, 'profile'])->name('seller.profile');
    Route::post('/seller/profile/update', [SellerDashboardController::class, 'updateProfile'])->name('seller.profile.update');
    
    Route::get('/seller/product/ai-recommendation', [SellerProductController::class, 'getAiRecommendation'])->name('seller.product.ai-recommendation');
    Route::resource('/seller/product', SellerProductController::class)->names([
        'index' => 'seller.product.index',
        'create' => 'seller.product.create',
        'store' => 'seller.product.store',
        'edit' => 'seller.product.edit',
        'update' => 'seller.product.update',
        'destroy' => 'seller.product.destroy',
    ]);
    
    Route::get('/seller/order', [SellerOrderController::class, 'index'])->name('seller.order.index');
    Route::get('/seller/order/{id}/confirm', [SellerOrderController::class, 'confirm'])->name('seller.order.confirm');
    Route::post('/seller/order/{id}/ship', [SellerOrderController::class, 'ship'])->name('seller.order.ship');
    Route::get('/seller/order/{id}/cancel', [SellerOrderController::class, 'cancel'])->name('seller.order.cancel');
    Route::get('/seller/order/{id}/label', [SellerOrderController::class, 'printLabel'])->name('seller.order.label');
    
    Route::get('/seller/finance', [SellerFinanceController::class, 'index'])->name('seller.finance.index');
    Route::post('/seller/finance/withdraw', [SellerFinanceController::class, 'withdraw'])->name('seller.finance.withdraw');
    
    Route::get('/seller/chat', [SellerChatController::class, 'index'])->name('seller.chat');
    Route::post('/seller/chat/send', [SellerChatController::class, 'sendMessage'])->name('seller.chat.send');
    Route::post('/seller/chat/accept-offer/{nego_id}', [SellerChatController::class, 'acceptOffer'])->name('seller.chat.accept-offer');
    Route::post('/seller/chat/decline-offer/{nego_id}', [SellerChatController::class, 'declineOffer'])->name('seller.chat.decline-offer');
    Route::post('/seller/chat/counter-offer/{nego_id}', [SellerChatController::class, 'counterOffer'])->name('seller.chat.counter-offer');
    Route::post('/seller/chat/autoreply', [SellerChatController::class, 'updateAutoReply'])->name('seller.chat.autoreply');
    
    Route::get('/seller/promo', [SellerPromoController::class, 'index'])->name('seller.promo.index');
    Route::post('/seller/promo/{id}/update', [SellerPromoController::class, 'updatePromo'])->name('seller.promo.update');
    Route::post('/seller/promo/{id}/boost', [SellerPromoController::class, 'boostProduct'])->name('seller.promo.boost');
    
    Route::get('/seller/review', [SellerReviewController::class, 'index'])->name('seller.review.index');
    Route::post('/seller/review/{id}/reply', [SellerReviewController::class, 'reply'])->name('seller.review.reply');
});

// Super Admin Ecosystem (20 Pillars)
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('superadmin.')->group(function () {
    // 1 & 2. Main Dashboard & Full Access View
    Route::get('/dashboard', [\App\Http\Controllers\SuperAdmin\SuperDashboardController::class, 'index'])->name('dashboard');

    // 3. Admin Management
    Route::get('/admins', [\App\Http\Controllers\SuperAdmin\AdminManagementController::class, 'index'])->name('admins.index');
    Route::post('/admins', [\App\Http\Controllers\SuperAdmin\AdminManagementController::class, 'store'])->name('admins.store');
    Route::post('/admins/{id}/suspend', [\App\Http\Controllers\SuperAdmin\AdminManagementController::class, 'suspend'])->name('admins.suspend');

    // 4 & 5. Global Users & KYC
    Route::get('/users', [\App\Http\Controllers\SuperAdmin\SuperDashboardController::class, 'globalUsers'])->name('users.global');
    Route::get('/users/kyc', [\App\Http\Controllers\SuperAdmin\SuperDashboardController::class, 'kycVerification'])->name('users.kyc');

    // 6 & 7. Global Products & Transactions
    Route::get('/products', [\App\Http\Controllers\SuperAdmin\SuperDashboardController::class, 'globalProducts'])->name('products.global');
    Route::get('/transactions', [\App\Http\Controllers\SuperAdmin\SuperDashboardController::class, 'globalTransactions'])->name('transactions.global');

    // 8 & 9. Financial & Gateway
    Route::get('/finance', [\App\Http\Controllers\SuperAdmin\SuperDashboardController::class, 'financeDashboard'])->name('finance.dashboard');
    Route::get('/settings/payment', [\App\Http\Controllers\SuperAdmin\SuperDashboardController::class, 'paymentSettings'])->name('settings.payment');

    // 10 & 16. CMS & Website
    Route::get('/cms', [\App\Http\Controllers\SuperAdmin\SuperDashboardController::class, 'cmsIndex'])->name('cms.index');

    // 11 & 12. Security & Server
    Route::get('/security/server', [\App\Http\Controllers\SuperAdmin\SecurityController::class, 'serverMonitor'])->name('security.server');
    Route::get('/security/logs', [\App\Http\Controllers\SuperAdmin\SecurityController::class, 'accessLogs'])->name('security.logs');

    // 13 & 14. Communication & Broadcast
    Route::get('/communication/broadcast', [\App\Http\Controllers\SuperAdmin\CommunicationController::class, 'broadcast'])->name('communication.broadcast');
    Route::post('/communication/broadcast', [\App\Http\Controllers\SuperAdmin\CommunicationController::class, 'sendBroadcast'])->name('communication.broadcast.send');

    // 15. Analytics & BI
    Route::get('/analytics/bi', [\App\Http\Controllers\SuperAdmin\SuperDashboardController::class, 'analyticsBi'])->name('analytics.bi');

    // 17. Vouchers
    Route::get('/promo/vouchers', [\App\Http\Controllers\SuperAdmin\SuperDashboardController::class, 'promoVouchers'])->name('promo.vouchers');

    // 18. Dispute
    Route::get('/dispute', [\App\Http\Controllers\SuperAdmin\SuperDashboardController::class, 'disputes'])->name('dispute.index');

    // 19 & 20. API Keys & Multiplatform
    Route::get('/api', [\App\Http\Controllers\SuperAdmin\ApiController::class, 'index'])->name('api.index');
    Route::post('/api/generate', [\App\Http\Controllers\SuperAdmin\ApiController::class, 'generate'])->name('api.generate');
});

