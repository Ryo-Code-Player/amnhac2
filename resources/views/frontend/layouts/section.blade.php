<div class="main">
    <!-- Header -->
    <header>
        <div class="search" style="opacity: 0">
            <input type="text" placeholder="Tìm kiếm bài hát, nghệ sĩ..." id="search-input" autocomplete="off">
            <i class="fas fa-search"></i>
            <div class="search-suggest" id="search-suggest"></div>
        </div>
        @if(auth()->check())
            <div class="user-dropdown" style="position: relative; display: inline-block;">
                <div id="user-toggle" style="display: flex; align-items: center; cursor: pointer;">
                    <img src="{{auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : 'https://i.pinimg.com/736x/bc/43/98/bc439871417621836a0eeea768d60944.jpg' }}" alt="User" style="width:36px;height:36px;border-radius:50%;border:2px solid #7200a1;object-fit:cover;">
                    <span style='margin-left:8px;'>{{ auth()->user()->email }}</span>
                    <i class="fas fa-caret-down" style="margin-left: 6px;"></i>
                </div>
                <div class="dropdown-content" id="user-dropdown-content" style="display:none;position:absolute;right:0;background:#fff;min-width:160px;box-shadow:0 8px 16px rgba(0,0,0,0.2);z-index:1;border-radius:6px;overflow:hidden;">
                    <a href="" style="color:#333;padding:12px 16px;display:block;text-decoration:none;">Quản lý thông tin</a>
                    <a href="#" id="event-manage-link" style="color:#333;padding:12px 16px;display:block;text-decoration:none;">Quản lý đăng kí sự kiện</a>

                    <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" style="width:100%;background:none;border:none;color:#333;padding:12px 16px;text-align:left;cursor:pointer;">Đăng xuất</button>
                    </form>
                </div>
            </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var toggle = document.getElementById('user-toggle');
                var dropdown = document.getElementById('user-dropdown-content');
                toggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
                });
                document.addEventListener('click', function() {
                    dropdown.style.display = 'none';
                });
            });
        </script>
        @else
        <div class="user" id="user-info">
            <button id="login-btn" style="background:none;border:none;color:#fff;font-size:16px;cursor:pointer;">Đăng nhập</button>
        </div>
        @endif
    </header>

    <!-- Content -->
    @yield('content')

    <!-- Popup Thông tin tài khoản -->
    @if(auth()->check())
        <div id="profile-modal">
            <div class="profile-modal-content">
                <button id="close-profile-modal">&times;</button>
                <h2>Thông tin tài khoản</h2>
                <form id="profile-form" method="POST" action="{{ route('FE.updateprofile_user') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ auth()->user()->id }}">
                    <div style="text-align:center;margin-bottom:18px;">
                        <img id="profile-avatar-preview" src="{{auth()->user()->photo ?  asset('storage/' . auth()->user()->photo) : 'https://i.pinimg.com/736x/bc/43/98/bc439871417621836a0eeea768d60944.jpg' }}" alt="Avatar">
                        <br>
                        <input type="file" name="avatar" id="profile-avatar" accept="image/*" style="margin-top:8px;">
                    </div>
                    <div style="margin-bottom:12px;">
                        <label>Họ tên người dùng</label>
                        <input type="text" name="name" value="{{ auth()->user()->full_name }}" class="form-control" required>
                    </div>
                    <div style="margin-bottom:12px;">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" class="form-control" disabled>
                    </div>
                    <div style="margin-bottom:18px;">
                        <label>Số điện thoại</label>
                        <input type="text" name="phone" value="{{ auth()->user()->phone ?? '' }}" class="form-control">
                    </div>
                    <div style="margin-bottom:12px;">
                        <label>Mật khẩu mới</label>
                        <input type="password" name="password" class="form-control" placeholder="Để trống nếu không đổi">
                    </div>
                    <button type="submit">Cập nhật</button>
                </form>
            </div>
        </div>
    @endif
    <script>
       
        document.addEventListener('DOMContentLoaded', function() {
            var profileBtn = document.querySelector('.dropdown-content a');
            var profileModal = document.getElementById('profile-modal');
            var closeProfile = document.getElementById('close-profile-modal');
            if(profileBtn && profileModal && closeProfile) {
                profileBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    profileModal.style.display = 'flex';
                });
                closeProfile.addEventListener('click', function() {
                    profileModal.style.display = 'none';
                });
                profileModal.addEventListener('click', function(e) {
                    if(e.target === profileModal) profileModal.style.display = 'none';
                });
            }
            // Preview avatar
            var avatarInput = document.getElementById('profile-avatar');
            var avatarPreview = document.getElementById('profile-avatar-preview');
            if(avatarInput && avatarPreview) {
                avatarInput.addEventListener('change', function(e) {
                    if(this.files && this.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(ev) {
                            avatarPreview.src = ev.target.result;
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }
            // Sự kiện mở popup quản lý đăng ký sự kiện
            var eventLink = document.getElementById('event-manage-link');
            var eventModal = document.getElementById('event-modal');
            var closeEvent = document.getElementById('close-event-modal');
            var eventTableBody = document.querySelector('#event-table tbody');

            if(eventLink && eventModal && closeEvent && eventTableBody) {
                eventLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Gọi AJAX lấy dữ liệu
                    @if(auth()->check())
                    fetch(`/api/user/event-registrations?id={{ auth()->user()->id }}`)
                        .then(response => response.json())
                        .then(data => {
                            eventTableBody.innerHTML = '';
                            if(data.length === 0) {
                                eventTableBody.innerHTML = '<tr><td colspan="4" style="text-align:center;">Không có dữ liệu</td></tr>';
                            } else {
                                data.forEach(function(item) {
                            var codeDisplay = item.code ? item.code : 'Chưa có mã giao dịch';

                                    var row = `<tr>
                                        <td> ${codeDisplay} </td>
                                        <td>${item.user.full_name}</td>
                                        <td>${item.event.title}</td>
                                        <td>${item.event.timestart_event}</td>
                                        <td>${item.event.timeend_event}</td>

                                        <td>${item.role.title}</td>
                                        <td>${item.created_at_format}</td>
                                    </tr>`;
                                    eventTableBody.innerHTML += row;
                                });
                            }
                            eventModal.style.display = 'flex';
                        })
                        .catch(err => {
                            eventTableBody.innerHTML = '<tr><td colspan="4" style="text-align:center;color:red;">Lỗi tải dữ liệu</td></tr>';
                            eventModal.style.display = 'flex';
                        });
                    @endif
                });
                closeEvent.addEventListener('click', function() {
                    eventModal.style.display = 'none';
                });
                eventModal.addEventListener('click', function(e) {
                    if(e.target === eventModal) eventModal.style.display = 'none';
                });
            }
        });
    </script>
