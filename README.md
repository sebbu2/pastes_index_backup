# pastes_index_backup
Backup pastes linked from an index page, decodes them serverside

Supported sites
===============

https://priv.atebin.com/

https://privatebin.secured.fi/

https://paste.tech-port.de/

https://paste.imirhil.fr/

Planned support
===============

or to verify

https://privatebin.net/

https://vim.cx/

all others from https://github.com/PrivateBin/PrivateBin/wiki/PrivateBin-Instances-Directory

Description
===========

config.inc.php settings of the application

novels.txt novels to keep in main folder

novels2.txt novels to keep in __others folder

qu_remove.php removes the cache of the 2 urls

qu_continue.php removes incomplete cache for the 2 urls, allowing it to continue from previous state

qu.php retrieve the first url

qu_or.php retrieve the second url

qu2.php parse the first url and retrieve the pastes

qu_or2.php parse the second url and retrieve the pastes

clean.php removes duplicates (pastes that have been replaced to contain more chapters)

clean2.php removes pastes from novels in the urls but not in the urls

novels3.php lists novels not in the urls

no_eq.php in case of keeping both htm and gz, removes orphaned files

ren.php renames folders according to current rules (if they have changed in qu2/qu_or2)

stats.php generates stats

functions.inc.php bypass cloudflare js challenge, retrieve pastes, fix old base64 js lib bug
