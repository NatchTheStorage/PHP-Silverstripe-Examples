<% include Header %>
<% include PageBanner %>

<div class="page__shop">
  <div class="shop__intro">
    <% include DividerHorizontal %>
    <div class="shop__intro-title">$BlockTitle</div>
    <div class="shop__intro-content">$BlockContent</div>
  </div>

  <div class="shop__items">
    <%-- This is the actual shopify code --%>
    <% include ShopPicker %>
  </div>
</div>

<%--$NewsletterForm--%>

<% include Footer %>
<% include FloatingDonate %>

