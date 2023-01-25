@extends('../components/layout')
@section('bladeViewContent')
<div class="p-12">
    <div class="mb-6">
        <div class="flex">
            <div class="grow h-14">
                <input type="text" class="border border-gray-200 rounded p-2 w-full" id="url" placeholder='Input Url' />
            </div>
            <div class="flex-none">
                <button id="submit" class="bg-laravel text-white rounded py-2 px-8 hover:bg-black ml-4"> Submit </button>
            </div>
        </div>
        <div class="row">
            <p class="text-red-300 text-xs mt-1" id='error'></p>
            <p class="text-green-300 text-xs mt-1" id='success'></p>
        </div>
    </div>

    <table class="border-collapse border border-slate-700 table-auto border-spacing-4 mx-auto w-full text-center">
        <thead class="">
            <tr class="bg-stone-200">
                <th class="border border-slate-600 p-2">
                    Destination
                </th>
                <th class="border border-slate-600 p-2">
                    Slug
                </th>
                <th class="border border-slate-600 p-2">
                    Shortened Url
                </th>
                <th class="border border-slate-600 p-2">
                    Views
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($urls as $url)
            <tr>
                <td class="border border-slate-600 p-2">
                    <a href="{{$url['destination']}}">{{$url['destination']}}</a>
                </td>
                <td class="border border-slate-600 p-2">
                    {{$url['slug']}}
                </td>
                <td class="border border-slate-600 p-2">
                    <a href="{{env('DOMAIN_URL').$url['slug']}}">{{env('DOMAIN_URL').$url['slug']}}</a>
                </td>
                <td class="border border-slate-600 p-2">
                    {{$url['views']}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('bladeScript')
<script>
    $('#submit').click(function() {
        $('#error').hide();
        $('#success').hide();
        $.ajax({
            type: 'post',
            url: '/urls',
            data: {
                destination: $('#url').val(),
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                $('#success').html('Url saved successfully!').show();
                let url = data.data;
                let new_row = 
                    `<tr>
                        <td class="border border-slate-600 p-2"><a href="${url.destination}">${url.destination}</a></td>
                        <td class="border border-slate-600 p-2">${url.slug}</td>
                        <td class="border border-slate-600 p-2"><a href="${url.shortened_url}">${url.shortened_url}</a></td>
                        <td class="border border-slate-600 p-2">${url.views}</td>
                    </tr>`;
                $('tbody').prepend(new_row);
            },
            error: function(data) {
                if (data.responseJSON.message != null) {
                    $('#error').html(data.responseJSON.message).show();
                }
            }
        })
    })
    $('#url').on('input',function() {
        $('#error').hide();
        $('#success').hide();
    })
</script>

@endsection