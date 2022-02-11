let accordions = document.querySelectorAll('.js-accordion-toggle');
if (accordions) {
    accordions.forEach((button, index) => {
        button.addEventListener('click', () => {
            button.parentElement.classList.toggle('is-active');
            if (button.parentElement.classList.contains('is-active')) {
                button.lastElementChild.outerHTML = '<img src="app/images/icons/arrow__down-red.svg" aria-hidden="true">';
            }
            else {
                if (button.classList.contains('accordionoffice__top')) {
                    button.lastElementChild.outerHTML = '<img src="app/images/icons/arrow__down-red.svg" aria-hidden="true">';
                }
                else {
                    button.lastElementChild.outerHTML = '<img src="app/images/icons/arrow__down-green.svg" aria-hidden="true">';
                }
            }

        });

        button.addEventListener('focus', () => {
        });

        button.addEventListener('blur', () => {
            button.parentElement.style.outline = "none";
        });
    })
}