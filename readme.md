```
    ______    __     ____    _   __    _   __  ______         ______    ______    ___     __  ___
   / ____/   / /    /  _/   / | / /   / | / / /_  __/        /_  __/   / ____/   /   |   /  |/  /
  / /_      / /     / /    /  |/ /   /  |/ /   / /            / /     / __/     / /| |  / /|_/ / 
 / __/     / /___ _/ /    / /|  /   / /|  /   / /            / /     / /___    / ___ | / /  / /  
/_/       /_____//___/   /_/ |_/   /_/ |_/   /_/            /_/     /_____/   /_/  |_|/_/  /_/   
                                                                                                
```

<br/>
# Contributing

* Merge requests must be sent from your `name` branch to `testing` branch.
* Make sure to write proper understandable merge request titles, assigned to `@tejassuthar` along with tags like `feature, bug, enhancement` etc.

<br/>
# Various guidelines
In order to maintain best code and quality, you are requested to please read below guidelines carefully and try to follow as much as possible.
- [Naming conventions](http://192.168.1.10:8081/flinnt/backoffice/wikis/naming-conventions-guidelines)
- [Route Name Guidelines](http://192.168.1.10:8081/flinnt/backoffice/wikis/route-name-guidelines)
- [Merge request guidelines](http://192.168.1.10:8081/flinnt/backoffice/wikis/merge-request-guidelines)
- [Contribution guidelines](http://192.168.1.10:8081/flinnt/backoffice/wikis/development-guidelines)

<br/>
# Code Documentation
 [Code documentation details](http://192.168.1.10:8081/flinnt/backoffice/wikis/code-documentation-details)

<br/>
# Generate SSH Key
In order to clone a GitLab project, you need to have a key added in your GitLab account. Please [generate SSH key here.] (http://192.168.1.10:8081/help/ssh/README.md)

<br/>
# Add SSH Key to your GitLab Account
- Go to your `Profile Settings > SSH Keys` in GitLab account or [click here] (http://192.168.1.10:8081/profile/keys)
- Paste the key copied from [generate SSH key here] (http://192.168.1.10:8081/help/ssh/README.md) page in `Key` textarea and hit `Add Key` button (Title will be available automatically)
 


<br/>
## Step 1: Installation

To install the project and run the tests, you need to clone it first:

```
$ mkdir /media/d/Projects/PHPProjects/flinnt_backoffice_laravel
$ cd /media/d/Projects/PHPProjects/flinnt_backoffice_laravel
$ git clone git@192.168.1.10:flinnt/backoffice.git .
```

Please note: While cloning the repository if you're getting any error like, `could not read from remote repository` then pelase run following command from terminal. 

Command: `ssh -T git@192.168.1.10`

<br/>
## Step 2: Composer Install

You will then need to run a composer installation (make sure you're in your project folder):

```
$ composer install 
```
<br/>
## Step 3: Create file .env from .env.example
All basic default configurations are available in `.env.example` file. You just need to create new `.env` file from `.env.example`.

```
$ cp .env.example .env
```
<br/>
## Step 4: Generate Key

You will need to generate key with artisan command. This will generate key in `.env` file.

```
$ php artisan key:generate
```
<br/>
## Step 5: Permission to Storage & Bootstrap/cache folder
Laravel will try to write to Storage and bootstrap/cache folders. So these folders needs to be writable.

```
sudo chgrp -R www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
```




