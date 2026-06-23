// ── NAVBAR SCROLL EFFECT ──
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
  if (window.scrollY > 60) {
    navbar.classList.add('scrolled');
  } else {
    navbar.classList.remove('scrolled');
  }
});

// ── MOBILE NAV TOGGLE ──
const navToggle = document.getElementById('navToggle');
const navLinks = document.getElementById('navLinks');
if (navToggle) {
  navToggle.addEventListener('click', () => {
    navLinks.classList.toggle('open');
  });
}

// Close mobile nav when a link is clicked
document.querySelectorAll('.nav-links a').forEach(link => {
  link.addEventListener('click', () => {
    navLinks.classList.remove('open');
  });
});

// ── SCROLL REVEAL ANIMATIONS ──
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.style.opacity = '1';
      entry.target.style.transform = 'translateY(0)';
    }
  });
}, { threshold: 0.1 });

document.querySelectorAll('.card, .why-card, .dest-card, .testi-card, .service-row, .dest-detail-card, .mv-card, .stat-item').forEach(el => {
  el.style.opacity = '0';
  el.style.transform = 'translateY(24px)';
  el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
  observer.observe(el);
});


function handleSubmit(e) {
  e.preventDefault();
  const form    = document.getElementById('contactForm');
  const success = document.getElementById('formSuccess');
  const btn     = form.querySelector('button[type="submit"]');

  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
  btn.disabled  = true;

  const data = new FormData(form);

  fetch('submit_enquiry.php', {
    method: 'POST',
    body: data
  })
  .then(res => res.json())
  .then(response => {
    if (response.status === 'success') {
      form.reset();
      btn.style.display = 'none';
      success.classList.add('show');
    } else {
      btn.innerHTML = 'Send Enquiry <i class="fas fa-paper-plane"></i>';
      btn.disabled  = false;
      alert('Error: ' + response.message);
    }
  })
  .catch(() => {
    btn.innerHTML = 'Send Enquiry <i class="fas fa-paper-plane"></i>';
    btn.disabled  = false;
    alert('Something went wrong. Please try again.');
  });
}



// ── ACTIVE NAV LINK ──
(function() {
  const path = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.nav-links a').forEach(a => {
    const href = a.getAttribute('href');
    if (href === path || (path === '' && href === 'index.html')) {
      a.classList.add('active');
    } else {
      a.classList.remove('active');
    }
  });
})();
