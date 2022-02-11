<div id="$IndexTitle" class="aboutus__team">
  <% if $GetTeam %>
    <% include DividerHorizontal %>
    <h2 class="aboutus__team-title">
      <% if $DisplayTitle %>
        $DisplayTitle
      <% else %>
        Team
      <% end_if %>
    </h2>
    <div class="aboutus__members">
      <% loop $GetTeam %>
        <% include CardTeam %>
      <% end_loop %>
    </div>
  <% end_if %>
</div>