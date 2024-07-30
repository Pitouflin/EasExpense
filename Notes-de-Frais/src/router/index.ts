import { createRouter, createWebHistory } from '@ionic/vue-router';
import { RouteRecordRaw } from 'vue-router';
import HomeView from '@/views/HomeView.vue';
import ConnexionComponent from '@/components/ConnexionComponent.vue';
import ExpenseReportComponent from '@/components/ExpenseReportComponent.vue';
import ReportListComponent from '@/components/ReportListComponent.vue';
import ReportComponent from '@/components/ReportComponent.vue';
import VehicleComponent from '@/components/VehicleComponent.vue';

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    redirect: '/home'
  },
  {
    path: '/home',
    name: 'Home',
    component: HomeView,
    children: [
      {
        path: 'connexion',
        name: 'Connexion',
        component: ConnexionComponent
      },
      {
        path: 'expenseReport',
        name: 'ExpenseReport',
        component: ReportComponent,
        children: [
          {
          path: 'new',
          name: 'newReport',
          component: ExpenseReportComponent
          }
        ]
      },
      {
        path: 'reportList',
        name: 'ReportList',
        component: ReportListComponent
      },
      {
        path: 'vehicle',
        name: 'Vehicle',
        component: VehicleComponent
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router
