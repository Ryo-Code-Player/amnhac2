<style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }
    footer {
      background-color: #111;
      color: #fff;
      padding: 40px 20px;
    }
    .footer-container {
      max-width: 1060px;
      margin: 0 auto;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
    }
    .footer-logo {
      font-size: 24px;
      font-weight: bold;
    }
    .footer-links {
      display: flex;
      gap: 20px;
    }
    .footer-links a {
      color: #fff;
      text-decoration: none;
      transition: color 0.3s;
    }
    .footer-links a:hover {
      color: #f39c12;
    }
    .footer-social {
      display: flex;
      gap: 15px;
      margin-top: 15px;
    }
    .footer-social a {
      color: #fff;
      font-size: 20px;
      text-decoration: none;
      transition: color 0.3s;
    }
    .footer-social a:hover {
      color: #f39c12;
    }
    .footer-bottom {
      text-align: center;
      margin-top: 20px;
      font-size: 18px;
      color: #aaa;
      margin-left: auto;
      margin-right: auto;
    }
    .page-footer li{
      list-style: none;
      margin-top: 16px
    }
    .policy_box{
      width: 30%;
    }
</style>

<footer>
    <div class="footer-container">
      <div style="display:flex;justify-content: space-between;border-bottom: 1px solid white; padding-bottom:16px">
        <div style="width: 30%">
          <div class="footer-logo">🎵 MyMusic</div>
          <p><strong>MyMusic</strong> là nền tảng nghe nhạc trực tuyến nơi bạn có thể khám phá hàng nghìn bài hát thuộc nhiều thể loại khác nhau. 
            Tận hưởng âm nhạc mọi lúc, mọi nơi với chất lượng cao và trải nghiệm mượt mà.
          </p>
        </div>
        
        <div class="footer-links">
          <div class="page-footer">
              <div style="font-size: 16px; font-weight:600; color:#f39c12">Trang</div>
              <li><a href="">Trang chủ</a></li>
              <li><a href="/category">Thể loại</a></li>
              <li><a href="/zingchart">Bảng xếp hạng</a></li>
              <li><a href="/blog-index">Cộng đồng</a></li>
              <li><a href="/fanclub">Fanclub</a></li>
              <li><a href="/playlist">Playlist</a></li>
          </div>
          
          {{-- <a href="#">Contact</a>
          <a href="#">Privacy Policy</a> --}}
        </div>
        <div class="policy_box">
          <div style="font-size: 16px; font-weight:600; color:#f39c12">Chính sách bảo mật</div>
          <div style="margin-top:30px">MyMusic cam kết bảo mật mọi thông tin cá nhân của người dùng.</div>
          <div style="margin-top:6px">Tất cả các bài hát, hình ảnh, và nội dung trên website đều thuộc sở hữu của MyMusic hoặc đối tác cung cấp nội dung hợp pháp.</div>
          {{-- <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-youtube"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a> --}}
        </div>
      </div>

      <div class="footer-bottom">
        &copy; <span id="year"></span> MyMusic. All rights reserved.
      </div>
    </div>
</footer>

<script>
    document.getElementById('year').textContent = new Date().getFullYear();
  </script>