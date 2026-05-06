const filterForm = document.getElementById('filterForm');
const menusGrid = document.getElementById('menusGrid');

filterForm.addEventListener('input', () => {
    chargerMenus();
});

filterForm.addEventListener('change', () => {
    chargerMenus();
});

function chargerMenus() {
    const formData = new FormData(filterForm);
    const params = new URLSearchParams();

    formData.forEach((value, key) => {
        if (value.trim() !== '') {
            params.append(key, value);
        }
    });

    fetch(`api/menus-filtre.php?${params.toString()}`)
        .then(response => response.json())
        .then(menus => {
            afficherTousLesMenus(menus);
        })
        .catch(error => {
            console.error('Erreur lors du chargement des menus :', error);
        });
}

function afficherTousLesMenus(menus) {
    menusGrid.innerHTML = '';

    if (menus.length === 0) {
        const message = document.createElement('p');
        message.textContent = 'Aucun menu ne correspond à votre recherche.';
        menusGrid.appendChild(message);
        return;
    }

    menus.forEach(menu => {
        const article = document.createElement('article');
        article.classList.add('card-menu');

        const image = document.createElement('img');

        if (menu.image_url) {
            image.src = menu.image_url;
        } else {
            image.src = 'https://picsum.photos/seed/menu2-1/800/600';
        }

        if (menu.image_alt) {
            image.alt = menu.image_alt;
        } else {
            image.alt = 'Image du menu';
        }

        const titre = document.createElement('h3');
        titre.textContent = menu.titre;

        const description = document.createElement('p');
        description.textContent = menu.description;

        const cardBottom = document.createElement('div');
        cardBottom.classList.add('card-bottom');

        const personnes = document.createElement('p');
        personnes.textContent = `${menu.nombre_personne_min} personnes minimum`;

        const prix = document.createElement('p');
        prix.textContent = `Prix : ${menu.prix_min} €`;

        const lien = document.createElement('a');
        lien.href = `menu_vuedetail.php?id=${menu.id}`;
        lien.classList.add('btn-commander');
        lien.textContent = 'Voir le menu';

        cardBottom.appendChild(personnes);
        cardBottom.appendChild(prix);
        cardBottom.appendChild(lien);

        article.appendChild(image);
        article.appendChild(titre);
        article.appendChild(description);
        article.appendChild(cardBottom);

        menusGrid.appendChild(article);
    });
}