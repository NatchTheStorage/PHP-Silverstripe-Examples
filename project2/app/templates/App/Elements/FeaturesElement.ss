<div class="element__featureselement <% if $First %> element-first<% end_if %><% if $Last %> element-last <% end_if %>">
    <div class="u-container">
        <% if $ShowTitle %>
            <h1 class="featureselement__title text-center h1">$Title</h1>
        <% end_if %>
        <% if $Features %>
            <% if $DisplayMode==0 %>
                <div class="feature__slider">
                    <% loop $Features %>
                        <% include FeatureSlider %>
                    <% end_loop %>
                </div>
                <div class="featureselement__list">
                    <% loop $Features %>
                        <% include FeatureNormal %>
                    <% end_loop %>
                </div>
            <% else %>
                <% if $DisplayMode==1 %>
                    <div class="featureselement__list u-mobile">
                        <% loop $Features %>
                            <% include FeatureNormal %>
                        <% end_loop %>
                    </div>
                <% end_if %>
            <% end_if %>
        <% end_if %>
    </div>
</div>
