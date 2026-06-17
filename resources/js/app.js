import { initTheme, toggleTheme } from './theme';

initTheme();

document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
    button.addEventListener('click', toggleTheme);
});
