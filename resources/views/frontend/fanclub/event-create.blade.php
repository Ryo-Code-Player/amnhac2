@extends('frontend.layouts.master')
@section('content')
<style>
    body {
        background: #18162b;
    }
    .container {
        max-width: 700px;
        margin: 40px auto;
        background: #232042;
        border-radius: 14px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.10);
        padding: 32px 28px 24px 28px;
    }
    h2 {
        text-align: center;
        margin-bottom: 28px;
        color: #fff;
        font-weight: 700;
        letter-spacing: 1px;
    }
    .form-row {
        display: flex;
        gap: 40px;
        flex-wrap: wrap;
    }
    .form-col {
        flex: 1 1 0;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 18px;
        padding-right: 25px;
    }
    .form-label {
        margin-bottom: 6px;
        font-weight: 500;
        color: #b3b8d0;
    }
    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #35315a;
        border-radius: 6px;
        font-size: 1rem;
        background: #1a1831;
        color: #e6e8f3;
        margin-bottom: 0;
        transition: border 0.2s, background 0.2s;
    }
    .form-control:focus {
        border: 1.5px solid #7c3aed;
        outline: none;
        background: #232042;
    }
    textarea.form-control {
        min-height: 90px;
        resize: vertical;
    }
    .btn-primary {
        width: 100%;
        background: linear-gradient(90deg, #7c3aed 0%, #38b6ff 100%);
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 12px 0;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        margin-top: 18px;
        transition: background 0.2s;
        box-shadow: 0 2px 8px rgba(60,40,120,0.10);
    }
    .btn-primary:hover {
        background: linear-gradient(90deg, #38b6ff 0%, #7c3aed 100%);
    }
    .img-preview {
        display: block;
        margin: 10px 0 0 0;
        max-width: 100%;
        max-height: 180px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(60,40,120,0.10);
        background: #18162b;
        object-fit: cover;
    }
    @media (max-width: 900px) {
        .container { padding: 18px 6px; }
        .form-row { flex-direction: column; gap: 0; }
        .form-col { gap: 18px; }
    }
</style>
<div class="container">
    <h2>Tạo sự kiện mới cho Fanclub</h2>
    <form action="{{ route('front.fanclub.event.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="fanclub_id" value="{{ $slug }}">
        <div class="form-row">
            <div class="form-col">
                <label for="image" class="form-label">Hình ảnh sự kiện</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required onchange="previewImage(event)">
                <img id="imgPreview" class="img-preview" style="display:none;" alt="Preview">

                <label for="title" class="form-label">Tên sự kiện</label>
                <input type="text" class="form-control" id="title" name="title" required>

               
                <label for="price" class="form-label">Giá vé (VNĐ)</label>
                <input type="number" class="form-control" id="price" name="price" min="0" required>
            </div>
            <div class="form-col">
                
                <label for="location" class="form-label">Địa điểm</label>
                <textarea class="form-control" id="location" name="location" required></textarea>
                <label for="quantity" class="form-label">Số lượng vé</label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>

                <label for="timestart" class="form-label">Thời gian bắt đầu</label>
                <input type="datetime-local" class="form-control" id="timestart" name="timestart" required>

                <label for="timeend" class="form-label">Thời gian kết thúc</label>
                <input type="datetime-local" class="form-control" id="timeend" name="timeend" required>
            </div>
        </div>
        <div>
            <label for="description" class="form-label">Mô tả sự kiện</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>
        <button type="submit" class="btn-primary">Tạo sự kiện</button>
    </form>
</div>
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('imgPreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }
    CKEDITOR.replace('description', {
        height: 150,
        removeButtons: 'PasteFromWord'
    });
</script>
@endsection

