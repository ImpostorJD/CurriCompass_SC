import { Routes } from "@angular/router"
import { StudentsListingComponent } from "./students-listing/students-listing.component"
import { StudentFormComponent } from "./student-form/student-form.component"
import { StudentRecordManagementComponent } from "./student-record-management/student-record-management.component"

export const studentRoutes: Routes = [
  { path : '', component: StudentsListingComponent },
  { path : 'add-student', component: StudentFormComponent },
  { path : ':id', component: StudentRecordManagementComponent },
]

