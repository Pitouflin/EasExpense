<template>
  <div id="container">
    <h2>Se connecter</h2>
    <form @submit.prevent="submitForm" class="form">
      <ion-input v-model="login" placeholder="Login" class="input"></ion-input>
      <ion-input v-model="password" type="password" placeholder="Mot de passe" class="input"></ion-input>
      <ion-button type="submit" class="button">Se connecter</ion-button>
    </form>
    <hr/>
  </div>
</template>

<script setup lang="ts">
import { IonInput, IonButton } from '@ionic/vue';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { Preferences } from '@capacitor/preferences';
import { API_BASE_URL } from '../config';
import { onBeforeUnmount } from 'vue';

onBeforeUnmount(() => {
  console.log('ConnexionComponent is being unmounted');
});


const login = ref('');
const password = ref('');
const router = useRouter();

const submitForm = async () => {
  try {
    const response = await axios.post(`${API_BASE_URL}/user/check`, {
      login: login.value,
      password: password.value
    }, { headers: { 
      'Content-Type': 'application/json'
    } });

    if (response.data) {
      await Preferences.set({ key: "id", value: response.data.user.id.toString() });
      await Preferences.set({ key: "name", value: response.data.user.name });

      // Utilisez router.replace pour remplacer l'historique
      window.location.href = '/newReport'; 
    } else {
      alert('Aucune donnée reçue de l\'API.');
    }
  } catch (error) {
    console.error('Erreur lors de la connexion:', error);
    alert('Adresse mail ou mot de passe incorrect. Veuillez réessayer');
  }
};
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

.input {
  border: 1px solid #FFFFFF;
  margin: 5px;
  text-align: center;
}

.button {
  width: 100%;
}
</style>
