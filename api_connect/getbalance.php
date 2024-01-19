<?php
    require($_SERVER['DOCUMENT_ROOT'].'/auth/config-alt.php');
    $sql = "SELECT * FROM `table` WHERE `NAME` = '" . $_SESSION["user"] . "'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $balance = number_format($row["BALANCE"], 2);
        };
    } 
    
    $conn->close();
?>