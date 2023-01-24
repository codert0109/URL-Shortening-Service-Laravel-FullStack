<?php

use App\Models\Listing;
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

//home  SHOW ALL listings
//Route::get('/', [ ListingController::class,'index' ]);
Route::get('/', [ MainController::class,'index' ]);

//NOTE - THE ORDER OF APPEARANCE IS IMPORTANT HERE SINCE WE ARE ROUTING VIA /LISTINGS/ ... if we want to route to a static resource then we need to put the static resource(in this case create view FIRST... then followed by the dynamic listings{whatever input variable is put in based on page, tag or search})

///// AUTH MIDDLEWARE PROTECTED ROUTES 

// these routes new to be available only to a authenticated user (loggedin). So we can use Auth middleware, attached as a additional method to be executed at the end of each of these routes, to make sure that whoever is accessing the routed path is an auth'd user.
// NOTE that these middleware check functions will not work if we don't first attach an auth middleware login redirect function to our login(splashpage) route below... which is what has been done below.

// CREATE record route (add a job)
////Route::get('/listings/create', [ ListingController::class, 'create'])->middleware('auth');

// STORE
Route::post('/urls', [MainController::class, 'store'])->middleware('auth');

Route::get('/{slug}', [MainController::class, 'update']);
// EDIT
////Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->middleware('auth');

// UPDATE
//Route::put('/listings/{listing}', [ListingController::class, 'update'])->middleware('auth');

// DELETE
//Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->middleware('auth');

// manage listings route
//Route::get('/listings/manage', [ListingController::class, 'manage'])->middleware('auth');

//SHOW ONE listing pages using variable to change id.
// rout fetched the page listing/{id}
//id is filled in according to the Listing model executing a find() function and returns whatever content of the listings array it defines according to the id parameter of the relevant listing that has been passed into the route.
/* initial construct but it is flawed since if the user adds a page number that is not recognised, then it will give a dump or blank screen, no 404 not found
Route::get('/listings/{id}', function($id){
  return view('listing', [
    'listing' => Listing::find($id)
  ]);
});
*/
// new construct is simiular but instead of passing the id var to function, we pass the Listing model's listing variable. not sure how this works, come back. Note this is called route model binding
//Route::get('/listings/{listing}', [ ListingController::class, 'show']);

//Route::get('/', function () {
  //  return view('welcome');
//});
// quick test here without any helper function: 
//Route::get('/hi', function () {
  //  return 'Hello';
//});
// same as above test but with a response header but this time using a response() wrapper function which allows us to specify the headers to be sent when someone gets this resource... in this case a 404:
/*Route::get('/err', function () {
    return response('<h3>Err_404- Page Not Found</h3>', 404)->header('Content-Type', 'text/html');
});
*/
//here we deploy a recursive function which passes any specific id as arg1 and which is then fed  into the routes destination. Note also the use of a numbers regexp in order to allow only numbers to be passed into arg1 - a way to have numbered pages for instance: 
//Route::get('/posts/{id}', function ($id) {
  //  return response('Post '.$id);
//})->where('id', '[0-9]+');
//we can use a route dynamically instead of statically by having variable listeners coded into the route uri awaiting for user input. Furthermore, we can output this input from the user either into a standard return or, if we are debugging, we can use the dd (debug dump) or ddd (for even more details) to get the inputs. However, be careful leaving such debugging functions in live code or it may give a hacker access to sourcecode...
//an example here: let's say we have uri route with two variables,  signified by the ? query demarcator followed by a name var and city var: /search?name=John&city=Dallas.
//note here that we need to import a class for http requests in order to deploy this request object and wrap the uri input vars into that object 
/*
Route::get('/search', function(Request $request){
    dd($request->name.' '.$request->city);
}); */

// AUTHENTICATION and LOGIN ROUTES

// Register splashPage ROUTE
Route::get('/register', [UserController::class, 'create'])->middleware('guest'); // middleware here is guest to block an alreayd logged in user from navigating to register route. the guest here means ONLY SHOW IF THE VISITOR IS ATTEMPTING TO ACCESS THIS PAGE AS A GUEST.
//NOTE also that we must change the default redirect for the inbuilt guest and the auth middleware check functions ... these are found in app/providers/RouteServiceProvider


// Create user Route

Route::post('/users', [UserController::class, 'store']);

// log user out route:
// note logout is also protected by auth middleware
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

// loging splashPage route:

// note the deployment of the Auth Middleware's redirect function login route (given the name of 'name') as an attached final method. This reroutes the attempted use of the middleware protected CRUD routes to this named login route -login being the value passed in the authenticate.php middleware class' redirect function which runs a check on whether the http request has recieve a JSON response. If there is no JSON, it means there has been no authentication and thus there is a need to trigger the middleware redirect, which leads the user to this splashpage for unauthaurised attempt to use protected CRUD routes.
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest'); //chain the guest middleware here because, along with re-routing to login for unauthorised attempts at accessing crud, we also want a user who is already logged in to not be able to access the /register or /login routes (which they can if they write /login /register in URL bar). middleware can be chained and these additional functions are acting a little like javascript promise->then asyncs

// login a user route:
Route::post('/users/authenticate', [UserController::class, 'authenticate']);