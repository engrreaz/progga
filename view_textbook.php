<?php
require_once 'db.php';

if (empty($_SESSION['usr']) || $_SESSION['role'] === 'Super Administrator') {
    // header('Location: login.php');
    // exit;
}
$error = $success = '';
$entryby = $_SESSION['usr'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $results = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $classname = $_POST['classname'];
        $subcode = $_POST['subcode'];

        $stmt = $conn->prepare("SELECT * FROM textbook WHERE classname = ? AND subcode = ?");
        $stmt->bind_param("si", $classname, $subcode);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }

        $stmt->close();
    }
}

include 'header.php';


?>

<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid #999;
        padding: 8px;
        text-align: left;
    }
</style>

<h2>View Textbook Entries</h2>

<form method="post" action="">
    <label>Class Name: <input type="text" name="classname" required></label>
    <label>Subject Code: <input type="number" name="subcode" required></label>
    <input type="submit" value="Search">
</form>

<?php if (!empty($results)): ?>
    <h3>Results:</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Class</th>
            <th>Sub Code</th>
            <th>UniqID</th>
            <th>Order1</th>
            <th>Order2</th>
            <th>Chapter</th>
            <th>Title</th>
            <th>Subchapter</th>
            <th>Subtitle</th>
            <th>ReqClass</th>
            <th>EntryBy</th>
            <th>EntryTime</th>
        </tr>
        <?php foreach ($results as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['classname']) ?></td>
                <td><?= htmlspecialchars($row['subcode']) ?></td>
                <td><?= htmlspecialchars($row['uniqid']) ?></td>
                <td><?= htmlspecialchars($row['order1']) ?></td>
                <td><?= htmlspecialchars($row['order2']) ?></td>
                <td><?= htmlspecialchars($row['chapter']) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['subchapter']) ?></td>
                <td><?= htmlspecialchars($row['subtitle']) ?></td>
                <td><?= htmlspecialchars($row['reqclass']) ?></td>
                <td><?= htmlspecialchars($row['entryby']) ?></td>
                <td><?= htmlspecialchars($row['entrytime']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <p>No records found for the given class and subject code.</p>
<?php endif; ?>


<?php include 'footer.php'; ?>