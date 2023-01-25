<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MainController;

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
// route doublecolon for access to specific static methof of get in the Route class object(arg1 destination folder, arg 2 closure function that returns a view of the welcome page held in resources under /resources/views)
//note that we need a closure function in order to access an object outside of the scope of this route-get method without declaring a global variable in this namespace and setting this welcome page view as its definition... Note also the view is a laravel-specific global helper function. These helpers are inbuilt and allow the phpengine to quickly resolve specific commands. The view system is actually a template generated under the blades engine, written for laravel.
// note in the upated version we are deploying a reference to the custom controller class which holds the routing function

// DISPLAY AND CRUD ROUTES

Route::get('/', [ MainController::class,'index' ])->middleware(('auth'));

//NOTE - THE ORDER OF APPEARANCE IS IMPORTANT HERE SINCE WE ARE ROUTING VIA /LISTINGS/ ... if we want to route to a static resource then we need to put the static resource(in this case create view FIRST... then followed by the dynamic listings{whatever input variable is put in based on page, tag or search})

///// AUTH MIDDLEWARE PROTECTED ROUTES 

// these routes new to be available only to a authenticated user (loggedin). So we can use Auth middleware, attached as a additional method to be executed at the end of each of these routes, to make sure that whoever is accessing the routed path is an auth'd user.
// NOTE that these middleware check functions will not work if we don't first attach an auth middleware login redirect function to our login(splashpage) route below... which is what has been done below.
// STORE
Route::post('/urls', [MainController::class, 'store'])->middleware('auth');

// URL Redirect
Route::get('/{slug}', [MainController::class, 'update']);
// AUTHENTICATION and LOGIN ROUTES

// Register splashPage ROUTE
Route::get('/auth/register', [UserController::class, 'create'])->middleware('guest'); // middleware here is guest to block an alreayd logged in user from navigating to register route. the guest here means ONLY SHOW IF THE VISITOR IS ATTEMPTING TO ACCESS THIS PAGE AS A GUEST.
//NOTE also that we must change the default redirect for the inbuilt guest and the auth middleware check functions ... these are found in app/providers/RouteServiceProvider


// Create user Route

Route::post('/users', [UserController::class, 'store']);

// log user out route:
// note logout is also protected by auth middleware
Route::post('/auth/logout', [UserController::class, 'logout'])->middleware('auth');

// loging splashPage route:

// note the deployment of the Auth Middleware's redirect function login route (given the name of 'name') as an attached final method. This reroutes the attempted use of the middleware protected CRUD routes to this named login route -login being the value passed in the authenticate.php middleware class' redirect function which runs a check on whether the http request has recieve a JSON response. If there is no JSON, it means there has been no authentication and thus there is a need to trigger the middleware redirect, which leads the user to this splashpage for unauthaurised attempt to use protected CRUD routes.
Route::get('/auth/login', [UserController::class, 'login'])->name('login')->middleware('guest'); //chain the guest middleware here because, along with re-routing to login for unauthorised attempts at accessing crud, we also want a user who is already logged in to not be able to access the /register or /login routes (which they can if they write /login /register in URL bar). middleware can be chained and these additional functions are acting a little like javascript promise->then asyncs

// login a user route:
Route::post('/users/authenticate', [UserController::class, 'authenticate']);