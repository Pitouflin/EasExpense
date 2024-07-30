<template>
    <div id="container">
        <Report
        v-for="r in reports" :key="r.id" :report="r">
        </Report>
    </div>
</template>

<script setup lang="ts">
//import { IonInput, IonButton } from '@ionic/vue';
import Report from '../components/ReportComponent.vue';

import { onMounted, ref } from 'vue';
	import axios from "axios";
	import { API_BASE_URL } from '../config';

	const reports = ref(null);

	const fetchreports = async () => {
		try {
			const response = await axios.get(`${API_BASE_URL}/expense/report`);
			reports.value = response.data;
		} catch (error) {
			console.error('Erreur lors de la récupération des reports :', error);
		}
	};

	interface User {
	id: number;
	name: string;
	login: string;
  passwords: string;
	reports: any[];
	}

	interface ExpenseReport {
	id: number;
	users_id_id: number;
  value:number;
	}

	/*const typeRelations = ref(null);

	const fetchData = async () => {
	typeRelations.value = null;
	try {
		const response = await axios.get(`${API_BASE_URL}/TypeRelation`);
		console.log(response.data)
		typeRelations.value = response.data;
		console.log(typeRelations.value)
	} catch (error) {
		console.error('Error fetching type relations:', error);
	}
	}; */

	onMounted(() => {
		fetchreports();
	});

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