<input type="text" id="alamat" placeholder="Masukkan desa/jalan">
<button onclick="cariLokasi()">Cari Lokasi</button>

<p id="hasil">Hasil: -</p>

<script>
    async function cariLokasi() {
        let alamat = document.getElementById("alamat").value;

        if (!alamat) {
            alert("Isi alamat dulu");
            return;
        }

        document.getElementById("hasil").innerHTML = "Mencari...";

        try {
            let response = await fetch(
                `https://nominatim.openstreetmap.org/search?q=${alamat}&format=json`
            );

            let data = await response.json();

            if (data.length > 0) {
                let lat = data[0].lat;
                let lon = data[0].lon;
                let nama = data[0].display_name;

                document.getElementById("hasil").innerHTML = `
                <b>Nama:</b> ${nama} <br>
                <b>Latitude:</b> ${lat} <br>
                <b>Longitude:</b> ${lon}
            `;
            } else {
                document.getElementById("hasil").innerHTML = "Lokasi tidak ditemukan";
            }

        } catch (error) {
            document.getElementById("hasil").innerHTML = "Error ambil data";
        }
    }
</script>
