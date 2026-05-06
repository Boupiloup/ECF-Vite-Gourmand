// Je récupère le formulaire des filtres
const filterForm = document.getElementById('filterForm');

// Je récupère la zone où les cartes menus sont affichées
const menusGrid = document.getElementById('menusGrid');

// Dès qu'on écrit dans un champ input, je recharge les menus
filterForm.addEventListener('input', () => {
    chargerMenus();
});

// Dès qu'on change un select, je recharge aussi les menus
filterForm.addEventListener('change', () => {
    chargerMenus();
});

function chargerMenus() {
    // Je récupère toutes les valeurs du formulaire
    const formData = new FormData(filterForm);

    // Je prépare les paramètres qui vont être envoyés dans l'URL
    const params = new URLSearchParams();

    // Je parcours chaque champ du formulaire
    formData.forEach((value, key) => {
        // Si le champ n'est pas vide, je l'ajoute dans l'URL
        if (value.trim() !== '') {
            params.append(key, value);
        }
    });

    // J'appelle mon endpoint PHP avec les filtres remplis
    fetch(`api/menus-filtre.php?${params.toString()}`)
        .then(response => response.json()) // Je transforme la réponse JSON en données utilisables
        .then(menus => {
            // Une fois les menus reçus, je les affiche dans la page
            afficherTousLesMenus(menus);
        })
        .catch(error => {
            // Si une erreur arrive, je l'affiche dans la console
            console.error('Erreur lors du chargement des menus :', error);
        });
}

function afficherTousLesMenus(menus) {
    // Je vide les anciennes cartes avant d'afficher les nouvelles
    menusGrid.innerHTML = '';

    // Si aucun menu ne correspond aux filtres, j'affiche un message
    if (menus.length === 0) {
        const message = document.createElement('p');
        message.textContent = 'Aucun menu ne correspond à votre recherche.';
        menusGrid.appendChild(message);
        return;
    }

    // Je parcours chaque menu reçu pour créer une carte
    menus.forEach(menu => {
        // Je crée la carte du menu
        const article = document.createElement('article');
        article.classList.add('card-menu');

        // Je crée l'image du menu
        const image = document.createElement('img');

        // Si une image existe en base, je l'utilise
        if (menu.image_url) {
            image.src = menu.image_url;
        } else {
            // Sinon je mets une image par défaut
            image.src = 'https://picsum.photos/seed/menu2-1/800/600';
        }

        // Si un texte alternatif existe, je l'utilise
        if (menu.image_alt) {
            image.alt = menu.image_alt;
        } else {
            image.alt = 'Image du menu';
        }

        // Je crée le titre du menu
        const titre = document.createElement('h3');
        titre.textContent = menu.titre;

        // Je crée la description du menu
        const description = document.createElement('p');
        description.textContent = menu.description;

        // Je crée le bloc du bas de la carte
        const cardBottom = document.createElement('div');
        cardBottom.classList.add('card-bottom');

        // Je crée le texte du nombre de personnes minimum
        const personnes = document.createElement('p');
        personnes.textContent = `${menu.nombre_personne_min} personnes minimum`;

        // Je crée le texte du prix
        const prix = document.createElement('p');
        prix.textContent = `Prix : ${menu.prix_min} €`;

        // Je crée le lien vers la page détail du menu
        const lien = document.createElement('a');
        lien.href = `menu_vuedetail.php?id=${menu.id}`;
        lien.classList.add('btn-commander');
        lien.textContent = 'Voir le menu';

        // Je range les éléments dans le bloc du bas
        cardBottom.appendChild(personnes);
        cardBottom.appendChild(prix);
        cardBottom.appendChild(lien);

        // Je range tous les éléments dans la carte
        article.appendChild(image);
        article.appendChild(titre);
        article.appendChild(description);
        article.appendChild(cardBottom);

        // J'ajoute la carte complète dans la grille
        menusGrid.appendChild(article);
    });
}