<?php
/* Registrer klasse */
/*
/* Programmet lager et HTML-skjema for å registrere en klasse
/* Programmet registrerer klassekode, klassenavn og studiumkode i databasen
*/
?>

<h3>Registrer klasse</h3>

<form method="post" action="" id="registrerKlasseSkjema" name="registrerKlasseSkjema">

  Klassekode <input type="text" id="klassekode" name="klassekode" maxlength="5" required /> <br/>

  Klassenavn <input type="text" id="klassenavn" name="klassenavn" maxlength="50" required /> <br/>

  Studiumkode <input type="text" id="studiumkode" name="studiumkode" maxlength="50" required /> <br/>

  <input type="submit" value="Registrer klasse" id="registrerKlasseKnapp" name="registrerKlasseKnapp" />

  <input type="reset" value="Nullstill" id="nullstill" name="nullstill" /> <br/>

</form>

<?php
if (isset($_POST["registrerKlasseKnapp"]))
{
    $klassekode = trim($_POST["klassekode"]);
    $klassenavn = trim($_POST["klassenavn"]);
    $studiumkode = trim($_POST["studiumkode"]);

    if (!$klassekode || !$klassenavn || !$studiumkode)
    {
        print("Alle feltene må fylles ut");
    }
    else
    {
        include("db-tilkobling.php");

        $sqlSetning = "SELECT klassekode FROM klasse WHERE klassekode = ?";
        $stmt = mysqli_prepare($db, $sqlSetning);
        mysqli_stmt_bind_param($stmt, "s", $klassekode);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        $antallRader = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_close($stmt);

        if ($antallRader != 0)
        {
            print("Klassen er registrert fra før");
        }
        else
        {
            $sqlSetning = "INSERT INTO klasse (klassekode, klassenavn, studiumkode) VALUES (?, ?, ?)";

            $stmt = mysqli_prepare($db, $sqlSetning);
            mysqli_stmt_bind_param($stmt, "sss", $klassekode, $klassenavn, $studiumkode);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            print("Følgende klasse er nå registrert: "
                . htmlspecialchars($klassekode, ENT_QUOTES, 'UTF-8') . " "
                . htmlspecialchars($klassenavn, ENT_QUOTES, 'UTF-8') . " "
                . htmlspecialchars($studiumkode, ENT_QUOTES, 'UTF-8'));
        }
    }
}
?>