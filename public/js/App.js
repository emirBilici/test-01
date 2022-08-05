import Alert from "./Alert.js";
import Tab from "./Tab.js";
import Dropdown from "./Dropdown.js";


!(function() {
    "use strict";
    if (!sessionStorage.getItem('updated_votes')) {
        sessionStorage.setItem('updated_votes', JSON.stringify([]));
    }
    new Dropdown(); // Start dropdown class..
})()

/**
 * Loader
 */
document.addEventListener("DOMContentLoaded", e => {
    const loader = document.getElementById("html-loader");
    loader.style.display = "none";
    e.target.body.style.overflow = "auto";
});

!(function() {
    "use strict";
    const url = new URL(window.location)
        , path = url.pathname.substring(0, 9);
    if (path === "/profile/") {
        /**
         * About me
         * User page
         * @type {Element}
         */
        const userContent = document.querySelector('.user-content');
        let maxLength = 345;
        let data = userContent.textContent;
        if (userContent.textContent.length > maxLength) {
            userContent.innerHTML = `${userContent.textContent.slice(0, maxLength)}...<span class="read-more">devamını oku</span>`;
            userContent.title = 'Tamamını görmek için tıkla.';
            userContent.style.cursor = 'pointer';
            userContent.dataset.collapsed = 'true';
        }
        if (userContent.dataset.collapsed) {
            userContent.addEventListener('click', function() {
                if(this.dataset.collapsed === "true") {
                    userContent.dataset.collapsed = 'false';
                    userContent.style.cursor = 'default';
                    userContent.innerText = data;
                }
            });
        }

        /**
         * Share
         * Profile
         */
        const share = document.getElementById("share");
        function handleShare(event) {
            event.preventDefault();

            /**
             * Copy
             * Link
             */
            const copy = this.querySelector(".copy");
            const handleCopy = e => {
                e.preventDefault();
                let text = window.location.href;
                navigator.clipboard.writeText(text)
                    .finally(() => {
                        let alert = new Alert("success");
                        alert.setMessage("Link başarıyla kopyalandı.");
                        alert.showAlert();
                    });
            }
            /**
             * Social
             * Share
             */
            const facebook = this.querySelector('.dropdown li a[data-sharer="facebook"]')
                , twitter = this.querySelector('.dropdown li a[data-sharer="twitter"]');

            facebook.dataset.url = window.location.href;
            twitter.dataset.url = window.location.href;

            copy.addEventListener("click", handleCopy);
        }
        share.addEventListener("click", handleShare);

        /**
         * Tab
         */
        const tabContainer = document.querySelector(".tab");
        new Tab(tabContainer, "entries");
    }
})()