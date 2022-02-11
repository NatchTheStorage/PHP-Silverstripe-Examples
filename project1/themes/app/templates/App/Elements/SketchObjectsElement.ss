<% if $Objects %>
  <div class="element__sketchobjects">
    <script type="text/javascript" src="https://static.sketchfab.com/api/sketchfab-viewer-1.10.1.js"></script>
    <% if $Objects.Count > 1 %>
      <div class="sketch__multiwrapper">
        <% loop $Objects %>
          <div class="sketch__multibox">
            <% include SketchObject %>
          </div>
        <% end_loop %>
      </div>
    <% else %>
      <% loop $FirstObject %>
        <% include SketchObject %>
      <% end_loop %>
    <% end_if %>
  </div>
<% end_if %>