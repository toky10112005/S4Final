## Le sujet, en clair

Vous devez créer un système qui **imite le fonctionnement d'un opérateur de mobile money** (comme Mvola, Orange Money, Airtel Money...). Il y a deux "utilisateurs" différents du système :

**1. L'opérateur** (l'administrateur du système) — celui qui gère les règles du jeu :
- Il définit quels préfixes téléphoniques (033, 037...) appartiennent à son réseau
- Il définit les types d'opérations possibles (dépôt, retrait, transfert) et **combien ça coûte** selon le montant (c'est le tableau que vous avez montré : plus tu retires/transfères une grosse somme, plus les frais sont élevés, par tranche)
- Il peut voir combien il a gagné grâce à ces frais (les dépôts ne coûtent rien, mais retrait et transfert rapportent de l'argent à l'opérateur)
- Il peut voir la situation de tous les comptes clients (soldes, etc.)

**2. Le client** (l'utilisateur normal) :
- Il n'a pas besoin de "s'inscrire" : dès qu'il entre son numéro de téléphone, il est automatiquement connecté (donc son compte est créé automatiquement s'il n'existe pas encore, avec un solde de départ)
- Il peut voir son solde
- Il peut déposer de l'argent (on suppose que l'argent apparaît magiquement, pas besoin de simuler un agent physique) 
- Il peut retirer de l'argent (pareil, automatique — les frais du barème s'appliquent)
- Il peut transférer de l'argent à un autre numéro (les frais du barème s'appliquent aussi)
- Il peut consulter l'historique de ses opérations

En résumé : c'est un **portefeuille électronique** avec une logique de frais configurable par l'opérateur, et deux interfaces (une pour gérer les règles/voir les statistiques, une pour que le client utilise son compte).
