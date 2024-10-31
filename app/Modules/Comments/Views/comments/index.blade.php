@extends('backend.layouts.master')
@section('content')

    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách bình luận
    </h2>
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$comments->currentPage()}} trong {{$comments->lastPage()}} trang</div>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <div class="w-56 relative text-slate-500">
                <form action="" method="get">
                    <input type="text" name="datasearch" class="ipsearch form-control w-56 box pr-10" placeholder="Search...">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                    <button type="submit" class="hidden">Search</button>
                </form>
            </div>
        </div>
    </div>

    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">NỘI DUNG</th>
                    <th class="whitespace-nowrap">NGƯỜI DÙNG</th>
                    <th class="whitespace-nowrap">NGUỒN</th>
                    <th class="text-center whitespace-nowrap">TRẠNG THÁI</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if($comments->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center text-slate-500">
                            Không có bình luận nào hiển thị.
                        </td>
                    </tr>
                @else
                    @foreach($comments as $comment)
                    <tr class="intro-x">
                        <td>
                            <a href="" class="font-medium whitespace-nowrap">{{ $comment->content }}</a> 
                        </td>
                        <td class="text-left">
                            <a href="{{ route('admin.users.show', $comment->user_id) }}" class="font-medium">
                                {{ \App\Models\User::find($comment->user_id)->full_name }}
                            </a>
                        </td>
                        <td class="text-left">
                            <a href="{{ route('admin.items.show', $comment->item_id) }}" class="font-medium">
                                {{ \App\Modules\Comments\Models\Item::find($comment->item_id)->name }}
                            </a>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" 
                                data-toggle="switchbutton" 
                                data-onlabel="active"
                                data-offlabel="inactive"
                                {{ $comment->status == "active" ? "checked" : "" }}
                                data-size="sm"
                                name="toggle"
                                value="{{ $comment->id }}"
                                data-style="ios">
                        </td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a href="{{ route('admin.comments.edit', $comment->id) }}" class="flex items-center mr-3"> 
                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit 
                                </a>
                                <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <a class="flex items-center text-danger dltBtn" data-id="{{ $comment->id }}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> 
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
    <!-- END: HTML Table Data -->

    <!-- BEGIN: Pagination -->
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
        <nav class="w-full sm:w-auto sm:mr-auto">
            {{ $comments->links('vendor.pagination.tailwind') }}
        </nav>
    </div>
    <!-- END: Pagination -->
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('backend/assets/vendor/js/bootstrap-switch-button.min.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });

    $('.dltBtn').click(function(e) {
        var form = $(this).closest('form');
        var dataID = $(this).data('id');
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

    $(".ipsearch").on('keyup', function (e) {
        e.preventDefault();
        if (e.key === 'Enter' || e.keyCode === 13) {
            var data = $(this).val();
            var form = $(this).closest('form');
            if(data.length > 0) {
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

    $("[name='toggle']").change(function() {
        var mode = $(this).prop('checked') ? 'active' : 'inactive'; // Xác định trạng thái
        var id = $(this).val();
        $.ajax({
            url: "{{ route('admin.comments.updateStatus', '') }}/" + id, // Cập nhật đường dẫn
            type: "post",
            data: {
                _token: '{{ csrf_token() }}',
                status: mode,
            },
            success: function(response) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: response.msg,
                    showConfirmButton: false,
                    timer: 1000
                });
                console.log(response.msg);
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                });
            }
        });
    });
</script>
@endsection
