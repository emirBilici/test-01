class Dropdown {
    constructor() {
        const buttons = document.querySelectorAll('[data-open-dropdown]')
            , dropdowns = document.querySelectorAll('[data-dropdown]');

        let pairedDropdowns = [...buttons].reduce((acc, button) => {
            const paired = {}
                , data = button.dataset.openDropdown;

            let find = [...dropdowns].filter(dropdown => dropdown.dataset.dropdown === data);
            if(find.length !== 1)
                return false;

            paired["button"] = button;
            paired["dropdown"] = find[0];
            acc.push(paired);

            return acc;
        }, []);

        let pairedOptions = this.options();
        Dropdown.#addClickListener(pairedDropdowns);
        Dropdown.#clickedOutside(pairedDropdowns, pairedOptions);
    }

    /**
     *
     * @param object
     */
    static #addClickListener(object) {
        object.forEach(({button, dropdown}) => {
            button.addEventListener("click", e => {
                e.preventDefault();
                this.#checkDropdown(dropdown);
            });
        });
    }

    /**
     *
     * @param elem
     */
    static #checkDropdown(elem) {
        const {classList} = elem;
        classList.toggle("open");
    }

    /**
     *
     * @param elems
     * @param pairedOptions
     */
    static #clickedOutside(elems, pairedOptions) {
        document.addEventListener("click", e => {
            let path = e.composedPath();
            elems.forEach(elem => {
                const {button, dropdown} = elem;

                if (!path.includes(button) && dropdown.classList.contains("open"))
                    dropdown.classList.remove("open");
            });
            pairedOptions.forEach(elem => {
                const {dropdown, option: button} = elem;

                if (!path.includes(button) && dropdown.classList.contains("active"))
                    dropdown.classList.remove("active");
            });
        });
    }

    /**
     *
     * @returns {*}
     */
    options() {
        const elems = [...document.querySelectorAll("div.options")];
        let pairedOptions = elems.reduce((acc, elem) => {
            let pair = {};
            const ul = elem.querySelector("ul");
            pair["option"] = elem;
            pair["dropdown"] = ul;

            acc.push(pair);
            return acc;
        }, []);

        pairedOptions.forEach(obj => {
            const {
                option: o,
                dropdown: d
            } = obj;
            o.addEventListener("click", e => Dropdown.#handleOption(e, d));
        });

        return pairedOptions;
    }

    /**
     *
     * @param event
     * @param dropdown
     */
    static #handleOption(event, dropdown) {
        event.preventDefault();
        const {classList: c} = dropdown;
        c.toggle("active");
    }
}

export default Dropdown;