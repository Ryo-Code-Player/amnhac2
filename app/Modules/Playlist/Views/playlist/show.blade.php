@extends('backend.layouts.master')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center py-4">
            <h3 class="card-title font-weight-bold">{{ $playlist->title }}</h3>
        </div>

        <div class="table-responsive">
            <table class="table table-borderless">
                <tbody>
                    <!-- Thông tin Playlist -->
                    <tr>
                        <td><strong><i class="fas fa-id-badge text-primary"></i> ID:</strong></td>
                        <td>{{ $playlist->id }}</td>
                    </tr>
                    <tr>
                        <td><strong><i class="fas fa-link text-primary"></i> Slug:</strong></td>
                        <td>{{ $playlist->slug }}</td>
                    </tr>
                    <tr>
                        <td><strong><i class="fas fa-music text-primary"></i> Các Bài Hát:</strong></td>
                        <td>
                            @if($songs->isNotEmpty())
                                @foreach($songs as $song)
                                    <span class="badge badge-info">{{ $song->title }}</span>
                                @endforeach
                            @else
                                <span>Không có bài hát nào trong playlist.</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong><i class="fas fa-toggle-on text-primary"></i> Loại Playlist:</strong></td>
                        <td>{{ $playlist->type == 'public' ? 'Công khai' : 'Riêng tư' }}</td>
                    </tr>
                    <tr>
                        <td><strong><i class="fas fa-calendar-alt text-primary"></i> Ngày tạo:</strong></td>
                        <td>{{ $playlist->created_at->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong><i class="fas fa-calendar-check text-primary"></i> Ngày cập nhật:</strong></td>
                        <td>{{ $playlist->updated_at->format('d/m/Y') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="card-footer text-center">
            <a href="{{ route('admin.playlist.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
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
            title: 'Thông tin Playlist!',
            text: 'Chi tiết playlist đã được hiển thị.',
            icon: 'info',
            confirmButtonText: 'OK'
        });
    });
</script>
@endsection
