<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="icon" href="images/favicon.ico" />
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
            integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"/>
        <script src="//unpkg.com/alpinejs" defer></script>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            laravel: "#ef3b2d",
                        },
                    },
                },
            };
        </script>
        <title>LaraGigs | Find Laravel Jobs & Projects</title>
    </head>
    <body class="mb-48">
        <nav class="flex justify-between items-center mb-4">
            <a href="/"
                ><img class="w-24" src="{{asset('images/logo.png')}}" alt="" class="logo"
            /></a>
            <ul class="flex space-x-6 mr-6 text-lg">
            {{--View assumes this logged in situation is the case by default and presents welcome and manage button... but then the @else directive below this says to Blade engine, if this auth session isnt found it means we are not logged in, so give use the none-logged in links allowing registration and log in buttons.--}}
            @auth  {{--if we are in authenticated session then show this: --}}
                <li>
                    <span class="font-bold uppercase">
                        {{--note here we are using the auth() method inherited into the user class instance object that was created upon registration and logged into (or already existing and logged into) and then we point to that user instance object's database array value for the name record/row and fetch this to present as welcome message --}}
                        Welcome {{auth()->user()->name}} !
                    </span>
                </li>
                <li>
                    <a href="/listings/manage" class="hover:text-laravel"><i class="fa-solid fa-gear"></i>
                        Manage My Job Listings</a>
                </li>
                {{--logout form with POST data method to send a logout directive--}}
                <li>
                    <form class="inline" method="POST" action="/logout">
                        @csrf
                        <button type="submit"> <i class="fa-solid fa-door-closed"></i>Logout
                        </button>
                    </form>    
                </li>
            @else     {{--else if not loggedin: --}}
                <li>
                    <a href="/register" class="hover:text-laravel"
                        ><i class="fa-solid fa-user-plus"></i> Register
                    </a>
                </li>
                <li>
                    <a href="/login" class="hover:text-laravel"><i class="fa-solid fa-arrow-right-to-bracket"></i>
                        Login</a>
                </li>
            @endauth {{--end the auth conditional check for th rest of blade view presentation--}}
            </ul>
        </nav>
<main>
{{--View ... followed by yield directive which will output content of the view--}}
@yield('bladeViewContent')
{{--this yield acts as a kind of overlay instruction. When we import the layout.blade.php template into our other blade views, we allow those other blade views to inject their content into this section (nicknamed bladeViewContent) all while being structured by this one html blade template--}} 
</main>
<footer class="fixed bottom-0 left-0 w-full flex items-center justify-start font-bold bg-laravel text-white h-24 mt-24 opacity-90 md:justify-center">
    <p class="ml-2 text-xl">&copy; Brad Traversy @ TraversyMedia who created the tutorial for building this Laravel project</p>
    <a href="/listings/create" class="absolute top-1/3 right-10 bg-black text-white py-2 px-5">Post Job</a>
</footer>
<x-flash-message />
</body>
</html>