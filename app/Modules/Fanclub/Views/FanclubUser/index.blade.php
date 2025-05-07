@extends('backend.layouts.master')

@section('content')

    <h2 class="intro-y text-lg font-medium mt-10">Danh sách thành viên câu lạc bộ</h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{ route('admin.FanclubUser.create') }}" class="btn btn-primary shadow-md mr-2">
                <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Thêm thành viên
            </a>
        </div>
        
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap" style="width: 20%;">Tên thành viên</th>
                        <th class="whitespace-nowrap" style="width: 20%;">Quyền</th>
                        <th class="whitespace-nowrap" style="width: 30%;">Câu lạc bộ</th>
                        <th class="whitespace-nowrap" style="width: 20%;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($fanclubuser) && count($fanclubuser) > 0)
                    @foreach ($fanclubuser as $item)
                        {{-- {{ dd($item) }} --}}
                        <tr class="intro-x">
                            <td class="text-gray-800">{{ $item->user->username }}</td>
                            <td class="text-green-600">{{ $item->user->role }}</td>
                            <td class="text-blue-600">{{ $item->fanclub->title }}</td>
                            
                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    <div class="dropdown py-3 px-1">  
                                        <a class="btn btn-primary" aria-expanded="false" data-tw-toggle="dropdown"> 
                                            Hoạt động
                                        </a>
                                        <div class="dropdown-menu w-40"> 
                                            <ul class="dropdown-content">
                                                <li><a class="dropdown-item" href="{{ route('admin.FanclubUser.edit', $item->id) }}">Edit</a></li>
                                                <li>
                                                    <form action="{{ route('admin.FanclubUser.destroy', $item->id) }}" method="post">
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
                            <td colspan="4" class="text-center p-4">
                                <p class="text-lg text-red-600">Không tìm thấy thành viên câu lạc bộ nào!</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{ $fanclubuser->links() }}

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
