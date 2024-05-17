import { Routes } from "@angular/router";
import { AuthGuard } from "../../services/auth/auth-access.guard";
import { ConsultationPageComponent } from "./consultation-page/consultation-page.component";
import { AddConsultationComponent } from "./add-consultation/add-consultation.component";
import { EditConsultationComponent } from "./edit-consultation/edit-consultation.component";

export const consultationRoutes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard(['Admin', 'Staff', 'Student'])],
    component: ConsultationPageComponent
  },
  {
    path: 'add-consultation',
    canActivate: [AuthGuard(['Admin', 'Staff'])],
    component: AddConsultationComponent
  },
  {
    path: ':id',
    canActivate: [AuthGuard(['Admin', 'Staff'])],
    component: EditConsultationComponent
  },
]
