@extends('backend.layouts.master')

@section('content')

    <h2 class="intro-y text-lg font-medium mt-10">Danh sách tài nguyên câu lạc bộ</h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{ route('admin.FanclubItem.create') }}" class="btn btn-primary shadow-md mr-2">
                <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Thêm tài nguyên
            </a>
        </div>
        
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap" style="width: 30%;">Mã</th>
                        <th class="whitespace-nowrap" style="width: 40%;">Tài nguyên</th>
                        <th class="whitespace-nowrap" style="width: 20%;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($fanclubitem) && count($fanclubitem) > 0)
                        @foreach ($fanclubitem as $item)
                            <tr class="intro-x">
                                <td class="text-gray-800">{{ $item->resource_code }}</td>
                                <td class="text-blue-600">{{ $item->resource->title }}</td>

                                <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                            <div class="dropdown py-3 px-1 ">  
                                <a class="btn btn-primary" aria-expanded="false" data-tw-toggle="dropdown"> 
                                    Hoạt động
                                </a>
                                <div class="dropdown-menu w-40"> 
                                    <ul class="dropdown-content">
                                    <li><a class="dropdown-item" href="{{route('admin.FanclubItem.edit',$item->id)}}" class="flex items-center mr-3" href="javascript:;"> <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit </a></li>
                                    <li>
                                        <form action="{{route('admin.FanclubItem.destroy',$item->id)}}" method = "post">
                                            @csrf
                                            @method('delete')
                                            <a class=" dropdown-item flex items-center text-danger dltBtn" data-id="{{$item->id}}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete </a>
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
                                <p class="text-lg text-red-600">Không tìm thấy loại liên kết nào!</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{ $fanclubitem->links() }}

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
