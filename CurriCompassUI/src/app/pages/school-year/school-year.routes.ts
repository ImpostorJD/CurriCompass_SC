import { Routes } from "@angular/router";
import { SchoolYearPageComponent } from "./school-year-page/school-year-page.component";
import { AddSchoolYearComponent } from "./add-school-year/add-school-year.component";
import { EditSchoolYearComponent } from "./edit-school-year/edit-school-year.component";

export const schoolYearRoutes: Routes = [
  { path: '', component: SchoolYearPageComponent },
  { path: 'add-school-year', component: AddSchoolYearComponent },
  { path: ':id', component: EditSchoolYearComponent },
]
