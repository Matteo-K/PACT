async function geocode(address) {
    let url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&addressdetails=1&limit=1`;

    try {
        const response = await fetch(url);
        const data = await response.json();

        if (data && data.length > 0) {
            const lat = data[0].lat;
            const lon = data[0].lon;
            console.log(`Coordonnées géographiques : Latitude = ${lat}, Longitude = ${lon}`);
            return [lat, lon];
        } else {
            console.log("Adresse non trouvée.");
        }
    } catch (error) {
        console.error("Erreur lors de la géocodification :", error);
        return null;
    }
}