<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\AppsMenuController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\BlocLocationController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\XenditController;
use App\Http\Controllers\Api\UserActivityController;
use App\Http\Controllers\Api\BeaconController;
use App\Http\Controllers\Api\VisitorController;
use App\Http\Controllers\Api\TicketOrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\MBlocFestController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\EatsOrderController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\FavoriteTenantController;
use App\Http\Controllers\Api\FavoriteProductController;
use App\Http\Controllers\Api\SyaratKetentuanController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LinkController;

use App\Models\Article;
use Illuminate\Support\Str;
use App\Models\LinkRedirect;

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


// link share

Route::get('/l/{code}', [LinkController::class, "getCustomSchemaUrl"]);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group( function () {
    Route::get('banner', [BannerController::class, 'index']);
    Route::get('menu', [AppsMenuController::class, 'index']);
    Route::get('promo', [PromoController::class, 'index']);
    Route::get('bloc', [BlocLocationController::class, 'index']);
    Route::get('location', [LocationController::class, 'index']);
    Route::get('wishlist', [WishlistController::class, 'index']);
    Route::post('wishlist', [WishlistController::class, 'store']);
    Route::group(['prefix' => 'product'], function(){
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'find']);
    });
    Route::group(['prefix' => 'ticket'], function(){
        Route::post('/calculate', [TicketController::class, 'calculate']);
    });
    Route::group(['prefix' => 'event'], function(){
        Route::get('/', [EventController::class, 'index']);
        Route::get('/{id}', [EventController::class, 'find']);
        Route::get('/ticket/{eventId}', [EventController::class, 'ticket']);
    });
    Route::group(['prefix' => 'visitor'], function(){
        Route::get('/', [VisitorController::class, 'index']);
        Route::post('/create', [VisitorController::class, 'create']);
    });
    Route::group(['prefix' => 'ticket-order'], function(){
        Route::get('/', [TicketOrderController::class, 'index']);
        Route::post('/create', [TicketOrderController::class, 'create']);
        Route::post('/redeem', [TicketOrderController::class, 'redeem'])->name('ticket.redeem');
    });
    Route::group(['prefix' => 'notification'], function(){
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/{id}', [NotificationController::class, 'detail']);
    });
    Route::group(['prefix' => 'favorite-tenant'], function(){
        Route::get('/popular', [FavoriteTenantController::class, 'popular']);
        Route::get('/', [FavoriteTenantController::class, 'index']);
        Route::post('/like', [FavoriteTenantController::class, 'like']);
        Route::post('/dislike', [FavoriteTenantController::class, 'dislike']);
    });
    Route::group(['prefix' => 'favorite-product'], function(){
        Route::get('/popular', [FavoriteProductController::class, 'popular']);
        Route::get('/', [FavoriteProductController::class, 'index']);
        Route::post('/like', [FavoriteProductController::class, 'like']);
        Route::post('/dislike', [FavoriteProductController::class, 'dislike']);
    });
    Route::post('update-profile', [AuthController::class, 'updateProfile']);
    Route::post('show-qr', [AuthController::class, 'showQr']);
    Route::get('profile', [AuthController::class, 'profile']);
    Route::group(['prefix' => 'user-activity'], function(){
        Route::post('/beacons', [UserActivityController::class, 'userActivities']);
        Route::post('/beacons-event', [UserActivityController::class, 'userActivitiesCheckInEvent']);
        Route::post('/event-checkout', [UserActivityController::class, 'eventCheckOut']);
        Route::post('/', [UserActivityController::class, 'userActivity']);
        // Route::post('checkout', [UserActivityController::class, 'checkout']);
        Route::get('/', [UserActivityController::class, 'index']);
        Route::get('/scan', [UserActivityController::class, 'scan']);
        Route::post('/petugas-scan', [UserActivityController::class, 'petugasScan'])->name('petugas.scan');
    });
    Route::get('beacon', [BeaconController::class, 'index']);
    Route::get('beacon/find', [BeaconController::class, 'find']);
    //FESTIVAL
    Route::post('festival/home', [MBlocFestController::class, 'home']);
    Route::post('festival/find', [MBlocFestController::class, 'find']);
    Route::post('festival/detail', [MBlocFestController::class, 'detail']);
    Route::post('festival/get-comment', [MBlocFestController::class, 'getComment']);
    Route::post('festival/get-similar', [MBlocFestController::class, 'getSimilar']);
    Route::post('festival/get-other-work', [MBlocFestController::class, 'getOtherWork']);
    Route::post('festival/like', [MBlocFestController::class, 'like']);
    Route::post('festival/comment', [MBlocFestController::class, 'comment']);
    Route::post('festival/delete-comment', [MBlocFestController::class, 'deleteComment']);
    Route::post('festival/my-creation', [MBlocFestController::class, 'myCreation']);

    //FESTIVAL V2
    Route::post('festival/home-v2', [MBlocFestController::class, 'homeV2']);
    Route::post('festival/find-v2', [MBlocFestController::class, 'findV2']);
    Route::post('festival/detail-v2', [MBlocFestController::class, 'detailV2']);
    Route::post('festival/todays-line-up', [MBlocFestController::class, 'todaysLineUp']);
    Route::post('festival/event-berlangsung', [MBlocFestController::class, 'eventBerlangsung']);
    Route::post('festival/event-detail', [MBlocFestController::class, 'eventDetail']);
    Route::get('festival/event-schedule', [MBlocFestController::class, 'eventSchedule']);
    Route::get('festival/location', [MBlocFestController::class, 'location']);
    Route::post('festival/galleries', [MBlocFestController::class, 'galleries']);

    //CART EATS
    Route::post('cart/add', [CartController::class, 'add']);
    Route::post('cart/remove', [CartController::class, 'remove']);
    Route::post('cart/detail', [CartController::class, 'cartDetail']);
    Route::post('cart/get-cart', [CartController::class, 'getCart']);

    Route::apiResource('groups', GroupController::class);
    Route::apiResource('articles', ArticleController::class);
    Route::post('eats/history', [EatsOrderController::class, 'history']);

    Route::get('get-notif', [MBlocFestController::class, 'itung']);

    //ORDER MBM
    Route::prefix('mbm')->group(function () {
        Route::post('/get-payment-channel', [OrderController::class, 'getPaymentChannel']);
        Route::post('/order', [OrderController::class, 'store']);
    });

    //LIKE COMMENT VIEW SAVE HISTORY
    Route::group(['prefix' => 'blocx'], function(){
        Route::apiResource('like', LikeController::class);
        Route::apiResource('comment', CommentController::class);
        Route::post('delete-comment', [CommentController::class, 'destroy']);
        // Route::get('comment', [CommentController::class, 'index']);
        Route::get('all-comment', [CommentController::class, 'allComment']);
    });

});
Route::group(['prefix' => 'xendit'], function(){
    Route::get('va/list', [XenditController::class, 'getListVa']);
    Route::post('va/invoice', [XenditController::class, 'createVa']);
    Route::post('va/callback', [XenditController::class, 'callbackVa']);
});
Route::post('payment-notification', [PaymentController::class, 'notification']);
Route::post('pay', [PaymentController::class, 'pay']);
Route::post('otp', [AuthController::class, 'otp']);
Route::post('beacon/find-x', [BeaconController::class, 'findX']);
Route::post('validate-otp', [AuthController::class, 'validateOtp']);
Route::post('otp-email', [AuthController::class, 'email']);
Route::post('validate-otp-email', [AuthController::class, 'validateOtpEmail']);

//ORDER EATS
Route::post('eats/my-order', [EatsOrderController::class, 'myOrder']);
Route::post('eats/order', [EatsOrderController::class, 'order']);
Route::post('eats/detail', [EatsOrderController::class, 'orderDetail']);
Route::post('eats/seats-available', [EatsOrderController::class, 'seatsAvailable']);

//GUES FCM
Route::post('/gues-fcm', [AuthController::class, 'setFcmToGues']);

//SYARAT KETENTUAN
Route::get('syarat-ketentuan', [SyaratKetentuanController::class, 'index']);

//DELETE ACCOUNT
Route::post('delete-account', [AuthController::class, 'deleteAccount']);

//MBM
Route::prefix('mbm')->group(function () {
    Route::post('payment-notification', [PaymentController::class, 'mbmPaymentNotification']);
});
