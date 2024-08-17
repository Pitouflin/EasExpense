<template>
  <div id="container">
    <h2>Déclarer une note de frais</h2>
    <form class="form" @submit.prevent="handleSubmit">
      <ion-select placeholder="Type de dépense" v-model="expenseType">
        <ion-select-option
          v-for="option in optionsType"
          :key="option.id"
          :value="option.id">
          {{ option.name }}
        </ion-select-option>
      </ion-select>

      <ion-select placeholder="Sélectionner le véhicule" v-model="selectedVehicleId">
        <ion-select-option
          v-for="vehicle in userVehicles"
          :key="vehicle.id"
          :value="vehicle.id">
          {{ vehicle.name }}
        </ion-select-option>
      </ion-select>

      <ion-input v-model="expenseValue" placeholder="Montant" class="input"></ion-input>
      <ion-input v-model="expenseComment" placeholder="Commentaire Dépense" class="input"></ion-input>
      <ion-button type="submit" class="button">Créer</ion-button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';
import jsPDF from 'jspdf';
import { API_BASE_URL } from '../config';
import { IonInput, IonButton, IonSelect, IonSelectOption } from '@ionic/vue';
import axios from 'axios';
import { Preferences } from '@capacitor/preferences';

const expenseType = ref<number | null>(null);
const expenseValue = ref('');
const expenseComment = ref('');
const optionsType = ref([]);
const userVehicles = ref([]);
const selectedVehicleId = ref<string | null>(null);
const selectedVehicle = ref<any>(null);
const userName = ref(''); // Nom de l'utilisateur
const expenseId = ref<number | null>(null); // ID de la note de frais
const expenseDate = ref<string>(''); // Date de la note de frais

const updateSelectedVehicle = () => {
  selectedVehicle.value = userVehicles.value.find(vehicle => vehicle.id === selectedVehicleId.value) || null;
};

watch(selectedVehicleId, updateSelectedVehicle);

const handleSubmit = async () => {
  if (!expenseComment.value) {
    alert("Le commentaire est obligatoire.");
    return;
  }

  await saveExpenseReport();
  await generatePdf();
};

const generatePdf = async () => {
  const selectedType = optionsType.value.find(option => option.id === expenseType.value);
  const typeName = selectedType ? selectedType.name : 'N/A';

  if (typeName === 'Kilométrage' && selectedVehicle.value) {
    await X();
  }

  const doc = new jsPDF();
  doc.text('Note de frais', 10, 10);
  doc.text(`ID : ${expenseId.value}`, 10, 20); // Ajout de l'ID de la note de frais
  doc.text(`Date : ${expenseDate.value}`, 10, 30); // Ajout de la date au format JJ/MM/AAAA
  doc.text(`Utilisateur : ${userName.value}`, 10, 40);
  doc.text(`Commentaire : ${expenseComment.value}`, 10, 50);
  doc.text(`Type de dépense : ${typeName}`, 10, 60);
  doc.text(`Montant : ${expenseValue.value}`, 10, 70);

  if (selectedVehicle.value) {
    doc.text(`Véhicule : ${selectedVehicle.value.name}`, 10, 80);
  }

  doc.save('note_de_frais.pdf');
};

const saveExpenseReport = async () => {
  try {
    const { value: userId } = await Preferences.get({ key: 'id' });
    if (!userId) {
      console.error("User ID non trouvé.");
      return;
    }

    const payload = {
      userId: userId,
      vehicleId: selectedVehicleId.value,
      expenseTypeId: expenseType.value,
      expenseValue: expenseValue.value,
      comment: expenseComment.value
    };

    const response = await axios.post('http://127.0.0.1:8000/expense_report/create', payload, {
      headers: {
        'Content-Type': 'application/json'
      }
    });

    if (response.status === 201) {
      console.log('Note de frais enregistrée avec succès');
      // Mise à jour de l'ID et de la date de la note de frais
      expenseId.value = response.data.id;
      expenseDate.value = new Date().toLocaleDateString('fr-FR'); // Format JJ/MM/AAAA
    }
  } catch (error) {
    console.error('Erreur lors de l\'enregistrement de la note de frais :', error);
  }
};

const X = async () => {
  try {
    if (selectedVehicle.value && selectedVehicle.value.ratioPer20000 !== undefined) {
      const montantFinal = parseFloat(expenseValue.value) * selectedVehicle.value.ratioPer20000;
      console.log(`Montant final après application du ratio: ${montantFinal}`);
      expenseValue.value = montantFinal.toFixed(2);
    } else {
      console.error('ratioPer20000 non trouvé pour', selectedVehicle.value ? selectedVehicle.value.name : 'inconnu');
    }
  } catch (error) {
    console.error('Erreur lors de la récupération des données du véhicule:', error);
  }
};

const getTypes = async () => {
  try {
    const response = await axios.get(`${API_BASE_URL}/expense_type`);
    optionsType.value = response.data;
  } catch (error) {
    console.error('Error fetching types:', error);
  }
};

const getUserDetails = async () => {
  try {
    const { value } = await Preferences.get({ key: 'id' });
    const userId = value;

    if (userId) {
      const response = await axios.get(`http://127.0.0.1:8000/user/${userId}`);
      console.log('Détails de l\'utilisateur:', response.data);
      userVehicles.value = response.data.vehicleList;
      userName.value = response.data.name;
    } else {
      console.error('User ID non trouvé.');
    }
  } catch (error) {
    console.error('Erreur lors de la récupération des détails de l\'utilisateur:', error);
  }
};

onMounted(() => {
  getTypes();
  getUserDetails();
});
</script>

<style scoped>
#container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  width: 80%;
  margin: 0 auto;
}

.form {
  border: 1px solid #FFFFFF;
  display: flex;
  flex-direction: column;
  justify-content: space-around;
  align-items: center;
  min-width: 300px;
  padding: 10px;
}

.input, ion-select {
  border: 1px solid #0000FF;
  margin: 5px;
  text-align: center;
  padding: 2px;
}

.button {
  width: 100%;
}
</style>
