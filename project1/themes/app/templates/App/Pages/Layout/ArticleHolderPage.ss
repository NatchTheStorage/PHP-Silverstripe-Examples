<% include Header %>
<% include PageBanner %>
<div class="page__articleholder">

  <div id="news-mount" data-articles="$getArticlesJSON($init)" data-month-years="$MonthYears"
       data-article-types="$ArticleTypes"></div>

  <% if $Links %>
    <div class="articleholderpage__links">
      <div class="articleholderpage__links-inner">
        <h2 class="articleholderpage__links-title">$LinksTitle</h2>
        <div class="articleholderpage__links-list">
          <% loop $Links %>
            <% if not $First %> • <% end_if %>
            <a class="articleholderpage__links-index <% if $First %>
              first
            <% end_if %>" href="$LinkURL">$Title</a>
          <% end_loop %>
        </div>
      </div>
    </div>
  <% end_if %>
</div>
<%--$NewsletterForm--%>
<% include Footer %>
<% require themedJavascript('javascript/dist/mount') %>
<% include FloatingDonate %>