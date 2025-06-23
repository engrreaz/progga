<?php
require_once 'db.php';

if (empty($_SESSION['usr']) || $_SESSION['userlevel'] != 'Super Administrator') {
    header('Location: login.php');
    exit;
}
$error = $success = '';
$entryby = $_SESSION['usr'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $classname = $_POST['classname'] ?? '';
    $subcode = intval($_POST['subcode']);
    $uniqid = $_POST['uniqid'] ?? '';
    $order1 = intval($_POST['order1']);
    $order2 = intval($_POST['order2']);

    $cuschapter = isset($_POST['custom_chapter']) ? intval($_POST['custom_chapter']) : 0;
    if ($cuschapter === 0) {
        $chapter = intval($_POST['chapter']);
    } else {
        $chapter = $cuschapter;
    }
    $title = $_POST['title'] ?? '';
    $subchapter = $_POST['subchapter'] ?? '';
    $subtitle = $_POST['subtitle'] ?? uniqid();
    $reqclass = intval($_POST['reqclass']);

    $stmt = $conn->prepare("INSERT INTO textbook (classname, subcode, uniqid, order1, order2, chapter, title, subchapter, subtitle, reqclass, entryby, entrytime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sisiiisisis", $classname, $subcode, $uniqid, $order1, $order2, $chapter, $title, $subchapter, $subtitle, $reqclass, $entryby);

    if ($stmt->execute()) {
        $success = "Textbook entry added successfully!";
    } else {
        $error = "Error: " . $stmt->error;
    }
    $stmt->close();
}




// db.php যুক্ত করতে হবে
$chapterOptions = [];
$result = $conn->query("SELECT DISTINCT chapter FROM textbook ORDER BY chapter ASC");
while ($row = $result->fetch_assoc()) {
    $chapterOptions[] = $row['chapter'];
}

// $classList = $conn->query("SELECT id, classname FROM classes")->fetch_all(MYSQLI_ASSOC);
$classList = [
    ['id' => 9, 'classname' => 'Nine'],
    ['id' => 10, 'classname' => 'Ten']
];
?>

<h4>নতুন Textbook ডেটা যুক্ত করুন</h4>
<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

<form method="POST">

    <div class="row">
        <div class="col-md-9">

            <div class="row">
   
                    <div class="mb-2">
                        <label class="form-label">Class</label>
                        <select name="classname" id="classname" class="form-select" required>
                            <option value="">--Select Class--</option>
                            <?php foreach ($classList as $cls): ?>
                                <option value="<?= $cls['classname'] ?>"><?= htmlspecialchars($cls['classname']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                       <div class="mb-3">
        <label class="form-label">Subject Code</label>
        <select name="subcode" id="subcode" class="form-select" required>
            <option value="">--Select Subject--</option>
        </select>
    </div>

            </div>
        </div>
        <div class="col-md-3">

        </div>
    </div>


 


    <div class="mb-3"><label>Unique ID</label><input type="text" name="uniqid" class="form-control" required></div>
    <div class="mb-3"><label>Order 1</label><input type="number" name="order1" class="form-control" required></div>
    <div class="mb-3"><label>Order 2</label><input type="number" name="order2" class="form-control" required></div>


    <div class="mb-3">
        <label class="form-label">Chapter (Select or Enter)</label>
        <select id="chapter" name="chapter" class="form-select" onchange="toggleCustomChapter(this.value)">
            <option value="">--Select Existing Chapter--</option>
            <?php foreach ($chapterOptions as $ch): ?>
                <option value="<?= $ch ?>"><?= $ch ?></option>
            <?php endforeach; ?>
            <option value="custom">Custom Chapter...</option>
        </select>
    </div>

    <div class="mb-3" id="custom_chapter_div" style="display: none;">
        <label class="form-label">Custom Chapter Number</label>
        <input type="number" name="custom_chapter" id="custom_chapter" class="form-control">
    </div>


    <div class="mb-3"><label>Title</label><input type="text" name="title" class="form-control" required></div>


    <div class="mb-3"><label>Sub Chapter</label><input type="text" name="subchapter" class="form-control" required>
    </div>
    <div class="mb-3"><label>Subtitle</label><input type="text" name="subtitle" class="form-control"></div>
    <div class="mb-3"><label>Required Class</label><input type="number" name="reqclass" class="form-control" required>
    </div>
    <button class="btn btn-primary">Add Textbook Entry</button>
</form>


<script>
    function toggleCustomChapter(val) {
        const div = document.getElementById('custom_chapter_div');
        div.style.display = (val === 'custom') ? 'block' : 'none';
    }
</script>

<script>
    document.getElementById('classname').addEventListener('change', function () {
        const classId = this.value;
        const subjectDropdown = document.getElementById('subcode');
        subjectDropdown.innerHTML = '<option>Loading...</option>';

        fetch('get_subjects.php?class_id=' + classId)
            .then(res => res.json())
            .then(data => {
                subjectDropdown.innerHTML = '<option value="">--Select Subject--</option>';
                data.forEach(sub => {
                    const opt = document.createElement('option');
                    opt.value = sub.subcode;
                    opt.text = sub.subject_name + ' (' + sub.subcode + ')';
                    subjectDropdown.appendChild(opt);
                });
            });
    });
</script>

<?php include 'footer.php'; ?>