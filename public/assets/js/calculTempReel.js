const inputPersonnes = document.getElementById('personnes');
const totalTempReel = document.getElementById('totalTempReel');
const livraisonTempReel = document.getElementById('livraisonTempReel');
const reductionTempReel = document.getElementById('reductionTempReel');
const inputVille = document.getElementById('ville');

const prixMin = menuData.prixMin;
const minPersonnes = menuData.minPersonnes;

inputPersonnes.addEventListener('input', () => {
    const personnes = parseInt(inputPersonnes.value) || 0;
    const ville = inputVille.value.trim().toLowerCase();

    if (personnes < minPersonnes) {
        totalTempReel.textContent = 'À calculer';
        livraisonTempReel.textContent = '0.00 €';
        reductionTempReel.textContent = '0.00 €';
    } else {
        const prixParPersonne = prixMin / minPersonnes;
        const prixTotal = prixParPersonne * personnes;

        let reduction = 0;
        let livraison = 0;

        if (ville === 'bordeaux') {
            livraison = 0;
        } else {
            livraison = 5;
        }

        if (personnes >= minPersonnes + 5) {
            reduction = prixTotal * 0.1;
        }

        const total = prixTotal - reduction + livraison;

        livraisonTempReel.textContent = livraison.toFixed(2) + ' €';
        reductionTempReel.textContent = reduction.toFixed(2) + ' €';
        totalTempReel.textContent = total.toFixed(2) + ' €';
    }
});

inputVille.addEventListener('input', () => {
    inputPersonnes.dispatchEvent(new Event('input'));
});