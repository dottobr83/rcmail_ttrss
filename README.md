# rcmail_ttrss
Roundcubemail TTRSS plugin __(Partially working but experimental).__

The aim is to make a plugin to run TT-RSS inside roundcubemail (Either vannilla or Kolab) and *automatically authenticate on the current user account (partially complete)*

#### Tested with:
  * Kolab 3.4 - chameleon theme

#Warning

The iframe currently passes password in __plaintext__ while not a huge issue when used in the described install below it is a security risk and is planned to be addressed soon.

## Requierments
  1. [Roundcubemail](https://github.com/roundcube/roundcubemail) or [Kolab](kolab.org)
  2. [Tiny Tiny RSS](https://github.com/gothfox/Tiny-Tiny-RSS)
  3. The modified auth_imap plugin for TT-RSS [auth_rcmail](https://github.com/dugite-code/auth_rcmail)
  4. PHP-IMAP *(php5-imap for php5-fpm users)*

## Installation

*__Note:__* *example from kolab 3.4 on debian wheezy*

  1. Clone the [Tiny-Tiny-RSS repository](https://github.com/gothfox/Tiny-Tiny-RSS)
    1. `cd /var/www/`
    2. `git clone https://github.com/gothfox/Tiny-Tiny-RSS.git`

  2. Clone the rcmail_ttrss repository into your roundcube installation plugin directory
    1. `cd /usr/share/roundcubemail/plugins`
    2. `git clone https://github.com/dugite-code/rcmail_ttrss.git`

  3. Simlink or copy TT-RSS directory into rcmail_ttrss directory
    1. `cd /usr/share/roundcubemail/plugins/rcmail_ttrss`
    2. `ln -s /var/www/Tiny-Tiny-RSS /usr/share/roundcubemail/plugins/rcmail_ttrss/ttrss`
    
  4. For kolab users simlink the rcmail plugin directory into the public_html directory
    1. ln -s /usr/share/roundcubemail/plugins/rcmail_ttrss /usr/share/roundcubemail/public_htm/assets/plugins/`
  
  4. Finish the Tiny-Tiny-RSS [installation](https://tt-rss.org/redmine/projects/tt-rss/wiki/InstallationNotes)
    1. Create either a MySQL database or a PostgreSQL database
      
      For MySQL:
      
      1. `mysql -u root -p`
      2. `mysql> CREATE DATABASE ttrssdb;`
      3. `mysql> GRANT ALL ON ttrssdb.* TO ttrssuser IDENTIFIED BY "SomePassword";`
      4. `mysql> quit;`
      
    2. Open the Tiny-Tiny-RSS page directly
      1. Roundcube: yoursite.com/plugins/rcmail_ttrss/ttrss/
      2. Kolab: yoursite.com/assets/rcmail_ttrss/ttrss/
    
    3. Run the setup process
      1. Enter database details into presented form
      2. Select the test database button
      3. If sucsessful select initalize database
      4. Copy the config options from the dialog presented
      5. `cd /var/www/Tiny-Tiny-RSS/plugins`
      6. `nano /var/www/Tiny-Tiny-RSS/plugins/config.php`
      7. Past the config options, exit and save the file
      8. Delete the file install `rm -rf /var/www/Tiny-Tiny-RSS/install` *__-important-__*
    
    4. Reload the direct page and login with `admin` and `password`
      1. Proceed to Actions -> Prefernces -> users
      2. select the admin account and *__change your password__*
    
  5. Clone the [auth_rcmail repository](https://github.com/dugite-code/auth_rcmail) into your Tiny-Tiny-RSS plugin directory
    1. `cd /var/www/Tiny-Tiny-RSS/plugins`
    2. `git clone https://github.com/dugite-code/auth_rcmail`
    3. `cd /var/www/Tiny-Tiny-RSS`
    4. `nano /var/www/Tiny-Tiny-RSS/config.php`
    5. Append the following code to the bottom of the config file:
    
        ```   
        // auth_rcmail imap configuration
        define('IMAP_AUTH_SERVER', 'your.imap.server:port');
        define('IMAP_AUTH_OPTIONS', '/tls/novalidate-cert/norsh');
        ```
    6. Modify the line `define('PLUGINS', 'auth_internal, note');` to be:
    
      `define('PLUGINS', 'auth_internal, auth_rcmail, note');`
      
      *__Note:__* auth_rcmail must be __after__ auth_internal or you will not be able to log into your administrator account.
