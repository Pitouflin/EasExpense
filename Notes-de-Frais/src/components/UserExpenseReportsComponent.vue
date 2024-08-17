<template>
  <div>
    <h2>Mes Notes de Frais</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Véhicule</th>
          <th>Date</th>
          <th>Montant</th>
          <th>Commentaire</th>
          <th>État</th>
          <th>Type de Dépense</th>
          <th>Commentaire Admin</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="report in expenseReports" :key="report.id">
          <td>{{ report.id }}</td>
          <td>{{ report.vehicle ? report.vehicle.name : 'N/A' }}</td>
          <td>{{ formatDate(report.date) }}</td>
          <td>{{ report.value }}</td>
          <td>{{ report.comment }}</td>
          <td>{{ report.state.value }}</td>
          <td>{{ report.expenseType.name }}</td>
          <td>{{ report.adminComment }}</td>

        </tr>
      </tbody>
    </table>


  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Preferences } from '@capacitor/preferences';

export default {
  setup() {
    const expenseReports = ref([]);
    const expenseTypes = ref([]);
    const vehicles = ref([]);
    const selectedReport = ref(null);

    onMounted(async () => {
      await fetchExpenseReports();
      await fetchExpenseTypes();
      await fetchVehicles();
    });

    const fetchExpenseReports = async () => {
      try {
        const { value: userId } = await Preferences.get({ key: 'id' });
        if (userId) {
          const response = await axios.get(`http://127.0.0.1:8000/user/${userId}/expense_reports`);
          expenseReports.value = response.data;
          console.log(expenseReports.value);
        }
      } catch (error) {
        console.error('Erreur lors de la récupération des notes de frais:', error);
      }
    };

    const fetchExpenseTypes = async () => {
      try {
        const response = await axios.get('http://127.0.0.1:8000/expense_type');
        expenseTypes.value = response.data;
      } catch (error) {
        console.error('Erreur lors de la récupération des types de dépense:', error);
      }
    };

    const fetchVehicles = async () => {
      try {
        const { value: userId } = await Preferences.get({ key: 'id' });
        if (userId) {
          const response = await axios.get(`http://127.0.0.1:8000/user/${userId}/vehicles`);
          vehicles.value = response.data;
        }
      } catch (error) {
        console.error('Erreur lors de la récupération des véhicules:', error);
      }
    };

    const formatDate = (dateString) => {
      if (!dateString) return 'N/A';
      const date = new Date(dateString);
      if (isNaN(date.getTime())) {
        console.error('Date invalide:', dateString);
        return 'Date invalide';
      }
      const day = String(date.getUTCDate()).padStart(2, '0');
      const month = String(date.getUTCMonth() + 1).padStart(2, '0');
      const year = date.getUTCFullYear();
      return `${day}/${month}/${year}`;
    };


    return { expenseReports, expenseTypes, vehicles, selectedReport, formatDate };
  }
};
</script>

<style scoped>
th, td {
  padding: 5px;
}
.edit-form {
  margin-top: 20px;
}
.edit-form label {
  display: block;
  margin-bottom: 10px;
}
</style>
