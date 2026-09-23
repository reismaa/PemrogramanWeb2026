async function muatDaftarData(urlJson, daftarKunci) {
    const tbody = document.querySelector(".table-responsive table tbody");
    const loading = document.getElementById("loading-indicator");
    if (!tbody) return;
 
    if (loading) loading.style.display = "block";
    tbody.innerHTML = "";
 
    try {
        // simulasi delay jaringan agar loading indicator terlihat
        await new Promise((resolve) => setTimeout(resolve, 3000));
 
        const res = await fetch(urlJson);
        if (!res.ok) {
            throw new Error("Gagal mengambil data (status " + res.status + ")");
        }
        const daftarData = await res.json();
 
        daftarData.forEach(function (item) {
            const tr = document.createElement("tr");
 
            const selKolom = daftarKunci
                .map(function (kunci) {
                    return "<td>" + item[kunci] + "</td>";
                })
                .join("");
 
            tr.innerHTML =
                selKolom +
                "<td>" +
                "<button type=\"button\">Edit</button> " +
                "<button type=\"button\" class=\"btn-hapus\">Hapus</button>" +
                "</td>";
            tbody.appendChild(tr);
        });
    } catch (err) {
        const kolomSpan = daftarKunci.length + 1; // +1 untuk kolom Aksi
        tbody.innerHTML =
            "<tr><td colspan=\"" + kolomSpan + "\">Gagal memuat data: " + err.message + "</td></tr>";
    } finally {
        if (loading) loading.style.display = "none";
    }
}
