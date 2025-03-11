export function geocode(address) {
    let url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&addressdetails=1&limit=1`;

    return fetch(url)
        .then(response => response.json())  // Résoudre la promesse de `fetch()` et récupérer les données JSON
        .then(data => {
            if (data && data.length > 0) {
                const lat = data[0].lat;
                const lon = data[0].lon;
                let coord = [lat, lon];
                return coord;  // Retourner les coordonnées
            } else {
                console.log("Adresse non trouvée.");
                return null;
            }
        })
        .catch(error => {
            console.error("Erreur lors de la géocodification :", error);
            return null;
        });
}
