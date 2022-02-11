<div class="element__eventslist" <% if $IndexTitle %> id="$IndexTitle" <% end_if %>>
  <% include DividerHorizontal %>
  <div class="eventslist-title">fundraising events</div>

  <div class="eventslist-mobiletablet">
    <% if $GetEventsMobileTablet %>
      <% loop $GetEventsMobileTablet %>
        <% include CardEvent %>
      <% end_loop %>
    <% else %>
      <div class="eventslist-emptytext">Whoops! There doesn't seem to be any events at the moment!</div>
    <% end_if %>
  </div>


  <div class="eventslist-desktop">
    <% if $GetEventsDesktop %>
      <% loop $GetEventsDesktop %>
        <% include CardEvent %>
      <% end_loop %>
    <% else %>
      <div class="eventslist-emptytext">Whoops! There doesn't seem to be any events at the moment!</div>
    <% end_if %>
  </div>
    <div class="eventslist-buttoncontainer">
      <a href="{$getSubsite(true)}/fundraising-events/" class="eventslist-button">SEE ALL EVENTS</a>
    </div>
</div>