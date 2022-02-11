let PROPERTY_MATRIX = [];

$(document).ready(function() {
    //check if we got a property filter form on this bad boi
    if ($('.property-filters')) {
        let buttons = $('.filter-button');
        $.each(buttons, function () {
            // TODO: perhaps move this out to in setFormValues
            updateText($(this));
            $(this).click(function () {
                let filterGroup = $(this).siblings('.filter-group');
                filterGroup.toggleClass('is-active');

                if (filterGroup.hasClass('is-active')) {
                    let minimise = $('.filter-group').not(filterGroup);
                    minimiseOptions(minimise);
                }
            });
        });

        let searchSortFormDropdown = document.querySelector('.js-listings-sort__dropdown');
        let searchForm = document.querySelector('.js-property-search');
        if (searchSortFormDropdown && searchForm) {
            searchSortFormDropdown.addEventListener('change', (e) => {
                let sortBy = searchForm.querySelector('#sort-by');
                sortBy.value = e.target.value;
                searchForm.submit();
            });
        }

        let homeSearchButtons = document.getElementById('home-search-button');
        if (homeSearchButtons) {
            homeSearchButtons.addEventListener('click', function (e) {
                let searchButton = document.querySelector('.search-action');
                searchButton.classList.add('is-loading');
            });
        }

        // set filter events on pre-existing filters (dynamic ones will set once built)
        setFilterEvents();
        // get and set the cities and their filter events
        buildCities(false);
        // get and set the suburbs and their filter events
        buildSuburbs([], false);
        // get and set the prices and their filter events
        buildPrices(false);

        setFormValues();
        buildPropertyMatrix(false);
        setCounts();
    }
});

/**
 * Gets cities from SilverStripe and builds the filters on the frontend
 * Then trigger the set up of their click events
 * @param {boolean} async - whether the function should run asynchronously or should block (defaults to async).
 */
function buildCities(async = true) {
    let url = 'locations/cities/';
    $.ajax({
        type: 'GET',
        url: url,
        async: async
    }).done(function (response) {
        let group = $('.filter-group.js-group--towncity');
        group.empty();

        response.forEach(function (item) {
            if (item.title !== '') {
                let option = newFilterOption(item.ID, item.Title, "towncity");
                group.append(option);
            }
        });

        setFilterEvents(group.children());
    })
}

/**
 * Gets suburbs from SilverStripe and builds the filters on the frontend
 * Then trigger the set up of their click events
 * @param {any[]} ids - optional array of city IDs to select suburbs from.
 * @param {boolean} async - whether the function should run asynchronously or should block (defaults to async).
 */
function buildSuburbs(ids = [], async = true) {
    let urlIDs = "";
    if (ids.length) {
        urlIDs = ids.join();
    }

    let url = 'locations/suburbs/'+urlIDs;
    $.ajax({
        type: 'GET',
        url: url,
        async: async
    }).done(function (response) {
        let group = $('.filter-group.js-group--suburb');
        group.empty();

        response.forEach(function (item) {
            if (item.title !== '') {
                let option = newFilterOption(item.ID, item.Title, "suburb");
                group.append(option);
            }
        });

        setFilterEvents(group.children());
    })
}

/**
 * Gets search type specific prices from SilverStripe and builds the filters on the frontend
 * Then trigger the set up of their click events
 * @param {boolean} async - whether the function should run asynchronously or should block (defaults to async).
 */
function buildPrices(async = true) {
    let searchType = $('#search_page_type');
    searchType = searchType ? searchType.val() : "";
    let url = 'filters/prices/?ID='+searchType;
    $.ajax({
        type: 'GET',
        url: url,
        async: async
    }).done(function (response) {
        let priceTypes = ["min", "max"];

        priceTypes.forEach(function (priceType) {
            let group = $('.filter-group.js-group--price-'+priceType);
            group.empty()

            response.forEach(function (item) {
                if (item.title !== '') {
                    let option = newFilterOption(item.ID, item.Title, "price_"+priceType, true);
                    group.append(option);
                }
            });

            setFilterEvents(group.children());
        })
    })
}

/**
 * Set the click events for a list of filters
 * @param {any[]} filterOptions - optional list of filters to set for (defaults to all existing filter options).
 */
function setFilterEvents(filterOptions = []) {
    if (!filterOptions.length) {
        filterOptions = $('.filter-option');
    }
    $.each(filterOptions, function () {
        $(this).unbind('click')
    });
    filterOptions.click(function (event) {
        let option = $(this);
        option.toggleClass('is-checked');

        let child = option.children('input').first();
        if (child) {
            child.prop("checked", !child.prop("checked"));

            if (child.prop("type") === "radio") {
                minimiseParent($(this));
            }
            updateText(option.parent().siblings('.filter-button').first(), option);
        }

        // if selected option is a city, update suburbs
        // else if it's type/category, update property counts
        if (option.data('type') === "towncity") {
            let ids = [];
            let checkedInputs = option.parent().find('input').filter(':checked');
            $.each(checkedInputs, function() {
                ids.push($(this).prop("value"))
            })

            // event.which is undefined when the click event is triggered by code rather than actual click
            // so we use this to decide if suburbs can build async (if triggered by code, we need it to wait)
            let async = event.which !== undefined;
            buildSuburbs(ids, async);
        } else if (option.data('type') === "type" || option.data('type') === "category") {
            setCounts();
        }
    });
}

/**
 * Minimise options list of parent
 * @param filterOption - filter to find parent of and minimise.
 */
function minimiseParent(filterOption) {
    let parent = filterOption.parent();
    if (parent) {
        // remove the class
        parent.toggleClass('is-active', false);
    }
}

