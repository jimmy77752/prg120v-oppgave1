<?php  /* registrer-student */
/*
/* Programmet lager et HTML-skjema for å registrere en student
/* Programmet registrerer data i student-tabellen
*/

include("db-tilkobling.php");

if (isset($_POST["registrerStudentKnapp"]))
{
    $brukernavn=trim($_POST["brukernavn"]);
    $fornavn=trim($_POST["fornavn"]);
    $etternavn=trim($_POST["etternavn"]);
    $klassekode=trim($_POST["klassekode"]);

    if (!$brukernavn || !$fornavn || !$etternavn || !$klassekode)
    {
        print("Alle feltene må fylles ut");
    }
    else
    {
        $sqlSetning="SELECT * FROM student WHERE brukernavn=?;";
        $stmt=mysqli_prepare($db,$sqlSetning);
        mysqli_stmt_bind_param($stmt,"s",$brukernavn);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        $antallRader=mysqli_stmt_num_rows($stmt);
        mysqli_stmt_close($stmt);

        if ($antallRader!=0)
        {
            print("Studenten er registrert fra før");
        }
        else
        {
            $sqlSetning="INSERT INTO student
            (brukernavn,fornavn,etternavn,klassekode)
            VALUES (?,?,?,?);";

            $stmt=mysqli_prepare($db,$sqlSetning);
            mysqli_stmt_bind_param($stmt,"ssss",
                $brukernavn,$fornavn,$etternavn,$klassekode);

            mysqli_stmt_execute($stmt)
                or die("Ikke mulig å registrere studenten");

            mysqli_stmt_close($stmt);

            print("Følgende student er nå registrert: "
                .htmlspecialchars($brukernavn,ENT_QUOTES,"UTF-8")." "
                .htmlspecialchars($fornavn,ENT_QUOTES,"UTF-8")." "
                .htmlspecialchars($etternavn,ENT_QUOTES,"UTF-8"));
        }
    }
}
?>

<h3>Registrer student</h3>

<form method="post" action=""
      id="registrerStudentSkjema"
      name="registrerStudentSkjema">

Brukernavn
<input type="text" id="brukernavn" name="brukernavn"
       maxlength="7" required /> <br/>

Fornavn
<input type="text" id="fornavn" name="fornavn"
       maxlength="50" required /> <br/>

Etternavn
<input type="text" id="etternavn" name="etternavn"
       maxlength="50" required /> <br/>

Klassekode
<select id="klassekode" name="klassekode" required>
    <option value="">Velg klasse</option>

    <?php
    $sqlSetning="SELECT * FROM klasse;";
    $sqlResultat=mysqli_query($db,$sqlSetning);

    while ($rad=mysqli_fetch_array($sqlResultat))
    {
        $kode=htmlspecialchars($rad["klassekode"],ENT_QUOTES,"UTF-8");
        $navn=htmlspecialchars($rad["klassenavn"],ENT_QUOTES,"UTF-8");

        print("<option value='$kode'>$kode - $navn</option>");
    }
    ?>
</select> <br/>

<input type="submit" value="Registrer student"
       id="registrerStudentKnapp"
       name="registrerStudentKnapp" />

<input type="reset" value="Nullstill"
       id="nullstill" name="nullstill" />

</form>