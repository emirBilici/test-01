import Form from "./Form.js";

class Validation extends Form
{
    /**
     *
     * @type {[]}
     */
    static errors = [];

    /**
     *
     * @param form
     * @param handleFnc
     */
    constructor(form, handleFnc) {
        super(form);
        this.listenForm(this.checkForm, handleFnc);
    }

    /**
     *
     * @param form
     * @returns {*|boolean}
     */
    checkForm(form) {
        /**
         *
         * @type {*[]}
         */
        let controls = [];

        /**
         *
         * @type {{input: {number: *, password: *, checkbox: *, text: *, email: *, radio: *}, textarea: *[]}}
         */
        let inputs = super.getInputs(form);

        /**
         *
         * @param key
         * @param value
         * @param status
         */
        const setControl = (key, value, status) => {
            /**
             *
             * @type {{check: {elements, name}, status}}
             */
            let pushObj = {
                check: {
                    name: key,
                    elements: value
                },
                status: status
            }
            controls.push(pushObj);
        }

        for (const input of Object.entries(inputs)) {
            const [key, value] = input;

            /**
             *
             * @type {arg is any[]}
             */
            let isArray = Array.isArray(value);

            if (isArray) {
                // textareas
                if (value.length === 0) {
                    setControl(key, value, 0); // Push check object to the control object
                } else {
                    setControl(key, value, 1);
                }
            } else {
                // inputs
                for (const entry of Object.entries(value)) {
                    const [key, value] = entry
                        , {length} = value;

                    if (length === 0) {
                        setControl(key, value, 0);
                    } else {
                        setControl(key, value, 1);
                    }
                }
            }
        }

        /**
         *
         * @type {*[]}
         */
        let getControls = controls.filter(control => control.status === 1);
        return Validation.#controlElements(getControls, form);
    }

    /**
     *
     * @param elems
     * @param form
     * @returns {boolean|*}
     */
    static #controlElements(elems, form) {
        const array = elems.reduce((acc, elem) => {
            acc.push(elem.check);
            return acc;
        }, []);

        for (let i = 0; i < array.length; i++) {
            const {name, elements} = array[i];
            let checkElems = elements.filter(elem => elem.dataset.novalidate !== "true");

            switch (name) {
                case "text":
                    this.#checkText(checkElems, form);
                    break;
                case "email":
                    this.#checkEmail(checkElems, form);
                    break;
                case "password":
                    this.#checkPassword(checkElems, form);
                    break;
                case "textarea":
                    this.#checkTextarea(elements, form);
                    break;
            }
        }

