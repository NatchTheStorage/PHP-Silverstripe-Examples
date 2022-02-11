<div class="pagepropertysearch__wrapper">
    <div class="pagepropertysearch__search">
        <% include PropertySearch %>
    </div>
    <div class="pagepropertysearch__results">
        <div class="u-container">
            <div class="pagepropertysearch__property_list-text">
                <div class="pagepropertysearch__property_list-header">
                    <h2 class="pagepropertysearch__property_list-title text-green">$TitleText</h2>
                    <% if $PaginatedProperties %>
                        <p>Showing $PaginatedProperties.getTotalItems results</p>
                    <% end_if %>
                </div>
                <div class="pagepropertysearch__filter">
                    <div class="menu-item__label-filter pagepropertysearch__filter-text">Sort by:</div>
                    <select form="property-search-form" class="pagepropertysearch__filter-box js-listings-sort__dropdown">
                        <option value="latest">Latest Listings</option>
                        <option value="lowestPrice">Lowest Price</option>
                        <option value="highestPrice">Highest Price</option>
                    </select>
                </div>
            </div>
            <div class="pagepropertysearch__property_list">
                <% if $PaginatedProperties %>
                    <% loop $PaginatedProperties %>
                        <% include PropertyCard %>
                    <% end_loop %>
                <% else %>
                    <p>No matching properties</p>
                <% end_if %>
            </div>
            <div class="propertysearchpage__pagination">
                <% if $PaginatedProperties %>
                    <% include PaginationNavigator %>
                <% end_if %>
            </div>
        </div>
    </div>
</div>
