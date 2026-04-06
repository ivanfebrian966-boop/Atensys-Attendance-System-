function filterTable() {
    let search = document.getElementById('searchInput').value.toLowerCase();
    let status = document.getElementById('statusFilter').value;

    document.querySelectorAll("#manageTable tbody tr").forEach(row => {
        let text = row.innerText.toLowerCase();
        let s = row.querySelector(".status").innerText;

        row.style.display =
            (text.includes(search)) &&
            (!status || s.includes(status))
            ? "" : "none";
    });
}

document.querySelectorAll("#searchInput,#statusFilter")
    .forEach(el => el.addEventListener("input", filterTable));


// ACTION BUTTON (dummy)
document.querySelectorAll(".btn-approve").forEach(btn => {
    btn.addEventListener("click", () => {
        alert("Approved ✅");
    });
});

document.querySelectorAll(".btn-reject").forEach(btn => {
    btn.addEventListener("click", () => {
        alert("Rejected ❌");
    });
});