<template>
  <header>
    <nav>
      <ul>
        <!-- Afficher le lien Connexion uniquement si l'utilisateur n'est pas connecté -->
        <li v-if="!isLoggedIn"><router-link to="/connexion">Connexion</router-link></li>
        <!-- Afficher le lien Créer Note de Frais -->
        <li v-if="isLoggedIn"><router-link to="/newReport">Créer Note de Frais</router-link></li>
        <!-- Afficher le lien Mes Notes de Frais -->
        <li v-if="isLoggedIn"><router-link to="/expenseReports">Mes Notes de Frais</router-link></li>
        <!-- Afficher le lien Approbation uniquement pour les administrateurs -->
        <li v-if="isAdmin"><router-link to="/approbation">Approbation</router-link></li>
        <!-- Afficher le bouton Se Déconnecter uniquement si l'utilisateur est connecté -->
        <li v-if="isLoggedIn"><button @click="logout">Se Déconnecter</button></li>
        <!-- Afficher le nom de l'utilisateur connecté en haut à droite -->
        <li v-if="isLoggedIn" class="user-name">{{ userName }}</li>
      </ul>
    </nav>
  </header>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { Preferences } from '@capacitor/preferences';
import axios from 'axios';

export default {
  setup() {
    const router = useRouter();
    const isAdmin = ref(false);
    const isLoggedIn = ref(false);
    const userName = ref('');

    onMounted(async () => {
      // Vérifier le statut de connexion de l'utilisateur et son rôle lors du montage du composant
      await checkUserStatus();
      await checkAdminRole();
    });

    const checkUserStatus = async () => {
      try {
        const { value: userId } = await Preferences.get({ key: 'id' });
        if (userId) {
          isLoggedIn.value = true;
          const response = await axios.get(`http://127.0.0.1:8000/user/${userId}`);
          const user = response.data;
          userName.value = user.name; // Stocker le nom de l'utilisateur
        } else {
          isLoggedIn.value = false;
        }
      } catch (error) {
        console.error('Erreur lors de la vérification du statut de l\'utilisateur:', error);
      }
    };

    const checkAdminRole = async () => {
      try {
        const { value: userId } = await Preferences.get({ key: 'id' });
        if (userId) {
          const response = await axios.get(`http://127.0.0.1:8000/user/${userId}`);
          const user = response.data;
          isAdmin.value = user.roles.includes('ROLE_ADMIN');
        }
      } catch (error) {
        console.error('Erreur lors de la vérification des rôles de l\'utilisateur:', error);
      }
    };

    const logout = async () => {
      await Preferences.remove({ key: 'id' });
      isLoggedIn.value = false;
      userName.value = ''; // Réinitialiser le nom de l'utilisateur
      router.push('/connexion');
    };

    return { isAdmin, isLoggedIn, userName, logout };
  }
};
</script>

<style scoped>
header {
  background: #333;
  color: #fff;
  padding: 10px;
  position: relative;
}
nav ul {
  list-style-type: none;
  padding: 0;
  margin: 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
nav ul li {
  margin-right: 10px;
}
nav ul li a {
  color: #fff;
  text-decoration: none;
  border: #fff solid 1px;
  padding: 2px;
}
nav ul li button {
  background: none;
  color: #fff;
  cursor: pointer;
  border: #fff solid 1px;
  padding: 2px;
}
.user-name {
  margin-left: auto;
  color: #fff;
  padding: 2px;
}
</style>
