document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('delete-form');

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        let id = document.getElementById('id').value;

        fetch(`/delete/part?id=${id}`, {
            method: "DELETE",
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.status === "success") {
                    console.log(data.message);
                    alert("Deletion successful!");
                } else if (data.status === "error") {
                    console.error(data.message);
                    alert("Delete failed: " + data.message);
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("An error occurred. Please try again.");
            });
    })


})