// https://developers.elementor.com/add-javascript-to-elementor-widgets/
class WidgetHandlerClass extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
        return {
            selectors: {
                menuToggle: '.menu-toggle', // get selector from HTML
                navigationContainer: '.child-navigation-inner',
                navigationIcon: '.menu-toggle-icon',
            },
        };
    }

    getDefaultElements() {
        const selectors = this.getSettings('selectors');
        return {
            $menuToggle: this.$element.find(selectors.menuToggle),
            $navigationContainer: this.$element.find(selectors.navigationContainer),
            $navigationIcon: this.$element.find(selectors.navigationIcon),
        };
    }

    bindEvents() {
        this.elements.$menuToggle.on('click', this.onFirstSelectorClick.bind(this));
    }

    onFirstSelectorClick(event) {
        event.preventDefault();
        // toggle icon
        this.elements.$navigationIcon.toggleClass('active');

        // toggle menu
        this.elements.$navigationContainer.toggleClass('active');

    }
}

jQuery(window).on('elementor/frontend/init', () => {
    const addHandler = ($element) => {
        elementorFrontend.elementsHandler.addHandler(WidgetHandlerClass, {
            $element,
        });
    };

    elementorFrontend.hooks.addAction('frontend/element_ready/child-navigation.default', addHandler);
});