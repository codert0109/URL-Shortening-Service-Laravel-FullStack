@extends('../components/layout')
@section('bladeViewContent')
    <x-card class="p-10 max-w-6xl mx-auto">
        <header class="text-center">
            <h2 class="text-2xl font-bold uppercase mb-1"> Login
            </h2>
            <p class="mb-4">Log into an account to post gigs</p>
        </header>
        <form method="POST" action="/users/authenticate">
        @csrf
            <div class="mb-6"> 
                <label for="email" class="inline-block text-lg mb-2">Email</label>
                <input type="email" class="border border-gray-200 rounded p-2 w-full" name="email" {{--NOTE THAT WITH THIS VALUE WE NEED QUOTE MARKS TO AVOID A BUG IN PRESENTATION--}} value="{{old('email')}}"/>
            @error('email')
                <p class="text-red-300 text-xs mt-1">{{$message}}</p>
            @enderror   
            </div>
            <div class="mb-6">
                <label for="password" class="inline-block text-lg mb-2"> Password </label>
                <input type="password" class="border border-gray-200 rounded p-2 w-full" name="password" />
            @error('password')
                <p class="text-red-300 text-xs mt-1">{{$message}}</p>
            @enderror       
            </div>
            <div class="mb-6">
                <button type="submit" class="bg-laravel text-white rounded py-2 px-4 hover:bg-black" > Sign In </button>
            </div>
            <div class="mt-8">
                <p> Don't have an account? <a href="/auth/register" class="text-laravel"
                >Register Here.</a>
                </p>
            </div>
        </form>
    </x-card>
@endsection
