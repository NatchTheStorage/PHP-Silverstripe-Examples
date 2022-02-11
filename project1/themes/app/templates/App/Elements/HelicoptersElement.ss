<div id="$IndexTitle" class="aboutus__aircraft">
  <% if $Helicopters %>
    <div class="aboutus__aircraft-inner">
      <% include DividerHorizontal %>
      <h2 class="aboutus__aircraft-title">
        <% if $DisplayTitle %>
          $DisplayTitle
        <% else %>
          Helicopters
        <% end_if %>
      </h2>
      <div class="aboutus__helicopterlist">
        <% loop $Helicopters %>
          <% include HelicopterBlock %>
        <% end_loop %>
      </div>
    </div>
  <% end_if %>
</div>