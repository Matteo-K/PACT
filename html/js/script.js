const formHeader = document.querySelector("header form");
const searchBar = document.querySelector("header input");

// Modifie le lien vers la recherche a chaque entré
searchBar.addEventListener("input", (e) => {
  formHeader.setAttribute("action", "search.php?search=" + e.target.value);
});


//Page DetailsOffer
document.getElementById('imageInput').addEventListener('change', function (event) {
  const files = event.target.files;
  const previewContainer = document.getElementById('imagePreview');
  previewContainer.innerHTML = ''; // Clear previous previews

  Array.from(files).forEach(file => {
      const reader = new FileReader();
      reader.onload = function (e) {
          const imgDiv = document.createElement('div');
          imgDiv.classList.add('image-preview');
          imgDiv.innerHTML = `<img src="${e.target.result}" alt="Image Preview">`;
          previewContainer.appendChild(imgDiv);
      }
      reader.readAsDataURL(file);
  });
});

  // Ici, vous pouvez envoyer les images au serveur si nécessaire
  // const formData = new FormData();
  // Array.from(files).forEach(file => {
  //     formData.append('images[]', file);
  // });
  // fetch('YOUR_SERVER_URL', {
  //     method: 'POST',
  //     body: formData
  // }).then(response => {
  //     console.log('Images envoyées avec succès!');
  // }).catch(error => {
  //     console.error('Erreur lors de l\'envoi:', error);
  // });
});