<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Metas</title>
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
    <a href="category"><i class="bi bi-tags"></i> Categoria</a>
    <a href="goal" class="active"><i class="bi bi-flag"></i> Metas</a>
    <a href="user"><i class="bi bi-people"></i> Usuários</a>
</div>

<div class="content">
    <h1>Gerenciar Metas</h1>

    <div class="text-end mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#goalModal" onclick="resetForm()">
            <i class="bi bi-plus-circle"></i> Nova Meta
        </button>
    </div>

    <table class="transaction-table">
        <thead>
            <tr>
                <th>Usuário</th>
                <th>Categoria</th>
                <th>Meta Alvo</th>
                <th>Quantia Atual</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="table-body"></tbody>
    </table>
</div>

<div class="modal fade" id="goalModal" tabindex="-1" aria-labelledby="goalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="goalModalLabel">Adicionar Nova Meta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="goal-form">
                    <input type="hidden" id="goal-id">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Usuário</label>
                        <select class="form-select" id="user_id" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Categoria</label>
                        <select class="form-select" id="category_id" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="target_amount" class="form-label">Meta Alvo</label>
                        <input type="number" class="form-control" id="target_amount" required step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="current_amount" class="form-label">Quantia Atual</label>
                        <input type="number" class="form-control" id="current_amount" required step="0.01">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const apiUrl = "http://localhost:8000/api/goal";

    document.addEventListener("DOMContentLoaded", async () => {
        await loadUsers();
        await loadCategories();
        fetchGoals();
        document.getElementById("goal-form").addEventListener("submit", handleFormSubmit);
    });

    async function loadUsers() {
        const userSelect = document.getElementById("user_id");
        try {
            const response = await fetch("http://localhost:8000/api/user/show/all");
            const users = await response.json();
            users.data.forEach(user => {
                const option = document.createElement("option");
                option.value = user.id;
                option.textContent = user.name;
                userSelect.appendChild(option);
            });
        } catch (error) {
            Swal.fire('Erro', 'Erro ao carregar usuários!', 'error');
            console.error("Erro ao carregar usuários:", error);
        }
    }

    let categoriesCache = {};
    async function loadCategories() {
        const categorySelect = document.getElementById("category_id");
        categorySelect.innerHTML = "";
        try {
            const response = await fetch("http://localhost:8000/api/category/show/all");
            const categories = await response.json();
            categories.data.forEach(category => {
                const option = document.createElement("option");
                option.value = category.id;
                option.textContent = category.name;
                categorySelect.appendChild(option);
                categoriesCache[category.id] = category.name;
            });
        } catch (error) {
            Swal.fire('Erro', 'Erro ao carregar categorias!', 'error');
            console.error("Erro ao carregar categorias:", error);
        }
    }

    async function fetchGoals() {
        try {
            const response = await fetch(apiUrl + "/show/all");
            const data = await response.json();
            renderTable(data.data);
        } catch (error) {
            Swal.fire('Erro', 'Erro ao buscar metas!', 'error');
            console.error("Erro ao buscar metas:", error);
        }
    }

    function renderTable(goals) {
        const tableBody = document.getElementById("table-body");
        tableBody.innerHTML = "";
        goals.forEach(goal => {
            const categoryName = categoriesCache[goal.category_id] || "-";
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${goal.user_id}</td>
                <td>${categoryName}</td>
                <td>R$ ${goal.target_amount}</td>
                <td>R$ ${goal.current_amount}</td>
                <td>${goal.description}</td>
                <td class="actions">
                    <button onclick="editGoal(${goal.id})"><i class="bi bi-pencil"></i></button>
                    <button onclick="deleteGoal(${goal.id})"><i class="bi bi-trash"></i></button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    async function handleFormSubmit(event) {
        event.preventDefault();
        const goal = {
            id: document.getElementById("goal-id").value || null,
            user_id: document.getElementById("user_id").value,
            category_id: document.getElementById("category_id").value,
            target_amount: document.getElementById("target_amount").value,
            current_amount: document.getElementById("current_amount").value,
            description: document.getElementById("description").value
        };
        try {
            if (goal.id) {
                await updateGoal(goal);
                Swal.fire('Sucesso', 'Meta atualizada com sucesso!', 'success');
            } else {
                await createGoal(goal);
                Swal.fire('Sucesso', 'Meta criada com sucesso!', 'success');
            }
            fetchGoals();
            bootstrap.Modal.getInstance(document.getElementById("goalModal")).hide();
        } catch (error) {
            Swal.fire('Erro', 'Erro ao salvar meta!', 'error');
            console.error("Erro ao salvar meta:", error);
        }
    }

    async function createGoal(goal) {
        await fetch(apiUrl + "/create", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify(goal) });
    }

    async function updateGoal(goal) {
        await fetch(`${apiUrl}/update/${goal.id}`, { method: "PUT", headers: { "Content-Type": "application/json" }, body: JSON.stringify(goal) });
    }

    async function deleteGoal(id) {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Você não poderá reverter esta ação!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    await fetch(`${apiUrl}/delete/${id}`, { method: "DELETE" });
                    fetchGoals();
                    Swal.fire('Excluído!', 'Meta excluída com sucesso!', 'success');
                } catch (error) {
                    Swal.fire('Erro', 'Erro ao excluir meta!', 'error');
                    console.error("Erro ao excluir meta:", error);
                }
            }
        });
    }

    async function editGoal(id) {
        try {
            const response = await fetch(`${apiUrl}/show/${id}`);
            const data = await response.json();
            if (data.success) {
                document.getElementById("goal-id").value = data.data.id;
                document.getElementById("user_id").value = data.data.user_id;
                document.getElementById("category_id").value = data.data.category_id;
                document.getElementById("target_amount").value = data.data.target_amount;
                document.getElementById("current_amount").value = data.data.current_amount;
                document.getElementById("description").value = data.data.description;
                document.getElementById("goalModalLabel").textContent = "Editar Meta";
                bootstrap.Modal.getOrCreateInstance(document.getElementById("goalModal")).show();
            }
        } catch (error) {
            Swal.fire('Erro', 'Erro ao carregar meta para edição!', 'error');
            console.error("Erro ao carregar meta para edição:", error);
        }
    }

    function resetForm() {
        document.getElementById("goal-form").reset();
        document.getElementById("goal-id").value = "";
        document.getElementById("goalModalLabel").textContent = "Adicionar Nova Meta";
    }
</script>
</body>
</html>