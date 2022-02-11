<% include Header %>
<% include BannerBack backlink='news' %>
<div class="page__article">

  <% with $Article %>

    <h1 class="article-title">$Title</h1>
    <h4 class="article-date">$Date.Nice</h4>

    <div class="article-content">
      <% if $Image %>
        <img class="article-image" src="$Image.URL" alt="picture">
      <% end_if %>
      <div class="article-text">$Content</div>
    </div>

  <% end_with %>
</div>
<%--$NewsletterForm--%>
<% include Footer %>