<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="images/favicon.ico" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
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
    <style>
        main {
            min-height: 80vh;
        }
    </style>
    <title>Url Shorten</title>
</head>

<body>
    <nav class="flex justify-end items-center mb-4">
        <ul class="flex space-x-6 mr-6 text-lg p-6">
            {{--View assumes this logged in situation is the case by default and presents welcome and manage button... but then the @else directive below this says to Blade engine, if this auth session isnt found it means we are not logged in, so give use the none-logged in links allowing registration and log in buttons.--}}
            @auth {{--if we are in authenticated session then show this: --}}
            <li>
                <span class="font-bold uppercase">
                    {{--note here we are using the auth() method inherited into the user class instance object that was created upon registration and logged into (or already existing and logged into) and then we point to that user instance object's database array value for the name record/row and fetch this to present as welcome message --}}
                    Welcome {{auth()->user()->name}} !
                </span>
            </li>
            {{--logout form with POST data method to send a logout directive--}}
            <li>
                <form class="inline" method="POST" action="/auth/logout">
                    @csrf
                    <button type="submit"> <i class="fa-solid fa-door-closed"></i>Logout
                    </button>
                </form>
            </li>
            @else {{--else if not loggedin: --}}
            <li>
                <a href="/auth/register" class="hover:text-laravel"><i class="fa-solid fa-user-plus"></i> Register
                </a>
            </li>
            <li>
                <a href="/auth/login" class="hover:text-laravel"><i class="fa-solid fa-arrow-right-to-bracket"></i>
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
    <footer class="left-0 w-full flex items-center justify-start font-bold bg-laravel text-white h-16 opacity-90 md:justify-center">
        <p class="ml-2 text-xl">&copy; Vasyl</p>
    </footer>
    @yield('bladeScript')
</body>

</html>