@extends('backend.layouts.master')
@section('content')
<div class="container">
    <h1>Edit Comment</h1>

    <form action="{{ route('admin.comments.update', $comment->id) }}" method="POST"> <!-- Sử dụng route đúng -->
    @csrf
    @method('PUT') <!-- Giữ PUT để cập nhật -->

    <div class="form-group">
        <label for="content">Content</label>
        <textarea name="content" id="content" class="form-control">{{ old('content', $comment->content) }}</textarea> <!-- Sử dụng old() để giữ dữ liệu cũ -->
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>

</div>
@endsection
