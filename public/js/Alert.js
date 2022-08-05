class Alert {
    constructor(type = "default") {
        // Create alert elem
        this.element = null;
        this.createElem(type);
    }
    createElem(alertType) {
        const elem = document.createElement("div");
        elem.className = "alert";
        switch (alertType) {
            case "success":
                elem.classList.add("success");
                break;
            case "danger":
                elem.classList.add("danger");
                break;
        }
        return this.element = elem;
    }
    setMessage(text) {
        this.element.innerText = text;
        return this.element;
    }
    showAlert(timeout = 2000) {
        const elem = this.element
            , container = document.getElementById("container");
        container.appendChild(elem);
        setTimeout(() => elem.remove(), timeout);
    }
}

export default Alert;