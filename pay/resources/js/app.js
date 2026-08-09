document.addEventListener('DOMContentLoaded', () => {
    const observerOptions = {
        threshold: 0.2,
    };

    const animateCount = (counter) => {
        const target = Number(counter.getAttribute('data-count')) || 0;
        const decimals = Number(counter.getAttribute('data-decimals') || 0);
        const prefix = counter.getAttribute('data-prefix') || '';
        const suffix = counter.getAttribute('data-suffix') || '';
        const duration = 1200;
        let start = null;

        const formatValue = (value) => {
            return prefix + new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals,
            }).format(value) + suffix;
        };

        const step = (timestamp) => {
            if (!start) start = timestamp;
            const progress = Math.min((timestamp - start) / duration, 1);
            const current = target * progress;
            counter.textContent = formatValue(current);

            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };

        window.requestAnimationFrame(step);
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;

            const el = entry.target;
            if (el.matches('[data-count]')) {
                animateCount(el);
            }

            if (el.matches('.progress-card')) {
                const bar = el.querySelector('.progress-bar');
                if (bar) {
                    bar.style.width = el.dataset.width + '%';
                }
            }

            el.classList.add('in-view');
            observer.unobserve(el);
        });
    }, observerOptions);

    document.querySelectorAll('.animate-on-scroll, .animate-fade-up, .progress-card, [data-count]').forEach((element) => {
        observer.observe(element);
    });

    document.querySelectorAll('.delete-form').forEach((form) => {
        form.addEventListener('submit', (e) => {
            const btn = form.querySelector('button[data-confirm]');
            if (btn && !confirm(btn.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });

    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    document.querySelectorAll('.interactive-card').forEach((card) => {
        card.addEventListener('pointermove', (event) => {
            const rect = card.getBoundingClientRect();
            const x = ((event.clientX - rect.left) / rect.width - 0.5) * 16;
            const y = ((event.clientY - rect.top) / rect.height - 0.5) * -16;
            card.style.transform = `perspective(1000px) rotateX(${y}deg) rotateY(${x}deg) translateZ(0)`;
        });

        card.addEventListener('pointerleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateZ(0)';
        });
    });
});

