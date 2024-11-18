@extends('backend.layouts.master')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center py-4">
            <h3 class="card-title font-weight-bold">{{ $musicType->title }}</h3>
        </div>
       

            <div class="table-responsive">
                <table class="table table-borderless">
                    <tbody>
                        <!-- Thêm các thông tin về loại âm nhạc -->
                        <tr>
                            <td><strong><i class="fas fa-id-badge text-primary"></i> ID:</strong></td>
                            <td>{{ $musicType->id }}</td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-link text-primary"></i> Slug:</strong></td>
                            <td>{{ $musicType->slug }}</td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-toggle-on text-primary"></i> Trạng thái:</strong></td>
                            <td><span class="badge badge-{{ $musicType->status == 'active' ? 'success' : 'danger' }}">{{ $musicType->status }}</span></td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-calendar-alt text-primary"></i> Ngày tạo:</strong></td>
                            <td>{{ $musicType->created_at->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-calendar-check text-primary"></i> Ngày cập nhật:</strong></td>
                            <td>{{ $musicType->updated_at->format('d/m/Y') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            

           
        <div class="card-footer text-center">
            <a href="{{ route('admin.musictype.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
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
            title: 'Thông tin Loại Âm nhạc!',
            text: 'Chi tiết loại âm nhạc đã được hiển thị.',
            icon: 'info',
            confirmButtonText: 'OK'
        });
    });
</script>
@endsection
