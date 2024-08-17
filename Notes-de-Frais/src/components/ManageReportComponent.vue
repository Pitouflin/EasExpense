<template>
  <div>
    <h2>Gestion des Notes de Frais</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Utilisateur</th>
          <th>Véhicule</th>
          <th>Date</th> <!-- Nouvelle colonne pour la date -->
          <th>Montant</th>
          <th>Commentaire</th>
          <th>État</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="report in expenseReports" :key="report.id">
          <td>{{ report.id }}</td>
          <td>{{ report.userId.name }}</td>
          <td>{{ report.vehicle ? report.vehicle.name : 'N/A' }}</td>
          <td>{{ formatDate(report.date) }}</td> <!-- Affichage de la date -->
          <td>{{ report.value }}</td>
          <td>{{ report.comment }}</td>
          <td>{{ report.state.value }}</td>
          <td>
            <button @click="showModal(report.id, 'approve')">Approuver</button>
            <button @click="showModal(report.id, 'reject')">Renvoyer</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Modal pour l'approbation ou le rejet -->
    <div v-if="modalVisible" class="modal">
      <div class="modal-content">
        <span class="close" @click="modalVisible = false">&times;</span>
        <h3>{{ actionType === 'approve' ? 'Approuver' : 'Renvoyer' }} la Note de Frais</h3>
        <!-- Commentaire est requis uniquement pour le rejet -->
        <textarea v-if="actionType === 'reject'" v-model="comment" placeholder="Ajouter un commentaire"></textarea>
        <button @click="submitAction">Confirmer</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { Preferences } from '@capacitor/preferences';

export default {
  data() {
    return {
      expenseReports: [],
      modalVisible: false,
      currentReportId: null,
      actionType: null,
      comment: ''
    };
  },
  methods: {
    async fetchExpenseReports() {
      try {
        const response = await axios.get('http://127.0.0.1:8000/expense_report/');
        this.expenseReports = response.data;
      } catch (error) {
        console.error('Erreur lors de la récupération des notes de frais:', error);
      }
    },
    showModal(reportId, action) {
      this.currentReportId = reportId;
      this.actionType = action;
      this.modalVisible = true;
    },
    async submitAction() {
      if (this.actionType === 'reject' && !this.comment.trim()) {
        alert('Veuillez entrer un commentaire pour renvoyer la note de frais.');
        return;
      }

      try {
        const stateId = this.actionType === 'approve' ? 2 : 3; // 2 pour acceptée, 3 pour refusée
        const payload = {
          stateId: stateId,
          // Inclure adminComment uniquement pour les rejets
          adminComment: this.actionType === 'reject' ? this.comment.trim() : null
        };

        await axios.put(`http://127.0.0.1:8000/expense_report/update_state/${this.currentReportId}`, payload);

        alert('Action effectuée avec succès.');
        this.modalVisible = false;
        this.comment = '';
        this.fetchExpenseReports(); // Recharger les notes de frais après la mise à jour
      } catch (error) {
        console.error('Erreur lors de la mise à jour de l\'état de la note de frais:', error.response.data);
        alert('Erreur lors de la mise à jour de la note de frais : ' + error.response.data.error);
      }
    },
    async checkAdminRole() {
      try {
        const { value: userId } = await Preferences.get({ key: 'id' });
        if (!userId) {
          alert('Utilisateur non authentifié.');
          window.location.href = '/'; // Utilisation de window.location.href pour une redirection complète
          return;
        }

        const response = await axios.get(`http://127.0.0.1:8000/user/${userId}`);
        const user = response.data;
        if (!user.roles.includes('ROLE_ADMIN')) {
          alert('Accès refusé : vous n\'avez pas les permissions nécessaires.');
          window.location.href = '/'; // Redirection complète si l'utilisateur n'est pas admin
        }
      } catch (error) {
        console.error('Erreur lors de la vérification des rôles de l\'utilisateur:', error);
        window.location.href = '/'; // Redirection en cas d'erreur
      }
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('fr-FR');
    }
  },
  created() {
    this.checkAdminRole();
    this.fetchExpenseReports();
  }
};
</script>

<style scoped>
th, td {
  padding: 5px;
}

tr{
  border-bottom: solid 1px #0000FF;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}
.modal-content {
  background: white;
  padding: 20px;
  border-radius: 5px;
  width: 400px;
  text-align: center;
}
.close {
  position: absolute;
  top: 10px;
  right: 10px;
  cursor: pointer;
}
textarea {
  width: 100%;
  height: 100px;
  margin-bottom: 10px;
}
button {
  padding: 5px;
  margin: 5px;
}
</style>
