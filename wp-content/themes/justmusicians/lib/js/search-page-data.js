// Alpine data for the search page form.
// Server-specific values are passed in as config so the static logic stays cacheable.
function searchPageData(config = {}) {
    return {
        showCategoryModal: false,
        showGenreModal: false,
        showSubgenreModal: false,
        showInstrumentationModal: false,
        showSettingModal: false,
        showEnsembleSizeModal: false,
        ensembleSizeCheckboxes: [],
        verifiedCheckbox: false,
        distance: 40,
        tagModalSearchQuery: '',
        ...config,

        updateLocation(location) {
            this.locationInput  = location.label;
            this.searchLocation = location.label;
            this.searchLat      = location.lat;
            this.searchLng      = location.lng;
            this.$nextTick(() => {
                this.$dispatch('filterupdate');
            });
        },

        get selectedFilters() {
            return [...this.categoriesCheckboxes, ...this.genresCheckboxes, ...this.subgenresCheckboxes, ...this.instrumentationsCheckboxes, ...this.settingsCheckboxes, ...this.ensembleSizeCheckboxes, this.verifiedCheckbox ? 'Verified' : '', this.listingSearchVal].filter(Boolean).join(' | ');
        },

        get selectedFiltersCount() {
            return [...this.categoriesCheckboxes, ...this.genresCheckboxes, ...this.subgenresCheckboxes, ...this.instrumentationsCheckboxes, ...this.settingsCheckboxes, ...this.ensembleSizeCheckboxes, this.verifiedCheckbox ? 'Verified' : '', this.listingSearchVal].filter(Boolean).length;
        },

        showTagModalOption(option) {
            return this.tagModalSearchQuery === '' || option.toLowerCase().includes(this.tagModalSearchQuery.toLowerCase());
        },

        clickTagModalOption(event) {
            const target = event.target;
            if (target === event.currentTarget || target.matches('input, label')) return;

            const option = target.classList.contains('custom-checkbox')
                ? target
                : target.parentElement;

            if (!option?.classList.contains('custom-checkbox')) return;

            const input = option.firstElementChild;
            if (input instanceof HTMLInputElement) input.click();
        },

        preloadTagModalOption(event) {
            const target = event.target;
            if (target === event.currentTarget) return;

            const option = target.classList.contains('custom-checkbox')
                ? target
                : target.parentElement;

            if (!option?.classList.contains('custom-checkbox') || option.contains(event.relatedTarget)) return;

            const input = option.firstElementChild;
            if (input instanceof HTMLInputElement) {
                input.dispatchEvent(new CustomEvent('preload-filter'));
            }
        },

        _clearListingForm() {
            clearListingForm(this, this.skipCategoryFilter);
        },
    };
}
