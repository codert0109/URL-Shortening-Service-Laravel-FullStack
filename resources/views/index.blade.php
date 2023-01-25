@extends('../components/layout')
@section('bladeViewContent')
<div class="p-12 bg-sky-50">
    <div class="mb-12">
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
        </div>
    </div>

    <table class="yajra-datatable table-bordered border-collapse border border-slate-700 mx-auto w-full text-center">
        <thead>
            <tr class="bg-stone-200">
                <th class="border border-slate-600 p-2">
                    No
                </th>
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
                <th class="border border-slate-600 p-2">
                    Last Update
                </th>
                <th class="border border-slate-600 p-2">
                    Action
                </th>
            </tr>
        </thead>
        <tbody class="text-left">
        </tbody>
    </table>
</div>
@endsection

@section('bladeScript')
<script>
    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('urls.list') }}",
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
            },
            {
                data: 'destination',
                name: 'destination',
            },
            {
                data: 'slug',
                name: 'slug',
            },
            {
                data: 'shortened_url',
                name: 'shortened_url',
                render: function(data, type, row) {
                    return `<a href="${data}">${data}</a>`;
                }
            },
            {
                data: 'views',
                name: 'views'
            },
            {
                data: 'updated_at',
                name: 'updated_at',
                render: function(data, type, row) {
                    return new Date(data).toISOString();
                }
            },
            {
                data: 'action',
                name: 'action',
            },
        ]
    });
    table.on('draw', function() {
        $('.delete').click(function(e) {
            console.log('sdd');
            e.preventDefault();
            let id = $(this).attr('id');
            $.ajax({
                type: 'get',
                url: '/delete/' + id,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    toastr.success('Delete Success');
                    table.ajax.reload();
                },
                error: function(data) {
                    if (data.responseJSON.message != null) {
                        toast.error(data.responseJSON.message);
                    }
                }
            })
        })
    });
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
                toastr.success('Save Success');
                table.ajax.reload();
            },
            error: function(data) {
                if (data.responseJSON.message != null) {
                    $('#error').html(data.responseJSON.message).show();
                }
            }
        })
    })

    $('#url').on('input', function() {
        $('#error').hide();
        $('#success').hide();
    })
</script>

@endsection