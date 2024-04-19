import { Routes } from "@angular/router";
import { CurriculaListComponent } from "./curricula-list/curricula-list.component";
import { AddCurriculumComponent } from "./add-curriculum/add-curriculum.component";
import { EditCurriculumComponent } from "./edit-curriculum/edit-curriculum.component";

export const curriculaRoutes: Routes = [
  { path : '', component: CurriculaListComponent },
  { path : 'add-curriculum', component: AddCurriculumComponent },
  { path : ':id', component: EditCurriculumComponent },
];


