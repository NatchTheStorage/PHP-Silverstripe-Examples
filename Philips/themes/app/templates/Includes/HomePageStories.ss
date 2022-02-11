<div class="homepage__stories">
  <% include DividerHorizontal %>
  <h2 class="homepage__storiescontainer-title">YOUR STORIES</h2>
  <div class="homepage__stories-list">
    <% loop $GetStories($init).Limit(3) %>
      <% include CardStory %>
    <% end_loop %>
  </div>
</div>