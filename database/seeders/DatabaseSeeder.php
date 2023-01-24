<?php

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Listing;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Listing::factory(6)->create([
        //    'user_id' => $user->id
       // ]);

       // get rid of the gnerice factory of users \App\Models\User::factory(4)->create();
        // import the User model class as a user object container var and use its inherited factory superclas static method and chain on its create() method with the two fields relating to the our mock user data.
       $user1 = User::factory()->create([
        'name' => 'Adam Jensen',
        'email' => 'deus@ex.net',
        'password' => bcrypt('coolpass')
      ]);
      $user2 = User::factory()->create([
        'name' => 'JC Denton',
        'email' => 'deus@mmail.net',
        'password' => bcrypt('mypass')
      ]);
      // if we have a factory, we can create on user to associate the listings to:  
      //Listing::factory()->create([
        //'user_id' => $user->id
       //]);
        
    }
}
