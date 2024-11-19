@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">Danh sách nhóm sự kiện</h2>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Table -->
            <div class="intro-y box p-5">
                <a href="{{ route('admin.EventGroup.create') }}" class="btn btn-primary mb-5">Thêm mới</a>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nhóm</th>
                            <th>Sự kiện</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if (isset($eventGroups) && count($eventGroups) > 0)
                        @foreach($eventGroups as $eventGroup)
                            <tr>
                                <td>{{ $eventGroup->group->title }}</td>
                                <td>{{ $eventGroup->event->title }}</td>
                                <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    <div class="dropdown py-3 px-1">  
                                        <a class="btn btn-primary" aria-expanded="false" data-tw-toggle="dropdown"> 
                                            Hoạt động
                                        </a>
                                        <div class="dropdown-menu w-40"> 
                                            <ul class="dropdown-content">
                                                <li><a class="dropdown-item" href="{{ route('admin.EventGroup.edit', $eventGroup->id) }}" class="flex items-center mr-3"> 
                                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Chỉnh sửa </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.EventGroup.destroy', $eventGroup->id) }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <a class="dropdown-item flex items-center text-danger dltBtn" data-id="{{ $eventGroup->id }}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal">
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
                    @else
                        <tr>
                            <td colspan="3" class="text-center p-4">
                                <p class="text-lg text-red-600">Không tìm thấy nhóm sự kiện nào!</p>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            <!-- END: Table -->
        </div>
    </div>

    {{ $eventGroups->links() }}
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