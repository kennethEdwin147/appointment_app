/**
 * Script pour gérer les avertissements de changement d'heure dans les formulaires
 * de disponibilités.
 */
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si nous sommes sur une page de création ou d'édition de disponibilité
    const isAvailabilityForm = document.querySelector('form[action*="availability"]');
    if (!isAvailabilityForm) return;

    // Éléments du formulaire
    const effectiveFromInput = document.getElementById('effective_from');
    const effectiveUntilInput = document.getElementById('effective_until');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const warningsContainer = document.createElement('div');
    
    // Ajouter le conteneur d'avertissements au formulaire
    warningsContainer.id = 'dst-warnings';
    warningsContainer.className = 'alert alert-warning d-none';
    isAvailabilityForm.prepend(warningsContainer);

    // Fonction pour vérifier les changements d'heure
    function checkDSTTransitions() {
        // Appel AJAX pour vérifier les transitions DST à venir
        fetch('/availability/api/dst-transitions')
            .then(response => response.json())
            .then(data => {
                if (data.transitions && data.transitions.length > 0) {
                    // Afficher un avertissement général sur les changements d'heure à venir
                    const generalWarning = document.createElement('div');
                    generalWarning.className = 'alert alert-info';
                    generalWarning.innerHTML = `
                        <strong>Information :</strong> Des changements d'heure sont prévus aux dates suivantes :
                        <ul>
                            ${data.transitions.map(t => `<li>${t.date} - ${t.description}</li>`).join('')}
                        </ul>
                        Certaines heures pourraient ne pas être disponibles à ces dates.
                    `;
                    
                    // Insérer l'avertissement après le titre du formulaire
                    const formTitle = document.querySelector('.avail-title') || document.querySelector('h1');
                    if (formTitle) {
                        formTitle.after(generalWarning);
                    } else {
                        isAvailabilityForm.prepend(generalWarning);
                    }
                }
            })
            .catch(error => console.error('Erreur lors de la récupération des transitions DST:', error));
    }

    // Fonction pour vérifier si une date spécifique est affectée par un changement d'heure
    function checkSpecificDate(date) {
        if (!date) return;
        
        // Vérifier si la date est au format YYYY-MM-DD
        if (!/^\d{4}-\d{2}-\d{2}$/.test(date)) return;
        
        // Appel AJAX pour vérifier si cette date est affectée par un changement d'heure
        fetch(`/availability/api/dst-transitions?date=${date}`)
            .then(response => response.json())
            .then(data => {
                if (data.transitions && data.transitions.length > 0) {
                    // Afficher un avertissement spécifique pour cette date
                    warningsContainer.classList.remove('d-none');
                    warningsContainer.innerHTML = `
                        <strong>Attention :</strong> La date sélectionnée (${date}) est affectée par un changement d'heure :
                        ${data.transitions[0].description}
                        <br>
                        Certaines heures pourraient ne pas être disponibles ou être ambiguës à cette date.
                    `;
                } else {
                    // Cacher l'avertissement si la date n'est pas affectée
                    warningsContainer.classList.add('d-none');
                }
            })
            .catch(error => console.error('Erreur lors de la vérification de la date:', error));
    }

    // Ajouter des écouteurs d'événements pour les champs de date
    if (effectiveFromInput) {
        effectiveFromInput.addEventListener('change', function() {
            checkSpecificDate(this.value);
        });
    }

    if (effectiveUntilInput) {
        effectiveUntilInput.addEventListener('change', function() {
            checkSpecificDate(this.value);
        });
    }

    // Vérifier les changements d'heure au chargement de la page
    checkDSTTransitions();

    // Vérifier si des dates sont déjà sélectionnées
    if (effectiveFromInput && effectiveFromInput.value) {
        checkSpecificDate(effectiveFromInput.value);
    }
});
