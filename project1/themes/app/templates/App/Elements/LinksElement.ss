<div class="element__links" <% if $IndexTitle %> id="$IndexTitle" <% end_if %>>
  <div class="links-inner">
    <% include DividerHorizontal %>
    <h2 class="links-title">$Title</h2>
    <div class="links-content">$Content</div>
    <div class="links__container">
      <% loop $Links %>
        <div class="links-link">
          <a href="$getLinkURL" $getTargetAttr title="$Title" class="c-button links-button">$Title</a>
        </div>
      <% end_loop %>
    </div>
  </div>
</div>