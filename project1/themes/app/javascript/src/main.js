/* eslint-disable no-restricted-globals */
const newsletterbanner = document.querySelector('.newsletter-form');
const newslettermobile = document.querySelector('.newsletter-title');
const newslettersuccess = document.querySelector('.newsletter-success');
if (newsletterbanner) {
  if (window.location.href.indexOf('?success=newsletter') > -1) {
    newslettersuccess.innerHTML = "<h2>You've been signed up for our newsletter!</h2>";
    newsletterbanner.classList.add('hide');
    newslettermobile.classList.add('hide');

    const successURL = location.pathname.split('?');
    history.pushState(null, '', successURL[0]);
  }
}

const hangarformsuccess = document.querySelector('.hangarvisitform-success');
const hangarform = document.querySelector('.hangarvisitform-form');
if (hangarform) {
  if (window.location.href.indexOf('#e') > -1) {
    hangarform.classList.add('hide');
    const successhangarURL = location.pathname.split('#');
    hangarformsuccess.innerHTML = "<h2>You've signed up for a hangar visit!<br>We will get in contact with you shortly!</h2>";
    hangarformsuccess.classList.add('active');
    history.pushState(null, '', successhangarURL[0]);
  }
}

const eventsLocationDropdown = document.querySelector('#eventslocation');
const eventsDateDropdown = document.querySelector('#eventsdate');
if (eventsLocationDropdown && eventsDateDropdown) {
  const urlParams = new URLSearchParams(window.location.search);
  const locValue = urlParams.get(eventsLocationDropdown.name);
  if (locValue) {
    eventsLocationDropdown.value = locValue;
  }
  const datValue = urlParams.get(eventsDateDropdown.name);
  if (datValue) {
    eventsDateDropdown.value = datValue;
  }
}

const eventsSeeMore = document.querySelector('.eventsholder__events-seemore');
if (eventsSeeMore) {
  const currentURL = window.location.href;
  if (currentURL.indexOf('all=true') > -1) {
    eventsSeeMore.classList.add('hide');
  }
}

const genderRadio1 = document.querySelector('#Form_SubmitDetailsForm_GenderField_1');
const genderRadio2 = document.querySelector('#Form_SubmitDetailsForm_GenderField_2');
if (genderRadio1 && genderRadio2) {
  genderRadio1.addEventListener('click', () => {
    if (genderRadio1.checked) {
      genderRadio1.parentNode.classList.add('active');
      genderRadio2.parentNode.classList.remove('active');
    }
  });
  genderRadio2.addEventListener('click', () => {
    if (genderRadio2.checked) {
      genderRadio2.parentNode.classList.add('active');
      genderRadio1.parentNode.classList.remove('active');
    }
  });
}
const allButton = document.getElementById('All');
const allContainer = document.getElementById('AllContainer');
const missionsButton = document.getElementById('Missions');
const missionsContainer = document.getElementById('MissionsContainer');
const articlesButton = document.getElementById('News');
const articlesContainer = document.getElementById('NewsContainer');
if (allButton && allContainer) {
  allContainer.addEventListener('click', () => {
    allContainer.classList.add('active');
    missionsContainer.classList.remove('active');
    articlesContainer.classList.remove('active');
  });
}
if (missionsButton && missionsContainer) {
  missionsContainer.addEventListener('click', () => {
    allContainer.classList.remove('active');
    missionsContainer.classList.add('active');
    articlesContainer.classList.remove('active');
  });
}
if (articlesButton && articlesContainer) {
  articlesContainer.addEventListener('click', () => {
    allContainer.classList.remove('active');
    missionsContainer.classList.remove('active');
    articlesContainer.classList.add('active');
  });
}
