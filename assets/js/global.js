function themeMode() {
  const modeBtn = document.getElementById('modeBtn');
  const htmlEl = document.querySelector('html');

  modeBtn.addEventListener('click', () => {
    console.log('click');
    const isLightTheme = htmlEl.classList.contains('light-theme');

    if (isLightTheme) {
      htmlEl.classList.remove('light-theme');
      modeBtn.innerHTML = sunIcon;
      // Update localStorage to store the current state
      localStorage.setItem('lightTheme', 'false');
    } else {
      // The 'light-theme' class is not present on the element
      htmlEl.classList.add('light-theme');
      modeBtn.innerHTML = moonIcon;
      // Update localStorage to store the current state
      localStorage.setItem('lightTheme', 'true');
    }
  })
};

function init() {
  themeMode();
  // carouselControl();
}
document.addEventListener('DOMContentLoaded', init);

///////////////////
// Dropdown menu on platform page
function handleDropdowns(foo) {

  const menu = foo.nextElementSibling

  const isActive = menu.classList.contains('active')
  isActive ? menu.classList.remove('active') : menu.classList.add('active')
}
const dropdownToggles = document.querySelectorAll('.tps-dropdown__button')
dropdownToggles.forEach((toggle) => toggle.addEventListener('click', () => handleDropdowns(toggle)));

// Check for a click anywhere else on the page outside of tps-dropdown
document.addEventListener('click', function (e) {
  if (!document.querySelector('.tps-dropdown')) return;

  const menu = document.querySelector('.tps-dropdown__menu')

  const isDropdown = e.target.closest('.tps-dropdown')

  if (isDropdown) return;

  menu.classList.remove('active')
})
