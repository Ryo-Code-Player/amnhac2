@extends('backend.layouts.master')

@section('content')
<div class="p-6 border rounded-lg bg-white shadow-md max-w-3xl mx-auto mt-8">
    <h3 class="text-2xl font-bold mb-4 text-center">{{ $company->title }}</h3>
    
    <div class="flex items-center justify-center mb-4">
        <img src="{{ $company->photo }}" alt="{{ $company->title }} Logo" class="w-32 h-32 object-cover rounded-full border border-gray-300 shadow">
    </div>

    <div class="mb-4">
        <p><strong>ID:</strong> <span class="text-gray-600">{{ $company->id }}</span></p>
        <p><strong>Slug:</strong> <span class="text-gray-600">{{ $company->slug }}</span></p>
        <p><strong>Địa chỉ:</strong> <span class="text-gray-600">{{ $company->address }}</span></p>
        <p><strong>Trạng thái:</strong> <span class="text-gray-600">{{ $company->status }}</span></p>
        <p><strong>Điện thoại:</strong> <span class="text-gray-600">{{ $company->phone }}</span></p>
        <p><strong>Email:</strong> <span class="text-gray-600">{{ $company->email }}</span></p>
        <p><strong>Người dùng:</strong> <span class="text-gray-600">{{ optional($company->user)->full_name ?? 'N/A' }}</span></p>
        <p><strong>Ngày tạo:</strong> <span class="text-gray-600">{{ $company->created_at }}</span></p>
        <p><strong>Ngày cập nhật:</strong> <span class="text-gray-600">{{ $company->updated_at }}</span></p>
    </div>
    
    <div class="mb-4">
        <strong>Tóm tắt:</strong>
        <p class="text-gray-700">{{ $company->summary }}</p>
    </div>

    <div>
        <strong>Nội dung:</strong>
        <div class="bg-gray-100 p-4 rounded border border-gray-300">{!! $company->content !!}</div>
    </div>

    <div class="mb-4">
        <strong>Tài nguyên:</strong>
        <ul class="text-gray-700">
            @if($company->resources->isNotEmpty())
                @foreach($company->resources as $resource)
                    <li>{{ $resource->title }} (ID: {{ $resource->id }})</li>
                @endforeach
            @else
                <li>Không có tài nguyên nào.</li>
            @endif
        </ul>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('admin.musiccompany.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
    </div>
</div>
@endsection
