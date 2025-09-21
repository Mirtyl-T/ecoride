# Manuel Utilisateur — EcoRide

Ce document décrit comment utiliser l'application EcoRide (interface web).

---

## 1. Présentation rapide
EcoRide permet de rechercher et proposer des trajets en covoiturage, gérer son compte et consulter l'historique des trajets.

---

## 2. Accéder à l'application
1. Ouvrez votre navigateur et rendez-vous sur l'URL.
---

## 3. Inscription
1. Cliquez sur le lien Accueil puis cherchez le bouton Connexion.
2. En dessous du formulaire de connexion se trouve le bouton du formulaire d'inscription.
3. Sur le formulaire d'inscription, remplissez les champs requis :
   - Nom 
   - Email
   - Mot de passe 
   - Encoche Agree terms (voir la partie Mention Légal)
4. Validez le formulaire. Vous serez renvoyez sur la page de Connexion.


## 4. Connexion
1. Cliquez sur le lien Accueil puis cherchez le bouton Connexion.
2. Renseignez :
   - Email
   - Mot de passe
3. Cliquez sur Se connecter.
4. Après authentification, vous verrez la zone d'accueil et un message (ex. “Bonjour Adrien”) et le lien Se déconnecter s'affiche en dessous du message.

---

## 5. Recherche d'un trajet
1. Dans le champ **Départ**, saisissez le lieu de départ.
2. Dans le champ **Arrivée**, saisissez la destination.
3. Choisissez la **date** (format affiché `jj/mm/aaaa`).
4. Cliquez sur **Rechercher**.
5. Les résultats s'affichent sur une nouvelle page : chaque trajet présente les informations principales.
6. Pour consulter les détails d'un trajet, cliquez sur le titre du trajet.

---

## 6. Proposer un trajet
1. Une fois connecté, cherchez un bouton Créer un trajet (dans la page d'accueil).
2. Remplissez le formulaire de création :
   - Point de départ
   - Destination
   - Date et heure d'arrivé et de départ
   - Nombre de places disponibles
   - Préférences éventuelle / prix
   - Choisissez le vehicule
3. Cliquez sur Créer.
4. Le trajet apparaît ensuite dans les recherches et dans votre espace `Mon Compte` .

---

## 7. Réserver un trajet 
1. Sur la fiche d'un trajet trouvé via recherche, cliquez sur Réserver ce trajet.
2. Confirmez la réservation.
3. Les réservations confirmées apparaissent dans votre `Historique`.
##  Annuler un trajet 
1. Sur la fiche du trajet trouvé via recherche, cliquez sur Annuler ce trajet.


---

## 8. Gestion du compte
1. Accéder à Mon Compte depuis la barre de navigation.
2. Modifier vos informations personnelles (nom, email), changer votre mot de passe.
3. Gérer vos trajets proposés et laisser un avis sur la réservations depuis les sections correspondantes.
4. Ajouter / modifier vos véhicules (voir section suivante).

---

## 9. Gestion des véhicules
1. Dans `Mon Compte`, cherchez là sous-section pour Gérer mes Véhicules.
2. Ajoutez un véhicule en renseignant : marque, modèle, immatriculation, type (électrique / thermique), nombre de places, Préférences.
3. Les véhicules ajoutés peuvent ensuite être associés aux trajets que vous proposez.

---

## 10. Historique
1. Cliquer sur Historique dans la barre de navigation.
2. La page présente la liste des trajets passés et des participations.
3. Vous pouvez consulter les détails d'un trajet ancien, laisser un commentaire avec une note.

---

## 11. Déconnexion
1. Cliquez sur Se déconnecter (sur la page d'acceuil).
2. Vous serez redirigé vers l'accueil ou la page de connexion.

---


## 14. Raccourcis et commandes utiles (développeur)
- Installer dépendances backend :
  ```bash
  composer install
  ```
- Installer dépendances frontend :
  ```bash
  npm install
  ```
- Compiler assets (dev / watch) :
  ```bash
  npm run dev
  ```
- Compiler assets (prod) :
  ```bash
  npm run build
  ```
- Créer la base et lancer les migrations :
  ```bash
  php bin/console doctrine:database:create
  php bin/console doctrine:migrations:migrate
  ```

---

## 16. Contact / Support
Pour toute question ou bug, créez une issue sur le dépôt GitHub : `https://github.com/Mirtylart/ecoride/issues`.

---
