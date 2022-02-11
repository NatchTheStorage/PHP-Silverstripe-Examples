<div class="u-container">
  <div class="element__textimage <% if $ImageOnLeft %> image-on-left<% end_if %>" <% if $IndexTitle %>
       id="$IndexTitle" <% end_if %>>
    <div class="textimage__text">
      <% include DividerHorizontal %>
      <% if $ShowTitle %>
        <h2 class="textimage__title">$Title</h2>
      <% end_if %>
      <div class="textimage__content">$Content</div>
      <% if $TextImageLink %>
        <div class="textimage__buttoncontainer">
          <a href="$TextImageLink.getLinkURL" $TextImageLink.getTargetAttr title="$TextImageLink.Title"
             class="c-button textimage__button">
            $TextImageLink.Title
          </a>
        </div>
      <% end_if %>
    </div>
    <% if $Images %>
      <% if $Images.Count == 1 %>
        <% loop $Images %>
          <div class="textimage__image">
            <img class="textimage__image__image" src="$Image.URL" alt="$Image.Title" >
          </div>
        <% end_loop %>
      <% else %>
        <div class="textimage__flickity">
          <% loop $Images %>
            <div class="textimage__flickity-cell">
              <img src="" alt="$Image.Title" data-flickity-lazyload="$Image.Fill(950,950).URL">
            </div>
          <% end_loop %>
        </div>
      <% end_if %>

    <% end_if %>
  </div>
</div>

<script src="/themes/app/javascript/src/flickity.pkgd.min.js"></script>