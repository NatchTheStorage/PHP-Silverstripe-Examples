<div class="element__textimage <% if $First %> element-first<% end_if %><% if $Last %> element-last <% end_if %> <% if $LessTopPadding %> lesspadding <% end_if %>">
    <%-- This is a shrinkable READMORE/READLESS element that should be only be visible in mobile --%>
    <div class="u-mobile">
        <div class="shrinkable">
            <div class="shrinkable-p item">
                <div class="textimage__text">
                    <% if $ShowTitle %>
                        <h2 class="textimage__title">$Title</h2>
                    <% end_if %>
                    <div class="textimage__content">$Content</div>
                </div>
            </div>
            <button class="show-more js-show-more text-green" type="button">Read More<img src="$ThemeDir/images/icons/arrow__down-red.svg" alt="Down arrow" class="" aria-hidden="true"></button>
        </div>
        <div class="textimage__image">
            <% if $Image %>
                <img class="textimage__image__image" src="$Image.URL" alt="$Image.Title">
            <% else %>
                <% if $VideoLink %>
                    <% include Video VideoLink=$returnParsedLink %>
                <% end_if %>
            <% end_if %>
        </div>
        <% if $TextImageLink %>
            <div class="textimage__buttoncontainer">
                <a href="$TextImageLink.getLinkURL" $TextImageLink.getTargetAttr title="$TextImageLink.Title" class="c-button textimage__button">$TextImageLink.Title</a>
            </div>
        <% end_if %>
    </div>
    <%-- This should only show up while in tablet and desktop mode --%>
    <div class="textimage__tabletdesktop <% if $ImageOnLeft %> image-on-left<% end_if %> u-tablet">
        <div class="textimage__text">
            <% if $ShowTitle %>
                <h2 class="textimage__title">$Title</h2>
            <% end_if %>
            <div class="textimage__content">$Content</div>
            <% if $TextImageLink %>
                <div class="textimage__buttoncontainer">
                    <a href="$TextImageLink.getLinkURL" $TextImageLink.getTargetAttr title="$TextImageLink.Title" class="c-button textimage__button">$TextImageLink.Title</a>
                </div>
            <% end_if %>
        </div>
        <div class="textimage__image">
            <% if $Image %>
                <img class="textimage__image__image" src="$Image.URL" alt="$Image.Title">
            <% else %>
                <% if $VideoLink %>
                    <% include Video VideoLink=$returnParsedLink %>
                <% end_if %>
            <% end_if %>
        </div>
    </div>
</div>