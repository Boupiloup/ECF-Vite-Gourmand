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
        totalTempReel.innerHTML = '<strong>Total estimé : </strong>À calculer';
        livraisonTempReel.innerHTML = '<strong>Livraison : </strong>0.00 €';
        reductionTempReel.innerHTML = '<strong>Réduction : </strong>0.00 €';
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

        livraisonTempReel.innerHTML = '<strong>Livraison : </strong>' + livraison.toFixed(2) + ' €';
        reductionTempReel.innerHTML = '<strong>Réduction : </strong>' + reduction.toFixed(2) + ' €';
        totalTempReel.innerHTML = '<strong>Total estimé : </strong>' + total.toFixed(2) + ' €';
    }
});

inputVille.addEventListener('input', () => {
    inputPersonnes.dispatchEvent(new Event('input'));
});