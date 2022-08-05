import Validation from "./Validation.js";
import Alert from "./Alert.js";

/**
 * Validating authentication forms
 * using the validation class
 */
!(async function() {
    "use strict";

    /**
     *
     * @type {URL}
     */
    const uri = new URL(location.href);

    /**
     *
     * @type {string}
     */
    const path = uri.pathname;

    /**
     *
     * @param formID
     * @returns {Promise<unknown>}
     */
    const getForm = formID => new Promise((resolve, reject) => {
        document.addEventListener("DOMContentLoaded", () => {
            let formElement = document.getElementById(formID);
            if (formElement) {
                resolve(formElement);
            } else {
                reject({
                    errMsg: "form_not_found",
                    id: formID
                });
            }
        });
    });

    /**
     *
     * @param form
     * @param button
     */
    const disableForm = (form, button) => {
        if (!form.classList.contains("disabled"))
            form.classList.add("disabled");
        button.disabled = true;
    }

    /**
     *
     * @param form
     * @param button
     */
    const revealForm = (form, button) => {
        if (form.classList.contains("disabled"))
            form.classList.remove("disabled");
        button.disabled = false;
    }

    /**
     *
     * @param elemID
     * @returns {HTMLElement|boolean}
     */
    const getElem = elemID => document.getElementById(elemID) ?? false;

    /**
     *
     * @param msg
     * @param type
     * @constructor
     */
    const ALERT = (msg, type = "success") => {
        const alert = new Alert(type);
        alert.setMessage(msg);
        alert.showAlert();
    }

    /**
     *
     * @param url
     * @param data
     * @returns {Promise<any>}
     */
    const fetchApi = async (url, data) => {
        const response = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify(data)
        }); return await response.json();
    }
    /**
     *
     * @type {HTMLElement}
     */
    const FORM_ERROR_ELEM = document.getElementById("form-error");

    /**
     *
     * @param message
     * @param status
     * @constructor
     */
    const FORM_ERROR = (message = null, status = 1) => {
        if (status) {
            FORM_ERROR_ELEM.innerText = message;
            FORM_ERROR_ELEM.style.display = "block";
        } else {
            FORM_ERROR_ELEM.innerText = message;
            FORM_ERROR_ELEM.style.display = "none"
        }
    }

    // Define authentication forms
    switch (path) {
        case "/login":
            await Login();
            break;
        case "/signup":
            await Signup();
            break;
        case "/new/post":
            await NewPost();
            break;
        default:
            return false;
    }

    /**
     *
     * @returns {Promise<void>}
     * @constructor
     */
    async function Signup()
    {
        /**
         *
         * @type {*}
         */
        const form = await getForm("sign-up")
            .catch(({errMsg, id}) => {
                if (errMsg === "form_not_found") {
                    console.error(`Form with id #${id} not found in the this document.`)
                }
            });

        /**
         *
         * @param value
         * @returns {Promise<void>}
         */
        const handleForm = async (value) => {
            /**
             * Get First data using
             * validation class
             */
            let firstData = value.reduce((acc, dataObject) => {
                switch (dataObject.type) {
                    case "text":
                        acc["username"] = dataObject.data[0][1];
                        break;
                    case "email":
                        acc["email"] = dataObject.data[0][1];
                        break;
                }
                return acc;
            }, {});

            /**
             * Check password strength and display
             * errors to user:
             */
            const Rules = [...document.querySelectorAll("span.rule[data-rule]")]
                , passwordInput = getElem("newPass")
                , confirmPasswordInput = getElem("confirmPass");

            try {
                if (!passwordInput || !confirmPasswordInput || Rules.length === 0) {
                    throw new Error("Some form elements not found in this document.");
                }
            } catch(e) {
                console.error(e); // display error to developer
            }

            /**
             *
             * @param elem
             * @constructor
             */
            const CheckPassword = function(elem) {
                this.input = elem;
                this.inputValue = null;
                this.confirmed = null;
                this.empty = null;
                this.spaces = null;
                this.lowercase = null;
                this.uppercase = null;
                this.length = null;
            };

            /**
             *
             * @type {{checkAll: ((function(): (boolean))|*), constructor: CheckPassword, isEmpty: (function(): CheckPassword), atLeastOneNumber: (function(): CheckPassword), isConfirmed: (function(): CheckPassword), containSpaces: (function(): CheckPassword), atLeastOneLowercase: (function(): CheckPassword), maxLength: (function(): CheckPassword), atLeastOneUppercase: (function(): CheckPassword)}}
             */
            CheckPassword.prototype = {
                constructor: CheckPassword,
                isEmpty: function() {
                    let value = this.input.value.trim();
                    this.empty = value.length === 0;
                    this.inputValue = value;
                    return this;
                },
                containSpaces: function() {
                    if (String(this.inputValue).match(/^(?=.*\s)/)) {
                        this.spaces = true;
                    } else {
                        this.spaces = false;
                    }
                    return this;
                },
                atLeastOneLowercase: function() {
                    (!/^(?=.*[a-z])/.test(this.inputValue)) ? this.lowercase = false : this.lowercase = true;
                    return this;
                },
                atLeastOneUppercase: function() {
                    !/^(?=.*[A-Z])/.test(this.inputValue) ? this.uppercase = false : this.uppercase = true;
                    return this;
                },
                atLeastOneNumber: function() {
                    !/^(?=.*[0-9])/.test(this.inputValue) ? this.number = false : this.number = true;
                    return this;
                },
                maxLength: function() {
                    !/^.{8,16}$/.test(this.inputValue) ? this.length = false : this.length = true;
                    return this;
                },
                isConfirmed: function() {
                    const confirmValue = confirmPasswordInput.value.trim();
                    if(this.inputValue !== confirmValue) {
                        this.confirmed = false;
                    } else {
                        this.confirmed = true;
                    }
                    return this;
                },
                checkAll: function() {
                    let field = this.input.closest(".form-field")
                        , {classList: c} = field;
                    const passErr = msg => {
                        if (!c.contains("error"))
                            c.add("error");
                        FORM_ERROR(msg);
                        passwordInput.focus();
                    };

                    if (this.empty === true || this.spaces === true) {
                        passErr("Şifre boş olamaz!");
                    } else {
                        if (c.contains("error"))
                            c.remove("error");
                        FORM_ERROR("", 0);
                    }

                    /**
                     *
                     * @type {*[]}
                     */
                    const errorRules = [];

                    !this.lowercase ? errorRules.push({
                        span: Rules.find(elem => elem.dataset.rule === "lowercase"),
                        type: "lowercase"
                    }) : false;
                    !this.uppercase ? errorRules.push({
                        span: Rules.find(elem => elem.dataset.rule === "uppercase"),
                        type: "uppercase"
                    }) : false;
                    !this.number ? errorRules.push({
                        span: Rules.find(elem => elem.dataset.rule === "number"),
                        type: "number"
                    }) : false;
                    !this.length ? errorRules.push({
                        span: Rules.find(elem => elem.dataset.rule === "count"),
                        type: "length"
                    }) : false;

                    Rules.forEach(elem => {
                        if(elem.classList.contains("error"))
                            elem.classList.remove("error");
                    });
                    errorRules.forEach(({span: elem}) => {
                        if (!elem.classList.contains("error"))
                            elem.classList.add("error");
                    });
                    let errorTypes = errorRules.reduce((acc, error) => {
                        acc.push(error.type)
                        return acc;
                    }, []);

                    if (errorTypes.length === 0) {
                        // Let's create second data
                        if (!this.confirmed) {
                            passErr("Şifreler eşleşmedi!");
                        } else {
                           return true;
                        }
                    }

                    return false;
                }
            }

            /**
             *
             * @type {CheckPassword}
             */
            const check = new CheckPassword(passwordInput);

            /**
             *
             * @type {boolean}
             */
            const checked = check.isEmpty()
                .containSpaces()
                .atLeastOneLowercase()
                .atLeastOneUppercase()
                .atLeastOneNumber()
                .maxLength()
                .isConfirmed()
                .checkAll();

            if (checked) {
                /**
                 * Add password to first data
                 * @type {*&{password: *, submit: string}}
                 */
                let data = {
                    ...firstData,
                    password: passwordInput.value,
                    submit: "register-form"
                }

                /**
                 *
                 * @type {*}
                 */
                const jsonData = await fetchApi("/auth/signup", data);
                const {message, status, target} = jsonData;
                let targetElement = document.getElementById(target);

                if (!status) {
                    // get error
                    if (target.length === 0) {
                        form.reset();
                        FORM_ERROR(message);
                    } else {
                        const field = targetElement.closest(".form-field")
                            , errText = field.querySelector("small.error");

                        if (!field.classList.contains("error"))
                            field.classList.add("error");
                        errText.innerText = message;
                    }
                } else {
                    if (message === "account_created" && status === 1) {
                        targetElement.disabled = true;
                        if (!form.classList.contains("disabled"))
                            form.classList.add("disabled");
                        console.log(">> redirecting login >>");

                        const {username, password} = data;
                        /**
                         *
                         * @type {*}
                         */
                        const redirectLoginData = await fetchApi("/auth/login", {
                            u: username,
                            p: password,
                            submit: "directly"
                        });

                        /**
                         *
                         * @type {string}
                         */
                        location.href = !redirectLoginData.accepted ? "/login" : "/";
                    }
                }
            }
        };

        /**
         * Start validation class
         */
        new Validation(form, handleForm);
    }

    /**
     *
     * @returns {Promise<void>}
     * @constructor
     */
    async function Login()
    {
        /**
         *
         * @type {*}
         */
        const form = await getForm("log-in")
            .catch(({errMsg, id}) => {
                if (errMsg === "form_not_found") {
                    console.error(`Form with id #${id} not found in the this document.`)
                }
            });
        /**
         *
         * @type {HTMLElement|boolean}
         */
        const submitBtn = getElem("submit-btn");

        /**
         *
         * @param value
         * @returns {Promise<void>}
         */
        const handleForm = async (value) => {
            /**
             *
             * @type {{}}
             */
            let formData = {};
            value.forEach(({data, type}) => {
                if (type !== "checkbox") {
                    for (const datum of data) {
                        const [key, value] = datum;
                        key === "username_or_email" ? formData["username_or_email"] = value : false;
                        key === "currPass" ? formData["password"] = value : false;
                    }
                } else {
                    data.forEach(({element, checked}) => {
                        if (element.id === "rememberMe") {
                            formData["remember_me"] = checked;
                        }
                    });
                }
            })

            /**
             *
             * @type {string}
             */
            formData["submit"] = "login-form";
            disableForm(form, submitBtn);

            const {accepted} = await fetchApi("/auth/login", formData);
            if (!accepted) {
                revealForm(form, submitBtn);
                FORM_ERROR("Girdiğiniz bilgilere ait kullanıcı bulunamadı");
            } else {
                const $url = new URL(location.href)
                    , $key = $url.searchParams.get('redirect_uri') ?? false;

                if ($key) {
                    location.href = $key;
                } else location.href = "/";
            }
        }

        /**
         * Start validation class
         */
        new Validation(form, handleForm);
    }

    async function NewPost()
    {
        const markdown = new SimpleMDE({
            element: document.getElementById("md_editor"),
            toolbar: [
                {
                    name: "heading-3",
                    action: SimpleMDE.toggleHeading3,
                    className: "fa fa-header fa-header-x fa-header-3",
                    title: "Başlık"
                },
                {
                    name: "bold",
                    action: SimpleMDE.toggleBold,
                    className: "fa fa-bold",
                    title: "Kalın Yazı"
                },
                {
                    name: "code",
                    action: SimpleMDE.toggleCodeBlock,
                    className: "fa fa-code",
                    title: "Kod"
                },
                {
                    name: "link",
                    action: SimpleMDE.drawLink,
                    className: "fa fa-link",
                    title: "Link"
                },
                "|",
                {
                    name: "preview",
                    action: SimpleMDE.togglePreview,
                    className: "fa fa-eye no-disable",
                    title: "Ön izleme"
                }
            ],
            placeholder: "Buraya yazın..",
            autosave: true,
            spellChecker: false
        });

        const form = await getForm("new-post")
            .catch(({errMsg, id}) => {
                if (errMsg === "form_not_found") {
                    console.error(`Form with id #${id} not found in the this document.`)
                }
            });

        const handleForm = async (value) => {
            let formData = {};
            value.forEach(({data}) => {
                for (const datum of data) {
                    const [key, value] = datum;
                    key === "post_title" ? formData["post_title"] = value : false;
                    key === "featured_code" ? formData["featured_code"] = value : false;
                }
            });
            formData["submit"] = "new_post";

            const descVal = markdown.value().trim().length;
            let md_field = document.getElementById('md_editor').closest('.form-field');
            if (descVal === 0) {
                if (!md_field.classList.contains('error'))
                    md_field.classList.add('error');
            } else {
                if (md_field.classList.contains('error'))
                    md_field.classList.remove('error');

                const select = getElem("tag");
                let field = select.closest(".form-field")
                    , {classList: c} = field
                    , index = select.selectedIndex;

                if(index === 0) {
                    if (!c.contains("error"))
                        c.add("error");
                } else {
                    if (c.contains("error"))
                        c.remove("error");

                    formData["tagIndex"] = select.options[index].value;
                    formData["description"] = markdown.value();
                    const response = await fetchApi("/new/post", formData);
                    if (response === "accepted") {
                        // success
                        form.reset();
                        window.scrollTo(0,0);
                        markdown.value("");
                        ALERT("Post başarıyla oluşturuldu!");
                    } else if (response === "reject") {
                        // error
                        ALERT("Post başarıyla oluşturuldu!", "danger");
                    } else location.reload();
                }
            }
        }

        new Validation(form, handleForm);
    }

})()