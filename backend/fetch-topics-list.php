<?php
require_once '../db-backend.php';
$classname = $_GET['classname'] ?? '';
$subcode = $_GET['subcode'] ?? '0';



$datam = [];

$stmt = $conn->prepare("  SELECT * FROM textbook where classname=? and subcode=? order by chapter, subchapter");
$stmt->bind_param("ss", $classname, $subcode);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $datam[] = $row;
}
$stmt->close();

?>
<h4><?php echo $subcode; ?></h4>

<ul class="tree">
<?php
$prev_chno = 0;
foreach ($datam as $data) {

    $id = $data['id'];
    $chno = $data['chapter'];
    $chtitle = $data['title'];
    $subno = $data['subchapter'];
    $subtitle = $data['subtitle'];

    if ($prev_chno != $chno) {
        ?>
        <li>
            <span class="caret">📁 <?php echo $chno . ' '. $chtitle; ?></span>
            <ul class="nested">

                <?php
    }
    ?>
            <li>📄 <?php echo $chno . '.' . $subno . ' ' . $subtitle; ?></li>

            <?php
            if ($prev_chno != $chno) {
                ?>

            </ul>
        </li>
        <?php
            }
            ?>
    <?php
}
?>

</ul>

