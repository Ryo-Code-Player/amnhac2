@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">Danh sách loại sự kiện</h2>

    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Table -->
            <div class="intro-y box p-5">
                <a href="{{ route('admin.EventType.create') }}" class="btn btn-primary mb-5">Thêm mới</a>
                <table class="table table-report -mt-2">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tiêu đề</th>
                            <th>Slug</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if (isset($eventTypes) && count($eventTypes) > 0)
                        @foreach($eventTypes as $key => $eventType)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $eventType->title }}</td>
                                <td>{{ $eventType->slug }}</td>
                                <td>{{ $eventType->status}}</td>
                                <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    <div class="dropdown py-3 px-1">  
                                        <a class="btn btn-primary" aria-expanded="false" data-tw-toggle="dropdown"> 
                                            Hoạt động
                                        </a>
                                        <div class="dropdown-menu w-40"> 
                                            <ul class="dropdown-content">
                                                <li><a class="dropdown-item" href="{{ route('admin.EventType.edit', $eventType->id) }}">Edit</a></li>
                                                <li>
                                                    <form action="{{ route('admin.EventType.destroy', $eventType->id) }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="dropdown-item text-danger">Delete</button>
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
                            <td colspan="5" class="text-center p-4">
                                <p class="text-lg text-red-600">Không tìm thấy sự kiện nào!</p>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- END: Table -->
        </div>
    </div>
    @section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('backend/assets/vendor/js/bootstrap-switch-button.min.js')}}"></script>
<script>
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });
    $('.dltBtn').click(function(e)
    {
        var form=$(this).closest('form');
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
                // alert(form);
                form.submit();
            }
        });
    });
</script>
@endsection

    @endsection


