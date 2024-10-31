@extends('backend.layouts.master')

@section('content')
<h2 class="intro-y text-lg font-medium mt-10">Danh sách Công ty Âm nhạc</h2>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <a href="{{ route('admin.musiccompany.create') }}" class="btn btn-primary shadow-md mr-2">Thêm Công ty Âm nhạc</a>
        <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$music_companies->currentPage()}} trong {{$music_companies->lastPage()}} trang</div>
    </div>
    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">ID</th>
                    <th class="whitespace-nowrap">TÊN CÔNG TY</th>
                    <th class="whitespace-nowrap">ĐỊA CHỈ</th>
                    <th class="text-center whitespace-nowrap">LOGO</th>
                    <th class="whitespace-nowrap">NGƯỜI DÙNG</th>
                    <th class="text-center whitespace-nowrap">TRẠNG THÁI</th>
                    <th class="text-center whitespace-nowrap">TÀI NGUYÊN</th> <!-- Cột mới cho tài nguyên -->
                    <th class="text-center whitespace-nowrap">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
    @if ($music_companies->isEmpty())
        <tr>
            <td colspan="8" class="text-center py-4"> <!-- Căn giữa và kéo dài ra các cột -->
                <strong>Không có công ty âm nhạc nào hiển thị.</strong>
            </td>
        </tr>
    @else
        @foreach($music_companies as $company)
        <tr class="intro-x">
            <td class="text-left">
                <a href="{{ route('admin.musiccompany.show', $company->id) }}" class="font-medium whitespace-nowrap">{{ $company->id }}</a>
            </td>
            <td>
                <a href="{{ route('admin.musiccompany.show', $company->id) }}" class="font-medium whitespace-nowrap">{{ $company->title }}</a>
            </td>
            <td class="text-left">{{ $company->address }}</td>
            <td class="w-40 text-center"> <!-- Căn giữa logo -->
                <div class="flex justify-center items-center h-full">
                    <img class="tooltip rounded-full h-10 w-10 object-cover" src="{{ asset($company->photo) }}" alt="Company Logo">
                </div>
            </td>
            <td class="text-left">{{ optional($company->user)->full_name ?? 'N/A' }}</td>
            <td class="text-center"> 
                <input type="checkbox" 
                data-toggle="switchbutton" 
                data-onlabel="active"
                data-offlabel="inactive"
                {{ $company->status == "active" ? "checked" : "" }}
                data-size="sm"
                name="toggle"
                value="{{ $company->id }}"
                data-style="ios">
            </td>
            <td>
                @if ($company->resources->isNotEmpty())
                    @foreach ($company->resources as $resource)
                        {{ $resource->title }}{{ !$loop->last ? ', ' : '' }} <!-- Hiển thị tên tài nguyên -->
                    @endforeach
                @else
                    N/A
                @endif
            </td>
            <td class="table-report__action w-56">
                <div class="flex justify-center items-center">
                    <a href="{{ route('admin.musiccompany.edit', $company->id) }}" class="flex items-center mr-3"> 
                        <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit 
                    </a>
                    <form action="{{ route('admin.musiccompany.destroy', $company->id) }}" method="post" style="display: inline;">
                        @csrf
                        @method('delete')
                        <a class="flex items-center text-danger dltBtn" data-id="{{ $company->id }}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> 
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
        {{ $music_companies->links('vendor.pagination.tailwind') }}
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
