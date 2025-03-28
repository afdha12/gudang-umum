document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("search");

    searchInput.addEventListener("keyup", function () {
        let query = this.value.trim();
        let type = new URLSearchParams(window.location.search).get('type') || '1';

        fetch(`/admin/stationeries?type=${type}&q=${query}`)
            .then(response => response.text())
            .then(html => {
                let parser = new DOMParser();
                let doc = parser.parseFromString(html, "text/html");
                let newTbody = doc.querySelector("tbody");

                if (newTbody) {
                    document.querySelector("tbody").innerHTML = newTbody.innerHTML;
                } else {
                    console.error("Error: Tidak menemukan <tbody> dalam response");
                }
            })
            .catch(error => console.error("Error fetching data:", error));
    });
});
