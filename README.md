4ChErN
======

4ChErN is a single-board version of 4chan, with its name being a parody based on
the "ermahgerd" meme. It's based on the same principals - threads, images, anons, and mods.

It is very rudimentary and has a gaping cookie vulnerability. Oops.

The Database
------------
Database parameters are set in Database.class.php

The Database module came from <http://kjventura.com/2011/11/kickass-php-database-class-for-simple-web-apps/>


Cookie Vulnerability
--------------------
Sessions are managed very crudely - with a browser cookie that merely hold
your username. If you login as one user, you can edit your browser cookie to 
give you the identity of another user, regardless of whether the user
actually exists.

Injecting HTML
--------------
Inputs are sanitized against SQL injections, however HTML is acceptable. Users can
include any HTML and it will be displayed. Sad times.