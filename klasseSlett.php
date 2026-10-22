[13:47, 22.10.2026] +47 973 33 930: <?php
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

  <input type="reset" value="Nullstill" id="nullstill" name="nullsti…
[13:48, 22.10.2026] +47 973 33 930: <?php  /* slett-klasse */
/*
/* Programmet lager et skjema for å velge en klasse som skal slettes
/* Programmet sletter den valgte klassen
*/
?>

<h3>Slett klasse</h3>

<form method="post" action="" id="slettKlasseSkjema" name="slettKlasseSkjema" onsubmit="return confirm('Er du sikker på at du vil slette klassen?')">

  
<?php
include_once("db-tilkobling.php");
?>

Klassekode
<select id="klassekode" name="klassekode" required>
    <option value="">Velg klasse</option>

    <?php
    $sqlSetning="SELECT * FROM klasse ORDER BY klassekode;";
    $sqlResultat=mysqli_query($db,$sqlSetning)
        or die("Ikke mulig å hente klasser");

    while ($rad=mysqli_fetch_array($sqlResultat))
    {
        $klassekode=htmlspecialchars(trim($rad["klassekode"]),ENT_QUOTES,"UTF-8");
        $klassenavn=htmlspecialchars($rad["klassenavn"],ENT_QUOTES,"UTF-8");

        print("<option value='$klassekode'>
            $klassekode - $klassenavn
        </option>");
    }
    ?>
</select> <br/>

  <input type="submit" value="Slett klasse" name="slettKlasseKnapp" id="slettKlasseKnapp" />

</form>

<?php
  if (isset($_POST["slettKlasseKnapp"]))
    {
      $klassekode=trim($_POST["klassekode"]);

      if (!$klassekode)
        {
          print ("Klassekode må fylles ut");
        }
      else
        {
          include("db-tilkobling.php");

          $sqlSetning="SELECT * FROM klasse WHERE klassekode=?;";
          $stmt=mysqli_prepare($db,$sqlSetning);
          mysqli_stmt_bind_param($stmt,"s",$klassekode);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_store_result($stmt);

          $antallRader=mysqli_stmt_num_rows($stmt);
          mysqli_stmt_close($stmt);

          if ($antallRader==0)
            {
              print ("Klassen finnes ikke");
            }
          else
            {
              /* Sjekker om studenter tilhører klassen */
              $sqlSetning="SELECT brukernavn FROM student WHERE klassekode=?;";
              $stmt=mysqli_prepare($db,$sqlSetning);
              mysqli_stmt_bind_param($stmt,"s",$klassekode);
              mysqli_stmt_execute($stmt);
              mysqli_stmt_store_result($stmt);

              $antallStudenter=mysqli_stmt_num_rows($stmt);
              mysqli_stmt_close($stmt);

              if ($antallStudenter>0)
                {
                  print ("Klassen kan ikke slettes fordi den har registrerte studenter");
                }
              else
                {
                  $sqlSetning="DELETE FROM klasse WHERE klassekode=?;";
                  $stmt=mysqli_prepare($db,$sqlSetning);
                  mysqli_stmt_bind_param($stmt,"s",$klassekode);
                  mysqli_stmt_execute($stmt);
                  mysqli_stmt_close($stmt);

                  print ("Følgende klasse er nå slettet: "
                    .htmlspecialchars($klassekode,ENT_QUOTES,"UTF-8"));
                }
            }
        }
    }
?>