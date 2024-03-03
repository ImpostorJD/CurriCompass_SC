import { Routes } from '@angular/router';
import { StudentFormComponent } from './student-form/student-form.component';
import { LoginUiComponent } from './login-ui/login-ui.component';
import { BaselayoutComponent } from './components/baselayout/baselayout.component';

export const routes: Routes = [
    { path: 'login', component: LoginUiComponent },
    { path: '', component: BaselayoutComponent, children: [
        { path : 'student', component: StudentFormComponent },
      ]
    },


];
