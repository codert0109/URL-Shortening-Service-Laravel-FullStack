<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Url;

// this custom sub-class of the controller super-class takes several functions relating to uri validation and routing. Essentially, it acts as a (re)-deployable abstract class/interface that can be used to undertake the routing that was previously done with 'hard coded' routed via the routs directory. To do so we simply pass the routing actions that were previously written into the relevant route directory files into this more abstract controller that will then direct user URI requests to the relevant view (while fetching/updating the models/incoming data) for that view.

// NOTE however that we have to use the router anyways... the difference is that now, instead of hard coding into the router the routes we want to present to our user, we can instead deploy a reference to our custom controller class.
class MainController extends Controller
{
    /* note the convention for route-paths: 
    - index for splash page/home
    - show to load up a single view from DB
    -Create 
    - Store
    - Update
    - Edit
    - Destroy   (for crud+ functionatility)   
    */
//SHOW ALL Listings
    public function index(){
          return view('index', [
            "urls" => Url::all()]);
    }
    public function store(Request $request){
        //dd($request->all());
        // first use the validate helper method to validate the form input (we create a formFields custom var that will hold the incoming array of submitted data (that has been validated)):
            # we create a container var formFields which will contain the array that is being transmitted by POST. Not also the use of inbuild dependency injected validation methods via Illuminate's HTTP\Request superclass.
            // to accept iconest, we use the file() illuminate meth and then the store meth which takes the default storage disk to store uploaded http request file (in public since we have changed the default in confie storage setting)
        //dd($request->file('logo')->store());
        $formFields = $request->validate([
            // note that here we add two validation filtering conditions to the company field. required as usual but also the magicmethod/helper unique(requirement) in which we set the table (listings) and make this company key-column record a unique one ... this is done as part normalisation of the SQL data to avoid overlapping inputs (in this case only one company can post jobs per account to avoid confusion)
            //'id' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'title' => 'required',
            'location' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required',
        ]);
        // check if logo has been uploaded
        if($request->hasFile('logo')){
            // set the value of the formfields data field  as the path of the file that is being uploaded (this file path and the file itself as simultaenously given here by the file() method which takes a file upload via http and the store() method, which sets it into the designated folder (files) within the -now defaulted to public- storage directory of the laravel project directory)
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }
        // here we also ensure that every time a listing is created, it also has a record-row value inputted for the foreign-key user_id column on the SQL listings table which associates it to a given existing user in the users table (on the basis of that shared user_id key-column value)
        // we assign value of the formFields listings class object container var (filled in by incoming post data from form on front end). That assigned value uses the authenticable superclass authentication check method on the id() method which is a method within the guard class under the scope of the authenticable superclass
        $formFields['user_id']= auth()->id();
        // also deploy the inbuilt create instant (instantiation of an instance object of our Listing model) which is inherited from the Model.php superclass.
        // REMEMEBR TO INCLUDING THE formeField container var OR THE create() method will be empty of any input, throwing an SQL 1364 error
        Listing::create($formFields);
        // return back to home upon submission note the use of the ->with directive that specified that a session flash message should be shown on the index page upon redirect to show user that the new entry has correctly been placed in the db/website. Note that this just deals iwth the backend of generating this message...the front end aspect is dealwith in a component (flash-message) which is injected with this message and itself injects into the / index view
        return redirect('/')->with('message', 'Success! Listing Created.');
    }
// SHOW EDIT FORM
    public function edit(Listing $listing){
    if($listing->user_id != auth()->id()){
        abort(403, '403 Unauthorised');
    }
        return view('listings.edit', ['listing'=>$listing]);
    }
// UPDATE
public function update(Request $request, Listing $listing){
    if($listing->user_id != auth()->id()){
        abort(403, '403 Unauthorised');
    }
    $formFields = $request->validate([
        'company' => 'required',
        'title' => 'required',
        'location' => 'required',
        'email' => ['required', 'email'],
        'tags' => 'required',
        'description' => 'required',
    ]);
    if($request->hasFile('logo')){
        $formFields['logo'] = $request->file('logo')->store('logos', 'public');
    }
    $listing->update($formFields);
    return back()->with('message', 'Listing Successfully Edited.');
    }
    public function destroy(Listing $listing){
        if($listing->user_id != auth()->id()){
            abort(403, '403 Unauthorised');
        }
        $listing->delete();
        return redirect('/')->with('message', 'Listing Deleted');
    }
}
