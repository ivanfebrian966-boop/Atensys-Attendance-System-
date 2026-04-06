function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebar-overlay");

    if (window.innerWidth >= 768) {
        // Desktop behavior: static position
        sidebar.classList.remove(
            "fixed",
            "inset-y-0",
            "left-0",
            "z-50",
            "-translate-x-full",
        );
        sidebar.classList.add("static");

        const texts = document.querySelectorAll(".sidebar-text");

        if (sidebar.classList.contains("w-64")) {
            sidebar.classList.replace("w-64", "w-20");

            texts.forEach((t) => {
                t.classList.add("opacity-0", "scale-95", "-translate-x-2");
            });
        } else {
            sidebar.classList.replace("w-20", "w-64");

            texts.forEach((t) => {
                t.classList.remove("opacity-0", "scale-95", "-translate-x-2");
            });
        }
    } else {
        // Mobile behavior: fixed position
        sidebar.classList.remove("static");
        sidebar.classList.add("fixed", "inset-y-0", "left-0", "z-50");

        if (sidebar.classList.contains("-translate-x-full")) {
            openSidebar();
        } else {
            closeSidebar();
        }
    }
}

function openSidebar() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebar-overlay");
    sidebar.classList.remove("-translate-x-full");
    overlay.classList.remove("hidden");
}

function closeSidebar() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebar-overlay");
    sidebar.classList.add("-translate-x-full");
    overlay.classList.add("hidden");
}

// Handle window resize
window.addEventListener("resize", function () {
    const sidebar = document.getElementById("sidebar");
    if (window.innerWidth >= 768) {
        sidebar.classList.remove(
            "fixed",
            "inset-y-0",
            "left-0",
            "z-50",
            "-translate-x-full",
        );
        sidebar.classList.add("static", "translate-x-0");
    } else {
        sidebar.classList.remove("static");
        sidebar.classList.add(
            "fixed",
            "inset-y-0",
            "left-0",
            "z-50",
            "-translate-x-full",
        );
    }
});
