<button onclick="getLocation()">Ambil Lokasi</button>

<p id="lokasi">Lokasi: belum diambil</p>

<form method="POST" action="/simpan-lokasi">
    @csrf
    <input type="text" name="latitude" id="latitude" readonly>
    <input type="text" name="longitude" id="longitude" readonly>

    <button type="submit">Simpan</button>
</form>

<script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(async function(position) {

                    let lat = position.coords.latitude;
                    let lng = position.coords.longitude;

                    // isi input
                    document.getElementById("latitude").value = lat;
                    document.getElementById("longitude").value = lng;

                    // tampilkan koordinat dulu
                    document.getElementById("lokasi").innerHTML =
                        "Mencari alamat...";

                    try {
                        // panggil API Nominatim
                        let response = await fetch(
                            `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`
                        );

                        let data = await response.json();

                        let alamat = data.display_name;

                        document.getElementById("lokasi").innerHTML = `
                    <b>Latitude:</b> ${lat} <br>
                    <b>Longitude:</b> ${lng} <br>
                    <b>Lokasi:</b> ${alamat}
                `;

                    } catch (error) {
                        document.getElementById("lokasi").innerHTML =
                            "Koordinat: " + lat + ", " + lng + "<br>Gagal ambil alamat";
                    }

                },
                function(error) {
                    document.getElementById("lokasi").innerHTML =
                        "Gagal ambil lokasi: " + error.message;
                });
        } else {
            document.getElementById("lokasi").innerHTML =
                "Browser tidak support GPS";
        }
    }
</script>
