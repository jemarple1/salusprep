const STORAGE_KEY = 'salusprep-cookie-consent';

export function hasCookieConsent() {
    try {
        return localStorage.getItem(STORAGE_KEY) === 'accepted';
    } catch {
        return false;
    }
}

export function acceptCookies() {
    try {
        localStorage.setItem(STORAGE_KEY, 'accepted');
    } catch {
        // Ignore storage failures; still hide the banner for this visit.
    }

    hideCookieConsent();
}

export function hideCookieConsent() {
    const banner = document.getElementById('cookie-consent');

    if (banner) {
        banner.classList.add('hidden');
    }
}

export function initCookieConsent() {
    if (hasCookieConsent()) {
        hideCookieConsent();

        return;
    }

    const banner = document.getElementById('cookie-consent');

    if (banner) {
        banner.classList.remove('hidden');
    }

    document.querySelectorAll('[data-cookie-accept]').forEach((button) => {
        button.addEventListener('click', acceptCookies);
    });
}
