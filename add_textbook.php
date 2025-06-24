<?php
require_once 'db.php';

if (empty($_SESSION['usr']) || $_SESSION['userlevel'] != 'Super Administrator') {
    header('Location: login.php');
    exit;
}
$error = $success = '';
$entryby = $_SESSION['usr'];


$exid = $_GET['id'] ?? 0;
if ($exid > 0) {
    $exdata = [];
    $stmt2 = $conn->prepare("SELECT * FROM textbook where id=?  ");
    $stmt2->bind_param("i", $exid);
    $stmt2->execute();
    $res = $stmt2->get_result();
    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();
        $exdata[] = $row;
    } else {
        $error = " Record Not Found.";
    }
    $stmt2->close();



    $id = $exdata[0]['id'] ?? '';
    $uniqid = $exdata[0]['uniqid'] ?? uniqid();
    $classname = $exdata[0]['classname'] ?? '';
    $subcode = $exdata[0]['subcode'] ?? '';
    $order1 = $exdata[0]['order1'] ?? '';
    $order2 = $exdata[0]['order2'] ?? '';
    $chapter = $exdata[0]['chapter'] ?? '';
    $title = $exdata[0]['title'] ?? '';
    $subchapter = $exdata[0]['subchapter'] ?? '';
    $subtitle = $exdata[0]['subtitle'] ?? '';
    $reqclass = $exdata[0]['reqclass'] ?? '';

} else {
    $id = '';
    $uniqid = uniqid();

    $classname = '';
    $subcode = '';
    $order1 = 1;
    $order2 = 1;
    $chapter = '';
    $title = '';
    $subchapter = '';
    $subtitle = '';
    $reqclass = 1;
}




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
    $subtitle = $_POST['subtitle'] ?? '';
    $reqclass = intval($_POST['reqclass']);

    if ($exid == 0) {
        $stmt = $conn->prepare("INSERT INTO textbook (classname, subcode, uniqid, order1, order2, chapter, title, subchapter, subtitle, reqclass, entryby, entrytime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sisiiisisis", $classname, $subcode, $uniqid, $order1, $order2, $chapter, $title, $subchapter, $subtitle, $reqclass, $entryby);

    } else {
        $stmt = $conn->prepare("UPDATE textbook set classname=?, subcode=?, order1=?, order2=?, chapter=?, title=?, subchapter=?, subtitle=?, reqclass=? where id=?");
        $stmt->bind_param("siiiisisii", $classname, $subcode, $order1, $order2, $chapter, $title, $subchapter, $subtitle, $reqclass, $exid);

    }

    if ($stmt->execute()) {
        $success = "Textbook entry added successfully!";
    } else {
        $error = "Error: " . $stmt->error;
    }
    $stmt->close();

    echo '<script>fetch_topics();</script>';
}




// db.php যুক্ত করতে হবে
$chapterOptions = [];

$stmt2 = $conn->prepare("SELECT * FROM textbook where classname=? and subcode=? group by chapter ORDER BY chapter ASC ");
$stmt2->bind_param("ss", $classname, $subcode);
$stmt2->execute();

$res = $stmt2->get_result();
if ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $chapterOptions[] = $row;
    }
}
$stmt2->close();

var_dump($chapterOptions);

// $classList = $conn->query("SELECT id, classname FROM classes")->fetch_all(MYSQLI_ASSOC);
$classList = [
    ['id' => 9, 'classname' => 'Nine'],
    ['id' => 10, 'classname' => 'Ten']
];
?>


<h4>পাঠ্যপুস্তক কাঠামো সূচী</h4>
<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

