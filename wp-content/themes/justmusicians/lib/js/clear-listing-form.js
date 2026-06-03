// Clears listing form on search page
function clearListingForm(alco) {
    alco.listingSearchVal = '';
    alco.searchInput = '';
    alco.categoriesCheckboxes = [];
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
