<div class="timezone-help card">
    <div class="card-header">
        <h5>Guide sur les fuseaux horaires</h5>
    </div>
    <div class="card-body">
        <h6>Comment fonctionnent les fuseaux horaires dans l'application</h6>
        <p>
            Notre application gère automatiquement les différents fuseaux horaires pour vous assurer que vos disponibilités 
            et réservations sont correctement affichées, quel que soit l'endroit où vous ou vos clients vous trouvez.
        </p>
        
        <div class="alert alert-info">
            <strong>Votre fuseau horaire actuel :</strong> <span id="current-timezone">{{ auth()->user()->creator->timezone ?? 'UTC' }}</span>
            <br>
            <small>Vous pouvez modifier votre fuseau horaire dans les paramètres de votre profil.</small>
        </div>
        
        <h6>Points importants à connaître</h6>
        <ul>
            <li>Toutes les heures que vous saisissez sont interprétées dans <strong>votre fuseau horaire</strong></li>
            <li>Vos clients verront ces heures converties dans <strong>leur propre fuseau horaire</strong></li>
            <li>Les changements d'heure (été/hiver) sont gérés automatiquement</li>
        </ul>
        
        <h6>Changements d'heure (été/hiver)</h6>
        <p>
            Lors des changements d'heure, certaines heures peuvent ne pas exister ou exister deux fois. 
            L'application vous avertira si vous essayez de créer une disponibilité pendant ces périodes.
        </p>
        
        <div class="alert alert-warning">
            <strong>Conseil :</strong> Évitez de planifier des disponibilités entre 1h00 et 3h00 du matin 
            aux dates de changement d'heure (généralement en mars et novembre).
        </div>
        
        <h6>Prochains changements d'heure</h6>
        <div id="upcoming-dst-changes">
            <div class="spinner-border spinner-border-sm" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            Chargement des informations...
        </div>
        
        <h6>Questions fréquentes</h6>
        <div class="accordion" id="timezoneAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#question1">
                        Pourquoi certaines heures sont-elles marquées comme invalides ?
                    </button>
                </h2>
                <div id="question1" class="accordion-collapse collapse" data-bs-parent="#timezoneAccordion">
                    <div class="accordion-body">
                        Lors du passage à l'heure d'été, certaines heures n'existent pas (par exemple, 2h30 n'existe pas si on passe directement de 2h00 à 3h00). 
                        L'application vous empêche de créer des disponibilités à ces heures pour éviter des problèmes de réservation.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#question2">
                        Comment sont gérées les disponibilités récurrentes pendant les changements d'heure ?
                    </button>
                </h2>
                <div id="question2" class="accordion-collapse collapse" data-bs-parent="#timezoneAccordion">
                    <div class="accordion-body">
                        Les disponibilités récurrentes qui tombent pendant un changement d'heure seront automatiquement ajustées. 
                        Par exemple, si vous avez une disponibilité récurrente à 2h30 et qu'il y a un passage à l'heure d'été, 
                        cette disponibilité sera décalée à 3h30 ce jour-là.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#question3">
                        Comment puis-je m'assurer que mes clients voient les bonnes heures ?
                    </button>
                </h2>
                <div id="question3" class="accordion-collapse collapse" data-bs-parent="#timezoneAccordion">
                    <div class="accordion-body">
                        L'application convertit automatiquement les heures dans le fuseau horaire de chaque client. 
                        Vous n'avez rien à faire de spécial. Nous recommandons toutefois d'indiquer dans la description 
                        de vos événements que les heures affichées sont dans le fuseau horaire local du client.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('documentation.timezone') }}" target="_blank" class="btn btn-sm btn-outline-primary">
            Documentation complète sur les fuseaux horaires
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Charger les prochains changements d'heure
    fetch('/availability/api/dst-transitions')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('upcoming-dst-changes');
            if (data.transitions && data.transitions.length > 0) {
                let html = '<ul class="list-group">';
                data.transitions.forEach(transition => {
                    const icon = transition.type === 'summer' ? '☀️' : '❄️';
                    const direction = transition.type === 'summer' ? '+1h' : '-1h';
                    html += `<li class="list-group-item">
                        ${icon} <strong>${transition.date}</strong> : ${transition.description}
                    </li>`;
                });
                html += '</ul>';
                container.innerHTML = html;
            } else {
                container.innerHTML = '<p>Aucun changement d\'heure prévu dans les 12 prochains mois.</p>';
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des transitions DST:', error);
            container.innerHTML = '<p class="text-danger">Impossible de charger les informations sur les changements d\'heure.</p>';
        });
});
</script>
