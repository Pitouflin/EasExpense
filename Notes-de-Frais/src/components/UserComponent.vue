<template>
    <ion-card id="report" v-for="user in listusers" :key="user.id" :value="user.id">
      <ion-card-title>
        {{ user.id }} {{ user.name }}
      </ion-card-title>
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

    const listusers = ref([]);

    const getuser = async() => {
        try{
            const response = await axios.get(`${API_BASE_URL}/user`);
            listusers.value = response.data;
            console.log(response.data);
        }
        catch(error){
            console.error(error);
        }
    }

  
    const props = defineProps({
      user: {
        type: Object,
        required: true,
      }
    });

    onMounted(() => {
        getuser();
    })
  
  
  
  </script>
  
  <style scoped>
  
    ion-nav-link {
      height: fit-content;
    }
  
  </style>