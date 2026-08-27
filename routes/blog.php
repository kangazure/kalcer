<?php

use App\Http\Controllers\Web\BlogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Blog Routes
|--------------------------------------------------------------------------
*/

Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/rss.xml', [BlogController::class, 'rssFeed'])->name('rss');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');

    Route::post('/{postId}/like', [BlogController::class, 'toggleLike'])
        ->whereNumber('postId')
        ->name('like');

    Route::post('/{postId}/bookmark', [BlogController::class, 'toggleBookmark'])
        ->whereNumber('postId')
        ->middleware('auth:sanctum')
        ->name('bookmark');

    Route::post('/{postId}/comment', [BlogController::class, 'storeComment'])
        ->whereNumber('postId')
        ->middleware(['throttle:comment'])
        ->name('comment');
});
