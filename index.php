<?php



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $title = "Bloodbank | Index"; ?>
    <?php require 'head.php'; ?>
    <style>
        :root {
            --primary-red: #C41E3A;
            --secondary-blue: #2C5F8B;
            --background-light: #F8F9FA;
            --white: #FFF;
            --gray-light: #E9ECEF;
        }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--background-light);
            overflow-x: hidden;
        }

        /* Canvas for floating blood drops */
        canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .overlay {
            background: rgba(69, 69, 69, 0.9);
            position: relative;
            z-index: 1;
            min-height: 100vh;
        }

        .container.cont {
            margin-top: 36px;
            margin-bottom: 36px;
            position: relative;
            z-index: 2;
        }

        .main-image-card img {
            border-radius: 18px;
            box-shadow: 0 8px 36px rgba(44, 95, 139, 0.11);
            margin-bottom: 2.2rem;
            width: 100%;
            max-height: 320px;
            object-fit: cover;
        }

        /* Cards */
        .card {
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(44, 95, 139, .07);
            border: none;
            background: var(--white);
        }

        .card-header {
            background: var(--primary-red);
            color: #fff;
            font-weight: 700;
            letter-spacing: .04em;
            border-radius: 12px 12px 0 0;
        }

        .health-tips .card-header {
            background: var(--secondary-blue);
        }

        dt {
            font-weight: bold;
            color: var(--primary-red);
        }

        dd {
            margin-left: 18px;
            margin-bottom: .8rem;
            color: #373737;
        }

        .card-body {
            font-size: 1.06rem;
        }

        /* Hero buttons */
        .blood-flow-hero {
    position: relative;
    height: 480px;
    overflow: hidden;
    border-radius: 20px;
    max-width: 1100px;
    margin: 50px auto;
    box-shadow: 0 8px 30px rgba(18, 14, 14, 0.2);
  }

  #bloodCanvas {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    pointer-events: none;
  }

  .hero-overlay {
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.3);
    top: 0;
    left: 0;
    z-index: 1;
  }

  .hero-text {
    position: relative;
    z-index: 2;
    text-align: center;
    top: 35%;
    color: #e70000ff;
    animation: floatText 3s ease-in-out infinite;
  }

  .hero-text h1 {
    font-size: 3rem;
    font-weight: 700;
    text-shadow: 0 4px 20px rgba(0,0,0,0.6);
  }

  .hero-text p {
    font-size: 1.3rem;
    margin: 15px 0;
  }

        .btn-lg {
            padding: 15px 35px;
            margin: 10px;
            border-radius: 30px;
            font-weight: bold;
            letter-spacing: 1px;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.25);
        }

        .btn-lg:hover {
            transform: scale(1.1);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
        }

        /* Responsive adjustments */
        @media (max-width: 900px) {
            .main-image-card img {
                max-height: 180px;
            }

            .container.cont {
                margin: 12px 2px;
            }
        }
        .btn-donate {
    display: inline-block;
    padding: 18px 45px;
    background: linear-gradient(45deg, #C41E3A, #FF4D4D);
    color: #fff;
    font-size: 1.2rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-radius: 50px;
    border: none;
    box-shadow: 0 8px 20px rgba(196,30,58,0.5);
    transition: all 0.3s ease-in-out;
    position: relative;
    overflow: hidden;
}

.btn-donate::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,0.2);
    transform: skewX(-20deg);
    transition: all 0.5s ease-in-out;
}

.btn-donate:hover::before {
    left: 200%;
}

.btn-donate:hover {
    transform: scale(1.1);
    box-shadow: 0 12px 25px rgba(196,30,58,0.6);
}

    </style>
</head>

<body>
    <canvas id="bloodCanvas"></canvas>
    <div class="overlay">
        <?php require 'header.php'; ?>
        <div class="container cont">
            <?php require 'message.php'; ?>

            <!-- Hero image -->
            
<!-- Hero Blood Flow Animation -->
<section class="blood-flow-hero">
  <canvas id="bloodCanvas"></canvas>
  <div class="hero-overlay"></div>
  <div class="hero-text">
    <h1>Donate Blood, Save Lives 🩸</h1>
    <p>Your little contribution can make a big difference!</p>
    <a href="login.php" class="btn-donate ">Donate Now</a>

  </div>
