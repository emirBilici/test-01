class Form
{
    /**
     *
     * @param formElem
     */
    constructor(formElem = null)
    {
        try {
            if (!formElem)
                throw new Error("Form class must be have form element");
        } catch(e) {
            console.error(e);
        }

        formElem.setAttribute("novalidate", "true");

        this.form = formElem;

        /**
         *
         * @type {{input: {number: *, password: *, checkbox: *, text: *, email: *, radio: *}, textarea: *[]}}
         */
        this.inputs = this.getInputs(formElem);
    }

    /**
     *
     * @param elem
     * @returns {{input: {number: *, password: *, checkbox: *, text: *, email: *, radio: *}, textarea: *[]}}
     */
    getInputs(elem)
    {
        /**
         *
         * @type {*[]}
         */
        const inputElems = [...elem.querySelectorAll("input")]
            , textareaElems = [...elem.querySelectorAll("textarea")];

        /**
         *
         * @type {*}
         */
        let TEXT_ELEMS = Form.#getElemByType(inputElems,"text")
            , EMAIL_ELEMS = Form.#getElemByType(inputElems,"email")
            , PASSWORD_ELEMS = Form.#getElemByType(inputElems,"password")
            , RADIO_ELEMS = Form.#getElemByType(inputElems,"radio")
            , NUMBER_ELEMS = Form.#getElemByType(inputElems,"number")
            , CHECKBOX_ELEMS = Form.#getElemByType(inputElems,"checkbox");

        return {
            input: {
                text: TEXT_ELEMS,
                email: EMAIL_ELEMS,
                password: PASSWORD_ELEMS,
                radio: RADIO_ELEMS,
                number: NUMBER_ELEMS,
                checkbox: CHECKBOX_ELEMS
            },
            textarea: textareaElems
        }
    }

    /**
     *
     * @param array
     * @param type
     * @returns {*}
     */
    static #getElemByType(array, type) {
        return array.filter(arrElem => arrElem.type === type);
    }

    /**
     *
     * @param callback
     * @param handleFnc
     */
    listenForm(callback, handleFnc) {
        this.form.addEventListener("submit", e => {
            e.preventDefault();
            const check = callback(this.form);
            if (check)
                handleFnc?.(this.editFormData(check));
        });
    }

    /**
     *
     * @param data
     * @returns {*}
     */
    editFormData(data) {
        /**
         * [{type: "text", data: [[key,value][key,value]]}, {type: "email", data: [[key,value][key,value]]}, {type: "password", data: [[key,value][key,value]]}...];
         * @type {{}}
         */
        let text = {}
            , email = {}
            , password = {}
            , radio = {}
            , number = {}
            , checkbox = {}
            , textarea = {};

        return data.reduce((acc, {check}) => {
            switch (check.name) {
                case "text":
                    this.categorizeData(text, check, acc, "text");
                    break;
                case "email":
                    this.categorizeData(email, check, acc, "email");
                    break;
                case "password":
                    this.categorizeData(password, check, acc, "password");
                    break;
                case "radio":
                    this.categorizeData(radio, check, acc, "radio");
                    break;
                case "number":
                    this.categorizeData(number, check, acc, "number");
                    break;
                case "checkbox":
                    this.categorizeData(checkbox, check, acc, "checkbox");
                    break;
                case "textarea":
                    this.categorizeData(textarea, check, acc, "textarea");
                    break;
            }
            return acc;
        }, []);
    }

    /**
     *
     * @param object
     * @param check
     * @param pushTo
     * @param type
     */
    categorizeData(object, check, pushTo, type) {
        object["type"] = type;
        object["data"] = check.elements.reduce((acc, elem) => {
            const {
                id: key,
                value
            } = elem;

            /**
             *
             * @type {*[]}
             */
            let pushArray = [];
            pushArray.push(key);
            pushArray.push(value);
            acc.push(pushArray);
            return acc;
        }, []);
        pushTo.push(object);
        if (type === "checkbox") {
            /**
             *
             * @type {*[]}
             */
            let newData = [];

            for (const objectElement of object.data) {
                const [key] = objectElement;

                /**
                 *
                 * @type {HTMLElement}
                 */
                const elem = document.getElementById(key);

                newData.push({
                    element: elem,
                    checked: elem.checked
                });
            };
            const replaceIndex = pushTo.indexOf(object);
            if (replaceIndex !== -1) {
                /**
                 *
                 * @type {{data: *[], type: string}}
                 */
                pushTo[replaceIndex] = {type: "checkbox", data: newData};
            }
        }
    }
}

export default Form;