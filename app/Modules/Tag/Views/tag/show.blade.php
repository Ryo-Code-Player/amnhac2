@extends('backend.layouts.master')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center py-4">
            <h3 class="card-title font-weight-bold">{{ $company->title }}</h3>
        </div>
        <div class="card-body">
            <div class="text-center mb-4">
                <img src="{{ asset($company->photo) }}" alt="{{ $company->title }} Logo" class="rounded-circle border border-secondary shadow-sm" style="width: 120px; height: 120px;">
            </div>

            <div class="table-responsive">
                <table class="table table-borderless">
                    <tbody>
                        <!-- Thêm các thông tin về công ty -->
                        <tr>
                            <td><strong><i class="fas fa-id-badge text-primary"></i> ID:</strong></td>
                            <td>{{ $company->id }}</td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-link text-primary"></i> Slug:</strong></td>
                            <td>{{ $company->slug }}</td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-map-marker-alt text-primary"></i> Địa chỉ:</strong></td>
                            <td>{{ $company->address }}</td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-toggle-on text-primary"></i> Trạng thái:</strong></td>
                            <td><span class="badge badge-{{ $company->status == 'active' ? 'success' : 'danger' }}">{{ $company->status }}</span></td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-phone text-primary"></i> Điện thoại:</strong></td>
                            <td>{{ $company->phone }}</td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-envelope text-primary"></i> Email:</strong></td>
                            <td>{{ $company->email }}</td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-user text-primary"></i> Người dùng:</strong></td>
                            <td>{{ optional($company->user)->full_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-calendar-alt text-primary"></i> Ngày tạo:</strong></td>
                            <td>{{ $company->created_at->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-calendar-check text-primary"></i> Ngày cập nhật:</strong></td>
                            <td>{{ $company->updated_at->format('d/m/Y') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mb-4">
                <h5 class="text-primary"><i class="fas fa-book-open"></i> Tóm tắt:</h5>
                <p class="text-muted">{{ $company->summary }}</p>
            </div>

            <div class="mb-4">
                <h5 class="text-primary"><i class="fas fa-file-alt"></i> Nội dung:</h5>
                <div class="bg-light p-3 border rounded">
                    {!! $company->content !!}
                </div>
            </div>

            <div class="mb-4">
                <h5 class="text-primary"><i class="fas fa-archive"></i> Tài nguyên:</h5>
                <ul class="list-group">
                    @if($company->resources->isNotEmpty())
                        @foreach($company->resources as $resource)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $resource->title }}
                                <span class="badge badge-primary badge-pill">ID: {{ $resource->id }}</span>
                            </li>
                        @endforeach
                    @else
                        <li class="list-group-item text-muted">Không có tài nguyên nào.</li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="card-footer text-center">
            <a href="{{ route('admin.musiccompany.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Thông tin Công ty Âm nhạc!',
            text: 'Chi tiết công ty đã được hiển thị.',
            icon: 'info',
            confirmButtonText: 'OK'
        });
    });
</script>
@endsection
