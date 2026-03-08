<script>
    function handleTableClick(table) {
        if (table.status === 'occupied') {
            document.getElementById('occupiedTableName').textContent = table.name;
            const childInfo = document.getElementById('childTableInfo');
            const masterInfo = document.getElementById('masterTableInfo');
            const viewOrderBtn = document.getElementById('viewOrderBtn');
            const btnTransfer = document.getElementById('btnTransfer');
            const btnMerge = document.getElementById('btnMerge');
            const btnAddMoreTables = document.getElementById('btnAddMoreTables');

            if (table.parent_id) {
                // This is a child table in a merged group
                childInfo.style.display = 'block';
                masterInfo.style.display = 'none';
                document.getElementById('parentNameLabel').textContent = table.parent_name;
                document.getElementById('unmergeTableId').value = table.id;
                viewOrderBtn.href = '<?= BASE_URL ?>/orders?table_id=' + table.parent_id;
                btnTransfer.parentElement.style.display = 'none';
                btnMerge.parentElement.style.display = 'none';
            } else {
                // This is either a standalone table or a master table
                childInfo.style.display = 'none';

                // Check if this is a master table managing merged children
                fetch('<?= BASE_URL ?>/tables/getMergedChildren?id=' + table.id)
                    .then(response => response.json())
                    .then(data => {
                        if (data.children && data.children.length > 0) {
                            // This is a master table with merged children
                            masterInfo.style.display = 'block';
                            document.getElementById('childCountLabel').textContent = data.children.length;
                            viewOrderBtn.href = '<?= BASE_URL ?>/orders?table_id=' + table.id;

                            // Set up the "Add More Tables" button
                            btnAddMoreTables.onclick = () => {
                                Aurora.closeModal('modalOccupied');
                                openTargetModal('merge', table, true);
                            };

                            btnTransfer.parentElement.style.display = 'block';
                            btnMerge.parentElement.style.display = 'block';
                        } else {
                            // This is a standalone table
                            masterInfo.style.display = 'none';
                            viewOrderBtn.href = '<?= BASE_URL ?>/orders?table_id=' + table.id;
                            btnTransfer.parentElement.style.display = 'block';
                            btnMerge.parentElement.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error checking merged children:', error);
                        masterInfo.style.display = 'none';
                        viewOrderBtn.href = '<?= BASE_URL ?>/orders?table_id=' + table.id;
                        btnTransfer.parentElement.style.display = 'block';
                        btnMerge.parentElement.style.display = 'block';
                    });
            }

            btnTransfer.onclick = () => { Aurora.closeModal('modalOccupied'); openTargetModal('transfer', table, false); };
            btnMerge.onclick = () => { Aurora.closeModal('modalOccupied'); openTargetModal('merge', table, false); };

            Aurora.openModal('modalOccupied');
        } else {
            document.getElementById('modalTableName').textContent = table.name;
            document.getElementById('openTableId').value = table.id;
            Aurora.openModal('modalOpenTable');
        }
    }

    function openTargetModal(type, sourceTable, isAdditionalMerge = false) {
        const form = document.getElementById('targetForm');
        const title = document.getElementById('targetModalTitle');
        const desc = document.getElementById('targetModalDesc');
        const sourceInput = document.getElementById('sourceTableId');
        const targetSelect = document.getElementById('targetSelect');
        const submitBtn = document.getElementById('targetSubmitBtn');
        
        sourceInput.value = sourceTable.id;
        targetSelect.name = type === 'transfer' ? 'to_table_id' : 'child_id';
        targetSelect.value = '';

        const optGroups = targetSelect.querySelectorAll('optgroup');
        optGroups.forEach(group => {
            if (type === 'merge') {
                if (isAdditionalMerge || group.dataset.area === sourceTable.area) {
                    group.style.display = '';
                    group.disabled = false;
                } else {
                    group.style.display = 'none';
                    group.disabled = true;
                }
            } else {
                group.style.display = '';
                group.disabled = false;
            }
        });

        if (type === 'transfer') {
            sourceInput.name = 'from_table_id';
            form.action = '<?= BASE_URL ?>/tables/transfer';
            title.innerHTML = 'Chuyển bàn';
            submitBtn.textContent = 'CHUYỂN SANG ' + sourceTable.name;
        } else {
            sourceInput.name = 'parent_id';
            form.action = '<?= BASE_URL ?>/tables/merge';
            title.innerHTML = 'Ghép thêm bàn';
            submitBtn.textContent = 'GHÉP VỚI ' + sourceTable.name;
        }
        Aurora.openModal('modalSelectTarget');
    }

    // Close modal on outside click
    document.addEventListener('click', function(e) {
        const modals = document.querySelectorAll('.modal-backdrop');
        modals.forEach(modal => {
            if (modal !== e.target && !modal.contains(e.target) && modal.classList.contains('show')) {
                Aurora.closeModal(modal.id);
            }
        });
    });
</script>
