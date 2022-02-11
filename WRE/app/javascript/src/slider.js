let featureSlider = document.querySelector('.feature__slider');
createSlider();

function createSlider() {
    if (featureSlider) {
        let options = {
            wrapAround: true,
            prevNextButtons: false,
            imagesLoaded: true
        };
        var feature_flkty = new Flickity(featureSlider, options);
    }
}
// This allows flickity fullscreen to pinchzoom
// https://github.com/metafizzy/flickity-fullscreen/issues/20
Flickity.prototype._touchActionValue = "pan-y pinch-zoom";
let propertyImagesSlider = document.querySelector('.photo-slider__main');
if (propertyImagesSlider) {
    let flickities = [];
    let main = new Flickity(propertyImagesSlider, {
        imagesLoaded: true,
        wrapAround: true,
        fullscreen: true,
    })
    flickities.push(main);

    let propertyImagesSliderNav = document.querySelector('.photo-slider__nav');
    flickities.push(new Flickity(propertyImagesSliderNav, {
        asNavFor: '.photo-slider__main',
        imagesLoaded: true,
        contain: true,
        prevNextButtons: false,
        pageDots: false,
        cellAlign: 'right'
    }));

    flickities[0].resize();
    flickities[1].resize();
}

let propertyCardSlider = document.querySelectorAll('.property__image-slider');
if (propertyCardSlider) {
    propertyCardSlider.forEach(cardSlider => {
        if (cardSlider.dataset.imagecount > 1) {
            let options = {
                wrapAround: false,
                prevNextButtons: false,
                imagesLoaded: true
            };
            let slider = new Flickity(cardSlider, options);
        }
    })
}