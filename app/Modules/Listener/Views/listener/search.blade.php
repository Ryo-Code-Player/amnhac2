@extends('backend.layouts.master')
@section('content')

<div class="content">
    <h2 class="intro-y text-lg font-medium mt-10">
        Kết quả tìm kiếm listener
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
             
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$listeners->currentPage()}} trong {{$listeners->lastPage()}} trang</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
    <div class="relative text-slate-500">
        <form action="{{ route('admin.listener.search') }}" method="get" class="flex items-center">
            <input type="text" name="datasearch" value="{{ $searchdata }}" class="ipsearch form-control w-56 pr-10 py-2 rounded-lg border-gray-300" placeholder="Search...">
            <button type="submit" class="absolute right-0 top-1/2 transform -translate-y-1 p-2 bg-transparent border-none cursor-pointer">
                <i class="w-4 h-4 text-gray-500" data-lucide="search"></i>
            </button>
        </form>
    </div>
</div>

        </div>
    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">ID</th>
                    <th class="whitespace-nowrap">Loại yêu thích</th>
                    <th class="text-center whitespace-nowrap">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @if ($listeners->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <strong>Không có kết quả tìm kiếm nào.</strong>
                        </td>
                    </tr>
                @else
                    @foreach($listeners as $listener)
                        <tr class="intro-x">
                            <td class="text-left">
                                <a href="{{ route('admin.listener.show', $listener->id) }}" class="font-medium whitespace-nowrap">{{ $listener->id }}</a>
                            </td>
                            <td>
                                <a href="{{ route('admin.listener.show', $listener->id) }}" class="font-medium whitespace-nowrap">{{ $listener->favorite_type }}</a>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" 
                                    data-toggle="switchbutton" 
                                    data-onlabel="active"
                                    data-offlabel="inactive"
                                    {{$listener->status=="active"?"checked":""}}
                                    data-size="sm"
                                    name="toogle"
                                    value="{{$listener->id}}"
                                    data-style="ios">
                            </td>
                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    <a href="{{ route('admin.listener.edit', $listener->id) }}" class="flex items-center mr-3"> 
                                        <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit 
                                    </a>
                                    <form action="{{ route('admin.listener.destroy', $listener->id) }}" method="post" style="display: inline;">
                                        @csrf
                                        @method('delete')
                                        <a class="flex items-center text-danger dltBtn" data-id="{{ $listener->id }}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> 
                                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete 
                                        </a>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
<!-- END: HTML Table Data -->
<!-- BEGIN: Pagination -->
<div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
    <nav class="w-full sm:w-auto sm:mr-auto">
        {{ $listeners->links('vendor.pagination.tailwind') }}
    </nav>
</div>
<!-- END: Pagination -->
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('.dltBtn').click(function(e) {
        var form = $(this).closest('form');
        e.preventDefault();
        Swal.fire({
            title: 'Bạn có chắc muốn xóa không?',
            text: "Bạn không thể lấy lại dữ liệu sau khi xóa",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Vâng, tôi muốn xóa!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>

<script>
    $(".ipsearch").on('keyup', function (e) {
        e.preventDefault();
        if (e.key === 'Enter' || e.keyCode === 13) {
            var data = $(this).val();
            var form = $(this).closest('form');
            if (data.length > 0) {
                form.submit();
            } else {
                Swal.fire(
                    'Không tìm được!',
                    'Bạn cần nhập thông tin tìm kiếm.',
                    'error'
                );
            }
        }
    });

    $("[name='toogle']").change(function() {
        var mode = $(this).prop('checked') ? 'active' : 'inactive';
        var id = $(this).val();

        $.ajax({
            url: "{{ route('admin.listener.status', ['id' => '__id__']) }}".replace('__id__', id),
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                mode: mode
            },
            success: function(response) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: response.msg,
                    showConfirmButton: false,
                    timer: 1000
                });
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Không thể cập nhật trạng thái.',
                });
            }
        });
    });
</script>
@endsection
