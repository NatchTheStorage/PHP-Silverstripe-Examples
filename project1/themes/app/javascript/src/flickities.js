const textImageFlickityTarget = document.querySelectorAll('.textimage__flickity');
if (textImageFlickityTarget) {
  textImageFlickityTarget.forEach((flickitytarget) => {
    let textImageFlickity = new Flickity(flickitytarget, {
      // options
      cellAlign: 'left',
      prevNextButtons: false,
      autoplay: 1000,
      wrapAround: true,
      imagesLoaded: true,
      lazyLoad: true,
    });
  });
}