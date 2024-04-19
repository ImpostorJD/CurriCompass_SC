import { Routes } from "@angular/router";
import { ProgramListComponent } from "./program-list/program-list.component";
import { ProgramsFormComponent } from "./programs-form/programs-form.component";
import { EditProgramsComponent } from "./edit-programs/edit-programs.component";

export const programsRoutes: Routes = [
  { path : '', component: ProgramListComponent },
  { path : 'add-program', component: ProgramsFormComponent },
  { path : ':id', component: EditProgramsComponent },
];

