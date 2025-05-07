@extends('backend.layouts.master')
@section('content')

<div class="content">
    @include('backend.layouts.notification')
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách các sự kiện
    </h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{ route('admin.Event.create') }}" class="btn btn-primary shadow-md mr-2">Tạo sự kiện mới</a>
            
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{ $events->currentPage() }} trong {{ $events->lastPage() }} trang</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <form action="" method="get">
                        @csrf
                        <input type="text" name="datasearch" class="ipsearch form-control w-56 box pr-10" placeholder="Tìm kiếm...">
                        <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i> 
                    </form>
                </div>
            </div>
        </div>

        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">Tiêu đề</th>
                        <!-- <th class="whitespace-nowrap">Mô tả</th> -->
                        {{-- <th class="whitespace-nowrap">Nội dung</th> --}}
                        <th class="whitespace-nowrap">Địa điểm</th>
                        <!-- <th class="whitespace-nowrap">Loại sự kiện</th> -->
                        <th class="whitespace-nowrap">Thời gian bắt đầu</th>
                        <th class="whitespace-nowrap">Thời gian kết thúc</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                        <tr class="intro-x">
                            <td>
                                {{ $event->title }}
                            </td>
                            <!-- <td>
                                {{ $event->summary }}
                            </td> -->
                            {{-- <td>
                                {{ $event->description }}
                            </td> --}}
                            <td>
                                {{ $event->diadiem }}
                            </td>

                            {{-- <td>
                                {{ $event->eventType->title }}
                            </td> --}}
                            <td>
                                {{ \Carbon\Carbon::parse($event->timestart)->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($event->timeend)->format('d/m/Y H:i') }}
                            </td>
                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    <div class="dropdown py-3 px-1">  
                                        <a class="btn btn-primary" aria-expanded="false" data-tw-toggle="dropdown"> 
                                            Hoạt động
                                        </a>
                                        <div class="dropdown-menu w-40"> 
                                            <ul class="dropdown-content">
                                                <li><a class="dropdown-item" href="{{ route('admin.Event.edit', $event->id) }}" class="flex items-center mr-3"> 
                                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Chỉnh sửa </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.Event.destroy', $event->id) }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <a class="dropdown-item flex items-center text-danger dltBtn" data-id="{{ $event->id }}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal">
                                                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Xóa 
                                                        </a>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div> 
                                    </div> 
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- END: Data List -->

        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            <nav class="w-full sm:w-auto sm:mr-auto">
                {{ $events->links('vendor.pagination.tailwind') }}
            </nav>
        </div>
        <!-- END: Pagination -->
    </div>
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
        var mode = $(this).prop('checked');
        var id = $(this).val();
        $.ajax({
            url: "",
            type: "post",
            data: {
                _token: '{{ csrf_token() }}',
                mode: mode,
                id: id,
            },
            success: function(response) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: response.msg,
                    showConfirmButton: false,
                    timer: 1000
                });
            }
        });
    });
</script>
@endsection
