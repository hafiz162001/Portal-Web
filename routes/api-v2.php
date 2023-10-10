<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiV2\LoginController;
use App\Http\Controllers\ApiV2\BannerController;
use App\Http\Controllers\ApiV2\AppsMenuController;
use App\Http\Controllers\ApiV2\PesertaController;
use App\Http\Controllers\ApiV2\ContestanController;
use App\Http\Controllers\ApiV2\MentorInterviewController;
use App\Http\Controllers\ApiV2\VoteController;
use App\Http\Controllers\ApiV2\StyleMusicController;
use App\Http\Controllers\ApiV2\TypeController;
use App\Http\Controllers\ApiV2\ProfileController;
use App\Http\Controllers\ApiV2\AlbumController;
use App\Http\Controllers\ApiV2\ContestanShowCaseController;
use App\Http\Controllers\ApiV2\ForumMemberController;
use App\Http\Controllers\ApiV2\ForumController;
use App\Http\Controllers\ApiV2\ForumThreadController;
use App\Http\Controllers\ApiV2\ThreadMessageController;
use App\Http\Controllers\ApiV2\ThreadLikeController;
use App\Http\Controllers\ApiV2\ThreadViewController;
use App\Http\Controllers\ApiV2\LivestreamController;
use App\Http\Controllers\ApiV2\LivestreamLikeController;
use App\Http\Controllers\ApiV2\MentorController;
use App\Http\Controllers\ApiV2\LikeController;
use App\Http\Controllers\ApiV2\CommentController;
use App\Http\Controllers\ApiV2\SaveController;
use App\Http\Controllers\ApiV2\ViewsController;
use App\Http\Controllers\ApiV2\ContestanMemberController;
use App\Http\Controllers\ApiV2\ReportController;
use App\Http\Controllers\ApiV2\ArticleController;
use App\Http\Controllers\ApiV2\ArticleDetailController;
use App\Http\Controllers\ApiV2\ProgramController;
use App\Http\Controllers\ApiV2\HistoryController;
use App\Http\Controllers\ApiV2\GalleryController;
use App\Http\Controllers\ApiV2\TicketController;
use App\Http\Controllers\ApiV2\TicketUserController;
use App\Http\Controllers\ApiV2\OrderController;
use App\Http\Controllers\Api\SyaratKetentuanController;
use App\Http\Controllers\ApiV2\EvoBeaconController;
use App\Http\Controllers\ApiV2\LinkController;
use App\Http\Controllers\ApiV2\VoucherController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/l/{code}', [LinkController::class, "getCustomSchemaUrl"]);

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [LoginController::class, 'register']);
Route::post('delete-account', [LoginController::class, 'deleteAccount']);

Route::post('otp', [LoginController::class, 'otp']);
Route::post('email', [LoginController::class, 'email']);
Route::post('validate-otp', [LoginController::class, 'validateOtp']);
Route::post('resend-email', [LoginController::class, 'resend_email']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group( function () {
    Route::get('banner', [BannerController::class, 'index']);
    Route::get('menu', [AppsMenuController::class, 'index']);
    Route::apiResource('contestan', ContestanController::class);
    Route::apiResource('peserta', PesertaController::class);
    Route::apiResource('mentor-interview', MentorInterviewController::class);
    Route::post('update-profile', [LoginController::class, 'updateProfile']);
    Route::post('vote', [VoteController::class, 'vote']);
    Route::apiResource('style', StyleMusicController::class);
    Route::apiResource('type', TypeController::class);
    Route::apiResource('profile', ProfileController::class );
    Route::apiResource('album', AlbumController::class);
    Route::apiResource('show-case', ContestanShowCaseController::class);
    Route::get('last-listening', [ContestanShowCaseController::class, 'lastListening']);
    Route::get('recomended', [ContestanShowCaseController::class, 'diRekomendasikan']);
    Route::post('show-case-playing', [ContestanShowCaseController::class, 'showCaseMusicPlaying']);
    Route::apiResource('forum-member', ForumMemberController::class);
    Route::apiResource('forum', ForumController::class);
    Route::apiResource('threads', ForumThreadController::class);
    Route::apiResource('threads-likes', ThreadLikeController::class);
    Route::apiResource('threads-messages', ThreadMessageController::class);
    Route::post('delete-messages', [ThreadMessageController::class, 'deleteMessages']);
    Route::apiResource('threads-views', ThreadViewController::class);
    Route::apiResource('livestream', LivestreamController::class);
    Route::post('stop-livestream', [LivestreamController::class, 'stopLivestream']);
    Route::apiResource('livestream-likes', LivestreamLikeController::class);
    Route::apiResource('mentor', MentorController::class);
    Route::apiResource('contestan-member', ContestanMemberController::class);
    Route::apiResource('report', ReportController::class);
    // Route::apiResource('articles', ArticleController::class);
    Route::apiResource('article-detail', ArticleDetailController::class);
    Route::apiResource('program', ProgramController::class);
    Route::apiResource('tickets', TicketController::class);
    Route::apiResource('order', OrderController::class);
    Route::apiResource('ticket-user', TicketUserController::class);
    Route::prefix('tickets')->group(function () {
        Route::post('scan', [TicketUserController::class, 'ticketScan'])->name('tickets.scan');
        Route::post('checkin', [TicketUserController::class, 'ticketCheckin'])->name('tickets.checkin');
        Route::post('checkout', [TicketUserController::class, 'ticketCheckout'])->name('tickets.checkout');
        Route::post('redeem', [TicketUserController::class, 'redeemTicket'])->name('tickets.redeem');
    });

    //Galleries
    Route::prefix('galleries')->group(function () {
        Route::get('', [GalleryController::class, 'index'])->name('galleries.index');
    });

    //Galleries
    Route::prefix('threads')->group(function () {
        Route::post('delete', [ForumThreadController::class, 'deleteThreads'])->name('threads.delete');
    });

    //LIKE COMMENT VIEW SAVE HISTORY
    // Route::apiResource('like', LikeController::class);
    // Route::apiResource('comment', CommentController::class);
    Route::post('delete-comment', [CommentController::class, 'destroy']);
    // Route::get('comment', [CommentController::class, 'index']);
    Route::get('all-comment', [CommentController::class, 'allComment']);
    Route::apiResource('save', SaveController::class);
    Route::apiResource('view', ViewsController::class);
    Route::apiResource('history', HistoryController::class);

    Route::prefix('beacons')->group(function () {
        Route::post('/', [EvoBeaconController::class, 'beaconActivities']);
    });

    //voucher
    Route::apiResource('voucher', VoucherController::class);

});

Route::get('syarat-ketentuan', [SyaratKetentuanController::class, 'indexV2']);

Route::prefix('pawoon')->group(function () {
    Route::post('oauth', [LoginController::class, 'auth'])->name('pawoon.auth');
    Route::get('outlets', [LoginController::class, 'outlets'])->name('pawoon.outlets');
    Route::get('products', [LoginController::class, 'products'])->name('pawoon.products');
});


