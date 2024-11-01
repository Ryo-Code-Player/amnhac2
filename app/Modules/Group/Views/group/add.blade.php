@extends('backend.layouts.master')
@section('content')

<div class="content">
@include('backend.layouts.notification')
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{route('admin.group.add', [$group->id, 'user'])}}" class="btn btn-primary shadow-md mr-2">User</a>
            <a href="{{route('admin.group.add', [$group->id, 'blog'])}}" class="btn btn-primary shadow-md mr-2">Blog</a>
            <a href="{{route('admin.group.add', [$group->id, 'resource'])}}" class="btn btn-primary shadow-md mr-2">Resource</a>
            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <form action="{{route('admin.group.search')}}" method = "get">
                        @csrf
                        <input type="text" name="datasearch" class="ipsearch form-control w-56 box pr-10" placeholder="Search...">
                        <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i> 
                    </form>
                </div>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        @if($linkadd == 'user')
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">Tên người dùng</th>
                        <th class="whitespace-nowrap">Username</th>
                        <th class="whitespace-nowrap">Email</th>
                        <th class="whitespace-nowrap">SĐT</th>
                        <th class="text-center whitespace-nowrap">Trạng thái</th>
                         
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user as $item)
                    <tr class="i.0ntro-x">
                        <td>
                             {{$item->full_name}} 
                        </td>
                        <td>
                            {{ $item->username }}
                        </td>
                        <td class="text-left">{{$item->email}}</td>
                        <td class="text-left">{{$item->phone}}</td>

                        <td class="text-center"> 
                            <input type="checkbox" 
                            data-toggle="switchbutton" 
                            data-onlabel="active"
                            data-offlabel="inactive"
                            {{$item->status=="active"?"checked":""}}
                            data-size="sm"
                            name="toogle"
                            value="{{$item->id}}"
                            data-style="ios">
                        </td>
                       
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                            <div class="dropdown py-3 px-1 ">  
                                <a class="btn btn-primary" href="{{route('admin.group.addgroupuser',[$group->id, $item->id, 'user'])}}" aria-expanded="false" data-tw-toggle="dropdown"> 
                                    Add
                                </a>
                            </div> 
                            </div>
                        </td>
                    </tr>

                    @endforeach
                    
                </tbody>
            </table>
            
        </div>
        @elseif($linkadd == 'blog')
        <!-- blog -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">Tiêu đề</th>
                        <th class="whitespace-nowrap">Mô tả</th>
                        <th class="whitespace-nowrap">Nội dung</th>
                        <th class="text-center whitespace-nowrap">Trạng thái</th>
                         
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($blog as $item)
                    <tr class="i.0ntro-x">
                        <td>
                             {{$item->title}} 
                        </td>
                        <td>
                            {{ $item->summary }}
                        </td>
                        <td class="text-left">{{$item->content}}</td>

                        <td class="text-center"> 
                            <input type="checkbox" 
                            data-toggle="switchbutton" 
                            data-onlabel="active"
                            data-offlabel="inactive"
                            {{$item->status=="active"?"checked":""}}
                            data-size="sm"
                            name="toogle"
                            value="{{$item->id}}"
                            data-style="ios">
                        </td>
                       
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                            <div class="dropdown py-3 px-1 ">  
                                <a class="btn btn-primary" href="{{route('admin.group.addgroupuser',[$group->id, $item->id, 'blog'])}}" aria-expanded="false" data-tw-toggle="dropdown"> 
                                    Add
                                </a>
                            </div> 
                            </div>
                        </td>
                    </tr>

                    @endforeach
                    
                </tbody>
            </table>
            
        </div>

        <!-- resource -->
        @elseif($linkadd == 'resource')
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">Mã tài nguyên</th>
                        <th class="whitespace-nowrap">Tài nguyên</th>
                        <th class="whitespace-nowrap">Nội dung</th>
                        <th class="text-center whitespace-nowrap">Trạng thái</th>
                         
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resource as $item)
                    <tr class="i.0ntro-x">
                        <td>
                             {{$item->type_code}} 
                        </td>
                        <td>
                            {{ $item->title}}
                        </td>
                        <td class="text-left">{{$item->description}}</td>

                        <td class="text-center"> 
                            <input type="checkbox" 
                            data-toggle="switchbutton" 
                            data-onlabel="inactive"
                            data-offlabel="active"
                            {{$item->status=="active"?"checked":""}}
                            data-size="sm"
                            name="toogle"
                            value="{{$item->id}}"
                            data-style="ios">
                        </td>
                       
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                            <div class="dropdown py-3 px-1 ">  
                                <a class="btn btn-primary" href="{{route('admin.group.addgroupuser',[$group->id, $item->id, 'resource'])}}" aria-expanded="false" data-tw-toggle="dropdown"> 
                                    Add
                                </a>
                            </div> 
                            </div>
                        </td>
                    </tr>

                    @endforeach
                    
                </tbody>
            </table>
            
        </div>
        @endif
    </div>
    <!-- END: HTML Table Data -->
        <!-- BEGIN: Pagination -->
        <!-- END: Pagination -->
</div>
@endsection
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
        var mode = $(this).prop('checked');
        var id=$(this).val();
        $.ajax({
            // admin.roles.store
            url:"",
            type:"post",
            data:{
                _token:'{{csrf_token()}}',
                mode:mode,
                id:id,
            },
            success:function(response){
                Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: response.msg,
                showConfirmButton: false,
                timer: 1000
                });
                console.log(response.msg);
            }
            
        });
  
});  
    
</script>
 
@endsection