/**
 * Minimise options lists
 * @param include - string selectors to include in query for elements
 * @param exclude - string selectors to exclude from query for elements
 */
function minimiseOptions(options) {
    $.each(options, function () {
        // remove the class
        $(this).toggleClass('is-active', false);
    })
}

/**
 * Set form values according to URL params, and trigger button texts to update
 */
function setFormValues() {
    //get url params
    //loop through
    //if element exists matching that url param
    //SET SELECTED VALUES (with 'click' so that filter events happen?)
    //UPDATE TEXT
    let urlParams = new URLSearchParams(window.location.search);
    urlParams.forEach(function (value, key) {
        let keyName = key.split('[')[0];
        // console.log(keyName+": "+value);
        if (value) {
            if (keyName === "sortBy") {
                $(".js-listings-sort__dropdown").val(value);
            } else {
                let filterElement = $('.search-'+keyName);

                if (filterElement) {
                    let input = filterElement.find('input[value='+value+']');
                    if (input.length) {
                        if (keyName === "available") {
                            input.click()
                        } else {
                            input.parent().click();
                        }
                    }
                }

            }
        }
    });
}

/**
 * Gets matrix of property details for counting from SilverStripe
 * @param {boolean} async - whether the function should run asynchronously or should block (defaults to async).
 */
function buildPropertyMatrix(async = true) {
    let url = 'locations/propertyCounts/';
    $.ajax({
        type: 'GET',
        url: url,
        async: async
    }).done(function (response) {
        PROPERTY_MATRIX = response;
    })
}

/**
 * Set property counts for all city/suburb options
 */
function setCounts() {
    let filters = $('.js-group--towncity, .js-group--suburb').children();
    let typeVal = selectedRadio("type");
    let categoryVal = selectedRadio("category");

    $.each(filters, function () {
        let filterType = $(this).data('type');
        let filterTitle = $(this).data('title');

        // [filterType] is so it evaluates to the actual value of that variable ("towncity" or "suburb")
        // instead of trying to be "filterType"
        // console.log({type: typeVal, category: categoryVal, [filterType]: filterTitle});
        let count = getPropertyCount({type: typeVal, category: categoryVal, [filterType]: filterTitle});

        $(this).find('label').first().html(filterTitle+" ("+count+")");
    });
}

function updateText(button, option = null) {
    let inputs = [];
    if (option) {
        inputs = option.parent().find('input').filter(':checked');
    } else {
        inputs = button.siblings('.filter-group').find('input').filter(':checked');
    }

    let buttonString = '';

    if (inputs.length) {
        if (inputs.length > 1) {

            $.each(inputs, function(index, value) {
                let isLastElement = index === inputs.length -1;
                buttonString += $(this).data("title");

                if (!isLastElement) {
                    buttonString += ", ";
                }
            });

        } else {
            buttonString = inputs.first().data("title");
        }
    }

    button.prop('innerHTML', (buttonString ? buttonString : (button.data('placeholder') ?? 'Select')));
}

/**
 * Calculate count of matching properties according to named parameters.
 * Should take in either {type, category, city} OR {type, category, suburb}.
 * Object notation in the parameter allows for having named parameters.
 * @param type  buy or rent
 * @param category  residential or commercial
 * @param towncity  city name
 * @param suburb  suburb name
 * @returns {number}
 */
function getPropertyCount({type = "", category = "", towncity = "", suburb = ""}) {

    let matrix = PROPERTY_MATRIX;

    let filtered = matrix.filter(item => {
        let typeMatch = !!type ? type === item.type : true;
        let catMatch = !!category ? category === item.category : true;
        let cityMatch = !!towncity ? towncity === item.towncity : true;
        let subMatch = !!suburb ? suburb === item.suburb : true;

        return typeMatch && catMatch && cityMatch && subMatch;
    });

    return filtered.length;
}

/**
 * Create a new radio or checkbox filter option
 * @param {string} id
 * @param {string} title
 * @param {string} name
 * @param {boolean} isRadio  whether or not element should be a radio option (defaults to false: checkbox)
 * @returns {HTMLDivElement}
 */
function newFilterOption(id, title, name, isRadio = false) {
    let filterOption = document.createElement('div');
    filterOption.className = "filter-option";
    filterOption.dataset.inputValue = id;
    filterOption.dataset.title = title;
    filterOption.dataset.type = name;

    let input = document.createElement('input');
    input.type = isRadio ? "radio" : "checkbox";
    input.name = isRadio ? name : name+"[]";
    input.value = id;
    input.id =  (name+"_"+id);
    input.dataset.title = title;
    input.dataset.id = id;

    let checkmark = document.createElement('span');
    checkmark.className = "checkmark";

    let label = document.createElement('label');
    label.htmlFor = name+"_"+id;
    label.innerHTML = title;

    filterOption.appendChild(input);
    filterOption.appendChild(checkmark);
    filterOption.appendChild(label);

    return filterOption;
}

/**
 * Checks for a selected radio button with matching name, and returns the value
 * @param name
 * @returns {string}
 */
function selectedRadio(name) {
    let option = $('input[name="'+name+'"]:checked');
    return option.length > 0 ? option[0].value : "";
}


// function checkFocusStates(element) {
//     $.each(element.siblings(), function() {
//         console.log($(this));
//         if ($(this).is(":focus") || $(this).find(":focus") || $(this).is(":hover") || $(this).find(":hover")) {
//             return true
//         }
//     });
//
//     return false
// }

let searchAccordion = document.getElementById("js-search-accordion");
if (searchAccordion) {
    searchAccordion.addEventListener('click', () => {
        searchAccordion.parentElement.classList.toggle('is-active');
    });
}
