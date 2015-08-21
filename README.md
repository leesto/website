### About
This is the git repository of the website for Backstage Technical Services. 

### Installation
*   Clone the repo using `git clone`
*   Install the dependencies using `composer install`
*   Create your *.env* file using the included *.env.example* as a template

	> This site requires both `mysql` and `smtp` settings
*   Run `php artisan key:generate` and `php artisan migrate`

	> While `php artisan migrate` will create the necessary database structure, it will not be populated with any data
*   Run `php artisan db:seed` to insert the default data

	> This will insert the su2bc account, default webpages and initial committee structure.
*   Run the PHP server or configure your own server to point to the *public* directory

### Description
The current Backstage website (v2) has been well-used by the membership for many years but the gradual addition of features has left the file structure in disrepair. It has also been a while since the core functionality was reviewed and this has left it using some fairly outdated and insecure functions.

In January 2015 Ben Jones began development on a new version of the Backstage website (this would be v4 to avoid confusion over the previously planned v3). This version would make use of a framework to both organise the structure and update the core to ensure that the site was as stable and secure as possible, using the latest standards set by the PHP development team.

The initial aim was to re-build the same functionality from scratch with this framework and to produce a new style (or theme) that was more modern and was responsive, to account for the increased usage of mobile phones. Once this new site is up and running, which is planned for September 2015, new functionality will be developed.

As the new site will use a slightly different database structure, it is a requirement that the current database is automatically migrated over. This will occur over the summer when the usage of the website is at its lowest.

### License
This website uses code from Laravel and various packages, which retain their original licenses. The code developed specifically for this app is covered by the GNU General Public License v2.