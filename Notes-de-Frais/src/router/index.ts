import { createRouter, createWebHistory } from '@ionic/vue-router';
import { RouteRecordRaw } from 'vue-router';
import ConnexionComponent from '@/components/ConnexionComponent.vue';
import ExpenseReportComponent from '@/components/ExpenseReportComponent.vue';
import ManageReportComponent from '@/components/ManageReportComponent.vue';
import UserExpenseReportsComponent from '@/components/UserExpenseReportsComponent.vue'; // Nouveau composant

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    redirect: '/connexion'
  },
  {
    path: '/connexion',
    name: 'Connexion',
    component: ConnexionComponent
  },
  {
    path: '/newReport',
    name: 'NewReport',
    component: ExpenseReportComponent
  },
  {
    path: '/approbation',
    name: 'Approbation',
    component: ManageReportComponent
  },
  {
    path: '/expenseReports',
    name: 'UserExpenseReports',
    component: UserExpenseReportsComponent // Composant pour les notes de frais de l'utilisateur
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
});

export default router;
