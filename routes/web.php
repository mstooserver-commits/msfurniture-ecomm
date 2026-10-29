<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use App\Livewire\Store\CartPage;
use App\Livewire\Store\CheckoutPage;
use App\Livewire\Store\HomePage;
use App\Livewire\Store\ProductShow;
use App\Livewire\Store\ShopPage;
use App\Livewire\Store\WishlistPage;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home');
Route::get('/shop', ShopPage::class)->name('shop');
Route::get('/products/{product:slug}', ProductShow::class)->name('products.show');
Route::get('/cart', CartPage::class)->name('cart');
Route::get('/checkout', CheckoutPage::class)->name('checkout');
Route::get('/checkout/success/{order}', [OrderController::class, 'success'])->name('checkout.success');
Route::get('/wishlist', WishlistPage::class)->middleware('auth')->name('wishlist');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/orders/{order}/invoice', [InvoiceController::class, 'download'])->name('orders.invoice');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{blog:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

Route::view('account', 'account.dashboard')->middleware(['auth', 'verified'])->name('account');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
