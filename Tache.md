### À faire ENSEMBLE 
- Définir ensemble le schéma de données (les tables/entités)
- Créer le repo GitHub, la structure de dossiers

### Répartition des taches 

**Personne A — "Moteur & Opérateur"**
- Modèles de données + logique métier centrale (calcul des frais selon le barème et la tranche)
- Gestion des préfixes (CRUD)
- Gestion des types d'opérations et barèmes (CRUD, avec modification des tranches)
- Écran "situation des gains" (somme des frais perçus, filtrable par type d'opération)
- Écran "situation des comptes clients" (liste des comptes, soldes)

**Personne B — "Client & Transactions"**
- Login automatique par numéro de téléphone (création de compte à la volée)
- Écran solde
- Dépot / Retrait / Transfert (ces écrans appellent la fonction de calcul de frais faite par Personne A — d'où l'intérêt de définir cette interface ensemble à l'étape 0)
- Écran historique des opérations

### Version 2

**Personne A (déjà sur l'opérateur)**

Préfixes autres opérateurs (CRUD simple, réutilise le code des préfixes existants)
Commission % supplémentaire (champ en plus dans la config des barèmes)
Séparer les gains (filtrer par "même opérateur" vs "autre opérateur" dans la requête déjà existante)
Montants à envoyer à chaque opérateur (somme des transferts sortants groupés par opérateur destinataire)

**Personne B  (client):**

D'abord : détecter si un numéro destinataire appartient au même opérateur ou à un autre (fonction utilitaire, réutilisable pour les 2 features suivantes — basée sur les préfixes que Personne A configure)
Envoi multiple : formulaire avec plusieurs numéros + division du montant total, bloqué si un numéro n'est pas du même opérateur
Option "inclure frais de retrait" : checkbox sur le formulaire de transfert existant, désactivée/cachée si le destinataire est chez un autre opérateur