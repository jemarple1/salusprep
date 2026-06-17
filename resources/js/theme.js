const STORAGE_KEY = 'salusprep-theme';

export function getStoredTheme() {
    try {
        const stored = localStorage.getItem(STORAGE_KEY);

        return stored === 'light' || stored === 'dark' ? stored : 'dark';
    } catch {
        return 'dark';
    }
}

export function applyTheme(theme) {
    const resolved = theme === 'light' ? 'light' : 'dark';

    document.documentElement.dataset.theme = resolved;
    document.documentElement.style.colorScheme = resolved;

    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        const nextTheme = resolved === 'dark' ? 'light' : 'dark';
        button.setAttribute('aria-label', nextTheme === 'light' ? 'Switch to light mode' : 'Switch to dark mode');
    });
}

export function initTheme() {
    applyTheme(getStoredTheme());
}

export function toggleTheme() {
    const nextTheme = getStoredTheme() === 'dark' ? 'light' : 'dark';

    try {
        localStorage.setItem(STORAGE_KEY, nextTheme);
    } catch {
        // Ignore storage failures and still apply the theme for this page load.
    }

    applyTheme(nextTheme);
}
