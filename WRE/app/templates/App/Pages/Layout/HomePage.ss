<div class="home-page">
    <div class="banner home__banner" style="background-image: url('{$BannerBackground.URL}')">
        <div class="banner__container">
            <h1 class="banner__title">$BannerTitle</h1>
            <div class="banner__searchcontainer">
                <% include HomeSearch %>
            </div>
        </div>
    </div>
    <div class="home__searchcontainer">
        <% include HomeSearch %>
    </div>
    <div class="home__ctas">
        <div class="ctas__container">
            <% loop $CTAs %>
                <% include HomeCTA %>
            <% end_loop %>
        </div>
    </div>
    <% if $FeaturedProperties('rent') || $FeaturedProperties('buy') %>
        <div class="home__featured">
            <div class="featured__title">Featured Homes</div>
            <% if $FeaturedProperties('rent') %>
                <div class="featured__properties">
                    <div class="featured__properties--title">For Rent</div>
                    <div class="featured__properties--list rent">
                        <% loop $FeaturedProperties('rent') %>
                            <% include PropertyCard %>
                        <% end_loop %>
                    </div>
                </div>
            <% end_if %>
            <% if $FeaturedProperties('buy') %>
                <div class="featured__properties">
                    <div class="featured__properties--title">For Sale</div>
                    <div class="featured__properties--list buy">
                        <% loop $FeaturedProperties('buy') %>
                            <% include PropertyCard %>
                        <% end_loop %>
                    </div>
                </div>
            <% end_if %>
        </div>
    <% end_if %>

    $ElementalArea

    <div class="home__content">
        <div class="home__content--text">
            <div>$Content</div>
        </div>
        <div class="home__content--video">
            <div class="home__video">
                <% include Video VideoLink=$returnParsedLink %>
            </div>
        </div>
    </div>
</div>
