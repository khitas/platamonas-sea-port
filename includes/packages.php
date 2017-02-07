<?php

$packages = array(
    "home" => array("level"=>0, "name"=>"home", "path"=>"", "title"=>"ΛΙΜΕΝΑΡΧΕΙΟ", "parent"=>""),

    "boats" => array("level"=>1, "name"=>"boats", "path"=>"boats/", "title"=>"Σκάφη", "parent"=>""),
    "engines" => array("level"=>1, "name"=>"engines", "path"=>"engines/", "title"=>"Μηχανές", "parent"=>""),
    "owners" => array("level"=>1, "name"=>"owners", "path"=>"owners/", "title"=>"Ιδιοκτήτες", "parent"=>""),
    "users" => array("level"=>1, "name"=>"users", "path"=>"users/", "title"=>"Χρήστες", "parent"=>""),
    "options" => array("level"=>1, "name"=>"options", "path"=>"options/", "title"=>"Ρυθμίσεις", "parent"=>""),
    "profile" => array("level"=>1, "name"=>"profile", "path"=>"profile/", "title"=>"Ο λογαριασμός μου", "parent"=>""),
    "admin" => array("level"=>1, "name"=>"admin", "path"=>"admin/", "title"=>"Διαχείριση", "parent"=>""),

    "boat-colors" => array("level"=>2, "name"=>"boat-colors", "path"=>"boat-colors/", "title"=>"Χρώματα", "parent"=>"admin/"),
    "boat-types" => array("level"=>2, "name"=>"boat-types", "path"=>"boat-types/", "title"=>"Τύποι Σκαφών", "parent"=>"admin/"),
    "boat-kinds" => array("level"=>2, "name"=>"boat-kinds", "path"=>"boat-kinds/", "title"=>"Είδη Σκαφών", "parent"=>"admin/"),
    "boat-status" => array("level"=>2, "name"=>"boat-status", "path"=>"boat-status/", "title"=>"Καταστάσεις Σκαφών", "parent"=>"admin/"),
    "boat-materials" => array("level"=>2, "name"=>"boat-materials", "path"=>"boat-materials/", "title"=>"Υλικά Κατασκευής", "parent"=>"admin/"),

    "engine-power-types" => array("level"=>2, "name"=>"engine-power-types", "path"=>"engine-power-types/", "title"=>"Μονάδες Δύναμης Μηχανής", "parent"=>"admin/"),
    "engine-types" => array("level"=>2, "name"=>"engine-types", "path"=>"engine-types/", "title"=>"Τύποι Μηχανής", "parent"=>"admin/"),
    "engine-brands" => array("level"=>2, "name"=>"engine-brands", "path"=>"engine-brands/", "title"=>"Μάρκες Μηχανής", "parent"=>"admin/"),
    "movement-types" => array("level"=>2, "name"=>"movement-types", "path"=>"movement-types/", "title"=>"Τύποι Κίνησης", "parent"=>"admin/"),
    "engine-kinds" => array("level"=>2, "name"=>"eengine-kinds", "path"=>"engine-kinds/", "title"=>"Είδη Μηχανής", "parent"=>"admin/"),
    "engine-status" => array("level"=>2, "name"=>"engine-status", "path"=>"engine-status/", "title"=>"Καταστάσεις Μηχανής", "parent"=>"admin/"),
    "registry-types" => array("level"=>2, "name"=>"registry-types", "path"=>"registry-types/", "title"=>"Τύποι Εγγραφής", "parent"=>"admin/"),
    "amyen-types" => array("level"=>2, "name"=>"amyen-types", "path"=>"amyen-types/", "title"=>"Τύποι ΑΜΥΕΝ", "parent"=>"admin/"),
    "boat-ports" => array("level"=>2, "name"=>"boat-ports", "path"=>"boat-ports/", "title"=>"Λιμένες", "parent"=>"admin/"),
    "page-records" => array("level"=>2, "name"=>"page-records", "path"=>"page-records/", "title"=>"Σελιδοποίηση", "parent"=>"admin/"),

    "owner-status" => array("level"=>2, "name"=>"owner-status", "path"=>"owner-status/", "title"=>"Καταστάσεις Ιδιοκτητών", "parent"=>"admin/"),

    "no-perms" => array("level"=>1, "name"=>"no-perms", "path"=>"no_perms/", "title"=>"Σφάλμα", "parent"=>""),
)

?>