</div>

<div id="event-modal">
    <div class="event-modal-content">
        <button id="close-event-modal">&times;</button>
        <h2>Danh sách đăng ký sự kiện</h2>
        <table id="event-table">
            <thead>
                <tr>
                    <th>Mã giao dịch</th>
                    <th>Tên khách hàng</th>
                    <th>Sự kiện</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Vai trò</th>
                    <th>Ngày đăng ký</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dữ liệu sẽ được render ở đây -->
            </tbody>
        </table>
    </div>
</div>

<style>
#profile-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0; top: 0;
    width: 100vw; height: 100vh;
    background: rgba(24, 13, 38, 0.85);
    align-items: center;
    justify-content: center;
}
#profile-modal .profile-modal-content {
    background: #23113a;
    border-radius: 24px;
    min-width: 340px;
    max-width: 95vw;
    padding: 32px 24px 24px 24px;
    position: relative;
    box-shadow: 0 8px 32px rgba(0,0,0,0.25);
    color: #fff;
    font-family: inherit;
}
#close-profile-modal {
    position: absolute;
    top: 18px; right: 18px;
    background: none;
    border: none;
    font-size: 28px;
    color: #fff;
    cursor: pointer;
}
#profile-modal h2 {
    margin-bottom: 18px;
    text-align: center;
    font-size: 22px;
    font-weight: bold;
    color: #fff;
}
#profile-modal input[type="text"],
#profile-modal input[type="email"],
#profile-modal input[type="password"] {
    width: 100%;
    padding: 12px 16px;
    border-radius: 12px;
    border: none;
    background: #f1f4ff;
    color: #23113a;
    margin-bottom: 16px;
    font-size: 16px;
    outline: none;
    box-sizing: border-box;
}
#profile-modal label {
    display: block;
    margin-bottom: 6px;
    color: #fff;
    font-size: 15px;
    font-weight: 500;
}
#profile-modal button[type="submit"] {
    width: 100%;
    background: #a259e6;
    color: #fff;
    padding: 12px 0;
    border: none;
    border-radius: 12px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    margin-top: 8px;
    transition: background 0.2s;
}
#profile-modal button[type="submit"]:hover {
    background: #7200a1;
}
#profile-avatar-preview {
    width: 80px; height: 80px;
    border-radius: 50%;
    border: 2px solid #a259e6;
    object-fit: cover;
    margin-bottom: 8px;
}
#event-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0; top: 0;
    width: 100vw; height: 100vh;
    background: rgba(24, 13, 38, 0.85);
    align-items: center;
    justify-content: center;
}
#event-modal .event-modal-content {
    background: #fff;
    border-radius: 24px;
    min-width: 540px;
    max-width: 95vw;
    padding: 32px 24px 24px 24px;
    position: relative;
    box-shadow: 0 8px 32px rgba(0,0,0,0.25);
    color: #23113a;
    font-family: inherit;
}
#close-event-modal {
    position: absolute;
    top: 18px; right: 18px;
    background: none;
    border: none;
    font-size: 28px;
    color: #23113a;
    cursor: pointer;
}
#event-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 18px;
}
#event-table th, #event-table td {
    border: 1px solid #ddd;
    padding: 8px 12px;
    text-align: left;
}
#event-table th {
    background: #a259e6;
    color: #fff;
}
</style>