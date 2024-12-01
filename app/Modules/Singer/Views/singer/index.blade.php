@extends('backend.layouts.master')

@section('content')
<h2 class="intro-y text-lg font-medium mt-10">Danh sách Ca sĩ</h2>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <a href="{{ route('admin.singer.create') }}" class="btn btn-primary shadow-md mr-2">Thêm Ca sĩ</a>
        <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$singers->currentPage()}} trong {{$singers->lastPage()}} trang</div>
    </div>
    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">ID</th>
                    <th class="whitespace-nowrap">TÊN CA SĨ</th>
                    <th class="text-center whitespace-nowrap">ẢNH</th>
                    <th class="whitespace-nowrap">LOẠI</th>
                    <th class="whitespace-nowrap">CÔNG TY</th>
                    <th class="text-center whitespace-nowrap">TRẠNG THÁI</th>
                    
                    <th class="text-center whitespace-nowrap">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
    @if ($singers->isEmpty())
        <tr>
            <td colspan="8" class="text-center py-4">
                <strong>Không có ca sĩ nào hiển thị.</strong>
            </td>
        </tr>
    @else
        @foreach($singers as $singer)
        <tr class="intro-x">
            <td class="text-left">
                <a href="{{ route('admin.singer.show', $singer->id) }}" class="font-medium whitespace-nowrap">{{ $singer->id }}</a>
            </td>
            <td>
                <a href="{{ route('admin.singer.show', $singer->id) }}" class="font-medium whitespace-nowrap">{{ $singer->alias }}</a>
            </td>
            <td class="w-40 text-center">
                <div class="flex justify-center items-center h-full">
                    <img class="tooltip rounded-full h-10 w-10 object-cover" src="{{ asset($singer->photo) }}" alt="Singer Photo">
                </div>
            </td>
            <td class="text-left">{{ optional($singer->musicType)->title ?? 'N/A' }}</td>
            <td class="text-left">{{ optional($singer->company)->title ?? 'N/A' }}</td>

            <td class="text-center"> 
                            <input type="checkbox" 
                            data-toggle="switchbutton" 
                            data-onlabel="active"
                            data-offlabel="inactive"
                            {{$singer->status=="active"?"checked":""}}
                            data-size="sm"
                            name="toogle"
                            value="{{$singer->id}}"
                            data-style="ios">
                        </td>
            <td class="table-report__action w-56">
                <div class="flex justify-center items-center">
                    <a href="{{ route('admin.singer.edit', $singer->id) }}" class="flex items-center mr-3"> 
                        <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit 
                    </a>
                    <form action="{{ route('admin.singer.destroy', $singer->id) }}" method="post" style="display: inline;">
                        @csrf
                        @method('delete')
                        <a class="flex items-center text-danger dltBtn" data-id="{{ $singer->id }}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> 
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
        {{ $singers->links('vendor.pagination.tailwind') }}
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
        url: "{{ route('admin.singer.status', ['id' => '__id__']) }}".replace('__id__', id), // Thay thế __id__ bằng id thực tế
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
