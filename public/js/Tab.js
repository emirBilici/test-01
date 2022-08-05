class Tab {
    /**
     *
     * @param elem
     * @param defaultTab
     */
    constructor(elem = null, defaultTab = false) {
        this.container = elem;
        const openers = this.getOpeners()
            , containers = this.getContainer();

        this.checkCompatibility(openers, containers);
        const url = new URL(window.location)
            , get = url.searchParams.get("tab") ?? false;

        // ?tab= --> Selecting the default tab using the get parameter
        if (get) {
            const elem = openers.reduce((acc, elem) => {
                const data = elem.dataset.content
                    , value = get.trim();
                if (data === value)
                    acc = elem;

                return acc;
            }, false);

            if (elem) {
                Tab.#setDefaultTab(elem.dataset.content);
            }
        } else if (defaultTab) {
            Tab.#setDefaultTab(defaultTab);
        }
    }

    /**
     *
     * @returns {*[]}
     */
    getOpeners() {
        return [...this.container.querySelectorAll(".openers a")];
    }

    /**
     *
     * @returns {*[]}
     */
    getContainer() {
        return [...this.container.querySelectorAll(".contents div.content")];
    }

    /**
     *
     * @param args
     */
    checkCompatibility(...args) {
        const [openers, containers] = args;
        // Hidden all containers
        Tab.#hideContainers(containers);
        openers.forEach(elem => elem.addEventListener("click", event => Tab.#handleOpener(event, elem, containers, openers)));
    }

    /**
     *
     * @param event
     * @param opener
     * @param containers
     * @param openers
     * @returns {boolean}
     */
    static #handleOpener(event, opener, containers, openers) {
        event.preventDefault();
        const data = opener.dataset.content
            , associatedContainer = containers.filter(container => container.id === data)[0] ?? false;

        // Allows the user to reach the same tab when refreshing the page
        history.pushState({},"",`${new URL(location).pathname}?tab=${data}`);

        if (!associatedContainer)
            return false;
        this.#hideContainers(containers);
        openers.forEach(elem => {
            if (elem.classList.contains("active"))
                elem.classList.remove("active");
        });
        associatedContainer.className = "content show";
        if (!opener.classList.contains("active"))
            opener.classList.add("active");
    }

    /**
     *
     * @param elements
     * @returns {*}
     */
    static #hideContainers(elements) {
        return elements.forEach(elem => elem.className = "content");
    }

    /**
     *
     * @param dataContent
     * @returns {boolean|*|void}
     */
    static #setDefaultTab(dataContent) {
        const opener = document.querySelector(`a[data-content="${dataContent}"]`) ?? false;
        if (!opener)
            return false;
        return opener.click();
    }
}

export default Tab;