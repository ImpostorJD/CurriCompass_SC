import { Routes } from "@angular/router";
import { AuthGuard } from "../../services/auth/auth-access.guard";
import { ConsultationPageComponent } from "./consultation-page/consultation-page.component";
import { StudentConsultationComponent } from "./student-consultation/student-consultation.component";

export const consultationRoutes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard(['Admin', 'Staff', 'Student'])],
    component: ConsultationPageComponent
  },
  {
    path: 'advising/:id',
    canActivate: [AuthGuard(['Admin', 'Staff'])],
    component: StudentConsultationComponent
  }
  //Add consultation and edit consultation will be changed to:
  //consultation/:id -> consultation of student  (can be edited by admin)

  // { //to be depricated
  //   path: 'add-consultation',
  //   canActivate: [AuthGuard(['Admin', 'Staff'])],
  //   component: AddConsultationComponent
  // },
  // { //to be depricated
  //   path: ':id',
  //   canActivate: [AuthGuard(['Admin', 'Staff'])],
  //   component: EditConsultationComponent
  // },
]
