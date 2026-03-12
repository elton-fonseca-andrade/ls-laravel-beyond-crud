<?php

use App\Admin\Inquiries\Controllers\AdmitInquiryController;
use App\Admin\Inquiries\Controllers\InquiriesController;
use App\Admin\Patients\Controllers\PatientsController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('/inquiries', [InquiriesController::class, 'index'])->name('admin.inquiries.index');
    Route::post('/inquiries', [InquiriesController::class, 'store'])->name('admin.inquiries.store');
    Route::post('/inquiries/{inquiry}/admit', AdmitInquiryController::class)->name('admin.inquiries.admit');

    Route::get('/patients', [PatientsController::class, 'index'])->name('admin.patients.index');
});
