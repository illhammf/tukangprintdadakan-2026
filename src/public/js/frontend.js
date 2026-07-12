document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('[data-nav-toggle]');
    const nav = document.querySelector('[data-nav-menu]');
    const closeElements = document.querySelectorAll('[data-nav-close]');

    if (!toggle || !nav) {
        return;
    }

    const setMenuState = (isOpen) => {
        nav.classList.toggle('open', isOpen);
        toggle.classList.toggle('active', isOpen);

        toggle.setAttribute(
            'aria-expanded',
            isOpen ? 'true' : 'false'
        );

        toggle.setAttribute(
            'aria-label',
            isOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi'
        );

        document.body.classList.toggle('nav-open', isOpen);
    };

    toggle.addEventListener('click', () => {
        setMenuState(!nav.classList.contains('open'));
    });

    closeElements.forEach((element) => {
        element.addEventListener('click', () => {
            setMenuState(false);
        });
    });

    nav.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 900) {
                setMenuState(false);
            }
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setMenuState(false);
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 900) {
            setMenuState(false);
        }
    });
});