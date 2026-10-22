
<?php
/* Programmet sletter en registrert student */

require_once __DIR__ . "/db-tilkobling.php";

if (isset($_POST["slettStudentKnapp"])) {

    $brukernavn = trim($_POST["brukernavn"] ?? "");

    if ($brukernavn === "") {
        print("Du må velge en student.");
    } else {

        $sqlSetning = "SELECT brukernavn FROM student WHERE brukernavn = ?";

        $stmt = mysqli_prepare($db, $sqlSetning);
        mysqli_stmt_bind_param($stmt, "s", $brukernavn);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        $antallRader = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_close($stmt);

        if ($antallRader == 0) {
            print("Studenten finnes ikke.");
        } else {

            $sqlSetning = "DELETE FROM student WHERE brukernavn = ?";

            $stmt = mysqli_prepare($db, $sqlSetning);
            mysqli_stmt_bind_param($stmt, "s", $brukernavn);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            print("Følgende student er nå slettet: "
                . htmlspecialchars($brukernavn, ENT_QUOTES, "UTF-8"));
        }
    }
}
?>

<!DOCTYPE html>
<html lang="no">

<head>
    <meta charset="UTF-8">
    <title>Slett student</title>
    <script src="funksjoner.js"></script>
</head>

<body>

<h3>Slett student</h3>

<form method="post" action="" onsubmit="return bekreft()">

    Brukernavn

    <select id="brukernavn" name="brukernavn" required>
        <option value="">Velg student</option>

        <?php
        $sqlSetning = "SELECT * FROM student ORDER BY brukernavn";
        $sqlResultat = mysqli_query($db, $sqlSetning);

        while ($rad = mysqli_fetch_assoc($sqlResultat)) {

            $brukernavn = htmlspecialchars(
                $rad["brukernavn"],
                ENT_QUOTES,
                "UTF-8"
            );

            $fornavn = htmlspecialchars(
                $rad["fornavn"],
                ENT_QUOTES,
                "UTF-8"
            );

            $etternavn = htmlspecialchars(
                $rad["etternavn"],
                ENT_QUOTES,
                "UTF-8"
            );

            print("<option value='$brukernavn'>
                $brukernavn - $fornavn $etternavn
            </option>");
        }
        ?>

    </select>

    <br><br>

    <input
        type="submit"
        name="slettStudentKnapp"
        id="slettStudentKnapp"
        value="Slett student"
    >

</form>

<p><a href="index.php">Tilbake til hovedsiden</a></p>

</body>
</html>
