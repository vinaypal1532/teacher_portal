<?php
include '../teacher_portal/db.php';
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.html');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM students");
$stmt->execute();
$students = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../teacher_portal/styles.css">
    <script src="../teacher_portal/script.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Student Listing</h1>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#studentModal">Add Student</button>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>Marks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr data-id="<?php echo $student['id']; ?>">
                    <td class="name"><?php echo $student['name']; ?></td>
                    <td class="subject"><?php echo $student['subject']; ?></td>
                    <td class="marks"><?php echo $student['marks']; ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="openEditModal(<?php echo $student['id']; ?>)">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteStudent(<?php echo $student['id']; ?>)">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addStudentForm">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject:</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="marks">Marks:</label>
                            <input type="number" class="form-control" id="marks" name="marks" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Student</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Student Modal -->
    <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editStudentForm">
                        <input type="hidden" id="editStudentId" name="id">
                        <div class="form-group">
                            <label for="editName">Name:</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="editSubject">Subject:</label>
                            <input type="text" class="form-control" id="editSubject" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="editMarks">Marks:</label>
                            <input type="number" class="form-control" id="editMarks" name="marks" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Student</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('addStudentForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('../teacher_portal/students.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Student added successfully',
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.message || 'Error adding student',
                });
            }
        })
        .catch(error => console.error('Error:', error));
    });

    function openEditModal(id) {
        const row = document.querySelector(`tr[data-id='${id}']`);
        const name = row.querySelector('.name').innerText;
        const subject = row.querySelector('.subject').innerText;
        const marks = row.querySelector('.marks').innerText;

        document.getElementById('editStudentId').value = id;
        document.getElementById('editName').value = name;
        document.getElementById('editSubject').value = subject;
        document.getElementById('editMarks').value = marks;

        $('#editStudentModal').modal('show');
    }

    document.getElementById('editStudentForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const id = document.getElementById('editStudentId').value;
        const name = document.getElementById('editName').value;
        const subject = document.getElementById('editSubject').value;
        const marks = document.getElementById('editMarks').value;

        const data = { id, name, subject, marks };

        fetch('../teacher_portal/edit_student.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Student updated successfully',
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error updating student',
                });
            }
        })
        .catch(error => console.error('Error:', error));
    });

    function deleteStudent(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`../teacher_portal/delete_student.php?id=${id}`, {
                    method: 'GET',
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        document.querySelector(`tr[data-id='${id}']`).remove();
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Student has been deleted.',
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error deleting student',
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    }
    </script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
