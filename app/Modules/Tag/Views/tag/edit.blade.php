@extends('backend.layouts.master')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center py-4">
            <h3 class="card-title font-weight-bold">Sửa Tag</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.tag.update', $tag->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="title">Tên Tag</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ $tag->name }}" required>
                </div>

 

                <button type="submit" class="btn btn-success">Cập Nhật Tag</button>
                <a href="{{ route('admin.tag.index') }}" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>
@endsection
