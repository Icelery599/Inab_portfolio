<?php
require_once __DIR__ . '/data_store.php';

initialize_database();

$services = read_collection('services');
$projects = read_collection('projects');
$posts = read_collection('posts');
$feedback = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'contact') {
  add_item('messages', [
    'name' => trim($_POST['name'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'message' => trim($_POST['message'] ?? ''),
  ]);
  $feedback = 'Thanks! Your message has been received.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>INAB COMPUTER</title>
  <!-- Google Fonts + Font Awesome 6 -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="shortcut icon" href="logo.png" type="image/x-icon">
 <link rel="stylesheet" href="stylez.css">
</head>
<body>

<header>
  <div class="container">
    <div class="navbar">
      <div class="logo"><Img src="logo.png"> INAB COMPUTER</div>
      <button class="hamburger" id="hamburger" aria-label="menu"><i class="fas fa-bars"></i></button>
      <ul class="nav-links" id="navLinks">
        <li><a href="#home" class="nav-link">Home</a></li>
        <li><a href="#about" class="nav-link">About</a></li>
        <li><a href="#services" class="nav-link">Services</a></li>
        <li><a href="#portfolio" class="nav-link">Portfolio</a></li>
        <li><a href="#contact" class="nav-link">Contact</a></li>
      </ul>
    </div>
  </div>
</header>

<main>
  <!-- Hero -->
  <section id="home" class="hero scroll-margin">
    <div class="container hero-grid">
      <div class="hero-content">
        <div class="hero-badge"><i class="fas fa-chart-line"></i> Next-gen digital agency</div>
        <h1>Ideas that <span class="hero-highlight">ignite</span> <br> brands & growth</h1>
        <p>Witness the next generation tech with Inab bringing a world class businesses virtually.</p>
        <a href="#portfolio" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Explore work</a>
        <a href="#contact" class="btn btn-outline" style="margin-left: 1rem;">Let's talk</a>
      </div>
      <div class="hero-image">
        <img src="https://picsum.photos/id/26/500/400" alt="hero visual" style="border-radius: 32px;">
      </div>
    </div>
  </section>

  <!-- About -->
  <section id="about" class="scroll-margin">
    <div class="container">
      <div class="section-title"><span>Who we are</span></div>
      <div class="about-content">
        <div class="about-text">
          <h3>Driven by innovation, powered by design</h3>
          <p>Inab is a progressive digital brand that aims at providing quality web development, graphic design, 
            and creative digital solutions. We assist companies, organizations, and individuals to establish a 
            powerful internet presence with a state-of-the-art responsive web design and visually appealing websites.
            We do not only use creativity and technology at Inab; we make sure that the result of our efforts 
            is not only appealing to the eye but also practical and easy to use. In creating custom websites, 
            branding, UI/UX design, and other digital services, we aim to transform ideas into a reality and assist
             our clients in standing out in the current competitive digital environment.
            Our credo is excellence, detail and providing solutions that are based on the unique needs of each client. 
            Inab is with you in your new business or in the process of enhancing your digital footprint.</p>
          <div class="about-stats">
            <div class="stat"><div class="stat-number">120+</div><div>Projects delivered</div></div>
            <div class="stat"><div class="stat-number">24</div><div>Expert team</div></div>
            <div class="stat"><div class="stat-number">98%</div><div>Client retention</div></div>
          </div>
        </div>
        <div class="about-image">
          <img src="https://picsum.photos/id/20/400/300" alt="team collaboration">
        </div>
      </div>
    </div>
  </section>

  <!-- Services -->
  <section id="services" class="scroll-margin" style="background: var(--gray-bg);">
    <div class="container">
      <div class="section-title"><span>Our core services</span></div>
      <div class="services-grid">
        <?php if ($services): foreach($services as $service): ?>
          <div class="service-card"><div class="service-icon"><i class="fas <?= htmlspecialchars($service['icon'] ?? 'fa-code') ?>"></i></div><h3><?= htmlspecialchars($service['title']) ?></h3><p><?= htmlspecialchars($service['description']) ?></p></div>
        <?php endforeach; else: ?>
          <div class="service-card"><div class="service-icon"><i class="fas fa-code"></i></div><h3>Web Development</h3><p>High-performance websites with modern stacks, blazing fast & responsive.</p></div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Portfolio with JavaScript filtering -->
  <section id="portfolio" class="scroll-margin">
    <div class="container">
      <div class="section-title"><span>Featured projects</span></div>
      <div class="filter-buttons" id="filterButtons">
        <button class="filter-btn active" data-filter="all">All</button>
        <button class="filter-btn" data-filter="web">Web Development</button>
        <button class="filter-btn" data-filter="branding">Branding</button>
      </div>
      <div class="portfolio-grid" id="portfolioGrid">
        <?php if ($projects): foreach($projects as $project): ?>
        <div class="portfolio-item" data-category="<?= htmlspecialchars($project['category']) ?>">
          <img class="portfolio-img" src="<?= htmlspecialchars($project['image']) ?>" alt="project">
          <div class="portfolio-info"><h3><?= htmlspecialchars($project['title']) ?></h3><div class="portfolio-cat"><?= htmlspecialchars(ucfirst($project['category'])) ?></div><p><?= htmlspecialchars($project['description']) ?></p><?php if (!empty($project['url'])): ?><a class="project-link" href="<?= htmlspecialchars($project['url']) ?>" target="_blank" rel="noopener">Visit Project</a><?php endif; ?></div>
        </div>
        <?php endforeach; else: ?>
        <div class="portfolio-item" data-category="web"><img class="portfolio-img" src="https://picsum.photos/id/1/400/260" alt="project"><div class="portfolio-info"><h3>Fintech Dashboard</h3><div class="portfolio-cat">Web Development</div><p>Interactive platform with real-time analytics.</p></div></div>
        <?php endif; ?>
      </div>
      <div style="margin-top:24px;"><h3>Latest Product & Service Updates</h3><?php foreach($posts as $post): ?><article style="margin:10px 0;"><strong><?= htmlspecialchars($post['title']) ?></strong><p><?= htmlspecialchars($post['excerpt']) ?></p></article><?php endforeach; ?></div></div>
    </div>
  </section>

  <!-- Contact -->
  <section id="contact" class="scroll-margin" style="background: #F1F5FA;">
    <div class="container">
      <div class="section-title"><span>Let’s create together</span></div>
      <div class="contact-wrapper">
        <form id="contactForm" method="post"><input type="hidden" name="action" value="contact">
          <div class="form-group"><input type="text" id="name" name="name" placeholder="Full name" required></div>
          <div class="form-group"><input type="email" id="email" name="email" placeholder="Email address" required></div>
          <div class="form-group"><textarea rows="4" id="message" name="message" placeholder="Tell us about your project..." required></textarea></div>
          <button type="submit" class="contact-btn"><i class="fas fa-paper-plane"></i> Send message</button>
          <div id="form-feedback"><?php if ($feedback): ?><span style="color:#16a34a;"><?= htmlspecialchars($feedback) ?></span><?php endif; ?></div>
        </form>
      </div>
    </div>
  </section>
</main>

<footer>
  <div class="container">
    <div class="social-icons">
      <a href="#"><i class="fab fa-linkedin-in"></i></a>
      <a href="#"><i class="fab fa-twitter"></i></a>
      <a href="#"><i class="fab fa-dribbble"></i></a>
      <a href="#"><i class="fab fa-github"></i></a>
    </div>
    <p>© 2025 Inab Computer. All visions brought to life.</p>
  </div>
</footer>

<script src="app.js"></script>
</body>
</html>