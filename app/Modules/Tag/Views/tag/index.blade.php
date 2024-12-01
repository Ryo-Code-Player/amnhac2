@extends('backend.layouts.master')

@section('content')
<h2 class="intro-y text-lg font-medium mt-10">Danh Sách Tags</h2>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <a href="{{ route('admin.tag.create') }}" class="btn btn-primary shadow-md mr-2">Thêm Tag Mới</a>
        <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{ $tags->currentPage() }} trong {{ $tags->lastPage() }} trang</div>
    </div>
    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">ID</th>
                    <th class="whitespace-nowrap">TÊN TAG</th>
                    <th class="text-center whitespace-nowrap">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @if ($tags->isEmpty())
                    <tr>
                        <td colspan="3" class="text-center py-4">
                            <strong>Không có tag nào hiển thị.</strong>
                        </td>
                    </tr>
                @else
                    @foreach($tags as $tag)
                    <tr class="intro-x">
                        <td class="text-left">
                            <a href="{{ route('admin.tag.show', $tag->id) }}" class="font-medium whitespace-nowrap">{{ $tag->id }}</a>
                        </td>
                        <td>
                            <a href="{{ route('admin.tag.show', $tag->id) }}" class="font-medium whitespace-nowrap">{{ $tag->title }}</a>
                        </td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a href="{{ route('admin.tag.edit', $tag->id) }}" class="flex items-center mr-3"> 
                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Chỉnh sửa 
                                </a>
                                <form action="{{ route('admin.tag.destroy', $tag->id) }}" method="post" style="display: inline;">
                                    @csrf
                                    @method('delete')
                                    <a class="flex items-center text-danger dltBtn" data-id="{{ $tag->id }}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> 
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Xóa 
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
        {{ $tags->links('vendor.pagination.tailwind') }}
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
                form.submit(); // Gọi hàm submit của form
            }
        });
    });
</script>
@endsection
