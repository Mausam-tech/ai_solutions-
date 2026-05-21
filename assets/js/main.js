/* ============================================================
   AI-Solutions — main.js
   Navbar scroll, counters, filters, form validation, utilities
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {

    // ─── 1. NAVBAR SCROLL BEHAVIOUR ──────────────────────────
    const nav = document.getElementById('mainNav');
    if (nav) {
        const handleScroll = () => {
            nav.classList.toggle('navbar-scrolled', window.scrollY > 50);
        };
        window.addEventListener('scroll', handleScroll, { passive: true });
        handleScroll(); // run once on load
    }

    // ─── 2. BACK TO TOP ──────────────────────────────────────
    const btt = document.getElementById('back-to-top');
    if (btt) {
        window.addEventListener('scroll', () => {
            btt.classList.toggle('d-none', window.scrollY < 300);
        }, { passive: true });
        btt.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ─── 3. ANIMATED STAT COUNTERS ───────────────────────────
    const counters = document.querySelectorAll('[data-count]');
    if (counters.length) {
        const animateCounter = (el) => {
            const target   = parseInt(el.dataset.count, 10);
            const suffix   = el.dataset.suffix || '';
            const duration = 1800;
            const start    = performance.now();
            const easeOut  = t => 1 - Math.pow(1 - t, 3);

            const update = (now) => {
                const elapsed  = now - start;
                const progress = Math.min(elapsed / duration, 1);
                el.textContent = Math.round(easeOut(progress) * target) + suffix;
                if (progress < 1) requestAnimationFrame(update);
                else el.textContent = target + suffix;
            };
            requestAnimationFrame(update);
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.dataset.animated) {
                    entry.target.dataset.animated = '1';
                    animateCounter(entry.target);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(c => observer.observe(c));
    }

    // ─── 4. GENERIC FILTER SYSTEM ────────────────────────────
    // Works for portfolio, gallery, testimonials filter buttons
    // Buttons have [data-filter] attribute; items have [data-category] or [data-rating]
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const group  = this.closest('[data-filter-group]') || this.parentElement;
            const target = this.dataset.filter;
            const itemsContainer = document.querySelector(this.dataset.container || '[data-filterable]');
            const attrName       = this.dataset.attr || 'data-category';

            // Toggle active button
            group.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            if (!itemsContainer) return;
            const items = itemsContainer.querySelectorAll('[data-category], [data-rating]');

            items.forEach(item => {
                const val = item.dataset.category || item.dataset.rating || '';
                if (target === 'all' || val === target) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // ─── 5. CONTACT FORM CLIENT-SIDE VALIDATION ──────────────
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            let valid = true;

            // Clear previous errors
            contactForm.querySelectorAll('.invalid-feedback').forEach(el => {
                el.style.display = 'none';
            });
            contactForm.querySelectorAll('.form-control, .form-select').forEach(el => {
                el.classList.remove('is-invalid');
            });

            const show = (field, msg) => {
                field.classList.add('is-invalid');
                const fb = field.nextElementSibling;
                if (fb && fb.classList.contains('invalid-feedback')) {
                    fb.textContent = msg;
                    fb.style.display = 'block';
                }
                valid = false;
            };

            const name    = contactForm.querySelector('#name');
            const email   = contactForm.querySelector('#email');
            const phone   = contactForm.querySelector('#phone');
            const company = contactForm.querySelector('#company_name');
            const country = contactForm.querySelector('#country');
            const jobT    = contactForm.querySelector('#job_title');
            const jobD    = contactForm.querySelector('#job_details');

            if (!name.value.trim() || name.value.trim().length < 2)
                show(name, 'Please enter your full name (at least 2 characters).');

            const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email.value.trim() || !emailRe.test(email.value.trim()))
                show(email, 'Please enter a valid email address.');

            const phoneRe = /^[\d\s\+\-\(\)]{7,20}$/;
            if (!phone.value.trim() || !phoneRe.test(phone.value.trim()))
                show(phone, 'Please enter a valid phone number.');

            if (!company.value.trim())
                show(company, 'Please enter your company name.');

            if (!country.value)
                show(country, 'Please select your country.');

            if (!jobT.value.trim())
                show(jobT, 'Please enter your job title.');

            if (!jobD.value.trim() || jobD.value.trim().length < 10)
                show(jobD, 'Please describe your job requirement (at least 10 characters).');

            if (!valid) e.preventDefault();
        });
    }

    // ─── 6. AUTO-DISMISS FLASH ALERTS ───────────────────────
    document.querySelectorAll('.alert-dismissible').forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) bsAlert.close();
        }, 5000);
    });

    // ─── 7. ADMIN: IMAGE FILE PREVIEW ───────────────────────
    document.querySelectorAll('.file-preview-input').forEach(input => {
        input.addEventListener('change', function () {
            const previewId = this.dataset.preview;
            const preview   = document.getElementById(previewId);
            if (!preview) return;

            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    // ─── 8. ADMIN: DELETE CONFIRMATION ──────────────────────
    document.querySelectorAll('.delete-confirm').forEach(btn => {
        btn.addEventListener('click', function (e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });

    // ─── 9. SMOOTH ANCHOR SCROLL ────────────────────────────
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                const offset = 80;
                const top = target.getBoundingClientRect().top + window.scrollY - offset;
                window.scrollTo({ top, behavior: 'smooth' });
            }
        });
    });

    // ─── 10. PORTFOLIO MODAL — populate from data attrs ─────
    const portfolioModal = document.getElementById('portfolioModal');
    if (portfolioModal) {
        portfolioModal.addEventListener('show.bs.modal', function (e) {
            const btn    = e.relatedTarget;
            if (!btn) return;
            const d      = btn.dataset;
            portfolioModal.querySelector('#pm-title').textContent      = d.title     || '';
            portfolioModal.querySelector('#pm-industry').textContent   = d.industry  || '';
            portfolioModal.querySelector('#pm-tech').textContent       = d.tech      || '';
            portfolioModal.querySelector('#pm-challenge').textContent  = d.challenge || '';
            portfolioModal.querySelector('#pm-solution').textContent   = d.solution  || '';
            portfolioModal.querySelector('#pm-outcome').textContent    = d.outcome   || '';
            const img = portfolioModal.querySelector('#pm-img');
            if (img) {
                if (d.img) { img.src = d.img; img.style.display = 'block'; }
                else        { img.style.display = 'none'; }
            }
        });
    }

});
