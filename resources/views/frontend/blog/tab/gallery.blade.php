<div class="tab-content" id="tab-gallery" style="display:none;">
    <div class="profile-update-modern"
        style="max-width:700px;margin:40px auto;background:#fff;padding:36px 32px 32px 32px;border-radius:18px;box-shadow:0 4px 32px #0001;">
        <h2 style="margin-bottom:28px;text-align:center;font-weight:700;font-size:2em;color:#222;">Cập nhật thông tin cá
            nhân</h2>
        <form id="profile-update-form" enctype="multipart/form-data"
            style="display:flex;flex-direction:column;align-items:center;gap:18px;">
            @csrf
            <div style="position:relative;margin-bottom:18px;">
                <div id="profile-photo-preview"
                    style="width:120px;height:120px;border-radius:50%;overflow:hidden;box-shadow:0 2px 16px #0002;display:flex;align-items:center;justify-content:center;background:#f4f6fb;">
                    <img src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://i.pinimg.com/736x/bc/43/98/bc439871417621836a0eeea768d60944.jpg' }}"
                        alt="Avatar" id="profile-photo-img" style="width:100%;height:100%;object-fit:cover;">
                </div>
                <label for="profile-photo-input"
                    style="position:absolute;bottom:0;right:0;background:#1877f2;color:#fff;border-radius:50%;width:38px;height:38px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px #0002;cursor:pointer;border:3px solid #fff;">
                    <i class="fa fa-camera" style="font-size:1.3em;"></i>
                    <input type="file" name="photo" id="profile-photo-input" accept="image/*"
                        style="display:none;">
                </label>
            </div>
            <div style="width:100%;">
                <label style="font-weight:500;color:#444;margin-bottom:6px;display:block;">Tên người dùng</label>
                <input type="text" name="name" class="form-control" value="{{ $user->full_name }}"
                    style="width:95%;padding:12px 16px;border-radius:10px;border:1.5px solid #e0e6ed;font-size:1.08em;background:#f8fafc;">
            </div>
            <div style="width:100%;">
                <label style="font-weight:500;color:#444;margin-bottom:6px;display:block;">Email</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}"
                    style="width:95%;padding:12px 16px;border-radius:10px;border:1.5px solid #e0e6ed;font-size:1.08em;background:#f8fafc;"
                    disabled>
            </div>
            <div style="width:100%;">
                <label style="font-weight:500;color:#444;margin-bottom:6px;display:block;"><i class='fa fa-phone'
                        style='margin-right:6px;'></i> Số điện thoại</label>
                <input type="text" name="phone" class="form-control" value="{{ $user->phone ?? '' }}"
                    style="width:95%;
          padding:12px 16px;border-radius:10px;border:1.5px solid #e0e6ed;font-size:1.08em;background:#f8fafc;">
            </div>
            <div style="width:100%;">
                <label style="font-weight:500;color:#444;margin-bottom:6px;display:block;">Địa chỉ</label>
                <input type="text" name="address" class="form-control" value="{{ $user->taxaddress ?? '' }}"
                    style="width:95%;padding:12px 16px;border-radius:10px;border:1.5px solid #e0e6ed;font-size:1.08em;background:#f8fafc;">
            </div>
            <div style="width:100%;">
                <label style="font-weight:500;color:#444;margin-bottom:6px;display:block;">Mật khẩu (Không thay đổi mật
                    khẩu hãy bỏ trống)</label>
                <input type="password" name="password" class="form-control"
                    style="width:95%;padding:12px 16px;border-radius:10px;border:1.5px solid #e0e6ed;font-size:1.08em;background:#f8fafc;">
            </div>
            <div style="width:100%;">
                <label style="font-weight:500;color:#444;margin-bottom:6px;display:block;">Mô tả</label>
                <textarea name="description" class="form-control" rows="3"
                    style="width:95%;
          padding:12px 16px;border-radius:10px;border:1.5px solid #e0e6ed;font-size:1.08em;background:#f8fafc;resize:vertical;">{{ $user->description ?? '' }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary"
                style="margin-top:10px;background:#1877f2;color:#fff;padding:12px 0;width:70%;border:none;border-radius:10px;font-size:1.15em;font-weight:600;box-shadow:0 2px 8px #0001;transition:background 0.2s;">Lưu
                thay đổi</button>
        </form>
    </div>
</div>
