<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(5, "mmi_customer", $Language->MenuPhrase("5", "MenuText"), "customerlist.php", -1, "", IsLoggedIn() || AllowListMenu('{CB0737F4-C35F-4485-A00D-4D7E8040366B}customer'), FALSE, FALSE);
$RootMenu->AddMenuItem(1, "mmi_inventory", $Language->MenuPhrase("1", "MenuText"), "inventorylist.php", -1, "", IsLoggedIn() || AllowListMenu('{CB0737F4-C35F-4485-A00D-4D7E8040366B}inventory'), FALSE, FALSE);
$RootMenu->AddMenuItem(2, "mmi_pesanan", $Language->MenuPhrase("2", "MenuText"), "pesananlist.php", -1, "", IsLoggedIn() || AllowListMenu('{CB0737F4-C35F-4485-A00D-4D7E8040366B}pesanan'), FALSE, FALSE);
$RootMenu->AddMenuItem(4, "mmi_user", $Language->MenuPhrase("4", "MenuText"), "userlist.php", -1, "", IsLoggedIn() || AllowListMenu('{CB0737F4-C35F-4485-A00D-4D7E8040366B}user'), FALSE, FALSE);
$RootMenu->AddMenuItem(-2, "mmi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
