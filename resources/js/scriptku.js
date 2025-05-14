// Fungsi untuk tombol Tempel dari clipboard
document.querySelector("button").addEventListener("click", async function () {
    try {
        const text = await navigator.clipboard.readText();
        document.getElementById("videoUrl").value = text;
    } catch (err) {
        alert("Gagal menempel dari clipboard. Izinkan akses clipboard.");
    }
});

// Fungsi Download MP4
function downloadmp4() {
    const url = document.getElementById("videoUrl").value;
    if (!url) {
        alert("Silakan masukkan URL terlebih dahulu.");
        return;
    }

    // Ganti ini dengan request ke backend Laravel-mu nanti
    alert("Proses download MP4 untuk URL:\n" + url);
}
