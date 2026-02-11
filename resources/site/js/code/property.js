import '../code/vue/map_vue';

window.openGalleryAt = function(index) {
    const modalEl = document.getElementById('galleryModal');
    if (!modalEl) return;

    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    modalEl.addEventListener('shown.bs.modal', function () {
        const carouselEl = document.getElementById('propCarousel');
        let carousel = bootstrap.Carousel.getInstance(carouselEl);
        if (!carousel) carousel = new bootstrap.Carousel(carouselEl);

        carousel.to(index);
        updateImageCounter(index);
    }, { once: true });
};

window.scrollToContact = function() {
    const el = document.getElementById('contact-form-sidebar');
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
};

function updateImageCounter(currentIndex) {
    const counterEl = document.getElementById('imageCounter');
    if (counterEl) {
        const total = counterEl.getAttribute('data-total');
        counterEl.textContent = `${currentIndex + 1} / ${total}`;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const carouselEl = document.getElementById('propCarousel');
    if (carouselEl) {
        carouselEl.addEventListener('slide.bs.carousel', function (e) {
            updateImageCounter(e.to);
        });
    }

    const messageTxt = document.getElementById('messageTextarea');
    const waBtn = document.getElementById('whatsappBtn');

    if (messageTxt && waBtn) {
        messageTxt.addEventListener('input', function() {
            const msg = encodeURIComponent(this.value);
            const base = waBtn.href.split('?text=')[0];
            waBtn.href = `${base}?text=${msg}`;
        });
    }
});


