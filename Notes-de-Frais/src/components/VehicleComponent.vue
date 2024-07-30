<template>
    <ion-card id="report" v-for="vehicle in listVehicles" :key="vehicle.id" :value="vehicle.id">
      <ion-card-header>
        {{ vehicle.name }}
      </ion-card-header>
      <ion-card-title>
        Propriétaire : {{ vehicle.userId.name }}
      </ion-card-title>
      <ion-card-subtitle>
        Cheveaux fiscaux : {{ vehicle.fiscalHorses }}
      </ion-card-subtitle>
    </ion-card>
  </template>
  
  <script setup lang="ts">
    import { IonCard, IonCardHeader, IonCardSubtitle, IonCardTitle } from '@ionic/vue';
    import { defineProps } from 'vue';
    import Vue from 'vue';
    import { ref, onMounted  } from 'vue';
    import axios from 'axios';
    import { useRoute } from 'vue-router';
    import { useRouter } from 'vue-router';
    import {API_BASE_URL} from '../config';

    const listVehicles = ref([]);

    const getVehicle = async() => {
        try{
            const response = await axios.get(`${API_BASE_URL}/vehicle`);
            listVehicles.value = response.data;
            console.log(response.data);
        }
        catch(error){
            console.error(error);
        }
    }

  
    const props = defineProps({
      vehicle: {
        type: Object,
        required: true,
      }
    });

    onMounted(() => {
        getVehicle();
    })
  
  
  
  </script>
  
  <style scoped>
  
    ion-nav-link {
      height: fit-content;
    }
  
  </style>