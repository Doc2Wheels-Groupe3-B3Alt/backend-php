<section class="table-page">
    <h1>Donner votre avis sur la demande</h1>

    <form action="/profil/demande/avis" method="POST" class="connexion-bloc">
        <input type="hidden" name="demande_id" value="<?= htmlspecialchars($demandeId) ?>">


        <label for="type">Type :</label>
        <select name="type" id="type" required onchange="toggleNoteField()">
            <option value="avis">Avis</option>
            <option value="reclamation">Réclamation</option>
        </select>



        <label for="note">Note :</label>
        <select name="note" id="note">
            <option value="5">5 - Excellent</option>
            <option value="4">4 - Très bien</option>
            <option value="3">3 - Bien</option>
            <option value="2">2 - Moyen</option>
            <option value="1">1 - Insatisfait</option>
        </select>



        <label for="description">Description :</label>
        <textarea name="description" id="description" rows="5" required></textarea>


        <div class="form-buttons">
            <button type="submit" class="button">Envoyer</button>
            <a href="/profil/demandes" class="button button-secondary">Retour</a>
        </div>
    </form>

    <script>
        function toggleNoteField() {
            const type = document.getElementById('type').value;
            const noteGroup = document.getElementById('noteGroup');
            noteGroup.style.display = type === 'avis' ? 'block' : 'none';
            document.getElementById('note').required = type === 'avis';
        }

        // Exécuter au chargement de la page
        document.addEventListener('DOMContentLoaded', toggleNoteField);
    </script>
</section>