=== Beriyack Optimizations ===
Contributors: beriyack
Donate link: https://www.buymeacoffee.com/beriyack
Tags: optimization, performance, security, revisions, speed
Requires at least: 5.0
Tested up to: 6.8
Stable tag: 1.4.1
Requires PHP: 7.4
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Un plugin léger et modulaire pour appliquer des micro-optimisations de performance, de sécurité et de base de données sur votre site WordPress.

== Description ==

**Beriyack Optimizations** vous redonne le contrôle sur les fonctionnalités de base de WordPress. Au lieu d'installer plusieurs plugins pour de petites tâches, activez uniquement les optimisations dont vous avez besoin depuis une seule page de réglages simple et intuitive.

Ce plugin est idéal pour les administrateurs de sites qui souhaitent améliorer la vitesse et la sécurité de leur site sans ajouter de code superflu.

**Fonctionnalités actuelles :**

*   **Contrôler le nombre de révisions** : Définissez le nombre exact de révisions à conserver par article/page (ou -1 pour illimité).
*   **Désactiver les Emojis** : Supprime les scripts et styles liés aux emojis pour alléger vos pages.
*   **Désactiver XML-RPC** : Bloque l'accès à `xmlrpc.php` pour prévenir les attaques par force brute.
*   **Supprimer la version de WordPress** : Masque la version de votre WordPress du code source public.
*   **Supprimer les liens des flux RSS** : Nettoie l'en-tête de votre site en retirant les liens vers les flux RSS.

Chaque fonctionnalité est indépendante. Vous avez le contrôle total.

== Installation ==

1.  Dans votre tableau de bord WordPress, allez dans `Extensions > Ajouter`.
2.  Recherchez "Beriyack Optimizations".
3.  Cliquez sur `Installer maintenant` puis sur `Activer`.
4.  Allez dans `Réglages > Beriyack Optimizations` pour choisir les optimisations à activer.

Vous pouvez également installer le plugin manuellement en téléversant le dossier du plugin dans le répertoire `/wp-content/plugins/`.

== Frequently Asked Questions ==

= Pourquoi désactiver XML-RPC ? =

Le fichier `xmlrpc.php` est une ancienne méthode pour permettre à des applications tierces de communiquer avec votre site. Aujourd'hui, il est largement remplacé par l'API REST de WordPress et est une cible fréquente pour les attaques par force brute. Le désactiver est une mesure de sécurité simple et efficace si vous n'utilisez pas d'applications qui en dépendent (comme l'ancienne application mobile WordPress).

= La désactivation des emojis va-t-elle casser mon site ? =

Non. La quasi-totalité des navigateurs et systèmes d'exploitation modernes affichent les emojis nativement. Le script que WordPress charge est une solution de compatibilité pour de très vieux systèmes. Le désactiver permet de gagner en performance sans impact visible pour la grande majorité de vos visiteurs.

= À quoi sert la limitation des révisions ? =

Chaque fois que vous enregistrez un article, WordPress crée une copie (une révision). Avec le temps, cela peut ajouter des milliers de lignes inutiles à votre base de données, la ralentissant. Limiter les révisions (par exemple, aux 5 dernières) est une excellente pratique pour maintenir une base de données saine.

== Confidentialité ==

Ce plugin ne collecte et ne stocke aucune donnée personnelle des visiteurs de votre site. Toutes les informations gérées par ce plugin sont récupérées depuis votre base de données WordPress existante ou configurées par l'administrateur du site.

== Screenshots ==

1. La page de réglages simple et claire, vous donnant le contrôle sur chaque optimisation.

== Changelog ==

= 1.4.1 =
* Correction : Préfixage des variables globales pour se conformer aux standards de codage de WordPress et éviter les conflits potentiels.

= 1.4.0 =
* Amélioration : Simplification de l'interface pour la limitation des révisions.
* Amélioration : Correction finale et robuste pour la désactivation de XML-RPC.
* Suppression : Retrait des options pour jQuery Migrate, Embeds et Self-pings pour une meilleure clarté et fiabilité.

= 1.3.1 =
* Correction : Résolution d'un bug critique empêchant l'affichage correct de la page des réglages et la sauvegarde des options.

= 1.3.0 =
* Ajout : Option pour désactiver la fonctionnalité d'intégration (Embeds) et supprimer le script `wp-embed.min.js`.

= 1.2.0 =
* Ajout : Option pour supprimer le script jQuery Migrate.

= 1.1.0 =
* Ajout : Option pour supprimer les liens des flux RSS.
* Ajout : Option pour désactiver les auto-pings (self-pings).
* Ajout : Option pour supprimer la version de WordPress.
* Ajout : Page de réglages pour activer/désactiver chaque fonctionnalité.
* Ajout : Fichier de traduction et préparation à l'internationalisation.

= 1.0.0 =
* Lancement initial avec les optimisations de base (limitation des révisions, désactivation des emojis et de XML-RPC).

== Upgrade Notice ==

= 1.4.1 =
Cette version est une mise à jour de maintenance qui améliore la qualité du code pour une meilleure conformité avec les standards de WordPress.

= 1.4.0 =
Version de simplification et de stabilisation. Certaines fonctionnalités moins universelles ont été retirées pour améliorer la fiabilité.

= 1.3.1 =
Cette version corrige un bug critique lié à l'affichage et la sauvegarde des réglages. Mise à jour fortement recommandée.

= 1.3.0 =
Cette version ajoute une nouvelle optimisation pour désactiver la fonctionnalité d'intégration de contenu (Embeds).

= 1.2.0 =
Cette version ajoute une nouvelle optimisation pour supprimer jQuery Migrate.

= 1.1.0 =
Cette version introduit une page de réglages complète ! Veuillez visiter la nouvelle page dans "Réglages > Beriyack Optimizations" pour configurer les fonctionnalités.
