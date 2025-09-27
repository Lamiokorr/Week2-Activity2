document.addEventListener("DOMContentLoaded", () => {
    fetchCategories();

    // Handle Add
    document.getElementById("addCategoryForm").addEventListener("submit", e => {
        e.preventDefault();
        let formData = new FormData(e.target);

        fetch("../actions/add_category_action.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(msg => {
            alert(msg);
            fetchCategories();
        });
    });
});

// Fetch categories
function fetchCategories() {
    fetch("../actions/fetch_category_action.php")
    .then(res => res.json())
    .then(data => {
        let output = "<ul>";
        data.forEach(cat => {
            output += `
                <li>
                    ${cat.name} 
                    <button onclick="deleteCategory(${cat.id})">Delete</button>
                    <button onclick="updateCategory(${cat.id}, '${cat.name}')">Update</button>
                </li>`;
        });
        output += "</ul>";
        document.getElementById("categoryList").innerHTML = output;
    });
}

// Delete category
function deleteCategory(id) {
    let formData = new FormData();
    formData.append("id", id);

    fetch("../actions/delete_category_action.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(msg => {
        alert(msg);
        fetchCategories();
    });
}

// Update category
function updateCategory(id, oldName) {
    let newName = prompt("Enter new name:", oldName);
    if (!newName) return;

    let formData = new FormData();
    formData.append("id", id);
    formData.append("name", newName);

    fetch("../actions/update_category_action.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(msg => {
        alert(msg);
        fetchCategories();
    });
}