</section>


            <!-- Blood group compatibility -->
            <div class="row">
                <div class="col-12 mb-3">
                    <h2 class="text-center" style="color:var(--primary-red);font-weight:700;margin-bottom:2rem;">
                        Blood Group Compatibility
                    </h2>
                </div>
                <?php
                $compatibility = [
                    ["A+", "If you are A+: You can give blood to A+ and AB+. You can receive blood from A+, A-, O+ and O-."],
                    ["A-", "If you are A-: You can give blood to A-, A+, AB- and AB+. You can receive blood from A- and O-."],
                    ["B+", "If you are B+: You can give blood to B+ and AB+. You can receive blood from B+, B-, O+ and O-."],
                    ["B-", "If you are B-: You can give blood to B-, B+, AB+ and AB-. You can receive blood from B- and O-."],
                    ["AB+", "AB+ people can receive from any blood type. They can donate with AB+ only."],
                    ["AB-", "AB- patients can receive from all negative types. AB- can give to AB-, AB+."],
                    ["O+", "O+ can donate to A+, B+, AB+ and O+. O group can donate red blood cells to anybody (universal donor)."],
                    ["O-", "O- can donate to all blood types. O- people can only receive from O- donors."]
                ];
                foreach ($compatibility as $bg) {
                    echo '
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
                        <div class="card h-100">
                            <div class="card-header text-center">' . $bg[0] . '</div>
                            <div class="card-body">' . $bg[1] . '</div>
                        </div>
                    </div>';
                }
                ?>
            </div>

            <!-- Health Tips -->
            <div class="row mb-5 mt-4">
                <div class="col-lg-12">
                    <div class="card health-tips">
                        <div class="card-header">Health Tips</div>
                        <div class="card-body">
                            <dl>
                                <dt>Eat a healthy diet</dt>
                                <dd>Eat a combination of different foods, including fruit, vegetables, legumes, nuts and whole grains...</dd>
                                <dt>Consume less salt and sugar</dt>
                                <dd>Consuming excessive sugars increases the risk of tooth decay and unhealthy weight gain...</dd>
                                <dt>Be active</dt>
                                <dd>Physical activity is defined as any bodily movement produced by skeletal muscles that requires energy expenditure...</dd>
                                <dt>Don’t smoke</dt>
                                <dd>Smoking tobacco causes NCDs such as lung disease, heart disease and stroke...</dd>
                                <dt>Drink only safe water</dt>
                                <dt>Get tested</dt>
                                <dt>Follow traffic laws</dt>
                                <dt>Talk to someone you trust if you're feeling down</dt>
                                <dt>Clean your hands properly</dt>
                                <dt>Have regular check-ups</dt>
                            </dl>
                            <div>
                                For more health tips, visit
                                <a href="https://www.who.int/philippines/news/feature-stories/detail/20-health-tips-for-2020" target="_blank">
                                    <b>World Health Organisation</b>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <?php require 'footer.php'; ?>
    </div>

    <!-- Floating blood drops animation -->
    <script>
        const canvas = document.getElementById("bloodCanvas");
        const ctx = canvas.getContext("2d");
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        let drops = [];

        class Drop {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.radius = Math.random() * 8 + 5;
                this.speed = Math.random() * 2 + 1;
                this.color = "rgba(196,30,58,0.7)";
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                ctx.fillStyle = this.color;
                ctx.fill();
            }

            update() {
                this.y += this.speed;
                if (this.y > canvas.height) {
                    this.y = 0 - this.radius;
                    this.x = Math.random() * canvas.width;
                }
                this.draw();
            }
        }

        function init() {
            drops = [];
            for (let i = 0; i < 50; i++) {
                drops.push(new Drop());
            }
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            drops.forEach(drop => drop.update());
            requestAnimationFrame(animate);
        }

        init();
        animate();

        window.addEventListener("resize", () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            init();
        });
    </script>
    <!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  

  // Canvas animation
  const canvas = document.getElementById("bloodCanvas");
  if (!canvas) return;
  const ctx = canvas.getContext("2d");
  let dpr = window.devicePixelRatio || 1;
  let cssW = 0, cssH = 0;
  let bloodCells = [];
  let animationId = null;
  const CELL_COUNT = 40;

  function setSize() {
    dpr = window.devicePixelRatio || 1;
    cssW = canvas.clientWidth || canvas.offsetWidth || 600;
    cssH = canvas.clientHeight || canvas.offsetHeight || 480;
    canvas.width = Math.round(cssW * dpr);
    canvas.height = Math.round(cssH * dpr);
    canvas.style.width = cssW + "px";
    canvas.style.height = cssH + "px";
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  }

  function createCells(count = CELL_COUNT) {
    bloodCells = [];
    for (let i = 0; i < count; i++) {
      bloodCells.push({
        x: Math.random() * cssW,
        y: Math.random() * cssH,
        r: 10 + Math.random() * 12,
        dx: 0.6 + Math.random() * 2.0,
        dy: 0.2 + Math.random() * 0.9,
        opacity: 0.6 + Math.random() * 0.4,
        angle: Math.PI / 4 + (Math.random() - 0.5) * 0.6
      });
    }
  }

  function drawEllipse(x, y, rx, ry, rotation) {
    if (typeof ctx.ellipse === 'function') {
      ctx.ellipse(x, y, rx, ry, rotation, 0, Math.PI * 2);
    } else {
      ctx.save();
      ctx.translate(x, y);
      ctx.rotate(rotation);
      ctx.scale(rx / ry, 1);
      ctx.beginPath();
      ctx.arc(0, 0, ry, 0, Math.PI * 2);
      ctx.restore();
    }
  }

  function drawFrame() {
    ctx.clearRect(0, 0, cssW, cssH);
    const gradient = ctx.createLinearGradient(0, 0, cssW, cssH);
    gradient.addColorStop(0, "#C41E3A");
    gradient.addColorStop(1, "#2C5F8B");
    ctx.fillStyle = gradient;
    ctx.fillRect(0, 0, cssW, cssH);

    for (let cell of bloodCells) {
      ctx.beginPath();
      ctx.fillStyle = `rgba(255,0,0,${cell.opacity})`;
      drawEllipse(cell.x, cell.y, cell.r * 1.3, cell.r, cell.angle);
      ctx.fill();
      cell.x += cell.dx;
      cell.y += cell.dy;
      if (cell.x - cell.r > cssW) cell.x = -cell.r;
      if (cell.y - cell.r > cssH) cell.y = -cell.r;
      if (cell.y + cell.r < 0) cell.y = cssH + cell.r;
    }

    animationId = requestAnimationFrame(drawFrame);
  }

  function start() {
    if (animationId) cancelAnimationFrame(animationId);
    drawFrame();
  }

  let resizeTimer = null;
  function handleResize() {
    if (resizeTimer) clearTimeout(resizeTimer);
    if (animationId) cancelAnimationFrame(animationId);
    resizeTimer = setTimeout(() => {
      setSize();
      createCells(CELL_COUNT);
      start();
    }, 100);
  }

  setSize();
  createCells(CELL_COUNT);
  start();
  window.addEventListener('resize', handleResize);
});
</script>
</body>
</html>
