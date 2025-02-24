<section class="reclamations-dashboard p-8">
    <h1 class="text-center">Gestion des avis et réclamations</h1>

    <!-- Statistiques -->
    <div class="stats-grid mt-8">
        <div class="stat-card">
            <h3>Total</h3>
            <div class="stat-value"><?= $stats['total'] ?></div>
        </div>
        <div class="stat-card">
            <h3>Avis</h3>
            <div class="stat-value"><?= $stats['total_avis'] ?></div>
            <div class="stat-detail">Note moyenne: <?= number_format($stats['moyenne_avis'], 1) ?>/5</div>
        </div>
        <div class="stat-card">
            <h3>Réclamations</h3>
            <div class="stat-value"><?= $stats['total_reclamations'] ?></div>
        </div>
        <div class="stat-card">
            <h3>En attente</h3>
            <div class="stat-value"><?= $stats['en_attente'] ?></div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="filters mt-8">
        <form method="GET" class="flex gap-4">
            <select name="type" onchange="this.form.submit()">
                <option value="all" <?= $filters['type'] === 'all' ? 'selected' : '' ?>>Tous les types</option>
                <option value="avis" <?= $filters['type'] === 'avis' ? 'selected' : '' ?>>Avis</option>
                <option value="reclamation" <?= $filters['type'] === 'reclamation' ? 'selected' : '' ?>>Réclamations</option>
            </select>
            <select name="statut" onchange="this.form.submit()">
                <option value="all" <?= $filters['statut'] === 'all' ? 'selected' : '' ?>>Tous les statuts</option>
                <option value="en_attente" <?= $filters['statut'] === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                <option value="en_cours" <?= $filters['statut'] === 'en_cours' ? 'selected' : '' ?>>En cours</option>
                <option value="resolue" <?= $filters['statut'] === 'resolue' ? 'selected' : '' ?>>Résolue</option>
                <option value="rejetee" <?= $filters['statut'] === 'rejetee' ? 'selected' : '' ?>>Rejetée</option>
            </select>
        </form>
    </div>

    <!-- Liste des réclamations -->
    <div class="reclamations-list">
        <?php foreach ($reclamations as $reclamation): ?>
            <div class="reclamation-card">
                <div class="reclamation-header">
                    <h3><?= htmlspecialchars($reclamation['type'] === 'avis' ? 'Avis' : 'Réclamation') ?></h3>
                    <span class="status <?= $reclamation['statut'] ?>"><?= ucfirst($reclamation['statut']) ?></span>
                </div>

                <div class="reclamation-content">
                    <?php if ($reclamation['type'] === 'avis'): ?>
                        <div class="rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="star <?= $i <= $reclamation['note'] ? 'filled' : '' ?>">★</span>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>

                    <p class="description"><?= htmlspecialchars($reclamation['description']) ?></p>

                    <div class="metadata flex flex-column gap-2 mb-2">
                        <span>Client: <?= htmlspecialchars($reclamation['user_prenom'] . ' ' . $reclamation['user_nom']) ?></span>
                        <span>Demande #<?= $reclamation['demande_id'] ?></span>
                        <span>Date: <?= (new DateTime($reclamation['date_creation']))->format('d/m/Y H:i') ?></span>
                    </div>

                    <?php if ($reclamation['reponse']): ?>
                        <div class="response">
                            <h4>Réponse:</h4>
                            <p><?= htmlspecialchars($reclamation['reponse']) ?></p>
                            <span class="response-date">
                                Le <?= (new DateTime($reclamation['date_reponse']))->format('d/m/Y H:i') ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($reclamation['statut'] === 'en_attente'): ?>
                    <div class="actions flex gap-2">
                        <button
                            onclick="openRepondreModal(<?= $reclamation['id'] ?>)"
                            class="button button-sm">
                            Répondre
                        </button>
                        <form method="POST" class="inline">
                            <input type="hidden" name="action" value="rejeter">
                            <input type="hidden" name="reclamation_id" value="<?= $reclamation['id'] ?>">
                            <button type="submit" class="button button-sm bg-red">Rejeter</button>
                        </form>
                        <form method="POST" class="inline">
                            <input type="hidden" name="action" value="accepter">
                            <input type="hidden" name="reclamation_id" value="<?= $reclamation['id'] ?>">
                            <button type="submit" class="button button-sm bg-green">Accepter</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Modal de réponse -->
    <div id="repondreModal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2>Répondre à la réclamation</h2>
            <form method="POST">
                <input type="hidden" name="action" value="repondre">
                <input type="hidden" name="reclamation_id" id="modalReclamationId">
                <textarea name="reponse" required class="w-full h-32"></textarea>
                <div class="modal-actions">
                    <button type="button" onclick="closeRepondreModal()" class="btn btn-secondary">Annuler</button>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRepondreModal(reclamationId) {
            document.getElementById('modalReclamationId').value = reclamationId;
            document.getElementById('repondreModal').style.display = 'block';
        }

        function closeRepondreModal() {
            document.getElementById('repondreModal').style.display = 'none';
        }
    </script>
</section>