<form method="POST">

    <div class="row">
        <div class="col-md-9">


            <div class="row mb-3">
                <div class="col-md-2 "><label>ID</label><input type="text" name="id" class="form-control "
                        value="<?Php echo $id; ?>" readonly></div>
                <div class="col-md-2 "><label>Unique ID</label><input type="text" name="uniqid" class="form-control"
                        value="<?Php echo $uniqid; ?>" readonly></div>
            </div>

            <div class="row mb-3">

                <div class="col-md-2">
                    <label>Class</label>
                    <select name="classname" id="classname" class="form-select" required>
                        <option value="">--Select Class--</option>
                        <?php

                        foreach ($classList as $cls):
                            if ($cls['classname'] == $classname) {
                                $selcls = 'selected';
                            } else {
                                $selcls = '';
                            }

                            ?>
                            <option value="<?= $cls['classname'] ?>" <?php echo $selcls; ?>>
                                <?= htmlspecialchars($cls['classname']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Subject Code</label>
                    <select name="subcode" id="subcode" class="form-select" onchange="fetch_topics();" required>
                        <option value="">--Select Subject--</option>
                    </select>
                </div>




                <div class="col-md-2 "><label>Order 1</label><input type="number" name="order1"
                        value="<?Php echo $order1; ?>" class="form-control">
                </div>
                <div class="col-md-2 "><label>Order 2</label><input type="number" name="order2"
                        value="<?Php echo $order2; ?>" class="form-control">
                </div>


                <div class="col-md-2 "><label>Required Class</label><input type="number" name="reqclass"
                        value="<?Php echo $reqclass; ?>" class="form-control">

                </div>
            </div>
            <div class="row mb-3">



                <div class="col-md-2 ">
                    <label>Chapter No.</label>
                    <select id="chapter" name="chapter" class="form-select" onchange="toggleCustomChapter(this.value)">
                        <option value="">--Select Existing Chapter--</option>
                        <?php foreach ($chapterOptions as $ch): ?>
                            <?php
                            if ($ch['chapter'] == $chapter) {
                                $selchap = 'selected';
                            } else {
                                $selchap = '';
                            }
                            ?>

                            <option value="<?= $ch['chapter'] ?>" <?php echo $selchap; ?>><?= $ch['chapter'] ?></option>
                        <?php endforeach; ?>
                        <option value="custom">Custom Chapter...</option>
                    </select>

                    <div class="mb-2" id="custom_chapter_div" style="display: none;">
                        <input type="number" name="custom_chapter" id="custom_chapter" placeholder="Enter Chapter"
                            class="form-control">
                    </div>

                </div>




                <div class="col-md-4 "><label>Title</label><input type="text" name="title" class="form-control"
                        value="<?Php echo $title; ?>" required></div>


                <div class="col-md-2 "><label>Sub Chapter</label><input type="text" name="subchapter"
                        value="<?Php echo $subchapter; ?>" class="form-control" required>
                </div>
                <div class="col-md-4 "><label>Subtitle</label><input type="text" name="subtitle" class="form-control"
                        value="<?Php echo $subtitle; ?>">
                </div>

            </div>

            <div class="row mb-3">
                <div class="row mb-3">
                    <div class="col-md-2">
                        <button name="main-form" class="btn btn-primary d-block"> Submit </button>
                    </div>

                </div>
            </div>

        </div>

        <div class="col-md-3" id="topics-list">
            <ul class="tree">
                <li>
                    <span class="caret ">📁 1. পর্যায় সারণী</span>
                    <ul class="nested  ">
                        <li>📄 1.1 অষ্ঠক</li>
                        <li>📄 1.2 পর্যায় সূত্র</li>
                        <li>📄 1.3 মেন্ডেলিফের সারণী</li>
                        <li>
                            <span class="caret">📁 JavaScript</span>
                            <ul class="nested ">
                                <li>📄 Basics</li>
                                <li>📄 DOM</li>
                                <li>📄 Events</li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <span class="caret">📁 Data Science</span>
                    <ul class="nested">
                        <li>📄 Python</li>
                        <li>📄 Pandas</li>
                    </ul>
                </li>
            </ul>

        </div>


    </div>








</form>


<?php var_dump($chapterOptions); ?>


<?php include 'footer.php'; ?>


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
                    opt.text = sub.subject + ' (' + sub.subcode + ')';
                    subjectDropdown.appendChild(opt);
                });
                const subsub = '<?php echo $subcode; ?>';
                subjectDropdown.value = subsub;
            });

        document.getElementById("subcode").value = "<?php echo $subcode; ?>";
    });
</script>

<script>
    function activateCaretToggle() {
        const carets = document.querySelectorAll('.caret');
        carets.forEach(caret => {
            caret.onclick = function () {
                const nested = this.parentElement.querySelector(".nested");
                if (nested) {
                    nested.classList.toggle("active");
                    this.classList.toggle("caret-down");
                }
            }
        });
    }

    // প্রথমবার লোডের সময় রান করাবেন
    document.addEventListener('DOMContentLoaded', activateCaretToggle);
</script>


<script>
    function fetch_topics() {

        const clsname = document.getElementById('classname').value;
        const subcode = document.getElementById('subcode').value;
        document.getElementById('topics-list').innerHTML = '<option>Loading...</option>';

        fetch('backend/fetch-topics-list.php?classname=' + clsname + '&subcode=' + subcode)
            .then(res => res.text())  // <-- JSON নয়, raw HTML চাই
            .then(html => {
                document.getElementById('topics-list').innerHTML = html;
                activateCaretToggle();
            });
    };

    fetch_topics();
</script>


<script>
    const classnameSelect = document.getElementById("classname");
    classnameSelect.dispatchEvent(new Event('change'));

    document.getElementById('subcode').appendChild("<?php echo $subcode; ?>");

    if (<?= $exid; ?> > 0 && <?= count($exdata); ?> == 0) {
        alert('Record Not Found...');
        window.location.href = 'add_textbook.php';
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglers = document.getElementsByClassName("caret");
        for (let i = 0; i < togglers.length; i++) {
            togglers[i].addEventListener("click", function () {
                this.parentElement.querySelector(".nested").classList.toggle("active");
                this.classList.toggle("caret-down");
            });
        }
    });
</script>

