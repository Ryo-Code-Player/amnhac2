@extends('backend.layouts.master')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center py-4">
            <h3 class="card-title font-weight-bold">Thêm Tag Mới</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.tag.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="title">Tiêu Đề Tag</label> <!-- Đổi từ "Tên Tag" thành "Tiêu Đề Tag" -->
                    <input type="text" name="title" id="title" class="form-control" required> <!-- Đổi từ name thành title -->
                </div>

              
                <button type="submit" class="btn btn-success">Thêm Tag</button>
                <a href="{{ route('admin.tag.index') }}" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>
@endsection