        if (this.errors.length === 0) {
            // callback fnc
            const {classList: c} = form;
            if (c.contains("form-error"))
                c.remove("form-error");
            return elems;
        }
        return false;
    }

    /**
     *
     * @param elements
     * @param form
     */
    static #checkText(elements, form) {
        elements.forEach(textElem => {
            /**
             *
             * @type {boolean|string}
             */
            let check = this.checkInputLength(textElem);

            /**
             *
             * @type {string|boolean}
             */
            let dataCheck = textElem.dataset.check ?? false;

            if (!check) {
                // get error
                if (dataCheck === "username/email") {
                    this.#formError({
                        type: "empty",
                        key: "Kullanıcı adı / email"
                    }, form, textElem);
                } else if (dataCheck === "username")  {
                    this.#formError({
                        type: "empty",
                        key: "Kullanıcı adı"
                    }, form, textElem);
                } else {
                    this.#formError({
                        type: "custom_err",
                        key: "Bu alan zorunludur"
                    }, form, textElem);
                }
            } else if (dataCheck === "username"  && (!new RegExp("[a-z0-9_-]{3,16}$").test(check) || check.length > 16)) {
                // get error
                this.#formError({
                    type: "not_valid",
                    key: "Kullanıcı adı"
                }, form, textElem);
            } else {
                /**
                 * No error
                 * @type {*[]}
                 */
                this.errors = this.#removeFormError(textElem);
            }
        });
    }

    /**
     *
     * @param elements
     * @param form
     */
    static #checkEmail(elements, form) {
        elements.forEach(emailElem => {
            /**
             *
             * @type {boolean|string}
             */
            let check = this.checkInputLength(emailElem);

            if (!check) {
                // get error
                this.#formError({
                    type: "empty",
                    key: "Email"
                }, form, emailElem);
            } else if (!String(check).toLowerCase().match(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)) {
                // get error
                this.#formError({
                    type: "not_valid",
                    key: "Email"
                }, form, emailElem);
            } else {
                /**
                 * No error
                 * @type {*[]}
                 */
                this.errors = this.#removeFormError(emailElem);
            }
        });
    }

    /**
     *
     * @param elements
     * @param form
     */
    static #checkPassword(elements, form) {
        elements.forEach(passwordElem => {
            /**
             *
             * @type {boolean|string}
             */
            let check = this.checkInputLength(passwordElem);

            /**
             *
             * @type {string|boolean}
             */
            let data = passwordElem.dataset.confirmPassword ?? false;

            /**
             *
             * @type {HTMLElement|boolean}
             */
            let pairPass = document.getElementById(data) ?? false;

            if (!check) {
                // get error
                this.#formError({
                    type: "empty",
                    key: "Şifre"
                }, form, passwordElem);
            } else if (String(check).match(/^(?=.*\s)/)) {
                // get error
                this.#formError({
                    type: "custom_err",
                    key: "Şifre boşluk içermemelidir"
                }, form, passwordElem);
            } else if (!/^(?=.*[A-Z])/.test(check)) {
                // get error
                this.#formError({
                    type: "custom_err",
                    key: "Şifre en az bir tane büyük harf içermelidir"
                }, form, passwordElem);
            } else if (!/^(?=.*[a-z])/.test(check)) {
                // get error
                this.#formError({
                    type: "custom_err",
                    key: "Şifre en az bir tane küçük harf içermelidir"
                }, form, passwordElem);
            } else if (!/^(?=.*[0-9])/.test(check)) {
                // get error
                this.#formError({
                    type: "custom_err",
                    key: "Şifre en az bir tane rakam içermelidir"
                }, form, passwordElem);
            } else if (!/^.{8,16}$/.test(check)) {
                // get error
                this.#formError({
                    type: "custom_err",
                    key: "Şifre 8-16 karakter uzunluğunda olmalıdır"
                }, form, passwordElem);
            } else if (data && data.length !== 0 && passwordElem.value !== pairPass.value) {
                this.#formError({
                    type: "custom_err",
                    key: "Şifreler eşit değil"
                }, form, passwordElem);
            } else {
                /**
                 * No error
                 * @type {*[]}
                 */
                this.errors = this.#removeFormError(passwordElem);
            }
        });
    }

    /**
     *
     * @param elements
     * @param form
     */
    static #checkTextarea(elements, form) {
        // elements.forEach(textarea => {
        //     if (textarea.dataset.novalidate !== "true") {
        //         /**
        //          *
        //          * @type {string|boolean}
        //          */
        //         let check = this.checkInputLength(textarea);
        //
        //         if (!check) {
        //             // get error
        //             this.#formError({
        //                 type: "empty",
        //                 key: "Bu alan"
        //             }, form, textarea);
        //         } else {
        //             /**
        //              *
        //              * @type {*[]}
        //              */
        //             this.errors = this.#removeFormError(textarea);
        //         }
        //     }
        // });
    }

    /**
     *
     * @param inputElem
     * @returns {string|boolean}
     */
    static checkInputLength(inputElem) {
        /**
         *
         * @type {string}
         */
        let value = inputElem.value.trim();
        if (value.length === 0)
            return false;
        return value;
    }


    /**
     *
     * @param props
     */
    static #formError(...props) {
        /**
         *
         * @param msg
         * @returns {`${string} boş olamaz`}
         * @constructor
         */
        const EMPTY_MESSAGE = msg => `${msg} boş olamaz`;

        /**
         *
         * @param msg
         * @returns {`${string} geçersiz`}
         * @constructor
         */
        const NOT_VALID_MESSAGE = msg => `${msg} geçersiz`;

        const [message, form, target] = props
            , {classList: c} = form;
        /**
         *
         * @type {Element|SVGSymbolElement|SVGMetadataElement|SVGUseElement|SVGAnimateElement|SVGFEImageElement|SVGPathElement|SVGViewElement|SVGFEConvolveMatrixElement|SVGFECompositeElement|SVGEllipseElement|SVGFEOffsetElement|SVGTextElement|SVGDefsElement|SVGFETurbulenceElement|SVGImageElement|SVGFEFuncGElement|SVGMPathElement|SVGTSpanElement|SVGClipPathElement|SVGLinearGradientElement|SVGFEFuncRElement|SVGScriptElement|SVGFEColorMatrixElement|SVGFEComponentTransferElement|SVGStopElement|SVGMarkerElement|SVGFEMorphologyElement|SVGFEMergeElement|SVGFEPointLightElement|SVGForeignObjectElement|SVGFEDiffuseLightingElement|SVGStyleElement|SVGFEBlendElement|SVGCircleElement|SVGPolylineElement|SVGDescElement|SVGFESpecularLightingElement|SVGLineElement|SVGFESpotLightElement|SVGFETileElement|SVGPatternElement|SVGTitleElement|SVGSwitchElement|SVGRectElement|SVGFEDisplacementMapElement|SVGFEFuncAElement|SVGFEFuncBElement|SVGFEMergeNodeElement|SVGTextPathElement|SVGFEFloodElement|SVGMaskElement|SVGAElement|SVGAnimateTransformElement|SVGSetElement|SVGSVGElement|SVGAnimateMotionElement|SVGGElement|SVGFEDistantLightElement|SVGFEDropShadowElement|SVGRadialGradientElement|SVGFilterElement|SVGPolygonElement|SVGFEGaussianBlurElement|HTMLSelectElement|HTMLLegendElement|HTMLElement|HTMLTableCaptionElement|HTMLTextAreaElement|HTMLModElement|HTMLHRElement|HTMLOutputElement|HTMLEmbedElement|HTMLCanvasElement|HTMLFrameSetElement|HTMLMarqueeElement|HTMLScriptElement|HTMLInputElement|HTMLMetaElement|HTMLStyleElement|HTMLObjectElement|HTMLTemplateElement|HTMLBRElement|HTMLAudioElement|HTMLIFrameElement|HTMLMapElement|HTMLTableElement|HTMLAnchorElement|HTMLMenuElement|HTMLPictureElement|HTMLParagraphElement|HTMLTableCellElement|HTMLTableSectionElement|HTMLQuoteElement|HTMLProgressElement|HTMLLIElement|HTMLTableRowElement|HTMLFontElement|HTMLSpanElement|HTMLTableColElement|HTMLOptGroupElement|HTMLDataElement|HTMLDListElement|HTMLFieldSetElement|HTMLSourceElement|HTMLBodyElement|HTMLDirectoryElement|HTMLDivElement|HTMLUListElement|HTMLDetailsElement|HTMLHtmlElement|HTMLAreaElement|HTMLPreElement|HTMLMeterElement|HTMLFrameElement|HTMLOptionElement|HTMLImageElement|HTMLLinkElement|HTMLHeadingElement|HTMLSlotElement|HTMLVideoElement|HTMLTitleElement|HTMLButtonElement|HTMLHeadElement|HTMLDialogElement|HTMLParamElement|HTMLTrackElement|HTMLOListElement|HTMLDataListElement|HTMLLabelElement|HTMLFormElement|HTMLTimeElement|HTMLBaseElement|boolean}
         */
        const button = form.querySelector('button[type="submit"]') ?? false;

        if(!this.errors.includes(this.errors.filter(err => err === target)[0]))
            this.errors.push(target);

        /**
         *
         * @type {null}
         */
        let errText = null;
        switch (message.type) {
            case "empty":
                errText = EMPTY_MESSAGE(message.key);
                break;
            case "not_valid":
                errText = NOT_VALID_MESSAGE(message.key);
                break;
            case "custom_err":
                errText = message.key;
        }

        try {
            if (!button)
                throw new Error("The form should've a button.");
        } catch(e) {
            console.error(e);
        }

        if (!c.contains("form-error"))
            c.add("form-error");

        /**
         *
         * @type {Element | SVGSymbolElement | SVGMetadataElement | SVGUseElement | SVGAnimateElement | SVGFEImageElement | SVGPathElement | SVGViewElement | SVGFEConvolveMatrixElement | SVGFECompositeElement | SVGEllipseElement | SVGFEOffsetElement | SVGTextElement | SVGDefsElement | SVGFETurbulenceElement | SVGImageElement | SVGFEFuncGElement | SVGMPathElement | SVGTSpanElement | SVGClipPathElement | SVGLinearGradientElement | SVGFEFuncRElement | SVGScriptElement | SVGFEColorMatrixElement | SVGFEComponentTransferElement | SVGStopElement | SVGMarkerElement | SVGFEMorphologyElement | SVGFEMergeElement | SVGFEPointLightElement | SVGForeignObjectElement | SVGFEDiffuseLightingElement | SVGStyleElement | SVGFEBlendElement | SVGCircleElement | SVGPolylineElement | SVGDescElement | SVGFESpecularLightingElement | SVGLineElement | SVGFESpotLightElement | SVGFETileElement | SVGPatternElement | SVGTitleElement | SVGSwitchElement | SVGRectElement | SVGFEDisplacementMapElement | SVGFEFuncAElement | SVGFEFuncBElement | SVGFEMergeNodeElement | SVGTextPathElement | SVGFEFloodElement | SVGMaskElement | SVGAElement | SVGAnimateTransformElement | SVGSetElement | SVGSVGElement | SVGAnimateMotionElement | SVGGElement | SVGFEDistantLightElement | SVGFEDropShadowElement | SVGRadialGradientElement | SVGFilterElement | SVGPolygonElement | SVGFEGaussianBlurElement | HTMLSelectElement | HTMLLegendElement | HTMLElement | HTMLTableCaptionElement | HTMLTextAreaElement | HTMLModElement | HTMLHRElement | HTMLOutputElement | HTMLEmbedElement | HTMLCanvasElement | HTMLFrameSetElement | HTMLMarqueeElement | HTMLScriptElement | HTMLInputElement | HTMLMetaElement | HTMLStyleElement | HTMLObjectElement | HTMLTemplateElement | HTMLBRElement | HTMLAudioElement | HTMLIFrameElement | HTMLMapElement | HTMLTableElement | HTMLAnchorElement | HTMLMenuElement | HTMLPictureElement | HTMLParagraphElement | HTMLTableCellElement | HTMLTableSectionElement | HTMLQuoteElement | HTMLProgressElement | HTMLLIElement | HTMLTableRowElement | HTMLFontElement | HTMLSpanElement | HTMLTableColElement | HTMLOptGroupElement | HTMLDataElement | HTMLDListElement | HTMLFieldSetElement | HTMLSourceElement | HTMLBodyElement | HTMLDirectoryElement | HTMLDivElement | HTMLUListElement | HTMLDetailsElement | HTMLHtmlElement | HTMLAreaElement | HTMLPreElement | HTMLMeterElement | HTMLFrameElement | HTMLOptionElement | HTMLImageElement | HTMLLinkElement | HTMLHeadingElement | HTMLSlotElement | HTMLVideoElement | HTMLTitleElement | HTMLButtonElement | HTMLHeadElement | HTMLDialogElement | HTMLParamElement | HTMLTrackElement | HTMLOListElement | HTMLDataListElement | HTMLLabelElement | HTMLFormElement | HTMLTimeElement | HTMLBaseElement}
         */
        let formField = target.closest(".form-field")
            , {classList: f} = formField
            , smallErr = formField.querySelector("small.error");
        if (!f.contains("error"))
            f.add("error");
        smallErr.innerText = errText;
    }

    /**
     *
     * @param elem
     * @returns {*[]}
     */
    static #removeFormError(elem) {
        const {classList: c} = elem.closest(".form-field");
        if (c.contains("error"))
            c.remove("error");
        return this.errors.filter(err => err !== elem);
    }
}

export default Validation;