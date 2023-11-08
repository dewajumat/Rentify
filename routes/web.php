<?php

// use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminHunianController;
use App\Http\Controllers\AdminKeuanganController;
// use App\Http\Controllers\AdminModalController;
use App\Http\Controllers\AdminPemeliharaanController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AdminPropertiController;
// use App\Http\Controllers\PenghuniController;
use App\Http\Controllers\PenghuniHunianController;
use App\Http\Controllers\PenghuniKeuanganController;
// use App\Http\Controllers\PenghuniModalController;
use App\Http\Controllers\PenghuniPemeliharaanController;
use App\Http\Controllers\PenghuniProfileController;
use App\Http\Controllers\ProfileController;
// use App\Http\Controllers\StatUpdateController;
use App\Http\Controllers\WelcomeController;
// use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/view-clear', function() {
    Artisan::call('view:clear');
    return 'Compiled views cleared!';
});

Route::get('/view-cache', function() {
    Artisan::call('view:cache');
    return 'Compiled views cleared! <br> Blade templates cached successfully!';
});

Route::get('/route-cache', function() {
    Artisan::call('route:cache');
    return 'Route cache cleared! <br> Routes cached successfully!';
});

Route::get('/config-clear', function() {
    Artisan::call('config:clear'); 
    return 'Configuration cache cleared!';
});

Route::get('/config-cache', function() {
    Artisan::call('config:cache');
    return 'Configuration cache cleared! <br> Configuration cached successfully!';
});

Route::get('/cache-clear', function() {
    Artisan::call('cache:clear');
    return 'Application cache cleared!';
});

Route::get('/route-clear', function() {
    Artisan::call('route:clear');
    return 'Route cache cleared!';
});

