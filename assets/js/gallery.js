/* ============================================================
   AI-Solutions — gallery.js
   Gallery image filter + custom lightbox
   ============================================================ */

(function () {
    'use strict';

    // Build lightbox HTML and inject into body
    const lightboxHTML = `
        <div id="lightbox">
            <button id="lightbox-close" aria-label="Close lightbox"><i class="bi bi-x-lg"></i></button>
            <img id="lightbox-img" src="" alt="">
            <div id="lightbox-caption"></div>
            <div id="lightbox-counter"></div>
            <button class="lightbox-btn" id="lightbox-prev" aria-label="Previous image"><i class="bi bi-chevron-left"></i></button>
            <button class="lightbox-btn" id="lightbox-next" aria-label="Next image"><i class="bi bi-chevron-right"></i></button>
        </div>`;
    document.body.insertAdjacentHTML('beforeend', lightboxHTML);

    const lightbox     = document.getElementById('lightbox');
    const lbImg        = document.getElementById('lightbox-img');
    const lbCaption    = document.getElementById('lightbox-caption');
    const lbCounter    = document.getElementById('lightbox-counter');
    const lbClose      = document.getElementById('lightbox-close');
    const lbPrev       = document.getElementById('lightbox-prev');
    const lbNext       = document.getElementById('lightbox-next');

    if (!lightbox) return;

    let currentIndex = 0;
    let filteredItems = [];

    // ─── GET VISIBLE GALLERY ITEMS ───────────────────────────
    function getVisibleItems() {
        return Array.from(document.querySelectorAll('.gallery-item'))
            .filter(el => el.style.display !== 'none');
    }

    // ─── OPEN LIGHTBOX ───────────────────────────────────────
    function openLightbox(index) {
        filteredItems = getVisibleItems();
        if (!filteredItems.length) return;

        currentIndex = Math.max(0, Math.min(index, filteredItems.length - 1));
        updateLightbox();
        lightbox.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    // ─── UPDATE LIGHTBOX CONTENT ─────────────────────────────
    function updateLightbox() {
        const item    = filteredItems[currentIndex];
        if (!item) return;

        const img     = item.querySelector('img');
        const caption = item.querySelector('.gallery-caption');

        lbImg.src            = img ? img.src : '';
        lbImg.alt            = img ? (img.alt || '') : '';
        lbCaption.textContent = caption ? caption.textContent : '';
        lbCounter.textContent = `${currentIndex + 1} / ${filteredItems.length}`;

        // Show/hide nav arrows based on position
        lbPrev.style.visibility = currentIndex > 0 ? 'visible' : 'hidden';
        lbNext.style.visibility = currentIndex < filteredItems.length - 1 ? 'visible' : 'hidden';
    }

    // ─── CLOSE LIGHTBOX ──────────────────────────────────────
    function closeLightbox() {
        lightbox.classList.remove('open');
        document.body.style.overflow = '';
        lbImg.src = ''; // free memory
    }

    // ─── NAVIGATION ──────────────────────────────────────────
    lbClose.addEventListener('click', closeLightbox);
    lbPrev.addEventListener('click',  () => { if (currentIndex > 0) { currentIndex--; updateLightbox(); } });
    lbNext.addEventListener('click',  () => { if (currentIndex < filteredItems.length - 1) { currentIndex++; updateLightbox(); } });

    // Click outside image to close
    lightbox.addEventListener('click', e => { if (e.target === lightbox) closeLightbox(); });

    // Keyboard navigation
    document.addEventListener('keydown', e => {
        if (!lightbox.classList.contains('open')) return;
        if (e.key === 'ArrowLeft'  && currentIndex > 0)                      { currentIndex--; updateLightbox(); }
        if (e.key === 'ArrowRight' && currentIndex < filteredItems.length - 1){ currentIndex++; updateLightbox(); }
        if (e.key === 'Escape') closeLightbox();
    });

    // ─── ATTACH CLICK TO GALLERY ITEMS ───────────────────────
    function attachGalleryClicks() {
        document.querySelectorAll('.gallery-item').forEach((item, idx) => {
            item.addEventListener('click', () => {
                // Index among ALL items; openLightbox recalculates visible
                const visible = getVisibleItems();
                const visIdx  = visible.indexOf(item);
                if (visIdx !== -1) openLightbox(visIdx);
            });
        });
    }

    // Run on DOM ready
    attachGalleryClicks();

})();
