document.addEventListener('DOMContentLoaded', function () {
    const toggleButtons = document.querySelectorAll('[data-legacy-toggle]');

    toggleButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            const targetSelector = btn.getAttribute('data-legacy-toggle');
            const target = document.querySelector(targetSelector);
            if (!target) return;

            target.classList.toggle('is-hidden');
            btn.setAttribute('aria-expanded', !target.classList.contains('is-hidden'));
        });
    });
});
