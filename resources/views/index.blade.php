@extends('../components/layout')
@section('bladeViewContent')
<x-card class="pl-20 pr-20 max-w-6xl mx-auto mt-10">
    <div class="mb-6">
        <label for="url" class="inline-block text-lg mb-2">Url</label>
        <input type="text" class="border border-gray-200 rounded p-2 w-full" id="url" placeholder='Input Url' value="{{old('email')}}" />
        <p class="text-red-300 text-xs mt-1" id='error'></p>
        <p class="text-green-300 text-xs mt-1" id='success'></p>
    </div>

    <div class="mb-6 text-center">
        <button id="submit" class="bg-laravel text-white rounded py-2 px-4 hover:bg-black"> Sign In </button>
    </div>
    <table>
        <thead>
            <tr>
                <th>
                    Destination
                </th>
                <th>
                    Slug
                </th>
                <th>
                    Shortened Url
                </th>
                <th>
                    Views
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($urls as $url)
            <tr>
                <td>
                    {{$url['destination']}}
                </td>
                <td>
                    {{$url['slug']}}
                </td>
                <td>
                    {{env('DOMAIN_URL').$url['slug']}}
                </td>
                <td>
                    {{$url['views']}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-card>
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
                url: $('#url').val(),
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                $('#success').html('Url saved successfully!');
                $('#success').show();
                let url = data.data;
                let new_row = `<tr><td>${url.destination}</td><td>${url.slug}</td>
                <td>${url.shortened_url}</td><td>${url.views}</td></tr>`;
                $('tbody').prepend(new_row);
            },
            error: function(data) {
                if (data.responseJSON.message != null) {
                    $('#error').html(data.responseJSON.message).show();
                }
            }
        })
    })
</script>

@endsection