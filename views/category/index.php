<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Categorias</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: Arial, sans-serif; color: #333; background-color: #f4f5f7; display: flex; margin: 0; }
        .sidebar { width: 250px; background-color: #212529; color: white; height: 100vh; position: fixed; display: flex; flex-direction: column; align-items: center; padding-top: 20px; }
        .sidebar h2 { font-size: 1.5rem; margin-bottom: 2rem; }
        .sidebar a { text-decoration: none; color: #adb5bd; font-size: 1rem; padding: 10px; width: 100%; text-align: left; padding-left: 20px; display: flex; align-items: center; gap: 10px; }
        .sidebar a:hover, .sidebar a.active { background-color: #343a40; color: white; }
        .content { margin-left: 250px; padding: 20px; width: 100%; }
        .content h1 { font-size: 2rem; margin-bottom: 10px; }
        .transaction-table { width: 100%; border-collapse: collapse; border-radius: 8px; overflow: hidden; background-color: #ffffff; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); }
        .transaction-table thead { background-color: #343a40; color: white; }
        .transaction-table th, .transaction-table td { padding: 12px; text-align: left; }
        .transaction-table tr:nth-child(even) { background-color: #f8f9fa; }
        .transaction-table .actions { display: flex; gap: 5px; }
        .transaction-table .actions button { background: none; border: none; color: #6c757d; cursor: pointer; font-size: 1.2rem; }
        .transaction-table .actions button:hover { color: #0d6efd; }
        .modal-header { background-color: #007bff; color: #ffffff; }
        .modal-footer { display: flex; justify-content: space-between; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Gestão</h2>
    <a href="cash"><i class="bi bi-cash-stack"></i> Financeiro</a>
    <a href="category" class="active"><i class="bi bi-tags"></i> Categoria</a>
    <a href="goal"><i class="bi bi-flag"></i> Metas</a>
    <a href="user"><i class="bi bi-people"></i> Usuários</a>
</div>

<div class="content">
    <h1>Gerenciar Categorias</h1>

    <div class="text-end mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal" onclick="resetForm()">
            <i class="bi bi-plus-circle"></i> Nova Categoria
        </button>
    </div>

    <table class="transaction-table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="table-body"></tbody>
    </table>
</div>

<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Adicionar Nova Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="category-form">
                    <input type="hidden" id="category-id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control" id="description" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const apiUrl = "http://localhost:8000/api/category";

    document.addEventListener("DOMContentLoaded", () => {
        fetchCategories();
        document.getElementById("category-form").addEventListener("submit", handleFormSubmit);
    });

    async function fetchCategories() {
        try {
            const response = await fetch(apiUrl + "/show/all");
            const data = await response.json();
            renderTable(data.data);
        } catch (error) {
            console.error("Erro ao buscar categorias:", error);
            Swal.fire('Erro', 'Erro ao buscar categorias!', 'error');
        }
    }

    function renderTable(categories) {
        const tableBody = document.getElementById("table-body");
        tableBody.innerHTML = "";
        categories.forEach(category => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${category.name}</td>
                <td>${category.description}</td>
                <td class="actions">
                    <button onclick="editCategory(${category.id})"><i class="bi bi-pencil"></i></button>
                    <button onclick="deleteCategory(${category.id})"><i class="bi bi-trash"></i></button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    async function handleFormSubmit(event) {
        event.preventDefault();
        const category = {
            id: document.getElementById("category-id").value || null,
            name: document.getElementById("name").value,
            description: document.getElementById("description").value
        };
        if (category.id) {
            await updateCategory(category);
        } else {
            await createCategory(category);
        }
        fetchCategories();
        bootstrap.Modal.getInstance(document.getElementById("categoryModal")).hide();
    }

    async function createCategory(category) {
        try {
            await fetch(apiUrl + "/create", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify(category) });
            Swal.fire('Sucesso', 'Categoria criada com sucesso!', 'success');
        } catch (error) {
            console.error("Erro ao criar categoria:", error);
            Swal.fire('Erro', 'Erro ao criar categoria!', 'error');
        }
    }

    async function updateCategory(category) {
        try {
            await fetch(`${apiUrl}/update/${category.id}`, { method: "PUT", headers: { "Content-Type": "application/json" }, body: JSON.stringify(category) });
            Swal.fire('Sucesso', 'Categoria atualizada com sucesso!', 'success');
        } catch (error) {
            console.error("Erro ao atualizar categoria:", error);
            Swal.fire('Erro', 'Erro ao atualizar categoria!', 'error');
        }
    }

    async function deleteCategory(id) {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Você não poderá reverter isso!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    await fetch(`${apiUrl}/delete/${id}`, { method: "DELETE" });
                    console.log(apiUrl + "/delete/" + id)
                    fetchCategories();
                    Swal.fire('Excluído!', 'Categoria foi excluída com sucesso.', 'success');
                } catch (error) {
                    console.error("Erro ao excluir categoria:", error);
                    Swal.fire('Erro', 'Erro ao excluir categoria!', 'error');
                }
            }
        });
    }

    async function editCategory(id) {
        try {
            const response = await fetch(`${apiUrl}/show/${id}`);
            const data = await response.json();
            if (data.success) {
                document.getElementById("category-id").value = data.data.id;
                document.getElementById("name").value = data.data.name;
                document.getElementById("description").value = data.data.description;
                document.getElementById("categoryModalLabel").textContent = "Editar Categoria";
                bootstrap.Modal.getOrCreateInstance(document.getElementById("categoryModal")).show();
            }
        } catch (error) {
            console.error("Erro ao carregar categoria para edição:", error);
            Swal.fire('Erro', 'Erro ao carregar categoria para edição!', 'error');
        }
    }

    function resetForm() {
        document.getElementById("category-form").reset();
        document.getElementById("category-id").value = "";
        document.getElementById("categoryModalLabel").textContent = "Adicionar Nova Categoria";
    }
</script>
</body>
</html>