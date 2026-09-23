// Clears listing form on search page
function clearListingForm(alco, skipCategory = false) {
    alco.listingSearchVal = '';
    alco.searchInput = '';
    if (!skipCategory) { alco.categoriesCheckboxes = []; }
    alco.genresCheckboxes = [];
    alco.subgenresCheckboxes = [];
    alco.instrumentationsCheckboxes = [];
    alco.settingsCheckboxes = [];
    alco.ensembleSizeCheckboxes = [];
    alco.verifiedCheckbox = false;
    alco.$nextTick(() => {
        alco.$dispatch('filterupdate');
    });
}
