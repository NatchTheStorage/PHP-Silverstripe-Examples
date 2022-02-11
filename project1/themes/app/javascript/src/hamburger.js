const hamburger = document.querySelector('.header__hamburger');
const navList = document.querySelector('.header__links');

if (hamburger) {
  hamburger.addEventListener('click', () => {
    console.log('click');
    hamburger.classList.toggle('is-active');
    navList.classList.toggle('is-active');
  });
}
