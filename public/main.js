// Fungsi inisialisasi accordion
function initAccordion() {
    const accordionButtons = document.querySelectorAll('.accordion-button');
    
    if (accordionButtons.length > 0) {
        accordionButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.dataset.accordion;
                const targetAccordion = document.getElementById(targetId);
                const arrowIcon = this.querySelector('.arrow img');

                button.classList.toggle('open');
                targetAccordion.classList.toggle('hide');

                if (targetAccordion.classList.contains('hide')) {
                    targetAccordion.style.maxHeight = "0";
                } else {
                    targetAccordion.style.maxHeight = targetAccordion.scrollHeight + "px";
                }

                arrowIcon.classList.toggle('rotate-180');
            });
        });
    }
}

// Fungsi inisialisasi mobile nav
function initMobileNav() {
    const mobileNav = document.querySelector('.mobile-nav');
    const openMobileNavButtons = document.querySelectorAll('.open-mobile-nav');

    if (mobileNav && openMobileNavButtons.length > 0) {
        openMobileNavButtons.forEach(button => {
            button.addEventListener('click', function() {
                mobileNav.classList.toggle('hidden');
            });
        });
    }
}

// Single DOMContentLoaded event listener
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi komponen
    initAccordion();
    initMobileNav();

    // Inisialisasi Fancybox jika ada
    const fancyboxElements = document.querySelectorAll("[data-fancybox]");
    if (fancyboxElements.length > 0 && typeof Fancybox !== 'undefined') {
        Fancybox.bind("[data-fancybox]", {
            // Your custom options
        });
    }
});