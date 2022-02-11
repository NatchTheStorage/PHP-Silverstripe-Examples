<div class="pagination-navigator">
  <% if $Paginate.NotFirstPage %>
    <a href="$Paginate.PrevLink">
      <div class="c-pagination-arrow $Parent.Theme rotateleft"></div>
    </a>
  <% else %>
    <div class="c-pagination-arrow $Parent.Theme disabled rotateleft"></div>
  <% end_if %>
  <% loop $Paginate.PaginationSummary %>
    <% if $CurrentBool %>
      <a class="c-pagination-link" href="$Link">
        <div class="c-pagination-index active">$PageNum</div>
      </a>
    <% else %>
      <% if $Link %>
        <a class="c-pagination-link" href="$Link">
          <div class="c-pagination-index">$PageNum</div>
        </a>
      <% else %>
        ...
      <% end_if %>
    <% end_if %>
  <% end_loop %>
  <% if $Paginate.NotLastPage %>
    <a href="$Paginate.NextLink">
      <div class="c-pagination-arrow $Parent.Theme"></div>
    </a>
  <% else %>
    <div class="c-pagination-arrow $Parent.Theme disabled"></div>
  <% end_if %>
</div>