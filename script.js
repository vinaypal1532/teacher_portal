function editStudent(id) {
    const row = document.querySelector(`tr[data-id='${id}']`);
    const name = row.querySelector('.name').innerText;
    const subject = row.querySelector('.subject').innerText;
    const marks = row.querySelector('.marks').innerText;

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
            alert('Student updated successfully');
        } else {
            alert('Error updating student');
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteStudent(id) {
    if (!confirm('Are you sure you want to delete this student?')) return;

    fetch(`../teacher_portal/delete_student.php?id=${id}`, {
        method: 'GET',
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            document.querySelector(`tr[data-id='${id}']`).remove();
            alert('Student deleted successfully');
        } else {
            alert('Error deleting student');
        }
    })
    .catch(error => console.error('Error:', error));
}
