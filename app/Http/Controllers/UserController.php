<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //controller invokes a users form blade view to return upon a user wishing to create() a new user profile
    public function create(){
        return view('users.register');
    }
    public function store(Request $request){
        $formFields = $request->validate([
            'name' => ['required', 'min:3'], //minimu 3 chars
            'email' => ['required', 'email', Rule::unique('users', 'email')], // pass the email validaation and requiried but also create a rule checking that inputted email is unique to the users table email key-column in our SQL database
            'password' => ['required', 'confirmed', 'min:6'], // note the 'confirmed' invokes an illuminate helper function which, if our password confirmation field on the frontend has been named under a convention of password_confirmation, will pick up on this and check that the password input is also matching the password_confirmation input on the POST'd array that is being wrapped into this formFields variable. Note also the minimum 6 chars check.
        ]);
        // hash password with php's bcrypt lib  by simply passing the password array element that has been wrapped into the $formField array container var (incoming from the POST'd frontend user registration form data) and then running that into the inbuilt bcrypt funct (which is a PHP inbuilt not laravel only):
            $formFields['password'] = bcrypt($formFields['password']);

        // upon validatio of the incoming data into the wrapped array, we now create a new $user container variabale to hold the newly created object instance of the \App\Models\User class which is controlled/managed by our UserController class (models\User class itself inherits various Authenticable superclass meths). We pass the formFields array container var to fill in the properties of this $user container var object
        $user = User::create($formFields);
        // we deploy the auth() method, inherited into the $user object instance of the \App\Model\User class which itself inherist this method from the Authenticable superclas.
        //the auth illuminate method allows the passed user object to be contractually authenticated (and presumably handles the session token being given for session management?) 
        // note indeed in the storage tab of firefox dev edition tools we see that there is a XSRF-Token granted upon login with a 2 hour expiry period as well as a laravel_session object - note its secure XS reference flag is false? 
        // password is indeed hashed on DBeaver but remember token is null
        auth()->login($user);
        // upon successful login, redirect to index page
        return redirect('/')->with('message', 'Success! You are logged into your new profile!');
    }
    // logout function:
    public function logout(Request $request){
        // inbuilt logout function of the auth class,  revokes the granted laravel_session token.
        auth()->logout();
        // good opsec practice to also invalidate XSRF token 
        // we call on the request object which is a wrapper var containing the user instance object's XSRF session token and then call the authenticable superclass' session->invalidate() meths 
        $request->session()->invalidate();
        // also a good practice to regenerate a session token because of the danger of a session fixation attack where an attacker can send pre-cooked session info relating to the their preemptive visit to the site and then sending this somehose to the user (for example via an email link) to trick user into generating a session which the attacker can then keep login into or maintaining in logged in state for their purposes.
        $request->session()->regenerateToken();
        // redirect upon logout:
        return redirect('/')->with('message', 'You Logged out.');
    }
    //login splashpage present controller funct
    public function login(){
        return view('users.login');
    }
    //authenticate and loging the user controller funct
    public function authenticate(Request $request){
        // pass the request object from the login form into the formFieldslocal-scoped container var 
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password'=>['required'],
        ]);
        // authenticable superclass' attempt method inherited into out user class instance. we check that the user's attempt is authenticated via correct password. then we regenerate a session (to avoid session fixation attack)
        if(auth()->attempt($formFields)){
            $request->session()->regenerate();
        // on successful login redirect with success
        return redirect('/')->with('message', 'Log In Successful');
        }
        // on failed login redirect with failed message but note that the error message is intentionally obfuscated and also only shows on the email field... this is basic opSec to avoid hackers pinpointing that the email address used was correct and that the password was the only thing that was wrong - it forces more work on the part of the hacker.
        return back()->withErrors(['email'=>'Something Went Wrong with Login Details. Please try Again.'])->onlyInput('email');
    }
}
