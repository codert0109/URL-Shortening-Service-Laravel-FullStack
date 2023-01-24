@extends('../components/layout')
@section('bladeViewContent')
<div class="mt-6 text-center">
    <h1 class="text-lg mb-48">
        Not Found Url
    </h1>
    <a href="{{env('DOMAIN_URL')}}">Return Home</a>
</div>
@endsection