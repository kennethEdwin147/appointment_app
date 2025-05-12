# Gestion des fuseaux horaires

Ce document explique comment l'application gère les fuseaux horaires, les limites connues et les bonnes pratiques pour les utilisateurs et les développeurs.

## Table des matières

1. [Vue d'ensemble](#vue-densemble)
2. [Architecture technique](#architecture-technique)
3. [Conversion des heures](#conversion-des-heures)
4. [Gestion des changements d'heure](#gestion-des-changements-dheure)
5. [Limites connues](#limites-connues)
6. [Bonnes pratiques](#bonnes-pratiques)
7. [Dépannage](#dépannage)
8. [Références](#références)

## Vue d'ensemble

L'application gère les disponibilités des créateurs et les réservations des utilisateurs à travers différents fuseaux horaires. Pour garantir une expérience cohérente, toutes les heures sont stockées en UTC (Temps Universel Coordonné) dans la base de données, puis converties dans le fuseau horaire approprié lors de l'affichage.

### Principes fondamentaux

- **Stockage en UTC** : Toutes les heures sont stockées en UTC dans la base de données
- **Conversion à l'affichage** : Les heures sont converties dans le fuseau horaire de l'utilisateur lors de l'affichage
- **Validation intelligente** : Le système vérifie la validité des heures, notamment pendant les périodes de changement d'heure
- **Avertissements proactifs** : Les utilisateurs sont avertis des problèmes potentiels liés aux changements d'heure

## Architecture technique

### Trait HandlesTimezones

Le cœur de la gestion des fuseaux horaires est le trait `HandlesTimezones`, qui fournit des méthodes pour :

- Convertir les heures entre différents fuseaux horaires
- Valider les heures, notamment pendant les périodes de changement d'heure
- Détecter les changements d'heure à une date donnée
- Formater les heures pour l'affichage

```php
// Exemple d'utilisation du trait
use App\Traits\HandlesTimezones;

class MyClass
{
    use HandlesTimezones;
    
    public function someMethod()
    {
        // Convertir une heure locale en UTC
        $utcTime = $this->convertToUTC('09:00', 'America/Toronto', '2025-06-15');
        
        // Vérifier si une heure est valide
        $isValid = $this->isValidTime('02:30', 'America/Toronto', '2025-03-09');
        
        // Obtenir des informations sur un changement d'heure
        $dstInfo = $this->getDSTTransitionForDate('2025-03-09', 'America/Toronto');
    }
}
```

### Contrôleur AvailabilityController

Le contrôleur `AvailabilityController` utilise le trait `HandlesTimezones` pour gérer les disponibilités des créateurs. Il :

- Valide les heures saisies par les utilisateurs
- Convertit les heures du fuseau horaire du créateur vers UTC pour le stockage
- Convertit les heures d'UTC vers le fuseau horaire du créateur pour l'affichage
- Avertit les utilisateurs des problèmes potentiels liés aux changements d'heure

## Conversion des heures

### Du fuseau horaire local vers UTC

Lors de la création ou de la modification d'une disponibilité, les heures saisies par l'utilisateur sont converties de son fuseau horaire local vers UTC pour le stockage :

```php
// Exemple de conversion du fuseau horaire local vers UTC
$startTimeUTC = $this->convertToUTC($request->start_time, $creatorTimezone, $effectiveFrom);
$endTimeUTC = $this->convertToUTC($request->end_time, $creatorTimezone, $effectiveFrom);
```

### D'UTC vers le fuseau horaire local

Lors de l'affichage des disponibilités, les heures sont converties d'UTC vers le fuseau horaire local de l'utilisateur :

```php
// Exemple de conversion d'UTC vers le fuseau horaire local
$localStartTime = $this->convertFromUTC($availability->start_time, $userTimezone);
$localEndTime = $this->convertFromUTC($availability->end_time, $userTimezone);
```

## Gestion des changements d'heure

Les changements d'heure (passage à l'heure d'été ou à l'heure d'hiver) peuvent causer des problèmes avec les disponibilités et les réservations. L'application gère ces cas particuliers de manière proactive.

### Détection des changements d'heure

L'application peut détecter si une date donnée correspond à un changement d'heure :

```php
// Exemple de détection d'un changement d'heure
$dstTransition = $this->getDSTTransitionForDate('2025-03-09', 'America/Toronto');
if ($dstTransition) {
    // Cette date correspond à un changement d'heure
    $type = $dstTransition['type']; // 'summer' ou 'winter'
    $direction = $dstTransition['direction']; // '+1h' ou '-1h'
    $description = $dstTransition['description'];
}
```

### Validation des heures pendant les changements d'heure

Lors du passage à l'heure d'été, certaines heures n'existent pas (par exemple, 2h30 n'existe pas si on passe directement de 2h00 à 3h00). Lors du passage à l'heure d'hiver, certaines heures existent deux fois (par exemple, 1h30 existe deux fois si on revient de 2h00 à 1h00).

L'application valide les heures saisies par les utilisateurs pour éviter ces problèmes :

```php
// Exemple de validation d'une heure
$isValid = $this->isValidTime('02:30', 'America/Toronto', '2025-03-09');
if (!$isValid) {
    // Cette heure n'est pas valide à cette date
}
```

### Avertissements aux utilisateurs

L'application avertit les utilisateurs lorsqu'ils créent ou modifient des disponibilités qui pourraient être affectées par un changement d'heure :

```javascript
// Exemple d'avertissement côté client
if (dstTransition) {
    showWarning(`Attention : Un changement d'heure a lieu le ${date}. 
                 Certaines heures pourraient ne pas être disponibles.`);
}
```

## Limites connues

Malgré les efforts pour gérer correctement les fuseaux horaires, certaines limites subsistent :

### Heures ambiguës

Lors du passage à l'heure d'hiver, certaines heures existent deux fois. L'application ne peut pas toujours déterminer avec certitude quelle occurrence de l'heure est souhaitée par l'utilisateur.

### Disponibilités récurrentes

Les disponibilités récurrentes qui traversent plusieurs changements d'heure peuvent avoir des comportements inattendus. Par exemple, une disponibilité récurrente à 2h30 sera invalide le jour du passage à l'heure d'été.

### Fuseaux horaires exotiques

Certains fuseaux horaires ont des règles complexes ou des changements d'heure à des dates inhabituelles. L'application peut ne pas gérer correctement tous ces cas particuliers.

## Bonnes pratiques

### Pour les utilisateurs

- **Évitez les heures critiques** : Évitez de planifier des disponibilités entre 1h00 et 3h00 du matin, période où les changements d'heure se produisent généralement
- **Vérifiez les avertissements** : Prenez en compte les avertissements affichés par l'application concernant les changements d'heure
- **Utilisez des marges de sécurité** : Prévoyez une marge de sécurité avant et après les périodes de changement d'heure

### Pour les développeurs

- **Utilisez toujours UTC** : Stockez toujours les dates et heures en UTC dans la base de données
- **Convertissez au dernier moment** : Convertissez les heures UTC vers le fuseau horaire local uniquement au moment de l'affichage
- **Testez les cas limites** : Testez votre code avec des dates de changement d'heure
- **Utilisez des bibliothèques éprouvées** : Utilisez des bibliothèques comme Carbon pour manipuler les dates et les heures

## Dépannage

### Problèmes courants et solutions

| Problème | Cause possible | Solution |
|----------|----------------|----------|
| "L'heure sélectionnée n'existe pas" | Tentative de créer une disponibilité à une heure qui n'existe pas pendant le passage à l'heure d'été | Choisir une heure différente, en dehors de la période de changement d'heure |
| "L'heure sélectionnée est ambiguë" | Tentative de créer une disponibilité à une heure qui existe deux fois pendant le passage à l'heure d'hiver | Choisir une heure différente, en dehors de la période de changement d'heure |
| Décalage d'une heure dans l'affichage | Mauvaise détection du fuseau horaire de l'utilisateur | Vérifier les paramètres de fuseau horaire dans le profil utilisateur |

## Références

- [Documentation de Carbon](https://carbon.nesbot.com/docs/)
- [Liste des fuseaux horaires PHP](https://www.php.net/manual/fr/timezones.php)
- [Comprendre les changements d'heure](https://www.timeanddate.com/time/dst/)
