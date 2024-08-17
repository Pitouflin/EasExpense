<template>
  <ion-card id="report">
    <ion-card-header>
      <h2>Détails du Rapport</h2>
      <!-- Vérifie si le report est défini avant d'accéder à ses propriétés -->
      <ion-card-title v-if="report">{{ report.title }}</ion-card-title>
      <ion-card-subtitle v-if="report">{{ report.details }}</ion-card-subtitle> 
      <p v-else>Aucun rapport sélectionné.</p>
    </ion-card-header>
    
    <!-- Contenu dynamique du composant enfant -->
    <router-view />
  </ion-card>
</template>

<script setup lang="ts">
import { IonCard, IonCardHeader, IonCardSubtitle, IonCardTitle } from '@ionic/vue';
import { defineProps } from 'vue';

const props = defineProps({
  report: {
    type: Object,
    required: false,  // Modifie ici pour ne pas rendre la prop obligatoire
    default: () => null  // Valeur par défaut pour éviter les erreurs
  }
});
</script>

<style scoped>
#report {
  margin: 20px;
}
</style>
