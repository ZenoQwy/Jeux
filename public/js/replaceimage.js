var inputImage = document.querySelector('#ajout_produit_image');

if(inputImage == null ){
    inputImage = document.querySelector('#produit_image');
}

const imagePreview = document.getElementById('imagePreview');

console.log(inputImage)

if (inputImage) {
    inputImage.addEventListener('change', function() {
        if (inputImage.files && inputImage.files[0]) {
            const reader = new FileReader();

            reader.addEventListener('load', function(e) {
                imagePreview.src = e.target.result;
                inputImage.classList.add('d-none');
            });

            reader.readAsDataURL(inputImage.files[0]);
        }
    });
}