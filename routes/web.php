<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PassResetController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;

//frontend
Route::get('/', [FrontendController::class, 'index'])->name('index');
Route::get('/author/register', [FrontendController::class, 'author_register'])->name('author.register');
Route::get('/author/login', [FrontendController::class, 'author_login'])->name('author.login');
Route::get('/author/list', [FrontendController::class, 'author_list'])->name('author.list');



Route::get('/dashboard', [HomeController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

//Profile
Route::post('/add/user', [UserController::class, 'add_user'])->name('add.user');
Route::get('/profile/edit', [UserController::class, 'edit_profile'])->name('edit.profile');
Route::post('/profile/update', [UserController::class, 'update_profile'])->name('update.profile');
Route::post('/password/update', [UserController::class, 'update_password'])->name('update.password');
Route::post('/photo/update', [UserController::class, 'update_photo'])->name('update.photo');
Route::get('/users', [UserController::class, 'users'])->name('users');
Route::delete('/user/delete/{user_id}', [UserController::class, 'user_delete'])->name('user.delete');

//Category
Route::get('/add/category', [CategoryController::class, 'category'])->name('category');
Route::post('/store/category', [CategoryController::class, 'category_store'])->name('category.store');
Route::get('/delete/category/{category_id}', [CategoryController::class, 'category_delete'])->name('category.delete');
Route::get('/trash/category', [CategoryController::class, 'category_trash'])->name('category.trash');
Route::get('/restore/category/{category_id}', [CategoryController::class, 'category_restore'])->name('category.restore');
Route::get('/hard/delete/category/{category_id}', [CategoryController::class, 'category_hard_delete'])->name('category.hard.delete');
Route::post('checked/delete', [CategoryController::class, 'check_delete'])->name('check.delete');
Route::post('checked/restore', [CategoryController::class, 'check_restore'])->name('check.restore');

//Tags
Route::get('/tag', [TagController::class, 'tag'])->name('tag');
Route::post('/tag/store', [TagController::class, 'tag_store'])->name('tag.store');
Route::get('/tag/delete/{tag_id}', [TagController::class, 'tag_delete'])->name('tag.delete');


//Authors
Route::post('/author/store', [AuthorController::class, 'author_store'])->name('author.store');
Route::post('/author/signin', [AuthorController::class, 'author_signin'])->name('author.signin');
Route::get('/author/logout', [AuthorController::class, 'author_logout'])->name('author.logout');
Route::get('/author/dashboard', [AuthorController::class, 'author_dash'])->middleware('authorcheck')->name('author.dash');
Route::get('/author/profile/edit', [AuthorController::class, 'author_profile'])->name('author.profile');
Route::post('/author/profile/update', [AuthorController::class, 'author_profile_update'])->name('author.profile.update');
Route::post('/author/pass/update', [AuthorController::class, 'author_pass_update'])->name('author.pass.update');
Route::get('/authors', [UserController::class, 'authors'])->name('authors');
Route::get('/author/delete/{author_id}', [UserController::class, 'author_delete'])->name('author.delete');
Route::get('/author/status/{author_id}', [UserController::class, 'author_status'])->name('author.status');
Route::get('/author/verify/{token}', [UserController::class, 'author_verify'])->name('author.verify');
Route::get('/request/verify', [UserController::class, 'request_verify'])->name('request.verify');
Route::post('/request/verify/send', [UserController::class, 'request_verify_send'])->name('request.verify.send');

//Post
Route::get('/add/post', [PostController::class, 'add_post'])->name('add.post');
Route::post('/post/store', [PostController::class, 'post_store'])->name('post.store');
Route::get('/my/post', [PostController::class, 'my_post'])->name('my.post');
Route::get('/post/active/{post_id}', [PostController::class, 'post_active'])->name('post.active');
Route::get('/all/post', [PostController::class, 'all_post'])->name('all.post');
Route::get('/post/publish/{post_id}', [PostController::class, 'post_publish'])->name('post.publish');
Route::get('/post/details/{slug}', [PostController::class, 'post_details'])->name('post.details');
Route::get('/author/post/{author_id}', [PostController::class, 'author_post'])->name('author.post');
Route::get('/category/post/{category_id}', [PostController::class, 'category_post'])->name('category.post');
Route::get('/tag/post/{tag_id}', [PostController::class, 'tag_post'])->name('tag.post');


//Search
Route::get('/search', [FrontendController::class, 'search'])->name('search');

//Comments
Route::post('/comment/store/{author_id}', [FrontendController::class, 'comment_store'])->name('comment.store');

//Role Manager
Route::get('/role', [RoleController::class, 'role'])->middleware(['auth', 'verified'])->name('role');
Route::post('/permission/store', [RoleController::class, 'permission_store'])->name('permission.store');
Route::post('/role/store', [RoleController::class, 'role_store'])->name('role.store');
Route::get('/role/delete/{role_id}', [RoleController::class, 'role_delete'])->name('role.delete');
Route::post('/role/assign', [RoleController::class, 'role_assign'])->name('role.assign');
Route::get('/remove/role/{user_id}', [RoleController::class, 'remove_role'])->name('remove.role');

//Password Reset
Route::get('/pass/reset/req', [PassResetController::class, 'pass_reset_req'])->name('pass.reser.req');
Route::post('/pass/reset/req/sent', [PassResetController::class, 'pass_reset_req_send'])->name('pass.reser.req.send');
Route::get('/pass/reset/form/{token}', [PassResetController::class, 'pass_reset_form'])->name('pass.reset.form');
Route::post('/pass/reset/update/{token}', [PassResetController::class, 'pass_reset_update'])->name('pass.reset.update');


//FAQ
Route::resource('faq', FaqController::class);