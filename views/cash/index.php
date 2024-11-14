<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Transações</title>
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
        .summary { display: flex; gap: 15px; margin-bottom: 20px; }
        .summary-item { flex: 1; padding: 15px; background-color: white; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .summary-item h3 { font-size: 1.2rem; margin: 0; color: #495057; }
        .summary-item p { font-size: 1rem; margin: 5px 0 0; color: #6c757d; }
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
    <h2>Financeiro</h2>
    <a href="cash" class="active"><i class="bi bi-cash-stack"></i> Financeiro</a>
    <a href="category"><i class="bi bi-tags"></i> Categoria</a>
    <a href="goal"><i class="bi bi-flag"></i> Metas</a>
    <a href="user"><i class="bi bi-people"></i> Usuários</a>
</div>

<div class="content">
    <h1>Controle de Transações</h1>

    <div class="summary">
        <div class="summary-item">
            <h3>Total de Receitas</h3>
            <p id="total-income">R$ 0,00</p>
        </div>
        <div class="summary-item">
            <h3>Total de Despesas</h3>
            <p id="total-expense">R$ 0,00</p>
        </div>
        <div class="summary-item">
            <h3>Saldo Final</h3>
            <p id="total-balance">R$ 0,00</p>
        </div>
    </div>


    <div class="text-end mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cashModal" onclick="resetForm()">
            <i class="bi bi-plus-circle"></i> Novo Gasto
        </button>
    </div>

    <table class="transaction-table">
        <thead>
            <tr>
                <th>Data</th>
                <th>Valor</th>
                <th>Nome do Gasto</th>
                <th>Categoria</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="table-body"></tbody>
    </table>
</div>

<div class="modal fade" id="cashModal" tabindex="-1" aria-labelledby="cashModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cashModalLabel">Adicionar Nova Transação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cash-form">
                    <input type="hidden" id="cash-id">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Usuário</label>
                        <select class="form-select" id="user_id" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Categoria</label>
                        <select class="form-select" id="category_id" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipo</label>
                        <select class="form-select" id="type" required>
                            <option value="income">Receita</option>
                            <option value="expense">Despesa</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Quantia</label>
                        <input type="number" class="form-control" id="amount" required step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Data</label>
                        <input type="date" class="form-control" id="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Nome do Gasto</label>
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
    const apiUrl = "http://localhost:8000/api/cash";

    document.addEventListener("DOMContentLoaded", async () => {
        await loadUsers();
        await loadCategories();
        fetchTransactions();
        document.getElementById("cash-form").addEventListener("submit", handleFormSubmit);
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
        }
    }

    async function fetchTransactions() {
        try {
            await loadCategories();
            const response = await fetch(apiUrl + "/show/all");
            const data = await response.json();
            renderTable(data.data);
            updateTotalValue(data.data);
        } catch (error) {
            Swal.fire('Erro', 'Erro ao buscar transações!', 'error');
        }
    }

    function renderTable(transactions) {
        const tableBody = document.getElementById("table-body");
        tableBody.innerHTML = "";
        transactions.forEach(transaction => {
            const categoryName = categoriesCache[transaction.category_id] || "-";
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${formatDate(transaction.date)}</td>
                <td>R$ ${transaction.amount}</td>
                <td>${transaction.description}</td>
                <td>${categoryName || '-'}</td>
                <td class="actions">
                    <button onclick="editTransaction(${transaction.id})"><i class="bi bi-pencil"></i></button>
                    <button onclick="deleteTransaction(${transaction.id})"><i class="bi bi-trash"></i></button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    function formatDate(dateString) {
        const [year, month, day] = dateString.split("-");
        return `${day}/${month}/${year}`;
    }

    function updateTotalValue(transactions) {
        let totalIncome = 0;
        let totalExpense = 0;

        transactions.forEach(transaction => {
            if (transaction.type === 'income') {
                totalIncome += parseFloat(transaction.amount);
            } else if (transaction.type === 'expense') {
                totalExpense += parseFloat(transaction.amount);
            }
        });

        const totalBalance = totalIncome - totalExpense;

        document.getElementById("total-income").innerText = `R$ ${totalIncome.toFixed(2)}`;
        document.getElementById("total-expense").innerText = `R$ ${totalExpense.toFixed(2)}`;
        document.getElementById("total-balance").innerText = `R$ ${totalBalance.toFixed(2)}`;
    }

    async function handleFormSubmit(event) {
        event.preventDefault();
        const transaction = {
            id: document.getElementById("cash-id").value || null,
            user_id: document.getElementById("user_id").value,
            category_id: document.getElementById("category_id").value,
            type: document.getElementById("type").value,
            amount: document.getElementById("amount").value,
            date: document.getElementById("date").value,
            description: document.getElementById("description").value,
        };
        if (transaction.id) {
            await updateTransaction(transaction);
        } else {
            await createTransaction(transaction);
        }
        fetchTransactions();
        bootstrap.Modal.getInstance(document.getElementById("cashModal")).hide();
    }

    async function createTransaction(transaction) {
        try {
            await fetch(apiUrl + "/create", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify(transaction) });
            Swal.fire('Sucesso', 'Transação criada com sucesso!', 'success');
        } catch (error) {
            Swal.fire('Erro', 'Erro ao criar transação!', 'error');
        }
    }

    async function updateTransaction(transaction) {
        try {
            await fetch(`${apiUrl}/update/${transaction.id}`, { method: "PUT", headers: { "Content-Type": "application/json" }, body: JSON.stringify(transaction) });
            Swal.fire('Sucesso', 'Transação atualizada com sucesso!', 'success');
        } catch (error) {
            Swal.fire('Erro', 'Erro ao atualizar transação!', 'error');
        }
    }

    async function deleteTransaction(id) {
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
                    fetchTransactions();
                    Swal.fire('Excluído!', 'Transação foi excluída com sucesso.', 'success');
                } catch (error) {
                    Swal.fire('Erro', 'Erro ao excluir transação!', 'error');
                }
            }
        });
    }

    async function editTransaction(id) {
        try {
            const response = await fetch(`${apiUrl}/show/${id}`);
            const data = await response.json();
            if (data.success) {
                document.getElementById("cash-id").value = data.data.id;
                document.getElementById("user_id").value = data.data.user_id;
                document.getElementById("category_id").value = data.data.category_id;
                document.getElementById("type").value = data.data.type;
                document.getElementById("amount").value = data.data.amount;
                document.getElementById("date").value = data.data.date;
                document.getElementById("description").value = data.data.description;
                document.getElementById("cashModalLabel").textContent = "Editar Transação";
                bootstrap.Modal.getOrCreateInstance(document.getElementById("cashModal")).show();
            }
        } catch (error) {
            Swal.fire('Erro', 'Erro ao carregar transação para edição!', 'error');
        }
    }

    function resetForm() {
        document.getElementById("cash-form").reset();
        document.getElementById("cash-id").value = "";
        document.getElementById("cashModalLabel").textContent = "Adicionar Nova Transação";
    }
</script>
</body>
</html>