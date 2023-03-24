<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CampagneController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SmsController;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Public routes 
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

//Route::group(['middleware'=>['auth:sanctum']],function(){

    //User
    Route::get('/user',[UserController::class ,'index']);
    Route::post('/user/store',[UserController::class ,'store']);
    Route::get('/user/{id}', [UserController::class, 'show']); 
    Route::put('/user/update/{id}', [UserController::class, 'update']);
    Route::delete('/user/{id}', [UserController::class, 'destroy']);

    //Campagne
    Route::get('/campagnes',[CampagneController::class ,'index']);
    Route::post('/campagne/store',[CampagneController::class ,'store']);
    Route::get('/campagne/{id}', [CampagneController::class, 'show']); 
    Route::put('/campagne/update/{id}', [CampagneController::class, 'update']);
    Route::delete('/campagne/{id}', [CampagneController::class, 'destroy']);

    //Contacts
    Route::get('/contacts/{id}',[ContactController::class ,'index']);
    Route::post('/contact/store',[ContactController::class ,'store']);
    Route::get('/contact/{id}', [ContactController::class, 'show']); 
    Route::put('/contact/update/{id}', [ContactController::class, 'update']);
    Route::delete('/contact/{id}', [ContactController::class, 'destroy']);
    Route::post('/contact/upload',[ContactController::class ,'upload']);

    // envoie sms
    Route::post('/sms/send',[SmsController::class ,'sendSms']);

    // Post
    Route::get('/posts', [PostController::class, 'index']); // all posts
    Route::post('/posts', [PostController::class, 'store']); // create post
    Route::get('/posts/{id}', [PostController::class, 'show']); // get single post
    Route::put('/posts/{id}', [PostController::class, 'update']); // update post
    Route::delete('/posts/{id}', [PostController::class, 'destroy']); // delete post

    // Comment
    Route::get('/posts/{id}/comments', [CommentController::class, 'index']); // all comments of a post
    Route::post('/posts/{id}/comments', [CommentController::class, 'store']); // create comment on a post
    Route::put('/comments/{id}', [CommentController::class, 'update']); // update a comment
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']); // delete a comment

    // Like
    Route::post('/posts/{id}/likes', [LikeController::class, 'likeOrUnlike']); // like or dislike back a post
    
//});


