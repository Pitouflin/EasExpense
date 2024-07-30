<template>
    <div id="container">
        <h2>Déclarer une note de frais</h2>
        <form class="form">
            <ion-input placeholder="Donnée"></ion-input>
            <ion-input placeholder="Autre donnée"></ion-input>
            <ion-button>Envoyer</ion-button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { IonInput, IonButton } from '@ionic/vue';
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