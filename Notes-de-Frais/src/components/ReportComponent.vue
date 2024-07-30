<template>
  <ion-card id="report">
    <ion-card-header>
      <h2>TEST</h2>
      <!-- <ion-card-title >{{ report.users_id_id.name }} </ion-card-title> -->
      <ion-card-subtitle >{{ report/* .value */ }}</ion-card-subtitle> 
    </ion-card-header>
  </ion-card>
</template>

<script setup lang="ts">
  import { IonCard, IonCardHeader, IonCardSubtitle, IonCardTitle } from '@ionic/vue';
  import { defineProps } from 'vue';
  import Vue from 'vue';

  const props = defineProps({
    report: {
      type: Object,
      required: true
    }
  });



</script>

<style scoped>

  ion-nav-link {
    height: fit-content;
  }

</style>