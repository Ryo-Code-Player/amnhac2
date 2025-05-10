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
      font-size: 14px;
      color: #aaa;
    }
</style>

<footer>
    <div class="footer-container">
      <div class="footer-logo">🎵 MyMusic</div>
      <div class="footer-links">
        <a href="#">About</a>
        <a href="#">Contact</a>
        <a href="#">Privacy Policy</a>
      </div>
      <div class="footer-social">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-youtube"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
      </div>
    </div>
    <div class="footer-bottom">
      &copy; <span id="year"></span> MyMusic. All rights reserved.
    </div>
</footer>

<script>
    document.getElementById('year').textContent = new Date().getFullYear();
  </script>