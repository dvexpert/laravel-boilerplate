# Oneblink PPA PWD

### Prerequisites

1. Composer >= 2
2. Php >= 8.4
3. Node >= 22

### Installation

1. Clone Repo

```sh
git clone git@bitbucket.org:oneblinkcomm/ppa-pwd-2025.git
```

2. Install composer dependencies

```sh
composer install
```

3. Install node dependencies

```sh
yarn install
```

4. Create .env

```sh
cp .env.example .env
```

> Add DB Connection.

5. Generate App key

```sh
php artisan key:generate
```

6. Migrate Database

```sh
php artisan migrate --seed
```

7. Start dev server

```sh
composer run dev
```

- Visit: [localhost:8000](https://localhost:8000)

### Developer Notes

- To generate IDE helper files, run the following command:

```sh
php artisan ide-helper:model -MRN --write-eloquent-helper
```

- Use [Eslint vscode extension](https://marketplace.visualstudio.com/items/?itemName=dbaeumer.vscode-eslint) to lint your TS/VUE code.

```sh
ext install dbaeumer.vscode-eslint
```