Route::get('/', [WelcomeController::class, 'view'])->name('welcomePage');
// Route::post('/',[WelcomeController::class, 'index']);


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // -------------------------------------------------------------------------------------- RouteAdmin-Start --------------------------------------------------------------------------------------------

    // Profile Admin
    Route::get('/admin/{id}/profile', [AdminProfileController::class, 'indexProfileAdmin'])->name('rProfileAdmin')->middleware('userAkses:Admin');
    Route::get('/admin/{id}/updateProfile', [AdminProfileController::class, 'updateProfilAdmin'])->name('rUptdProfileAdmin')->middleware('userAkses:Admin');
    Route::post('/admin/{id}/sprofile', [AdminProfileController::class, 'storeProfileAdmin'])->name('rStoreProfileAdmin')->middleware('userAkses:Admin');

    // Penghuni Admin
    Route::get('/admin/{id}', [AdminHunianController::class, 'indexAdminId'])->name('radmin')->middleware('userAkses:Admin');
    Route::get('/admin/{id}/tambahPenghuniP1', [AdminHunianController::class, 'addPenghuniAdmin'])->name('rapadmin')->middleware('userAkses:Admin');
    Route::post('/admin/{id}/storePenghuni', [AdminHunianController::class, 'storePenghuniAdmin'])->name('rspadmin')->middleware('userAkses:Admin');
    Route::get('/admin/{id}/detailPenghuni/{bilik_id}', [AdminHunianController::class, 'detailBilik'])->name('rDBilAdmin')->middleware('userAkses:Admin');
    ;
    Route::delete('/admin/{id}/deleteRecordBilik/{bilik_id}', [AdminHunianController::class, 'deleteBilikPenghuni'])->name('rDelBilPenghuni')->middleware('userAkses:Admin');
    Route::get('/admin/{id}/canceledHunian/{bilik_id}', [AdminHunianController::class, 'canceledHunian'])->name('rCanceledHunian')->middleware('userAkses:Admin');


    // Properti Admin
    Route::get('/admin/{id}/properti', [AdminPropertiController::class, 'indexPropertiAdmin'])->name('rpradmin')->middleware('userAkses:Admin');
    Route::get('/admin/{id}/properti/tambahData', [AdminPropertiController::class, 'addPropertiAdmin'])->name('rapradmin')->middleware('userAkses:Admin');
    Route::post('/admin/{id}/properti/storeData', [AdminPropertiController::class, 'storePropertiAdmin'])->name('rspradmin')->middleware('userAkses:Admin');
    Route::get('/admin/{id}/properti/detailProperti/{properti_id}', [AdminPropertiController::class, 'detailProperti'])->name('rdpradmin')->middleware('userAkses:Admin');
    Route::get('/admin/{id}/properti/updateData/{properti_id}', [AdminPropertiController::class, 'updateProperti'])->name('rupradmin')->middleware('userAkses:Admin');
    Route::post('/admin/{id}/properti/storeUpdateData/{properti_id}', [AdminPropertiController::class, 'storeUpdateProperti'])->name('rsupradmin')->middleware('userAkses:Admin');
    Route::delete('/admin/{id}/deleteProperti/{bilik_id}', [AdminPropertiController::class, 'deleteProperti'])->name('rDelPropPenghuni')->middleware('userAkses:Admin');

    // Pemeliharaan Bilik Admin
    Route::get('/admin/{id}/pemeliharaan', [AdminPemeliharaanController::class, 'indexPemeliharaanAdmin'])->name('rmadmin')->middleware('userAkses:Admin');
    Route::get('/admin/{id}/pemeliharaan/{pemeliharaan_id}', [AdminPemeliharaanController::class, 'detailPemeliharaanAdmin'])->name('rdmadmin')->middleware('userAkses:Admin');
    Route::get('/admin/{id}/pemeliharaan/tambahData/{penghuni_id}/{bilik_id}/{pemeliharaan_id}', [AdminPemeliharaanController::class, 'addPemeliharaanAdmin'])->name('ramadmin')->middleware('userAkses:Admin');
    Route::post('/admin/{id}/pemeliharaan/storeData/{penghuni_id}/{bilik_id}/{pemeliharaan_id}', [AdminPemeliharaanController::class, 'storePemeliharaanAdmin'])->name('rsmadmin')->middleware('userAkses:Admin');

    // Keuangan Admin
    Route::get('/admin/{id}/pembayaran/{penghuni_id}/{bilik_id}/{pembayaran_id}', [AdminHunianController::class, 'konfirmasiPembayaran'])->name('rKonPembayaran')->middleware('userAkses:Admin');
    Route::post('/admin/{id}/pembayaran/{penghuni_id}/{bilik_id}/{pembayaran_id}', [AdminHunianController::class, 'sKonfirmasiPembayaran'])->name('rSKonPembayaran')->middleware('userAkses:Admin');
    Route::get('/admin/{id}/detailPenghuni/{bilik_id}/detailPembayaran/{pembayaran_id}/', [AdminKeuanganController::class, 'detailPembayaran'])->name('rDetPembayaranAdmin')->middleware('userAkses:Admin');
    Route::get('/admin/{id}/laporanKeuangan', [AdminKeuanganController::class, 'keuanganAdmin'])->name('rKeuanganAdmin')->middleware('userAkses:Admin');
    Route::get('/admin/{id}/laporanKeuangan/report', [AdminKeuanganController::class, 'generateReport'])->name('rGenReport')->middleware('userAkses:Admin');

    // Route::get('/admin/{id}/detailPenghuni/{bilik_id}', [AdminModalController::class, 'modalDetailPenghuni']);


    // Route::get('/admin/{id}/updateBilikPenghuni/{penghuni_id}/{bilik_id}',[AdminController::class, 'updateBilikPenghuni'])->name('rUptBilPenghuni')->middleware('userAkses:Admin');
    // Route::post('/admin/{id}/updateBilikPenghuni/{penghuni_id}/store',[AdminController::class, 'storeUpdateBilikPenghuni'])->name('rSUptBilPenghuni')->middleware('userAkses:Admin');



    // -------------------------------------------------------------------------------------- RouteAdmin-End ----------------------------------------------------------------------------------------------

    // ------------------------------------------------------------------------------------- RoutePenghuni-End --------------------------------------------------------------------------------------------

    // Index Penghuni
    Route::get('/penghuni/{id}', [PenghuniHunianController::class, 'indexPenghuniId'])->name('rpenghuni')->middleware('userAkses:Penghuni');
    Route::get('/penghuni/{id}/detailBilik/{bilik_id}', [PenghuniHunianController::class, 'detailBilikPenghuni'])->name('rdBilPenghuni')->middleware('userAkses:Penghuni');

    // Profile Penghuni
    Route::get('/penghuni/{id}/profile', [PenghuniProfileController::class, 'indexProfilePenghuni'])->name('rProfilePenghuni')->middleware('userAkses:Penghuni');
    Route::get('/penghuni/{id}/updateProfile', [PenghuniProfileController::class, 'updateProfilePenghuni'])->name('rUptdProfilePenghuni')->middleware('userAkses:Penghuni');
    Route::post('/penghuni/{id}/sprofile', [PenghuniProfileController::class, 'storeProfilePenghuni'])->name('rStoreProfilePenghuni')->middleware('userAkses:Penghuni');

    // Konfirmasi Bilik Penghuni
    Route::get('/penghuni/{id}/confirmation/{bilik_id}', [PenghuniHunianController::class, 'konfirmasiMenghuni'])->name('rKonPenghuni')->middleware('userAkses:Penghuni');
    Route::post('/penghuni/{id}/sconfirmation/{bilik_id}', [PenghuniHunianController::class, 'storeKonfirmasiMenghuni'])->name('rSKonPenghuni')->middleware('userAkses:Penghuni');

    // Pembayaran Penghuni
    Route::get('/penghuni/{id}/historyPembayaran/', [PenghuniKeuanganController::class, 'historyPembayaran'])->name('rHistoryPembayaran')->middleware('userAkses:Penghuni');
    Route::get('/penghuni/{id}/historyPembayaran/detailPembayaran/{pembayaran_id}/', [PenghuniKeuanganController::class, 'detailPembayaran'])->name('rDetPembayaranPenghuni')->middleware('userAkses:Penghuni');
    Route::get('/penghuni/{id}/historyPembayaran/generatedPayment', [PenghuniKeuanganController::class, 'generatePayment'])->name('rGenPayment')->middleware('userAkses:Penghuni');
    // Route::get('/penghuni/{id}/historyPembayaran/{bilik_id}',[PenghuniController::class, 'historyPembayaranBilik'])->name('rHistoryPembayaranBilik')->middleware('userAkses:penghuni');

    Route::get('/penghuni/{id}/formPembayaran/{bilik_id}', [PenghuniHunianController::class, 'formPembayaran'])->name('rPembayaranPenghuni')->middleware('userAkses:Penghuni');
    Route::post('/penghuni/{id}/sFormPembayaran/{bilik_id}', [PenghuniHunianController::class, 'sFormPembayaran'])->name('rSPembayaranPenghuni')->middleware('userAkses:Penghuni');
    Route::get('/penghuni/{id}/pembayaran/{bilik_id}/rejected/{pembayaran_id}', [PenghuniHunianController::class, 'rejectedPayment'])->name('rRejectedPayment')->middleware('userAkses:Penghuni');
    Route::get('/penghuni/{id}/pembayaran/{bilik_id}/rejected/{pembayaran_id}/edit', [PenghuniHunianController::class, 'editRejectedPayment'])->name('rEditRejectedPayment')->middleware('userAkses:Penghuni');
    Route::post('/penghuni/{id}//pembayaran{bilik_id}/rejected/{pembayaran_id}/edit/storeEditedPayment', [PenghuniHunianController::class, 'storeEditedPayment'])->name('rStoreEditedPayment')->middleware('userAkses:Penghuni');

    // Pemeliharaan Penghuni
    Route::get('/penghuni/{id}/pemeliharaan', [PenghuniPemeliharaanController::class, 'indexPemeliharaan'])->name('rIndexPemeliharaan')->middleware('userAkses:Penghuni');
    Route::get('/penghuni/{id}/pemeliharaan/{pemeliharaan_id}', [PenghuniPemeliharaanController::class, 'detailPemeliharaan'])->name('rDetailPemeliharaan')->middleware('userAkses:Penghuni');
    Route::get('/penghuni/{id}/addPemeliharaan', [PenghuniPemeliharaanController::class, 'addPemeliharaan'])->name('rAddPemeliharaan')->middleware('userAkses:Penghuni');
    Route::post('/penghuni/{id}/storePemeliharaan/{bilik_id}', [PenghuniPemeliharaanController::class, 'storePemeliharaan'])->name('rStorePemeliharaan')->middleware('userAkses:Penghuni');

    // Route::get('/penghuni/{id}/detailBilik/{bilik_id}', [PenghuniModalController::class, 'modalDetailBilik']);





    // -------------------------------------------------------------------------------------- RoutePenghuni-End ----------------------------------------------------------------------------------------------
});
require __DIR__ . '/auth.php';