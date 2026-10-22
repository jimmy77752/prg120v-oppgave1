<?php  /* vis-alle-studenter */
/*
  Programmet skriver ut alle registrerte studenter
*/

include("db-tilkobling.php");

/* Henter alle studenter fra databasen */
$sqlSetning="SELECT * FROM student;";

$sqlResultat=mysqli_query($db,$sqlSetning)
    or die("Ikke mulig å hente data fra databasen");

/* Antall registrerte studenter */
$antallRader=mysqli_num_rows($sqlResultat);

print("<h3>Registrerte studenter</h3>");

if ($antallRader==0)
{
    print("Ingen studenter er registrert");
}
else
{
    print("<table border=1>");

    print("<tr>
        <th align=left>Brukernavn</th>
        <th align=left>Fornavn</th>
        <th align=left>Etternavn</th>
        <th align=left>Klassekode</th>
    </tr>");

    for ($r=1;$r<=$antallRader;$r++)
    {
        $rad=mysqli_fetch_array($sqlResultat);

        $brukernavn=htmlspecialchars($rad["brukernavn"]);
        $fornavn=htmlspecialchars($rad["fornavn"]);
        $etternavn=htmlspecialchars($rad["etternavn"]);
        $klassekode=htmlspecialchars($rad["klassekode"]);

        print("<tr>
            <td>$brukernavn</td>
            <td>$fornavn</td>
            <td>$etternavn</td>
            <td>$klassekode</td>
        </tr>");
    }

    print("</table>");
}
?>