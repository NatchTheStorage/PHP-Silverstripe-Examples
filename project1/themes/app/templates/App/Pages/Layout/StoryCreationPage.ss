<% include Header %>
<% include BannerBack backlink='stories' %>
<div class="page__storycreation">
  <h1 class="storycreation-title">SHARE YOUR STORY</h1>
  <% if $formSuccess() %>
    <h3>$SuccessMessage</h3>
  <% else %>
    $StoryForm
  <% end_if %>
</div>


<%--$NewsletterForm--%>
<% include Footer %>
<% include FloatingDonate %>