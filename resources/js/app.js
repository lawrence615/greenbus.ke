import "./bootstrap";

import notyf from "./toast";

document.addEventListener("DOMContentLoaded", () => {
    if (window.__flashSuccess) {
        notyf.success(window.__flashSuccess);
    }
    if (window.__flashError) {
        notyf.error(window.__flashError);
    }
    if (window.__flashInfo) {
        notyf.info(window.__flashInfo);
    }
});
