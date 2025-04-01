export function geocode(address) {
    let url = `https://photon.komoot.io/api/?q=${encodeURIComponent(address)}&limit=1&lang=fr`;

    return fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data && data.features && data.features.length > 0) {
                const lat = data.features[0].geometry.coordinates[1]; // Latitude
                const lon = data.features[0].geometry.coordinates[0]; // Longitude
                return [lat, lon];
            } else {
                console.log("Adresse non trouvée. Essayez un format différent.");
                return null;
            }
        })
        .catch(error => {
            console.error("Erreur lors de la géocodification :", error);
            return null;
        });
}
