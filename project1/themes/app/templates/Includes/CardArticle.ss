<div class="articlecard">
  <div class="articlecard__image">
    <div class="articlecard__image-text<% if $Type == 1 %>
        mission
      <% else_if $Type == 2 %>
        news
      <% end_if %>">
      <% if $Type == 1 %>
        Mission
      <% else_if $Type == 2 %>
        News
      <% end_if %>
    </div>
    <a class="articlecard__image-link" href="{$PageLink}{$Link(true)}">
      <% if $Image %>
        $Image.Fill(370,277)
      <% end_if %>

    </a>
  </div>
  <div class="articlecard__content">
    <div class="articlecard__content-date">
      $Date.Nice
    </div>
    <div class="articlecard__content-title">
      $Title
    </div>
    <div class="articlecard__content-blurb">$Content.Summary(35)</div>
    <a href="{$PageLink}{$Link()}" class="articlecard__content-link">read more</a>
  </div>
</div>