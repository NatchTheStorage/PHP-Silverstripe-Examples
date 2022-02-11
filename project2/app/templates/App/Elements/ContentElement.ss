<div class="element__content <% if $First %> element-first<% end_if %><% if $Last %> element-last <% end_if %> ">
    <div class="u-container">
        <div class="content__textcontainer">
            <% if $ShowTitle %>
                <h2 class="content__title">$Title</h2>
            <% end_if %>
            <p class="content__content">$Content</p>
            <% if $ContentElementLink %>
                <div class="content__button">
                    <% include Link LinkItem=$ContentElementLink, ExtraClasses="c-button" %>
                </div>
            <% end_if %>
        </div>
    </div>
</div>