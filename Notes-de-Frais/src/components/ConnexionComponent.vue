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
  import { onMounted, ref } from 'vue';
  import { useRouter } from 'vue-router';
  import axios from 'axios';
  import { Preferences } from '@capacitor/preferences';
  import {API_BASE_URL} from '../config'
  import { Device } from '@capacitor/device';

  // État pour les champs du formulaire
  const login = ref('');
  const password = ref('');

  // État pour les données utilisateur récupérées
  const userData = ref(null);

  // Utiliser le routeur de Vue pour la redirection après la connexion
  const router = useRouter();

  const deviceType = ref(null);

  // Fonction pour gérer la soumission du formulaire
  const submitForm = async () => {
    try {
      const response = await axios.post(`${API_BASE_URL}/user/User/check`, {
          login: login.value,
          password: password.value      
      }, { headers: { 
        'Content-Type': 'multipart/form-data'
      }
      });
      // console.log(response.data)




      if (response.data) {
        console.log(response.data)
        userData.value = response.data.user;
        const data = response.data;
        if(data.role.id != 1 && (deviceType.value == "android" || deviceType.value == "iPhone")){
          alert("Vous ne pouvez pas vous connecter en tant qu'administrateur ou modérateur depuis ce type d'appareil.")
          return;
        }
        Preferences.set({key: "id", value: data.id})
        Preferences.set({key: "name", value: data.name})
        // Ajouter les infos utilisateur en storage
        router.push('/home');
      } else {
        alert(response.data.value);
      }

    } catch (error) {
      // console.error('Error logging in:', error);
      alert('Adresse mail ou mot de passe incorrect. Veuillez réessayer');
    }
  };

  // const clearCache = () => {
  //   Preferences.clear()
  //   console.log("Cache vidé")
  // }

  // const checkStorage = () => {
  //   console.log("test")
  //   console.log(Preferences.keys(), Preferences.keys)
  //   for(const key in Preferences.keys()){
  //     Preferences.get({key}).then(result => {
  //       console.log("retreived data : ", key, result.value)
  //     })
  //   }
  // }

  const logDeviceInfo = async () => {
    const info = await Device.getInfo();

    deviceType.value = info.operatingSystem
  };

  onMounted(() => {
    logDeviceInfo();
  })
</script>

<style scoped>

#container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;

  width : 80%;
  position: absolute;
  left: 0;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
  margin: 0 auto;
}

#container strong {
  font-size: 20px;
  line-height: 26px;
}

#container p {
  font-size: 16px;
  line-height: 22px;
  
  color: #8c8c8c;
  
  margin: 0;
}

#container a {
  text-decoration: none;
}

.form{
  border : 1px solid #FFFFFF;
  display: flex;
  flex-direction: column;
  justify-content: space-around;
  align-items: center;
  min-width: 300px;
  padding: 10px;
}


.titre{
  font-size : 2em;
  margin : 15px;
}

.input{
  border: 1px solid #FFFFFF;
  margin : 5px;
  text-align: center;
}

.button{
  width: 100%;
}
</style>