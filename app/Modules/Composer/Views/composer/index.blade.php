@extends('backend.layouts.master')

@section('content')
<h2 class="intro-y text-lg font-medium mt-10">Danh sách nhạc sĩ sáng tác</h2>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <a href="{{ route('admin.composer.create') }}" class="btn btn-primary shadow-md mr-2">Thêm nhạc sĩ</a>
        <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$composers->currentPage()}} trong {{$composers->lastPage()}} trang</div>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
    <div class="relative">
        <form action="{{ route('admin.composer.search') }}" method="get" class="flex items-center">
            <input type="text" name="datasearch" class="ipsearch form-control w-56 pl-10 pr-10 py-2 rounded-lg border-gray-300"
                placeholder="Search..." autocomplete="off">
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
            <th style="width: 10%; text-align: center;" class="whitespace-nowrap">ID</th>
            <th style="width: 30%; text-align: center;" class="whitespace-nowrap">HỌ TÊN</th>
            <th style="width: 20%; text-align: center;" class="whitespace-nowrap">ẢNH</th>
            <th style="width: 20%; text-align: center;" class="whitespace-nowrap">TRẠNG THÁI</th>
            <th style="width: 20%; text-align: center;" class="whitespace-nowrap">HÀNH ĐỘNG</th>
        </tr>
    </thead>
    <tbody>
        @if ($composers->isEmpty())
        <tr>
            <td colspan="5" class="text-center py-4">
                <strong>Không có nhạc sĩ sáng tác nào hiển thị.</strong>
            </td>
        </tr>
        @else
        @foreach($composers as $composer)
        <tr class="intro-x">
            <td style="text-align: center;">
                <a href="{{ route('admin.composer.show', $composer->id) }}" class="font-medium whitespace-nowrap">{{ $composer->id }}</a>
            </td>
            <td style="text-align: center;">
                <a href="{{ route('admin.composer.show', $composer->id) }}" class="font-medium whitespace-nowrap">{{ $composer->fullname }}</a>
            </td>
            <td style="text-align: center;">
                <div style="display: flex; align-items: center; justify-content: center; height: 100%;">
                    <img style="border-radius: 50%; width: 40px; height: 40px; object-fit: cover;" src="{{ asset($composer->photo) }}" alt="Composer Photo">
                </div>
            </td>

            <td class="text-center"> 
                            <input type="checkbox" 
                            data-toggle="switchbutton" 
                            data-onlabel="active"
                            data-offlabel="inactive"
                            {{$composer->status=="active"?"checked":""}}
                            data-size="sm"
                            name="toogle"
                            value="{{$composer->id}}"
                            data-style="ios">
                        </td>
            <td style="text-align: center;">
                <div style="display: flex; align-items: center; justify-content: center;">
                    <a href="{{ route('admin.composer.edit', $composer->id) }}" class="flex items-center mr-3"> 
                        <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit
                    </a>
                    <form action="{{ route('admin.composer.destroy', $composer->id) }}" method="post" style="display: inline;">
                        @csrf
                        @method('delete')
                        <a class="flex items-center text-danger dltBtn" data-id="{{ $composer->id }}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> 
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
        {{ $composers->links('vendor.pagination.tailwind') }}
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
           
            // Do something
            var data=$(this).val();
            var form=$(this).closest('form');
            if(data.length > 0)
            {
                form.submit();
            }
            else
            {
                  Swal.fire(
                    'Không tìm được!',
                    'Bạn cần nhập thông tin tìm kiếm.',
                    'error'
                );
            }
        }
    });

    $("[name='toogle']").change(function() {
    var mode = $(this).prop('checked') ? 'active' : 'inactive'; // Xác định trạng thái
    var id = $(this).val(); // ID của công ty âm nhạc

    // Đảm bảo URL đúng, truyền id vào URL
    $.ajax({
        url: "{{ route('admin.composer.status', ['id' => '__id__']) }}".replace('__id__', id), // Thay thế __id__ bằng id thực tế
        type: "POST",
        data: {
            _token: '{{ csrf_token() }}',
            mode: mode // Trạng thái cần cập nhật
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
            console.log(xhr.responseText); // Log lỗi nếu có
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
