const image = document.querySelector('.menu-detail__image img');
const prev = document.querySelector('.slider-prev');
const next = document.querySelector('.slider-next');
const slider_counter = document.querySelector('.slider-counter');

if (image && prev && next && slider_counter) {

    if (images.length === 1) {
        prev.style.display = 'none';
        next.style.display = 'none';
    }


    // initialisation de l'index à 0 (première image)
    let currentIndex = 0;

    function updateImage() {
        const currentImage = images[currentIndex];
        image.src = currentImage.url;
        image.alt = currentImage.alt;
        slider_counter.textContent = (currentIndex + 1) + ' / ' + images.length;

    }

    updateImage();
    //add event sur click
    next.addEventListener('click', () => {
        //increment +1
        currentIndex++;

        //si on dépasse le nombre d'images, on revient à la première
        if (currentIndex >= images.length) {
            currentIndex = 0;
        }

        //met à jour l'image affichée avec la nouvelle url
        updateImage();
    });

    prev.addEventListener('click', () => {
        //increment -1
        currentIndex--;

        //si on dépasse les photos en négative on va à la dernière photo
        if (currentIndex < 0) {
            currentIndex = images.length - 1;
        }

        updateImage();
    });

} else {
    console.log('aucune image à afficher')
}