<% with $Property %>
    <div class="property-page">
        <div class="property__images">
            <% include PropertyTagsContainer ExtraClass='listingtag' %>
            <div class="photo-slider__container">
                <div class="main-slider__container">
                    <div class="photo-slider__main">
                        <% if $Images %>
                            <% loop $Images %>
                                <img class="photo-slider__main-image lazy" data-src="$URL"
                                     src="$ThemeDir/images/PropertyPlaceholder--rectangle.svg" title="$Title">
                            <% end_loop %>
                        <% else %>
                            <img class="photo-slider__main-image"
                                 src="$ThemeDir/images/PropertyPlaceholder--rectangle.svg" title="Property Image">
                        <% end_if %>
                    </div>
                </div>
                <div class="slider__bottom">
                    <div class="photo-slider__nav <% if $Images %>slider-active<% end_if %>">
                        <% loop $Images %>
                            <img class="photo-slider__nav-image lazy" data-src="$Fill(80,80).URL"
                                 src="$ThemeDir/images/PropertyPlaceholder--square.svg" title="$Title">
                        <% end_loop %>
                    </div>
                    <div class="property__headline-container">
                        <div class="property__headline">
                            <h2>
                                {$Headline}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="property__info">
            <div class="info__container">

                <div class="property__info-amenities">
                 <% if $FeatureBeds || $FeatureBathrooms || $FeatureGarages %>
                    <div class="amenity">
                        <img src="$ThemeDir/images/icons/bed.svg" alt="bedrooms">
                        <h3>{$FeatureBeds}</h3>
                    </div>
                    <div class="amenity">
                        <img src="$ThemeDir/images/icons/bath.svg" alt="bathrooms">
                        <h3>{$FeatureBathrooms}</h3>
                    </div>
                    <div class="amenity">
                        <img src="$ThemeDir/images/icons/garage.svg" alt="parking">
                        <h3>{$FeatureGarages}</h3>
                    </div>
                    <% end_if %>
                    <div class="datum share-button u-mobile">
                        <button class="sharebutton_container">
                            <% include ButtonShare %>
                        </button>
                    </div>
                </div>
                <div class="property__info-data">
                    <% if $AreaFloor %>
                        <div class="datum">
                            <img src="$ThemeDir/images/icons/area.svg" alt="area">
                            <p>{$AreaFloor}m²</p>
                        </div>
                    <% end_if %>

                    <% if $AreaLand %>
                        <div class="datum">
                            <img src="$ThemeDir/images/icons/area-total.svg" alt="total area">
                            <p>{$AreaLand}m²</p>

                        </div>
                    <% end_if %>
                    <% if $VirtualTourLink %>
                        <div class="datum">
                            <a href="#propertytour">
                                <img id="datum-tour" src="$ThemeDir/images/icons/VR.svg" alt="video">
                                <p>tour</p>
                            </a>
                        </div>
                    <% end_if %>
                    <% if $VideoLink %>
                        <div class="datum">
                            <a href="#propertyvideo">
                                <img id="datum-video" src="$ThemeDir/images/icons/video.svg" alt="video">
                                <p>video</p>
                            </a>
                        </div>
                    <% end_if %>
                    <div class="datum share-button u-tablet">
                        <button class="sharebutton_container">
                            <% include ButtonShare %>
                        </button>
                    </div>
                </div>
                <div class="property__info-details property__details">
                    <div class="property__info-detail region">$Suburb.Title</div>
                    <h3 class="property__info-detail address">$PrettyAddress</h3>
                    <% if $Type == "rent" %>
                        <% if $OpenOrAvailable %>
                            <p class="property__info-detail auction">$OpenOrAvailable</p>
                        <% end_if %>
                        <% if $calculateBond %>
                            <div class="property__info-detail open-home rent">$calculateBond</div>
                        <% end_if %>
                    <% else %>
                        <% if $AuctionTime %>
                            <p class="property__info-detail auction">$AuctionTime</p>
                        <% end_if %>
                        <% if $OpenOrAvailable(true) %>
                            <div class="property__info-detail open-home">$OpenOrAvailable(true)</div>
                        <% end_if %>
                    <% end_if %>
                    <% if $ListedDate %>
                        <div class="listed text-grey">$ListedFormat</div>
                    <% end_if %>
                    <h2 class="property__info-detail price">$PrettyPrice</h2>
                </div>
                <% include PropertyLinks ExtraClasses="property__info-links u-touch" %>
            </div>
            <% include PropertyLinks ExtraClasses="property__info-links u-desktop" %>
        </div>
        <div class="property__summary">
            <div class="description p1">
                <div class="description__container">
                    <div class="shrinkable-p p1">
                        <h2 class="text-green">$Headline</h2>
                        <p>{$FormattedDescription}</p>
                    </div>
                    <button class="show-more js-show-more text-green" type="button">See More<img
                            src="$ThemeDir/images/icons/arrow__down-red.svg" alt="Down arrow" class=""
                            aria-hidden="true"></button>
                </div>
            </div>
            <% if $VirtualTourLink %>
                <div id="propertytour" class='video virtualtour'>
                    <iframe class="video__iframe"
                            src="$VirtualTourLink"
                            frameborder="0"
                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                    </iframe>
                </div>
            <% end_if %>
            <% if $VideoLink %>
                <div id="propertyvideo" class="video">
                    <% include Video VideoLink=$returnParsedLink %>
                </div>
            <% end_if %>
            <div class="map">
                <div class="listing__map" data-lat="$GeoLat" data-lng="$GeoLon"></div>
            </div>
            <% if $CVText || $RatesText %>
                <div class="additional">
                    <div class="additional__info">
                        <h3 class="additional__title">Additional Info</h3>
                        <div class="additional__blurb">
                            <p>$CVText</p>
                            <p>$RatesText</p>
                        </div>
                        <% if $Files %>
                        <div class="additional__downloads">
                            <% loop $Files %>
                                <a class="download" href="$URL">$Name</a>
                            <% end_loop %>
                        </div>
                        <% end_if %>
                    </div>
                </div>
            <% end_if %>
            <% if $StaffContact %>
                <div class="agent">
                    <% include PropertyAgent %>
                </div>
            <% end_if %>
            <div class="actions">
                <% include PropertyLinks ExtraClasses="actions__container" %>
            </div>
        </div>
        <div class="property__interesting u-container">
            <h2 class="text-green property__interesting-title">You may also be interested in...</h2>
            <% if $RelatedProperties %>
                <% loop $RelatedProperties %>
                    <% include PropertyCard %>
                <% end_loop %>
            <% end_if %>
        </div>
    </div>
    <% if $Type == 'buy' %>
        <% include LiveChatSnippet %>
    <% end_if %>
<% end_with %>
<%--Google Maps--%>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjtZA781GiCZtosaKJoGNvH4fDe3_EJd8&callback=initPropertyMap"
        defer></script>
<script>
        <% with $Property %>
        (function (exports) {
            "use strict";

            function initPropertyMap() {
                var maps = document.querySelectorAll(".listing__map");
                if (maps) {
                    maps.forEach(map => {
                        var latlng = new google.maps.LatLng(map.dataset.lat, map.dataset.lng);
                        var mm = (new google.maps.Map(map, {
                            center: latlng,
                            zoom: 13,
                            zoomControl: false,
                            mapTypeControl: false,
                            scaleControl: false,
                            streetViewControl: false,
                            rotateControl: false,
                            fullscreenControl: false,
                        }));
                        var mark = new google.maps.Marker({
                            position: latlng,
                            icon: "{$ThemeDir}/images/icons/pin-green.svg",
                            map: mm
                        })
                    })
                }
            }

            exports.initPropertyMap = initPropertyMap;
        })((this.window = this.window || {}));
        <% end_with %>
</script>
