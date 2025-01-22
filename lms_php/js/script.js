document.addEventListener("DOMContentLoaded", () => {
    console.log("LMS script loaded!");

    const logoutBtn = document.getElementById("logoutBtn");
    if (logoutBtn) {
        logoutBtn.addEventListener("click", () => {
            fetch("../php/logout.php")
                .then(() => {
                    window.location.href = "../html/login.html";
                })
                .catch(console.error);
        });
    }
});
