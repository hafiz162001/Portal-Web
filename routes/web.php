<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserBundle\ProfileController;
use App\Http\Controllers\UserBundle\AccessUserController;
use App\Http\Controllers\UserBundle\SubMenuController;
use App\Http\Controllers\UserBundle\MenuController;
use App\Http\Controllers\UserBundle\NavigationController;
use App\Http\Controllers\UserBundle\AccessUserRoleController;
use App\Http\Controllers\UserBundle\UserRoleController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ArticleController;

use App\Http\Controllers\CashierReportController;

use App\Http\Controllers\ContestanController;
use App\Http\Controllers\ContestanMemberController;
use App\Http\Controllers\ContestanShowCaseController;
use App\Http\Controllers\OtpUserController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\TermConditionController;
use App\Http\Controllers\CategoryArticleController;
use App\Http\Controllers\ChannelArticleController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\TagController;
use App\Models\Article;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', function () {
	// return redirect()->route('login');
	return redirect()->route('login');
});


\Auth::routes();

// mbloc
// Route::get('/login', function () {
// 	return redirect()->route('login');
// });
Route::get('/',[FrontController::class, 'index']);
Route::get('/detail/{item:slug}',[FrontController::class, 'detail']);
Route::get('/channels/{item:name}',[FrontController::class, 'channels']);
// Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('home', [ArticleController::class, 'index']);
Route::get('front',[FrontController::class, 'index'])->name('front');

// Route::get('/forum-thread/show-message/{id}', [ForumThreadController::class, 'showMessage'])->name('showMessage');
// Route::get('/get-bloc-access-user', [AccessUserController::class, 'getBlocAccessUser'])->name('getBlocAccessUser');
Route::get('get-location-access-user', [AccessUserController::class, 'getLocationAccessUser'])->name('getLocationAccessUser');
Route::get('/api/static-pages/payment-finish', [App\Http\Controllers\StaticPageController::class, 'paymentFinish'])->name('payment.finish');

Route::group(['middleware' => ['auth', 'acl.is.valid']], function () {
	Route::resource('access-users', AccessUserController::class);
	Route::resource('role', UserRoleController::class);
	Route::resource('change-user', OtpUserController::class);
	Route::resource('menu', MenuController::class);
	Route::resource('sub-menu', SubMenuController::class);
	Route::resource('navi', NavigationController::class);
	// Route::resource('profile', ProfileController::class, ['as' => 'profile-web']);
	Route::resource('profile-web', ProfileController::class);
	Route::resource('banner', BannerController::class);
	Route::resource('apps-menu', MenuController::class);


	Route::resource('term-condition', TermConditionController::class);


    //Article
    Route::resource('article', ArticleController::class);
    Route::resource('category-article', CategoryArticleController::class);
    Route::resource('channel-article', ChannelArticleController::class);
    Route::resource('tag-article', TagController::class);
	// cashier


});

Route::group(['middleware' => ['auth'], 'prefix' => 'api'], function () {
	Route::group(['prefix' => 'users'], function () {
		Route::post('menu/add', [AccessUserRoleController::class, 'add']);
		Route::post('menu/remove', [AccessUserRoleController::class, 'remove']);
	});
});
