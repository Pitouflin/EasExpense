<template>
  <div id="container">
    <h1>Déclarer une note de frais</h1>
    <h6>Tous les champs sont obligatoires</h6>
    <form class="form" @submit.prevent="handleSubmit">
      <ion-select placeholder="Type de dépense" v-model="expenseType">
        <ion-select-option
          v-for="option in optionsType"
          :key="option.id"
          :value="option.id">
          {{ option.name }}
        </ion-select-option>
      </ion-select>

      <!-- Afficher le sélecteur de véhicule uniquement si le type de dépense est 2 -->
      <ion-select
        v-if="isExpenseType2"
        placeholder="Sélectionner le véhicule"
        v-model="selectedVehicleId">
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
import { ref, watch, onMounted, computed } from 'vue';
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

// Variable pour stocker le montant après application du coefficient
const finalExpenseValue = ref('');

const updateSelectedVehicle = () => {
  selectedVehicle.value = userVehicles.value.find(vehicle => vehicle.id === selectedVehicleId.value) || null;
};

watch(selectedVehicleId, updateSelectedVehicle);

const handleSubmit = async () => {
  if (!expenseComment.value) {
    alert("Le commentaire est obligatoire.");
    return;
  }

  if (!expenseType.value) {
    alert("Le type de dépense est obligatoire.");
    return;
  }

  // Mettre à jour le montant si le type de dépense est 'Kilométrage'
  if (isExpenseType2 && selectedVehicle.value) {
    await calculateFinalExpenseValue();
  } else {
    finalExpenseValue.value = expenseValue.value;
  }

  // Vérifiez si l'ID et la date sont bien définis avant de générer le PDF
  if (finalExpenseValue.value && expenseType.value && expenseComment.value) {
    await saveExpenseReport();
    if (expenseId.value && expenseDate.value) {
      await generatePdf();
    } else {
      console.error('L\'ID ou la date de la note de frais est manquant.');
    }
  } else {
    console.error('Certaines informations sont manquantes.');
  }
};

const generatePdf = async () => {
  const selectedType = optionsType.value.find(option => option.id === expenseType.value);
  const typeName = selectedType ? selectedType.name : 'N/A';

  const doc = new jsPDF();
  doc.text('Note de frais', 10, 10);
  doc.text(`ID : ${expenseId.value || 'N/A'}`, 10, 20); // Ajout de l'ID de la note de frais
  doc.text(`Date : ${expenseDate.value || 'N/A'}`, 10, 30); // Ajout de la date au format JJ/MM/AAAA
  doc.text(`Utilisateur : ${userName.value}`, 10, 40);
  doc.text(`Commentaire : ${expenseComment.value}`, 10, 50);
  doc.text(`Type de dépense : ${typeName}`, 10, 60);
  doc.text(`Montant : ${finalExpenseValue.value}`, 10, 70);

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

    // Assurez-vous que expenseValue est un nombre
    const expenseValueNumber = parseFloat(expenseValue.value);
    if (isNaN(expenseValueNumber)) {
      console.error('Montant de la dépense invalide');
      return;
    }

    const payload = {
      userId: userId,
      vehicleId: isExpenseType2 ? selectedVehicleId.value : null, // Inclure vehicleId uniquement pour les types nécessitant un véhicule
      expenseTypeId: expenseType.value,
      expenseValue: expenseValueNumber, // Assurez-vous que expenseValue est un nombre
      comment: expenseComment.value
    };

    console.log('Payload avant envoi:', payload); // Log du payload

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
    console.error('Erreur lors de l\'enregistrement de la note de frais :', error.response ? error.response.data : error);
  }
};


const calculateFinalExpenseValue = async () => {
  try {
    if (selectedVehicle.value && selectedVehicle.value.ratioPer20000 !== undefined) {
      const montantFinal = parseFloat(expenseValue.value) * selectedVehicle.value.ratioPer20000;
      console.log(`Montant final après application du ratio: ${montantFinal}`);
      finalExpenseValue.value = montantFinal.toFixed(2);
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

// Propriété calculée pour vérifier si le type de dépense est 2
const isExpenseType2 = computed(() => expenseType.value === 2);

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
