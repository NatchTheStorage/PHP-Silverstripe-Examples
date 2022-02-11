<div class="mediahub__linksblock">
  <div class="mediahub__linksblock-inner">
    <div class="mediahub__linksblock-content">
      $Content
    </div>
    <div class="mediahub__linksblock-linklist">
      <% loop $Links %>
        <a class="mediahub__linksblock-link" href="$LinkURL">$Title</a>
      <% end_loop %>
    </div>
  </div>
</div>