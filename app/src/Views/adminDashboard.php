<section class="p-8">
    <h1 class="text-center m-8">Dashboard administrateur</h1>

    <div>
        <form action="" method="GET">
            <select name="periode" onchange="this.form.submit()">
                <option value="7" <?= $periode == '7' ? 'selected' : '' ?>>7 derniers jours</option>
                <option value="30" <?= $periode == '30' ? 'selected' : '' ?>>30 derniers jours</option>
                <option value="90" <?= $periode == '90' ? 'selected' : '' ?>>90 derniers jours</option>
                <option value="365" <?= $periode == '365' ? 'selected' : '' ?>>12 derniers mois</option>
            </select>
        </form>
    </div>

    <div>
        <div>
            <h3>Demandes</h3>
            <div><?= $stats_globales['total_demandes'] ?></div>
            <div class="mt-3">
                Terminées: <?= $stats_globales['demandes_terminees'] ?>
            </div>
        </div>

        <div>
            <h3>Clients</h3>
            <div><?= $stats_globales['nombre_clients'] ?></div>
        </div>

        <div>
            <h3>Techniciens actifs</h3>
            <div class="stat-value"><?= $stats_globales['nombre_techniciens'] ?></div>
        </div>

        <div>
            <h3>Taux de complétion</h3>
            <div>
                <?= $stats_globales['total_demandes'] > 0
                    ? round(($stats_globales['demandes_terminees'] / $stats_globales['total_demandes']) * 100, 1)
                    : 0 ?>%
            </div>
        </div>
    </div>

    <div>
        <h2 class="text-center m-8">Évolution des demandes</h2>
        <canvas id="demandesChart"></canvas>
    </div>

    <div class>
        <h2 class="text-center m-8">Services les plus demandés</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Nombre de demandes</th>
                    <th>Clients uniques</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($top_services as $service): ?>
                    <tr>
                        <td><?= htmlspecialchars($service['service']) ?></td>
                        <td><?= $service['nombre'] ?></td>
                        <td><?= $service['clients_uniques'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div>
        <h2 class="text-center m-8">Performance des techniciens</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Technicien</th>
                    <th>Demandes totales</th>
                    <th>Demandes terminées</th>
                    <th>Service le plus fréquent</th>
                    <th>Clients uniques</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stats_techniciens as $tech): ?>
                    <tr>
                        <td><?= htmlspecialchars($tech['prenom'] . ' ' . $tech['nom']) ?></td>
                        <td><?= $tech['nombre_demandes'] ?></td>
                        <td><?= $tech['demandes_terminees'] ?></td>
                        <td><?= htmlspecialchars($tech['service_plus_frequent'] ?? 'Aucun service') ?></td>
                        <td><?= $tech['nombre_clients_uniques'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script>
        const demandesData = <?= json_encode($evolution_demandes) ?>;

        new Chart(document.getElementById('demandesChart'), {
            type: 'line',
            data: {
                labels: demandesData.map(item => item.mois),
                datasets: [{
                    label: 'Nombre de demandes',
                    data: demandesData.map(item => item.nombre_demandes),
                    borderColor: '#2563eb',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    </script>
</section>