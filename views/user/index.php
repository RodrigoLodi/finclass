<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
    <a href="goal"><i class="bi bi-flag"></i> Metas</a>
    <a href="user" class="active"><i class="bi bi-people"></i> Usuários</a>
</div>

<div class="content">
    <h1>Gerenciar Usuários</h1>

    <div class="text-end mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal" onclick="resetForm()">
            <i class="bi bi-plus-circle"></i> Novo Usuário
        </button>
    </div>

    <table class="transaction-table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="table-body"></tbody>
    </table>
</div>

<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Adicionar Novo Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="user-form">
                    <input type="hidden" id="user-id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="password" required>
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
    const apiUrl = "http://localhost:8000/api/user";

    document.addEventListener("DOMContentLoaded", () => {
        fetchUsers();
        document.getElementById("user-form").addEventListener("submit", handleFormSubmit);
    });

    async function fetchUsers() {
        try {
            const response = await fetch(apiUrl + "/show/all");
            const data = await response.json();
            renderTable(data.data);
        } catch (error) {
            console.error("Erro ao buscar usuários:", error);
            Swal.fire('Erro', 'Erro ao buscar usuários!', 'error');
        }
    }

    function renderTable(users) {
        const tableBody = document.getElementById("table-body");
        tableBody.innerHTML = "";
        users.forEach(user => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td class="actions">
                    <button onclick="editUser(${user.id})"><i class="bi bi-pencil"></i></button>
                    <button onclick="deleteUser(${user.id})"><i class="bi bi-trash"></i></button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    async function handleFormSubmit(event) {
        event.preventDefault();
        const user = {
            id: document.getElementById("user-id").value || null,
            name: document.getElementById("name").value,
            email: document.getElementById("email").value,
            password: document.getElementById("password").value
        };
        if (user.id) {
            await updateUser(user);
        } else {
            await createUser(user);
        }
        fetchUsers();
        bootstrap.Modal.getInstance(document.getElementById("userModal")).hide();
    }

    async function createUser(user) {
        try {
            await fetch(apiUrl + "/create", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify(user) });
            Swal.fire('Sucesso', 'Usuário criado com sucesso!', 'success');
        } catch (error) {
            console.error("Erro ao criar usuário:", error);
            Swal.fire('Erro', 'Erro ao criar usuário!', 'error');
        }
    }

    async function updateUser(user) {
        try {
            await fetch(`${apiUrl}/update/${user.id}`, { method: "PUT", headers: { "Content-Type": "application/json" }, body: JSON.stringify(user) });
            Swal.fire('Sucesso', 'Usuário atualizado com sucesso!', 'success');
        } catch (error) {
            console.error("Erro ao atualizar usuário:", error);
            Swal.fire('Erro', 'Erro ao atualizar usuário!', 'error');
        }
    }

    async function deleteUser(id) {
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
                    fetchUsers();
                    Swal.fire('Excluído!', 'Usuário foi excluído com sucesso.', 'success');
                } catch (error) {
                    console.error("Erro ao excluir usuário:", error);
                    Swal.fire('Erro', 'Erro ao excluir usuário!', 'error');
                }
            }
        });
    }

    async function editUser(id) {
        try {
            const response = await fetch(`${apiUrl}/show/${id}`);
            const data = await response.json();
            if (data.success) {
                document.getElementById("user-id").value = data.data.id;
                document.getElementById("name").value = data.data.name;
                document.getElementById("email").value = data.data.email;
                document.getElementById("password").value = "";
                document.getElementById("userModalLabel").textContent = "Editar Usuário";
                bootstrap.Modal.getOrCreateInstance(document.getElementById("userModal")).show();
            }
        } catch (error) {
            console.error("Erro ao carregar usuário para edição:", error);
            Swal.fire('Erro', 'Erro ao carregar usuário para edição!', 'error');
        }
    }

    function resetForm() {
        document.getElementById("user-form").reset();
        document.getElementById("user-id").value = "";
        document.getElementById("userModalLabel").textContent = "Adicionar Novo Usuário";
    }
</script>
</body>
</html>
