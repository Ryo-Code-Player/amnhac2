@extends('backend.layouts.master')
@section('content')

<div class="content">
    <h2 class="intro-y text-lg font-medium mt-10">
        Kết quả tìm kiếm nhạc sĩ
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
             
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$composers->currentPage()}} trong {{$composers->lastPage()}} trang</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
    <div class="relative text-slate-500">
        <form action="{{ route('admin.composer.search') }}" method="get" class="flex items-center">
            <input type="text" name="datasearch" value="{{ $searchdata }}" class="ipsearch form-control w-56 pr-10 py-2 rounded-lg border-gray-300" placeholder="Search...">
            <button type="submit" class="absolute right-0 top-1/2 transform -translate-y-1 p-2 bg-transparent border-none cursor-pointer">
                <i class="w-4 h-4 text-gray-500" data-lucide="search"></i>
            </button>
        </form>
    </div>
</div>

        </div>
    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
    <table class="table table-report -mt-2">
    <thead>
        <tr>
            <th style="width: 10%; text-align: center;" class="whitespace-nowrap">ID</th>
            <th style="width: 30%; text-align: center;" class="whitespace-nowrap">HỌ TÊN</th>
            <th style="width: 20%; text-align: center;" class="whitespace-nowrap">ẢNH</th>
            <th style="width: 20%; text-align: center;" class="whitespace-nowrap">TRẠNG THÁI</th>
            <th style="width: 20%; text-align: center;" class="whitespace-nowrap">HÀNH ĐỘNG</th>
        </tr>
    </thead>
    <tbody>
        @if ($composers->isEmpty())
        <tr>
            <td colspan="5" class="text-center py-4">
                <strong>Không có nhạc sĩ sáng tác nào hiển thị.</strong>
            </td>
        </tr>
        @else
        @foreach($composers as $composer)
        <tr class="intro-x">
            <td style="text-align: center;">
                <a href="{{ route('admin.composer.show', $composer->id) }}" class="font-medium whitespace-nowrap">{{ $composer->id }}</a>
            </td>
            <td style="text-align: center;">
                <a href="{{ route('admin.composer.show', $composer->id) }}" class="font-medium whitespace-nowrap">{{ $composer->fullname }}</a>
            </td>
            <td style="text-align: center;">
                <div style="display: flex; align-items: center; justify-content: center; height: 100%;">
                    <img style="border-radius: 50%; width: 40px; height: 40px; object-fit: cover;" src="{{ asset($composer->photo) }}" alt="Composer Photo">
                </div>
            </td>
            <td class="text-center"> 
                <input type="checkbox" 
                       data-toggle="switchbutton" 
                       data-onlabel="active"
                       data-offlabel="inactive"
                       {{$composer->status=="active"?"checked":""}}
                       data-size="sm"
                       name="toogle"
                       value="{{$composer->id}}"
                       data-style="ios">
            </td>
            <td style="text-align: center;">
                <div style="display: flex; align-items: center; justify-content: center;">
                    <a href="{{ route('admin.composer.edit', $composer->id) }}" class="flex items-center mr-3"> 
                        <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit
                    </a>
                    <form action="{{ route('admin.composer.destroy', $composer->id) }}" method="post" style="display: inline;">
                        @csrf
                        @method('delete')
                        <a class="flex items-center text-danger dltBtn" data-id="{{ $composer->id }}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> 
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
        {{ $composers->links('vendor.pagination.tailwind') }}
    </nav>
</div>
<!-- END: Pagination -->

@endsection
