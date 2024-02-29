import { Routes } from '@angular/router';
import { StudentFormComponent } from './student-form/student-form.component';
import { LoginUiComponent } from './login-ui/login-ui.component';

export const routes: Routes = [
    { path: '', component: LoginUiComponent },
    {path : 'Student', component: StudentFormComponent},
   
];
