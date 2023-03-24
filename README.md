# The Social Network

On installe les dépendances avec Composer et npm :

```bash
composer i
npm i
```

On configure le fichier `.env.local`.
On créer la BDD :

```bash
php bin/console d:d:c
```

On lance les migrations :

```bash
php bin/console d:m:m
php bin/console d:f:l
```

On compile le CSS et le JS :

```bash
npm run dev-server
```
