class SimplifiedProjectCreation extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
        return {
            selectors: {
                widgetElement: '.simplified-project-creation-elementor-widget'
            }
        };
    }

    getDefaultElements() {
        const selectors = this.getSettings( 'selectors' );
        return {
            $widgetElement: this.$element.find( selectors.widgetElement )
        };
    }

    bindEvents() {
        this.elements.$widgetElement.on( 'click', this.onFirstSelectorClick.bind( this ) );
    }

    onFirstSelectorClick( event ) {
        event.preventDefault();

        this.elements.$widgetElement.show();
    }
}

jQuery( window ).on( 'elementor/frontend/init', () => {
    const addHandler = ( $element ) => {
        elementorFrontend.elementsHandler.addHandler( SimplifiedProjectCreation, {
            $element,
        } );
        
        let widgetElement = $element[0];
        let bookSizesSelect = jQuery(widgetElement).find('.book-size select');
        let buttonRedirectElement = jQuery(widgetElement).find('.action a');

        if (bookSizesData.activatedBookSizes.length > 0) {
            bookSizesData.activatedBookSizes.forEach((activatedBookSize) => {
                const bookSize = bookSizesData.bookSizes[activatedBookSize];

                jQuery(bookSizesSelect).append(`
                    <option value='${ bookSize.id }'>
                        ${ bookSize.name }
                    </option>
                `);
            });
        } else {
            bookSizesData.bookSizes.forEach((bookSize) => {
                jQuery(bookSizesSelect).append(`
                    <option value='${ bookSize.id }'>
                        ${ bookSize.name }
                    </option>
                `);
            });
        }

        jQuery(bookSizesSelect).on('change', (e) => {
            const selectElement = e.target;
            const selectedBookSize = jQuery(selectElement).find(':selected').val();

            jQuery(buttonRedirectElement).attr('href', `${bookSizesData.companyDomain}/fotolivros/
                createSimpleProject?bookSize=${ selectedBookSize }`);
            jQuery(buttonRedirectElement).attr('bookSize', selectedBookSize);
        });

        jQuery(buttonRedirectElement).on('click', (e) => {
            window.location = jQuery(e.target).attr('href');
        });

        jQuery(buttonRedirectElement).attr('bookSize', '');
    };

    elementorFrontend.hooks.addAction( 'frontend/element_ready/simplified-project-creation.default', addHandler );
} );