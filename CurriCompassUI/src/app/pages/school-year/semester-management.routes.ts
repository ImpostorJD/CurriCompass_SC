import { Routes } from "@angular/router";
import { SemesterManagementComponent } from "./semester-management/semester-management.component";
import { AddSemesterAvailabilityComponent } from "./add-semester-availability/add-semester-availability.component";
import { EditSemesterAvailabilityComponent } from "./edit-semester-availability/edit-semester-availability.component";

export const semesterRoutes: Routes = [
  { path: '', component: SemesterManagementComponent },
  // { path: 'add-semester', component: AddSemesterAvailabilityComponent },
  // { path: ':id', component: EditSemesterAvailabilityComponent },
]
