<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url;

// this custom sub-class of the controller super-class takes several functions relating to uri validation and routing. Essentially, it acts as a (re)-deployable abstract class/interface that can be used to undertake the routing that was previously done with 'hard coded' routed via the routs directory. To do so we simply pass the routing actions that were previously written into the relevant route directory files into this more abstract controller that will then direct user URI requests to the relevant view (while fetching/updating the models/incoming data) for that view.

// NOTE however that we have to use the router anyways... the difference is that now, instead of hard coding into the router the routes we want to present to our user, we can instead deploy a reference to our custom controller class.
class MainController extends Controller
{

//SHOW ALL URLS
    public function index(){
          return view('index', [
            "urls" => Url::where('id', '>', 0)->orderBy('created_at','desc')->get()]);
    }
    public function store(Request $request){
        // first use the validate helper method to validate the form input (we create a formFields custom var that will hold the incoming array of submitted data (that has been validated)):
            # we create a container var formFields which will contain the array that is being transmitted by POST. Not also the use of inbuild dependency injected validation methods via Illuminate's HTTP\Request superclass.
            // to accept iconest, we use the file() illuminate meth and then the store meth which takes the default storage disk to store uploaded http request file (in public since we have changed the default in confie storage setting)
        $formFields = $request->validate([
            'destination' => 'required|url|unique:urls,destination',
        ]);
        // Generate 5 length random numberic string.
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $unique = false;
        // Store tested results in array to not test them again
        $tested = [];
        do{

            $slug = substr(str_shuffle($permitted_chars), 0, 5);
            if( in_array($slug, $tested) ){
                continue;
            }

            // Check if it is unique in the database
            $count = Url::where('slug',$slug)->count();

            // Store the random character in the tested array
            // To keep track which ones are already tested
            $tested[] = $slug;

            // String appears to be unique
            if( $count == 0){
                // Set unique to true to break the loop
                $unique = true;
            }

            // If unique is still false at this point
            // it will just repeat all the steps until
            // it has generated a random string of characters

        }
        while(!$unique);

        $sql = Url::create(['destination' => $request->destination, 'slug' => $slug, 'views' => 0]);
        $sql->shortened_url = env('DOMAIN_URL').$sql->slug;
        if($sql)
            return response()
            ->json([
                'status' => 'Success',
                'data' => $sql,
            ], 200);
        else
            return response()
            ->json([
                'status' => 'Error',
                'message' => 'Url not saved!',
            ], 422);
        // return back to home upon submission note the use of the ->with directive that specified that a session flash message should be shown on the index page upon redirect to show user that the new entry has correctly been placed in the db/website. Note that this just deals iwth the backend of generating this message...the front end aspect is dealwith in a component (flash-message) which is injected with this message and itself injects into the / index view

    }

// UPDATE
public function update($slug){
        $url = Url::where('slug', $slug)->first();
        if($url) {
            $url->update(['views' => $url->views + 1]);
            return redirect($url->destination);
        }
        else {
            return view('NotFound');
        }
    }
}
