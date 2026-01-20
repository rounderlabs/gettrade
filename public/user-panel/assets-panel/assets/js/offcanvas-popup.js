/*==========================
    offcanvas js
 ==========================*/
// window.addEventListener("load", (event) => {
//   var myOffcanvas = document.getElementById("offcanvas");
//   var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
//   bsOffcanvas.show();
// });

// document.addEventListener("DOMContentLoaded", function () {
//     const el = document.getElementById("offcanvas");
//     if (el && window.bootstrap) {
//         new bootstrap.Offcanvas(el);
//     }
// });

//*==========================
Add to Home Screen Popup JS (Safe Version)
==========================*/
let deferredPrompt;

window.addEventListener("beforeinstallprompt", (e) => {
    e.preventDefault();
    deferredPrompt = e;
});

document.addEventListener("DOMContentLoaded", () => {
    const installAppBtn = document.getElementById("installapp");

    // If element not found, safely exit
    if (!installAppBtn) return;

    installAppBtn.addEventListener("click", async () => {
        if (deferredPrompt) {
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            if (outcome === "accepted") {
                console.log("✅ User accepted the PWA install prompt");
                deferredPrompt = null;
            } else {
                console.log("❌ User dismissed the PWA install prompt");
            }
        } else {
            console.log("⚠️ Install prompt not available yet.");
        }
    });
});
