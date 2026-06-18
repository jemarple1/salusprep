import { initTheme, toggleTheme } from './theme';
import { initCookieConsent } from './cookies';

initTheme();
initCookieConsent();

document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
    button.addEventListener('click', toggleTheme);
});
