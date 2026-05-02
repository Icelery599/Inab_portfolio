
  (function() {
    // --- MOBILE MENU TOGGLE ---
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('navLinks');
    if (hamburger) {
      hamburger.addEventListener('click', () => {
        navLinks.classList.toggle('active');
      });
    }
    // Close mobile nav on link click
    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', () => {
        navLinks.classList.remove('active');
      });
    });

    // --- SMOOTH SCROLL & active nav highlight ---
    const sections = document.querySelectorAll('section[id]');
    const navItems = document.querySelectorAll('.nav-link');
    function updateActiveNav() {
      let current = '';
      const scrollPos = window.scrollY + 150;
      sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if(scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
          current = section.getAttribute('id');
        }
      });
      navItems.forEach(item => {
        item.classList.remove('active');
        const href = item.getAttribute('href').substring(1);
        if(href === current) {
          item.classList.add('active');
        }
      });
    }
    window.addEventListener('scroll', updateActiveNav);
    updateActiveNav();

    // smooth scroll for anchor links
    document.querySelectorAll('.nav-link, .btn-primary[href^="#"], .btn-outline[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        const targetId = this.getAttribute('href');
        if(targetId && targetId !== '#' && targetId.startsWith('#')) {
          e.preventDefault();
          const targetElement = document.querySelector(targetId);
          if(targetElement) {
            const offset = 80;
            const elementPosition = targetElement.getBoundingClientRect().top + window.scrollY;
            window.scrollTo({ top: elementPosition - offset, behavior: 'smooth' });
          }
        }
      });
    });

    // --- PORTFOLIO FILTER (JS requirement) ---
    const filterBtns = document.querySelectorAll('.filter-btn');
    const portfolioItems = document.querySelectorAll('.portfolio-item');
    function filterProjects(category) {
      portfolioItems.forEach(item => {
        const itemCategory = item.getAttribute('data-category');
        if(category === 'all' || itemCategory === category) {
          item.style.display = 'block';
        } else {
          item.style.display = 'none';
        }
      });
    }
    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const filterValue = btn.getAttribute('data-filter');
        filterProjects(filterValue);
      });
    });
    // initial filter all visible
    filterProjects('all');

    // --- CONTACT FORM VALIDATION + submission simulation ---
    const contactForm = document.getElementById('contactForm');
    const feedbackDiv = document.getElementById('form-feedback');
    if(contactForm) {
      contactForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const message = document.getElementById('message').value.trim();
        if(!name || !email || !message) {
          feedbackDiv.innerHTML = '<span style="color:#e11d48;"><i class="fas fa-exclamation-circle"></i> All fields are required.</span>';
          return;
        }
        if(!email.includes('@') || !email.includes('.')) {
          feedbackDiv.innerHTML = '<span style="color:#e11d48;"><i class="fas fa-envelope"></i> Please enter a valid email.</span>';
          return;
        }
        feedbackDiv.innerHTML = '<span style="color:#16a34a;"><i class="fas fa-check-circle"></i> Thank you! We’ll get back within 24h.</span>';
        contactForm.reset();
        setTimeout(() => {
          feedbackDiv.innerHTML = '';
        }, 4000);
      });
    }

    // subtle additional JS: add hover dynamic gallery? not necessary but fine.
    // also ensure on window resize, if mobile menu open and resize to desktop, remove class? 
    window.addEventListener('resize', () => {
      if(window.innerWidth > 850) {
        if(navLinks.classList.contains('active')) navLinks.classList.remove('active');
      }
    });
  })();
