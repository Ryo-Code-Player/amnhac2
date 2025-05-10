<style>
    * {
      box-sizing: border-box;
    }
    .body_slider {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
    .slider {
      position: relative;
      width: 80%;
      margin: 50px auto;
      overflow: hidden;
      border-radius: 10px;
    }
    .slides {
      display: flex;
      transition: transform 0.5s ease-in-out;
    }
    .slide {
      min-width: 100%;
      height: 300px;
      background-size: cover;
      background-position: center;
    }
    .controls {
      position: absolute;
      top: 50%;
      width: 100%;
      display: flex;
      justify-content: space-between;
      transform: translateY(-50%);
    }
    .control-btn {
      background: rgba(0,0,0,0.5);
      color: white;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
      font-size: 18px;
    }
    .control-btn:hover {
      background: rgba(0,0,0,0.7);
    }
    .dots {
      text-align: center;
      margin-top: 15px;
    }
    .dot {
      display: inline-block;
      width: 12px;
      height: 12px;
      margin: 0 5px;
      background-color: #bbb;
      border-radius: 50%;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .dot.active {
      background-color: #717171;
    }
</style>
<div class="body_slider">
    <div class="slider">
        <div class="slides">
          <div class="slide" style="background-image: url('https://picsum.photos/id/1015/800/300');"></div>
          <div class="slide" style="background-image: url('https://picsum.photos/id/1016/800/300');"></div>
          <div class="slide" style="background-image: url('https://picsum.photos/id/1018/800/300');"></div>
        </div>
        <div class="controls">
          <button class="control-btn" id="prev">&#10094;</button>
          <button class="control-btn" id="next">&#10095;</button>
        </div>
      </div>
      
      <div class="dots">
        <span class="dot active" data-index="0"></span>
        <span class="dot" data-index="1"></span>
        <span class="dot" data-index="2"></span>
      </div>
      
      <script>
        const slides = document.querySelector('.slides');
        const slide = document.querySelectorAll('.slide');
        const prev = document.getElementById('prev');
        const next = document.getElementById('next');
        const dots = document.querySelectorAll('.dot');
        let index = 0;
        let interval = setInterval(() => showSlide(index + 1), 3000);
      
        function showSlide(i) {
          index = (i + slide.length) % slide.length;
          slides.style.transform = `translateX(-${index * 100}%)`;
          updateDots();
        }
      
        function updateDots() {
          dots.forEach(dot => dot.classList.remove('active'));
          dots[index].classList.add('active');
        }
      
        prev.addEventListener('click', () => {
          showSlide(index - 1);
          resetInterval();
        });
      
        next.addEventListener('click', () => {
          showSlide(index + 1);
          resetInterval();
        });
      
        dots.forEach(dot => {
          dot.addEventListener('click', (e) => {
            showSlide(parseInt(e.target.getAttribute('data-index')));
            resetInterval();
          });
        });
      
        function resetInterval() {
          clearInterval(interval);
          interval = setInterval(() => showSlide(index + 1), 3000);
        }
      </script>
</div>